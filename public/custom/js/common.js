function resetForm($formId, $addBtnId, $updateBtnId, $cancelBtnId) {
    const form = $("#class_room_form")[0];
    form.reset();
    $("#class_room_status").prop("checked", true);
    $("#class_room_id").val("");
    $("#add-class-room").removeClass("d-none");
    $("#update-class_room").addClass("d-none");
    $("#cancel-edit").addClass("d-none");
}
