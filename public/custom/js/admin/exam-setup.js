console.log('exam-setup.js loaded');

$(document).ready(function () {
    $('#institution_id').on('change', function () {
        let institutionId = $(this).val();
        if (!institutionId) {
            $('#exam_type').html('<option value="">Select Exam Type</option>');
            $('#class_id').html('<option value="">Select Class</option>');
            $('#section_id').html('<option value="">Select Section</option>');
            return;
        }
        $.ajax({
            url: '/admin/exam-management/exam-setup/fetch-data', // Update this route as per your backend
            type: 'GET',
            data: { institution_id: institutionId },
            success: function (response) {
                // Populate Exam Types
                let examTypeOptions = '<option value="">Select Exam Type</option>';
                response.exam_types.forEach(function (type) {
                    examTypeOptions += `<option value="${type.id}">${type.title}</option>`;
                });
                $('#exam_type').html(examTypeOptions);

                // Populate Classes
                let classOptions = '<option value="">Select Class</option>';
                response.classes.forEach(function (cls) {
                    classOptions += `<option value="${cls.id}">${cls.name}</option>`;
                });
                $('#class_id').html(classOptions);

                // Populate Sections
                let sectionOptions = '<option value="">Select Section</option>';
                response.sections.forEach(function (sec) {
                    sectionOptions += `<option value="${sec.id}">${sec.name}</option>`;
                });
                $('#section_id').html(sectionOptions);
            },
            error: function () {
                alert('Failed to fetch data for the selected institution.');
            }
        });
    });

    // Disable end date initially
    $('#end_date').prop('disabled', true);

    // Enable end date only when start date is set
    $('#start_date').on('change', function () {
        if ($(this).val()) {
            $('#end_date').prop('disabled', false);
        } else {
            $('#end_date').prop('disabled', true).val('');
            $('#schedule-table tbody').empty();
        }
        checkAndFetchSubjects();
    });

    // Listen for changes on end date and class
    $('#end_date, #class_id').on('change', function () {
        checkAndFetchSubjects();
    });

    function getDatesInRange(startDate, endDate) {
        const dateArray = [];
        let currentDate = new Date(startDate);
        const stopDate = new Date(endDate);
        while (currentDate <= stopDate) {
            dateArray.push(new Date(currentDate));
            currentDate.setDate(currentDate.getDate() + 1);
        }
        return dateArray;
    }

    function renderScheduleTable(subjects, startDate, endDate) {
        const dates = getDatesInRange(startDate, endDate);
        let subjectOptions = subjects.map(sub => `<option value="${sub.id}">${sub.name}</option>`).join('');
        let rows = '';
        dates.forEach((date, idx) => {
            let dateStr = date.toISOString().split('T')[0];
            rows += `<tr>
                <td>
                    ${dateStr}
                    <input type="hidden" name="subject_dates[]" value="${dateStr}">
                </td>
                <td>
                    <select name="morning_subjects[]" class="form-select subject-select" >
                        <option value="">Select Subject</option>
                        ${subjectOptions}
                    </select>
                </td>
                <td>
                    <select name="evening_subjects[]" class="form-select subject-select" >
                        <option value="">Select Subject</option>
                        ${subjectOptions}
                    </select>
                </td>
            </tr>`;
        });
        $('#schedule-table').html(
            `<thead>
                <tr>
                    <th>Day</th>
                    <th>
                        Morning <input type="time" name="morning_time" id="morning_time" class="form-control form-control-sm" style="width:120px;display:inline-block;">
                    </th>
                    <th>
                        Evening <input type="time" name="evening_time" id="evening_time" class="form-control form-control-sm" style="width:120px;display:inline-block;">
                    </th>
                </tr>
            </thead>
            <tbody>${rows}</tbody>`
        );

        // Global duplicate prevention
        $('#schedule-table').on('change', '.subject-select', function () {
            let selectedSubjects = [];
            $('.subject-select').each(function () {
                let val = $(this).val();
                if (val) selectedSubjects.push(val);
            });

            $('.subject-select').each(function () {
                let $this = $(this);
                let currentVal = $this.val();
                $this.find('option').each(function () {
                    if ($(this).val() === "") return; // skip placeholder
                    if ($(this).val() !== currentVal && selectedSubjects.includes($(this).val())) {
                        $(this).prop('disabled', true);
                    } else {
                        $(this).prop('disabled', false);
                    }
                });
            });
        });
    }

    function checkAndFetchSubjects() {
        let startDate = $('#start_date').val();
        let endDate = $('#end_date').val();
        let classId = $('#class_id').val();
        let institutionId = $('#institution_id').val();
        if (startDate && endDate && classId) {
            $.ajax({
                url: '/admin/exam-management/exam-setup/fetch-subjects',
                type: 'GET',
                data: { class_id: classId, institution_id: institutionId },
                success: function (response) {
                    renderScheduleTable(response.subjects, startDate, endDate);
                },
                error: function () {
                    $('#schedule-table tbody').empty();
                    alert('Failed to fetch subjects for the selected class.');
                }
            });
        } else {
            $('#schedule-table tbody').empty();
        }
    }
});
