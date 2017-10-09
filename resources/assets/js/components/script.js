$(document).on('load', function () {
    alert("Hello! I am an alert box!!");
    
    $('#top-menu').slicknav({
        label: '',
        prependTo: '#menu-wrap'
    });
    $('#fitur-list').lightSlider({
        item: 1,
        loop: false,
        slideMove: 1,
        slideMargin: 0,
        controls: false
    });
    $('#fitur-list .column').matchHeight();
    $('#home-teknologi .col-md-4').matchHeight();
});