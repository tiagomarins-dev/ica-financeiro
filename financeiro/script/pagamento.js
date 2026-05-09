

function apagarPagamento(event, id_pagamento) {

    event.preventDefault(event);

    var r = confirm("Deseja excluir esse tipo de pagamento?");

    if (r == true) {

        $.ajax({
            type: 'POST',
            data: {
                id:id_pagamento
            },
            url: 'script/apagarPagamento.php',

            success: function (_response) {

                // openPagePagamentos(event, id_agenda);
            }
        });
    }
}