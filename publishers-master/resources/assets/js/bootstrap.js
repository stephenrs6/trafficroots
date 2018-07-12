/*globals require, $*/
window.$ = window.jQuery = require('jquery');
window.moment = require('moment');

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('a[href="#"]').click(e => e.preventDefault());

$.fn.goTo = function () {
    $('html, body').animate({
        scrollTop: $(this).offset().top + 'px'
    }, 'fast');
    return this;
};