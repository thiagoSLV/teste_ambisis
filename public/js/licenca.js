$( document ).ready(function() {
    var date = new Date($('#emissao').html());
    var newDate = date.toString('d-M-y');
    console.log($.format.date(new Date($('#emissao').html()), 'dd M yy'));
    // $("#emissao").html(newDate);
    // $("#emissao").mask('00/00/0000');
});
