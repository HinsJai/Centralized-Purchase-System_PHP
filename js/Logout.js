$(document).ready(function () {
    $(".logout").click(function () {
        $.confirm({
            title: 'Logout Confirmation',
            content: 'Do you want to logout?',
            type: 'red',
            typeAnimated: true,
            buttons: {
                confirm: {
                    text: 'Yes',
                    btnClass: 'btn-red',
                    action: function () {
                        localStorage.clear();
                        itemList = [];
                        window.location = 'HeaderFunctions.php?op=logout';
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