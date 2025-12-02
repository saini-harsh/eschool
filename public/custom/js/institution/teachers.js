$(function () {
    const table = $(".datatable").DataTable({
        paging: true,
        lengthChange: true,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: false,
        responsive: true,
        columnDefs: [
            {
                targets: "no-sort",
                orderable: false,
            },
        ],
    });

    // Custom search input
    $("#custom-search-input").on("keyup", function () {
        table.search(this.value).draw();
    });

    // Filter dropdown submission
    $('form[action="#"]').on("submit", function (e) {
        e.preventDefault();
        applyFilters();
    });

    function applyFilters() {
        var nameFilters = [];
        $('#name-filter-dropdown input[type="checkbox"]:checked').each(
            function () {
                nameFilters.push($(this).val().toLowerCase());
            }
        );
        
        var emailFilters = [];
        $('#email-filter-dropdown input[type="checkbox"]:checked').each(
            function () {
                emailFilters.push($(this).val().toLowerCase());
            }
        );

        // Use DataTables custom filtering
        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                var nameMatch = true;
                var emailMatch = true;
                
                // Check name filter
                if (nameFilters.length > 0) {
                    var name = data[0].toLowerCase(); // Name column
                    nameMatch = nameFilters.some(function(filter) {
                        return name.indexOf(filter) !== -1;
                    });
                }
                
                // Check email filter
                if (emailFilters.length > 0) {
                    var email = data[1].toLowerCase(); // Email column
                    emailMatch = emailFilters.some(function(filter) {
                        return email.indexOf(filter) !== -1;
                    });
                }
                
                return nameMatch && emailMatch;
            }
        );
        
        table.draw();
        
        // Remove the custom filter after drawing to avoid affecting other searches
        $.fn.dataTable.ext.search.pop();
    }

    // Clear all filters
    $(".clear-all-filters").on("click", function () {
        $('#name-filter-dropdown input[type="checkbox"]').prop(
            "checked",
            false
        );
        $('#email-filter-dropdown input[type="checkbox"]').prop(
            "checked",
            false
        );
        
        // Clear any column searches and reset the table
        table.columns().search('').draw();
        table.search('').draw();
    });

    // Close the filter dropdown when clicking outside
    $(document).on("click", function (event) {
        if (!$(event.target).closest(".filter-dropdown").length) {
            $(".filter-dropdown").hide();
        }
    });
});

// Teacher status update
function change_status(teacher_id, status) {
    var a = confirm("Are you sure you want to change status?");
    if (a) {
        var url =
            "http://127.0.0.1:8000/institution/teachers/change_status/" +
            teacher_id;
        $.ajax({
            url: url,
            type: "post",
            data: { status: status },
            success: function (response) {
                if (response.status == "success") {
                    alert(response.message);
                    location.reload();
                } else {
                    alert(response.message);
                }
            },
        });
    }
}

// Teacher delete
function delete_teacher(teacher_id) {
    var a = confirm("Are you sure you want to delete this teacher?");
    if (a) {
        var url =
            "http://127.0.0.1:8000/institution/teachers/delete/" + teacher_id;
        $.ajax({
            url: url,
            type: "post",
            success: function (response) {
                if (response.status == "success") {
                    alert(response.message);
                    location.reload();
                } else {
                    alert(response.message);
                }
            },
        });
    }
}
