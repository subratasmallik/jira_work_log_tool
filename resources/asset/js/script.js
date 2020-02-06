$(document).ready(function () {
    $('form').submit(function () {
        pageLoaderOn();
    });
});
function pageLoaderOn() {
    $('#pageLoader').show();
}
function pageLoaderOff() {
    $('#pageLoader').hide();
}
function randomNumber() {
    return Math.floor((Math.random() * 123456789) + 1);
}