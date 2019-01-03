$(document).ready(function() {
    $('select').material_select();
    $('.modal-trigger').leanModal();
    $(".button-collapse").sideNav();
    $('.datepicker').pickadate({
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 200 // Creates a dropdown of 15 years to control year
    });
    $('.tooltipped').tooltip({delay: 50});
});


