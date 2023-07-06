let itemList = [];
var totalAmount = 0;
var discountRate = "";
var newOrderAmount = "";


function addItemToArray(button) {

    // Check if quantity is valid
    var row = button.closest('tr');
    var qty = row.querySelector('.add_Qty').value;
    var stockQty = parseInt(row.cells[5].textContent);
    if (qty == 0 || qty > stockQty) {
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
                        row.querySelector('.add_Qty').value = '';
                    }
                }
            },
            columnClass: 'alert-dialog'
        });
        return;
    }

    var row = button.closest('tr');
    var itemID = row.cells[0].textContent;
    var itemName = row.cells[3].textContent;
    var itemImg = row.querySelector('.item_img').src;
    var price = row.cells[6].textContent;
    var qty = row.querySelector('.add_Qty').value;
    var stockQty = parseInt(row.cells[5].textContent);

    // Check if the item with the same itemID already exists in itemList
    var existingItem = itemList.find(function (item) {
        return item.itemID === itemID;
    });

    if (existingItem) {
        // Increase the quantity of the existing item
        existingItem.qty = parseInt(existingItem.qty) + parseInt(qty);
    } else {
        var itemData = {
            itemName: itemName,
            itemID: itemID,
            itemImg: itemImg,
            price: price,
            qty: qty,
            stockQty: stockQty
        };

        itemList.push(itemData);
    }

    console.log(itemList);

    // Notify successful item addition
    alertify.set('notifier', 'position', 'top-right');
    alertify.success('Add item ID: ' +
        itemID + ' item Name: ' + itemName + ' Qty: ' + qty + ' successfully!');
}

function placeOrder() {
    // Use AJAX to submit the itemList array to CreateOrder.php using POST method
    if (itemList.length == 0) {
        $.alert({
            title: 'Error!',
            content: 'Please add at least one item!',
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
    } else {
        totalAmount = calculateTotal(itemList);
        discountCalculator(totalAmount);
        setItemList();
    }
}

function discountCalculator(totalAmount) {

    $.ajax({
        url: `http://127.0.0.1:8080/api/discountCalculator?TotalOrderAmount=${totalAmount}`,
        type: 'GET',
        dataType: 'json', // Set the expected data type as JSON
        success: function (response) {
            discountRate = JSON.stringify(response.DiscountRate);
            newOrderAmount = JSON.stringify(response.NewOrderAmount);


            $.ajax({
                url: 'SetDiscountCalculatorSession.php',
                type: 'POST',
                data: {
                    discountRate: discountRate,
                    newOrderAmount: newOrderAmount
                },
                success: function () {
                    console.log("Set session successfully");
                },
                error: function (err) {
                    console.log(err)
                }
            }
            )
            console.log(discountRate);
        },
        error: function (error) {
            alert(error)
            // Handle error if the discount calculator API request fails
        }
    });
}


function setItemList() {

    $.ajax({
        url: 'CreateOrder.php',
        type: 'POST',
        data: {
            itemList: itemList
        },
        success: function (response) {
            // Handle the response from CreateOrder.php
            window.location.href = 'CreateOrder.php';
        },
        error: function (error) {
            console.log(error);
        }
    });
}


function performSearch() {
    var searchBy = document.getElementById('searching').value;
    if (searchBy == "supplier_id") {
        searchBy = "item.supplierID";
    } else if (searchBy == "supplier_name") {
        searchBy = "companyName";
    } else if (searchBy == "item_id") {
        searchBy = "itemID";
    } else if (searchBy == "item_name") {
        searchBy = "itemName";
    }

    var searchValue = document.getElementById('search_bar').value;
    var searchURL = "ProductList.php?searchBy=" + searchBy + "&searchValue=" + searchValue;
    window.location.href = searchURL;
}

function calculateTotal(itemList) {
    var total = 0;
    for (var i = 0; i < itemList.length; i++) {
        var item = itemList[i];
        total += parseFloat(item.price) * parseInt(item.qty);
    }
    return total;
}




// Load itemList from localStorage on page load
window.addEventListener('DOMContentLoaded', function () {
    var storedItemList = localStorage.getItem('itemList');
    console.log(storedItemList);
    if (storedItemList) {
        itemList = JSON.parse(storedItemList);
    }
});

window.addEventListener('beforeunload', function () {
    localStorage.setItem('itemList', JSON.stringify(itemList));
});


