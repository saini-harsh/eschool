let currentScanType = null;
let html5QrCode = null;
let scannerStream = null;

document.addEventListener('DOMContentLoaded', function() {
    const scanBarcodeBtn = document.getElementById('scan-barcode-btn');
    const scanQrBtn = document.getElementById('scan-qr-btn');
    const scanBiometricBtn = document.getElementById('scan-biometric-btn');
    const scanForm = document.getElementById('scan-attendance-form');
    const scannerContainer = document.getElementById('scanner-container');
    const manualInputContainer = document.getElementById('manual-input-container');
    const stopScannerBtn = document.getElementById('stop-scanner-btn');
    const submitManualInputBtn = document.getElementById('submit-manual-input');
    const scanInstitution = document.getElementById('scan-institution');
    const scanRole = document.getElementById('scan-role');
    const scanClass = document.getElementById('scan-class');
    const scanSection = document.getElementById('scan-section');
    const scanDate = document.getElementById('scan-date');
    const manualInput = document.getElementById('manual-input');

    // Initialize scan buttons
    if (scanBarcodeBtn) {
        scanBarcodeBtn.addEventListener('click', () => initScan('barcode'));
    }
    if (scanQrBtn) {
        scanQrBtn.addEventListener('click', () => initScan('qr_code'));
    }
    if (scanBiometricBtn) {
        scanBiometricBtn.addEventListener('click', () => initScan('biometric'));
    }

    // Stop scanner
    if (stopScannerBtn) {
        stopScannerBtn.addEventListener('click', stopScanner);
    }

    // Submit manual input
    if (submitManualInputBtn) {
        submitManualInputBtn.addEventListener('click', submitManualInput);
    }

    // Role change handler
    if (scanRole) {
        scanRole.addEventListener('change', function() {
            const role = this.value;
            const classField = document.getElementById('scan-class-field');
            const sectionField = document.getElementById('scan-section-field');
            
            if (role === 'student') {
                classField.style.display = 'block';
                sectionField.style.display = 'block';
            } else {
                classField.style.display = 'none';
                sectionField.style.display = 'none';
            }
        });
    }

    // Institution change handler
    if (scanInstitution) {
        scanInstitution.addEventListener('change', function() {
            const institutionId = this.value;
            if (institutionId && scanRole.value === 'student') {
                fetch(`/admin/attendance/classes/${institutionId}`)
                    .then(res => res.json())
                    .then(data => {
                        scanClass.innerHTML = '<option value="">Select Class</option>';
                        data.forEach(cls => {
                            scanClass.innerHTML += `<option value="${cls.id}">${cls.name}</option>`;
                        });
                    });
            }
        });
    }

    // Class change handler
    if (scanClass) {
        scanClass.addEventListener('change', function() {
            const classId = this.value;
            if (classId) {
                fetch(`/admin/attendance/sections/${classId}`)
                    .then(res => res.json())
                    .then(data => {
                        scanSection.innerHTML = '<option value="">Select Section</option>';
                        data.forEach(section => {
                            scanSection.innerHTML += `<option value="${section.id}">${section.name}</option>`;
                        });
                    });
            }
        });
    }

    function initScan(type) {
        currentScanType = type;
        scanForm.style.display = 'flex';
        
        if (type === 'qr_code') {
            initQRScanner();
        } else {
            // For barcode and biometric, show manual input
            scannerContainer.style.display = 'none';
            manualInputContainer.style.display = 'block';
            manualInput.placeholder = type === 'barcode' ? 'Enter Barcode' : 'Enter Biometric ID';
        }
    }

    function initQRScanner() {
        if (html5QrCode) {
            stopScanner();
        }

        const scannerView = document.getElementById('scanner-view');
        scannerView.innerHTML = '<div id="qr-reader" style="width: 100%; max-width: 500px; margin: 0 auto;"></div>';
        
        scannerContainer.style.display = 'block';
        manualInputContainer.style.display = 'none';

        html5QrCode = new Html5Qrcode("qr-reader");
        
        html5QrCode.start(
            { facingMode: "environment" },
            {
                fps: 10,
                qrbox: { width: 250, height: 250 }
            },
            (decodedText, decodedResult) => {
                handleScanResult(decodedText);
            },
            (errorMessage) => {
                // Ignore errors
            }
        ).catch((err) => {
            console.error("Unable to start scanning", err);
            alert("Unable to access camera. Please use manual input.");
            scannerContainer.style.display = 'none';
            manualInputContainer.style.display = 'block';
        });
    }

    function stopScanner() {
        if (html5QrCode) {
            html5QrCode.stop().then(() => {
                html5QrCode.clear();
                html5QrCode = null;
            }).catch((err) => {
                console.error("Error stopping scanner", err);
            });
        }
        scannerContainer.style.display = 'none';
    }

    function submitManualInput() {
        const value = manualInput.value.trim();
        if (!value) {
            alert('Please enter a value');
            return;
        }
        handleScanResult(value);
    }

    function handleScanResult(scanValue) {
        if (!scanForm.checkValidity()) {
            scanForm.reportValidity();
            return;
        }

        const formData = {
            scan_type: currentScanType,
            scan_value: scanValue,
            role: scanRole.value,
            institution_id: scanInstitution.value,
            class_id: scanClass.value || null,
            section_id: scanSection.value || null,
            date: convertDateFormat(scanDate.value)
        };

        // Stop scanner if active
        if (html5QrCode) {
            stopScanner();
        }

        // Show loading
        const scanResults = document.getElementById('scan-results');
        scanResults.style.display = 'block';
        scanResults.innerHTML = '<div class="alert alert-info"><i class="ti ti-loader me-1"></i>Processing...</div>';

        fetch('/admin/attendance/scan', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(formData)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                scanResults.innerHTML = `
                    <div class="alert alert-success">
                        <h6 class="alert-heading"><i class="ti ti-check-circle me-1"></i>${data.message}</h6>
                        <p class="mb-0">User: ${data.user.name}</p>
                    </div>
                `;
                // Clear manual input
                manualInput.value = '';
                // Reset form after 3 seconds
                setTimeout(() => {
                    scanResults.style.display = 'none';
                }, 3000);
            } else {
                scanResults.innerHTML = `
                    <div class="alert alert-danger">
                        <h6 class="alert-heading"><i class="ti ti-alert-circle me-1"></i>Error</h6>
                        <p class="mb-0">${data.message}</p>
                    </div>
                `;
            }
        })
        .catch(err => {
            console.error('Error:', err);
            scanResults.innerHTML = `
                <div class="alert alert-danger">
                    <h6 class="alert-heading"><i class="ti ti-alert-circle me-1"></i>Error</h6>
                    <p class="mb-0">Failed to mark attendance. Please try again.</p>
                </div>
            `;
        });
    }

    function convertDateFormat(dateStr) {
        // Convert "d M, Y" to "Y-m-d"
        const date = new Date(dateStr);
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
});
