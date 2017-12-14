$(document).ready(function(){
    $('.button').click(function(){
        var clickBtnValue = $(this).val();
        var url = 'addtocart.php',
        data =  {'action': clickBtnValue};
        $.post(url, data, function (response) {
            // Response div goes here.
            alert("action performed successfully");
        });
    });

});