// Get the order ID
var orderIDString;
var orderID;

$(document).ready(function () {
    $("#delete_order").click(function (e) {
        e.preventDefault();
        orderIDString = document.querySelector('#orderID').innerHTML;
        orderID = parseInt(orderIDString);
        $.confirm({
            title: 'Delete Confirmation',
            content: 'Do you want to delete the order?',
            type: 'blue',
            typeAnimated: true,
            buttons: {
                confirm: {
                    text: 'Yes',
                    btnClass: 'btn-red',
                    action: function () {
                        deleteOrderedItem();
                        swal({
                            title: 'Delete Order Successfully!',
                            text: '',
                            icon: 'success',
                            button: 'OK'
                        }).then(function () {
                            window.location.href = "OrderRecord.php";
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

// Send AJAX request to deleteItem.php
function deleteOrderedItem() {
    $.ajax({
        url: "DeleteOrder.php",
        method: "POST",
        data: {orderID: orderID},
        success: function (response) {
        },
        error: function () {
            // AJAX request failed, display an error message or handle the error accordingly
            alert("Error: Unable to process request.");
        }
    });
}
