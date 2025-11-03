$(document).ready(function () {
    // Initialize form with current values if any
    var currentInstitution = $("#institution").val();
    var currentClass = $("#class").val();
    var currentSection = $("#section").val();

    if (currentInstitution) {
        // loadClassesAndSections(
        //     currentInstitution,
        //     currentClass,
        //     currentSection
        // );
        loadExamType(currentInstitution);
    }

    $("#institution").on("change", function () {
        var institutionId = $(this).val();
        console.log("Institution changed to:", institutionId);

        $("#class").html('<option value="">Select Class</option>');
        $("#section").html('<option value="">Select Section</option>');

        if (institutionId) {
            loadExamType(institutionId);
            // loadClassesAndSections(institutionId);
        } else {
            console.log("No institution selected, clearing dropdowns");
        }
    });

    function loadExamType(institutionId, selectedExamType = null) {
        $.ajax({
            url: "/admin/exam-management/exams/get-exam-type/" + institutionId,
            type: "GET",
            beforeSend: function () {
                // Optional: Show loading spinner
                console.log("Loading exam type for:", institutionId);
            },
            success: function (response) {
                console.log("AJAX Response:", response);

                if (response.types.length > 0) {
                    $.each(response.types, function (index, cls) {
                        var selected =
                            selectedExamType && cls.id == selectedExamType
                                ? "selected"
                                : "";
                        $("#exam-type").append(
                            '<option value="' +
                                cls.id +
                                '" ' +
                                selected +
                                ">" +
                                cls.title +
                                "</option>"
                        );
                    });
                } else {
                    $("#exam-type").append(
                        '<option value="">No Exam Type found</option>'
                    );
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", error);
                console.error("Response:", xhr.responseText);
                // Show user-friendly error message
                alert("Error loading classes and sections. Please try again.");
            },
        });
    }

    function loadClassesAndSections(
        institutionId,
        selectedClass = null,
        selectedSection = null
    ) {
        // Show loading state
        $("#class").html('<option value="">Loading classes...</option>');
        $("#section").html('<option value="">Loading sections...</option>');

        $.ajax({
            url:
                "/admin/exam-management/exams/get-classes-sections/" +
                institutionId,
            type: "GET",
            beforeSend: function () {
                // Optional: Show loading spinner
                console.log(
                    "Loading classes and sections for institution:",
                    institutionId
                );
            },
            success: function (response) {
                console.log("AJAX Response:", response);

                // Clear loading states
                $("#class").html('<option value="">Select Class</option>');
                $("#section").html('<option value="">Select Section</option>');

                if (response.classes && response.classes.length > 0) {
                    $.each(response.classes, function (index, cls) {
                        var selected =
                            selectedClass && cls.id == selectedClass
                                ? "selected"
                                : "";
                        $("#class").append(
                            '<option value="' +
                                cls.id +
                                '" ' +
                                selected +
                                ">" +
                                cls.name +
                                "</option>"
                        );
                    });
                } else {
                    $("#class").append(
                        '<option value="">No classes found</option>'
                    );
                }

                if (response.sections && response.sections.length > 0) {
                    $.each(response.sections, function (index, section) {
                        var selected =
                            selectedSection && section.id == selectedSection
                                ? "selected"
                                : "";
                        $("#section").append(
                            '<option value="' +
                                section.id +
                                '" ' +
                                selected +
                                ">" +
                                section.name +
                                "</option>"
                        );
                    });
                } else {
                    $("#section").append(
                        '<option value="">No sections found</option>'
                    );
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", error);
                console.error("Response:", xhr.responseText);

                $("#class").html(
                    '<option value="">Error loading classes</option>'
                );
                $("#section").html(
                    '<option value="">Error loading sections</option>'
                );

                // Show user-friendly error message
                alert("Error loading classes and sections. Please try again.");
            },
        });
    }

    // Handle form submission
    $("#exam-filter-form").on("submit", function (e) {
        // Form will submit normally, no need to prevent default
    });
});
