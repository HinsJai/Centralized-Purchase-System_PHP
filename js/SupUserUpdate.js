$(document).ready(function () {
    $("#supEdit").click(function (e) {
        e.preventDefault();
        $("#supContact").removeAttr("readonly");
        $("#supAddress").removeAttr("readonly");
        $("#supCancel").removeAttr("hidden");
        $("#supEdit").attr("hidden", "true");
        $("#supPwd").attr("hidden", "true");
        $("#supUpdate").removeAttr("hidden");
        $("#supReminder").removeAttr("hidden");
    });
});


$(document).ready(function () {
    $("#supCancel").click(function (e) {
        e.preventDefault();
        console.log("supEdit")
        $("#supEdit").removeAttr("hidden");
        $("#supCancel").attr("hidden", "true");
        $("#supAddress").removeAttr("readonly");
        $("#supContact").attr("readonly", "true");
        $("#supAddress").attr("readonly", "true");
        $("#supPwd").removeAttr("hidden");
        $("#supUpdate").attr("hidden", "true");
        $("#supReminder").attr("hidden", "true");
        window.location.reload();
    });
});

function checkSupInput() {
    var input = document.getElementById("supContact");
    var pattern = /^\d{8}$/;

    if (!pattern.test(input.value)) {
        input.value = '';
        input.focus();
        return false;
    }
}

function check_supplier_address() {
    var input = document.getElementById("supAddress");
    if (input.value.trim() == "") {
        input.focus();
        return false;
    }

}

$(document).ready(function () {
    $("#supUpdate").click(function (e) {
        e.preventDefault();

        if (checkSupInput() == false) {
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
        if (check_supplier_address() == false) {
            $.alert({
                title: 'Error!',
                content: 'Please enter supplier address!',
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
        var contactNumber = $("#supContact").val();
        var address = $("#supAddress").val();

        // Create the data object to be sent in the AJAX request
        var data = {
            contactNumber: contactNumber,
            address: address
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
                        updateSupUserAjax(data);
                        swal({
                            title: 'Update Successfully!',
                            text: '',
                            icon: 'success',
                            button: 'OK'
                        }).then(function () {
                            $("#supAddress").attr("readonly", "true");
                            $("#supContact").attr("readonly", "true");
                            $("#supCancel").attr("hidden", "true");
                            $("#supUpdate").attr("hidden", "true");
                            $("#supReminder").attr("hidden", "true");
                            $("#supEdit").removeAttr("hidden");
                            $('#supPwd').removeAttr("hidden");
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

function updateSupUserAjax(data) {

    // Send the AJAX request
    $.ajax({
        url: "UpdateUser.php",
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





