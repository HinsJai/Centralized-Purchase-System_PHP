//set the order time
/**
 * @param {Date} date
 */
const timeElement = document.querySelector(".time");
const dateElement = document.querySelector(".date");

function formatTime(date) {
    const hours12 = date.getHours() % 12 || 12;
    const minutes = date.getMinutes();
    const isAm = date.getHours() < 12;

    return `${hours12.toString().padStart(2, "0")}:${minutes
        .toString()
        .padStart(2, "0")} ${isAm ? "AM" : "PM"}`;
}

/**
 * @param {Date} date
 */
function formatDate(date) {
    const DAYS = [
        "Sunday",
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday",
        "Saturday"
    ];
    const MONTHS = [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December"
    ];

    return `${DAYS[date.getDay()]}, ${MONTHS[date.getMonth()]
    } ${date.getDate()} ${date.getFullYear()}`;
}

if (timeElement && dateElement) {
    setInterval(() => {
        const now = new Date();
        timeElement.textContent = formatTime(now);
        dateElement.textContent = formatDate(now);
    }, 200);
}

$(document).ready(function () {
    $("#submitOrder").click(function (e) {
        e.preventDefault();
        var deliveryAddress = $("#deliveryAddress").val().trim();
        var deliveryDate = $("#deliveryDate").val();
        var selectedQty = $("#selected_Qty").val();

        if (!deliveryAddress || !deliveryDate || deliveryDate < new Date().toISOString().split('T')[0]) {
            // Validation error, show an alert
            $.alert({
                title: 'Error!',
                content: 'Please enter a valid delivery address or delivery date!',
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
            return false;
        }

        if (selectedQty === '') {
            $.alert({
                title: 'Error!',
                content: 'Please enter valid Qty!',
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
            return false;
        }

        console.log(selectedQty);

        $.confirm({
            title: 'Submit Confirmation',
            content: 'Do you want to submit the order?',
            type: 'blue',
            typeAnimated: true,
            buttons: {
                confirm: {
                    text: 'Yes',
                    btnClass: 'btn-red',
                    action: function () {
                        insertOrder();
                        swal({
                            title: 'Successful Submission!',
                            text: '',
                            icon: 'success',
                            button: 'OK'
                        }).then(function () {
                            localStorage.setItem("itemList", JSON.stringify(itemList = []));
                            window.location = 'ProductList.php';
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

function insertOrder() {
    // User confirmed the order submission

    // Get the necessary data for the order
    var orderID = $("#orderID").val();
    var purchaseManagerID = $("#purchaseManagerID").val();
    var deliveryAddress = $("#deliveryAddress").val();
    var deliveryDate = $("#deliveryDate").val();
    var itemList = localStorage.getItem("itemList");

    // Create the order object
    var order = {
        orderID: orderID,
        purchaseManagerID: purchaseManagerID,
        deliveryAddress: deliveryAddress,
        deliveryDate: deliveryDate,
        itemList: itemList
    };

    // Send an AJAX request to insert the order into the database
    $.ajax({
        url: 'InsertOrder.php', // Replace with the actual PHP file for inserting the order into the database
        type: 'POST',
        data: order,
        success: function (response) {
        },
        error: function (xhr, status, error) {
            // Handle the error response
            alert("An error occurred while submitting the order. Please try again.");
            console.error(error);
        }
    });
}

$(document).ready(function () {
    $("#reset").click(function () {
        $.confirm({
            title: 'Cancel Confirmation',
            content: 'Do you want to cancel order?',
            type: 'red',
            typeAnimated: true,
            buttons: {
                confirm: {
                    text: 'Yes',
                    btnClass: 'btn-red',
                    action: function () {
                        //    localStorage.clear();
                        //  itemList = [];
                        localStorage.setItem('itemList', JSON.stringify(itemList = []));
                        window.location = 'ProductList.php';
                    }
                },
                cancel: {
                    text: 'No',
                    action: function () {
                    }
                }
            },
            columnClass: 'alert-dialog' // Add custom CSS classes for width and centering
        });
    });
});


function updateQty(input, event) {

    event.preventDefault();

    var row = input.closest('tr');
    var selectedQty = parseInt(input.value); // Convert to a number
    var stockQty = parseInt(row.cells[4].textContent);
    var itemAmount = parseFloat(row.cells[6].textContent);

    if (selectedQty <= 0 || selectedQty > stockQty || $(input).val().trim() === '') {
        $.alert({
            title: 'Error!',
            content: 'Please enter a valid quantity!',
            type: 'red',
            typeAnimated: true,
            buttons: {
                tryAgain: {
                    text: 'Try again',
                    btnClass: 'btn-red',
                    action: function () {
                        input.value = '';
                        input.focus();
                    }
                }
            },
            columnClass: 'alert-dialog'
        });
        return;
    }

    var row = input.closest('tr');
    var itemID = row.cells[0].textContent;
    var selectedQty = parseInt(input.value); // Convert to a number
    var price = parseFloat(row.cells[3].textContent);
    var amount = selectedQty * price;
    var totalAmount = 0;

    // Update the amount in the table
    row.cells[6].textContent = amount.toFixed(2);

    itemList = JSON.parse(localStorage.getItem('itemList')) || [];

    var index = itemList.findIndex(function (item) {
        return item.itemID === itemID;
    });

    if (index !== -1) {
        var item = itemList[index];
        item.qty = selectedQty;
        localStorage.setItem('itemList', JSON.stringify(itemList));

        // Update the total amount
        var totalAmountElement = document.getElementById("total_amount");

        totalAmount = calculateTotal(itemList);
        totalAmountElement.textContent = 'Total Amount $HKD = ' + totalAmount.toFixed(2);

        // Send an AJAX request to update the item on the server-side
        $.post('CreateOrder.php', {
            itemList: itemList,
            updateItemQty: selectedQty
        });

        updateDiscountRate(totalAmount);
        updateDiscountTotalAmount(totalAmount);
    }
}


var totalAmount = 0;

function calculateAndDisplayTotalAmount() {
    itemList = JSON.parse(localStorage.getItem('itemList')) || [];
    var totalAmountElement = document.getElementById("total_amount");

    if (totalAmountElement) {
        totalAmount = calculateTotal(itemList);
        totalAmountElement.textContent = 'Total Amount $HKD = ' + totalAmount.toFixed(2);
    }
}


window.addEventListener('load', function () {
    calculateAndDisplayTotalAmount();
    updateDiscountRate(totalAmount);
    updateDiscountTotalAmount(totalAmount);
});

function calculateTotal(itemList) {
    var total = 0;
    for (var i = 0; i < itemList.length; i++) {
        var item = itemList[i];
        total += item.price * item.qty;
    }
    return total;
}


function deleteItem(button, event) {
    event.preventDefault();
    var row = button.closest('tr');
    var itemID = row.cells[0].textContent;

    $.confirm({
        title: 'Delete Confirmation',
        content: `Do you want to delete Item ID: ${itemID} ?`,
        type: 'red',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: 'Yes',
                btnClass: 'btn-red',
                action: function () {

                    row = button.closest('tr');
                    itemID = row.cells[0].textContent;

                    itemList = JSON.parse(localStorage.getItem('itemList')) || [];

                    // Find the index of the item with the matching itemID in the itemList
                    var index = itemList.findIndex(function (item) {
                        return item.itemID === itemID;
                    });

                    if (index !== -1) {
                        // Remove the item from the itemList
                        itemList.splice(index, 1);

                        // Remove the item from localStorage
                        localStorage.setItem('itemList', JSON.stringify(itemList));

                        // Update the total amount
                        var totalAmountElement = document.getElementById("total_amount");
                        totalAmount = calculateTotal(itemList);
                        totalAmountElement.textContent = 'Total Original Amount $HKD = ' + totalAmount.toFixed(2);
                        updateDiscountRate(totalAmount);
                        updateDiscountTotalAmount(totalAmount);

                        // Send an AJAX request to delete the item on the server-side
                        $.post('CreateOrder.php', {
                            itemList: itemList,
                            deleteItemID: itemID
                        });

                        // Remove the row from the table
                        if (itemList.length == 0) {
                            window.location = 'ProductList.php';
                        } else {
                            row.remove();
                        }

                    }
                }
            },
            cancel: {
                text: 'No',
                action: function () {
                }
            }
        },
        columnClass: 'alert-dialog' // Add custom CSS classes for width and centering
    });
}

function updateDiscountRate(totalOrderAmount) {
    $.get(`http://127.0.0.1:8080/api/discountCalculator?TotalOrderAmount=${totalOrderAmount}`, function (data) {
        var discountRatePercentage = (data.DiscountRate * 100);
        $('#discount_rate').text('Discount Rate: ' + discountRatePercentage + '%');
    });
}

function updateDiscountTotalAmount(totalOrderAmount) {
    $.get(`http://127.0.0.1:8080/api/discountCalculator?TotalOrderAmount=${totalOrderAmount}`, function (data) {
        $('#new_total_amount').text('After Discount Total Amount $HKD = ' + (data.NewOrderAmount).toFixed(2));
    });
}

function calculateTotal(itemList) {
    var total = 0;
    for (var i = 0; i < itemList.length; i++) {
        var item = itemList[i];
        total += item.price * item.qty;
    }
    return total;
}

