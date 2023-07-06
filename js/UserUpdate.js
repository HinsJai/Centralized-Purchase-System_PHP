$(document).ready(function () {
    $("#edit").click(function (e) {
        e.preventDefault();
        $("#contact").removeAttr("readonly");
        $("#warehouse-address").removeAttr("readonly");
        $("#cancel").removeAttr("hidden");
        $("#edit").attr("hidden", "true");
        $("#cPwd").attr("hidden", "true");
        $("#update").removeAttr("hidden");
        $("#reminder").removeAttr("hidden");
    });
});


$(document).ready(function () {
    $("#cancel").click(function (e) {
        e.preventDefault();
        console.log("edit")
        $("#edit").removeAttr("hidden");
        $("#cancel").attr("hidden", "true");
        $("#warehouse-address").removeAttr("readonly");
        $("#contact").attr("readonly", "true");
        $("#warehouse-address").attr("readonly", "true");
        $("#cPwd").removeAttr("hidden");
        $("#update").attr("hidden", "true");
        $("#reminder").attr("hidden", "true");
        window.location.reload();
    });
});

function checkInput() {
    var input = document.getElementById("contact");
    var pattern = /^\d{8}$/;

    if (!pattern.test(input.value)) {
        input.value = '';
        input.focus();
        return false;
    }
}

function checkWarehouseAddress() {
    var input = document.getElementById("warehouse-address");
    if (input.value.trim() == "") {
        input.focus();
        return false;
    }

}

$(document).ready(function () {
    $("#update").click(function (e) {
        e.preventDefault();

        if (checkInput() == false) {
            $.alert({
                title: 'Error!',
                content: 'Please enter 8 digits for the contact number!',
                type: 'red',
                typeAnimated: true,
                buttons: {
                    tryAgain: {
                        text: 'Try again',
                        btnClass: 'btn-red',
                        action: function () {
                        }
                    }
                },
                columnClass: 'alert-dialog'
            });
            return;
        }
        if (checkWarehouseAddress() == false) {
            $.alert({
                title: 'Error!',
                content: 'Please enter warehouse address!',
                type: 'red',
                typeAnimated: true,
                buttons: {
                    tryAgain: {
                        text: 'Try again',
                        btnClass: 'btn-red',
                        action: function () {
                        }
                    }
                },
                columnClass: 'alert-dialog'
            });
            return;
        }
        // Get the updated values
        var contactNumber = $("#contact").val();
        var warehouseAddress = $("#warehouse-address").val();

        // Create the data object to be sent in the AJAX request
        var data = {
            contactNumber: contactNumber,
            warehouseAddress: warehouseAddress
        };

        $.confirm({
            title: 'Update Confirmation',
            content: 'Do you want to update user profile?',
            type: 'blue',
            typeAnimated: true,
            buttons: {
                confirm: {
                    text: 'Yes',
                    btnClass: 'btn-blue',
                    action: function () {
                        updateUserAjax(data);
                        swal({
                            title: 'Update Successfully!',
                            text: '',
                            icon: 'success',
                            button: 'OK'
                        }).then(function () {
                            $("#warehouse-address").attr("readonly", "true");
                            $("#contact").attr("readonly", "true");
                            $("#cancel").attr("hidden", "true");
                            $("#update").attr("hidden", "true");
                            $("#reminder").attr("hidden", "true");
                            $("#edit").removeAttr("hidden");
                            $('#cPwd').removeAttr("hidden");
                            window.location.reload();
                        });
                    }
                },
                cancel: {
                    text: 'No',
                    action: function () {
                    }
                }
            },
            columnClass: 'alert-dialog'
        });

    });

});

function updateUserAjax(data) {

    // Send the AJAX request
    $.ajax({
        url: "updateUser.php",
        type: "POST",
        data: data,
        success: function (response) {
            // Handle the response from the updateUser.php file
            console.log(response);
        },
        error: function (xhr, status, error) {
            // Handle the AJAX request error
            console.error(error);
        }
    });
}





