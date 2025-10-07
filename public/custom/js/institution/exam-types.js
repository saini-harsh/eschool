$(document).ready(function () {
    // Handle Add Exam Type
    $("#add-exam-type").on("click", function (e) {
        e.preventDefault();
        let form = $("#exam-type-form");
        let formData = form.serialize();

        $.ajax({
            url: "/institution/exam-management/exam-type/store", // Update this to your actual route
            type: "POST",
            data: formData,
            success: function (response) {
                // Show success message, reload table, reset form, etc.
                location.reload();
            },
            error: function (xhr) {
                // Handle validation errors
                alert("Error: " + xhr.responseJSON.message);
            },
        });
    });

    // Handle Update Exam Type
    $("#update-exam-type").on("click", function (e) {
        e.preventDefault();
        let form = $("#exam-type-form");
        let id = $("#exam-type-id").val();
        let formData = form.serialize();

        $.ajax({
            url: "/institution/exam-management/exam-type/update", // Update this to your actual route
            type: "POST",
            data: formData,
            success: function (response) {
                location.reload();
            },
            error: function (xhr) {
                alert("Error: " + xhr.responseJSON.message);
            },
        });
    });

    // Handle Edit Exam Type
    $(".edit-exam-type").on("click", function () {
        let row = $(this).closest("tr");
        let id = row.data("exam-type-id");
        let code = row.find("td:eq(0) h6").text().trim();
        let title = row.find("td:eq(1) h6").text().trim();
        let institutionName = row.find("td:eq(2)").text().trim();
        let description = row.find("td:eq(3)").text().trim();
        let status = row.find(".exam-type-status-select").val();

        // Set form values
        $("#exam-type-id").val(id);
        $("#code").val(code);
        $("#title").val(title);
        $("#description").val(description === "N/A" ? "" : description);
        $("#institution_id option")
            .filter(function () {
                return $(this).text().trim() === institutionName;
            })
            .prop("selected", true);
        $("#exam-type-status").prop("checked", status == 1);

        // Switch buttons
        $("#add-exam-type").addClass("d-none");
        $("#update-exam-type").removeClass("d-none");
        $("#cancel-edit").removeClass("d-none");
    });

    // Handle Cancel Edit
    $("#cancel-edit").on("click", function () {
        $("#exam-type-form")[0].reset();
        $("#exam-type-id").val("");
        $("#add-exam-type").removeClass("d-none");
        $("#update-exam-type").addClass("d-none");
        $("#cancel-edit").addClass("d-none");
    });
});
