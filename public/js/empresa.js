$( document ).ready(function() {
    $("#cnpj").mask("99.999.999/9999-99");
    $(".cnpj").mask("99.999.999/9999-99");
    $("#cep").mask("99999-999");
    $('#licencas').click(function() {
        $('.toggle').toggle();
    });
});
