var selectBoxElement = document.querySelector("#select_box");
dselect(selectBoxElement, {
    search: true
});


function orderSearch() {
    searchValue = document.getElementById('select_box').value;
    var searchURL = `OrderRecord.php?searchValue=${searchValue}`;
    window.location.href = searchURL;
}
