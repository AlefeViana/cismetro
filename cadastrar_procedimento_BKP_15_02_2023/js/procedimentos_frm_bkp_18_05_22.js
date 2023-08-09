$(document).ready(function () {
    $('#cd_procedimento').select2();
    $('#selectproc').select2({ width: '100%' });
    $('#cdespecialidade').select2();
    $('#cdgrupoproc').select2();
    $('#servico').select2();
    $('#class').select2();
    $("#commentForm").validate();

    /* ----------- LISTA PROCEDIMENTOS COM CODIGO SUS JA CADASTRADO -----------*/

    $('#cdsus').change(function () {
        $(this).val();
        $.ajax({
            url: 'cadastrar_procedimento/loads/load_procedimento_cdsus.php',
            type: 'POST',
            dataType: 'json',
            data: {
                cdsus: $('#cdsus').val()
            },
            success: function (data) {
                const {
                    dados
                } = data;

                var options = '';
                var select = 'selected';

                if (dados.length > 0) {
                    options += '<option value="0"' + select + '>Selecione uma opção...</option>';

                    data.dados.forEach(function (item) {
                        options += '<option value="' + `${item.CdEspecProc}` + '">' + `${item.NmEspecProc}` + ' | Valor: R$' + `${item.valor}` + ' | Valor SUS: R$' + `${item.valorsus}` + '</option>';
                    })

                    options += '<option value="0">Desejo realizar um novo cadastro com código SUS repetido</option>';
                    $('#selectproc').html(options);

                    $('#modal-mensagem').modal('show');

                    $('#selectproc').change(function () {
                        var valor = $('#selectproc').val();
                        if (valor > 0) {
                            $('#confirmar-edicao').attr('onclick', "window.location.href='index.php?i=4&s=e&id=" + valor + "&acao=edit'");
                        } else {
                            $('#confirmar-edicao').attr('onclick', "");
                        }
                    });
                }
            }
        })
    });

    /* ----------- PREENCHE DADOS DO PROCEDIMENTO -----------*/

    if ($('#cdespecproc').val() > 0) {
        $.ajax({
            url: 'cadastrar_procedimento/loads/load_procedimento_preenche_dados_iniciais.php',
            type: 'POST',
            dataType: 'json',
            data: {
                CdEspecProc: $('#cdespecproc').val()
            },
            success: function (data) {

                const item = data.espec.dados[0];

                data.espec.dados.forEach(function (item) {
                    $('#nm_especproc').val(`${item.NmEspecProc}`);
                    $('#cdsus').val(`${item.cdsus}`);
                    $('#valorsus').val(`${item.valorsus}`);
                    $('#valor').val(`${item.valor}`);
                    $('#desc_sus').val(`${item.desc_sus}`);
                    $('#cid').val(`${item.cid}`);
                    $('#cdgrupoproc').val(`${item.cdgrupoproc}`);
                    $("#filiacao").val(`${item.principal}`);
                    $("#quemAgendar").val(`${item.quemAgendar}`);
                    $("#ppi").val(`${item.ppi}`);
                    $("#bpa").val(`${item.bpa}`);
                    $("#status").val(`${item.status}`);
                });

                $('#cdgrupoproc').append(`<option selected="selected" value="${item.cdgrupoproc}">${item.nmgrupoproc}</option>`);
                $('#cd_procedimento').append(`<option selected="selected" value="${item.CdProcedimento}">${item.NmProcedimento}</option>`);

                if (`${item.co_servico}` > 0) {
                    $("#servico").append(`<option selected="selected" value="${item.co_servico}">${item.no_servico}</option>`);
                    $("#class").append(`<option id="option-class-inicial" selected="selected" value="${item.co_classificacao}">${item.no_classificacao}</option>`);

                    $.ajax({
                        url: 'cadastrar_procedimento/loads/load_procedimento_classificacao.php',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            co_servico: $('#servico').val()
                        },
                        success: function (data) {

                            var verifica = $('#class').val();

                            data.class.forEach(function (item) {
                                if (verifica != `${item.co_classificacao}`) {
                                    $('#class').append(`<option value="${item.co_classificacao}">${item.no_classificacao}</option>`);
                                }
                            });
                        }
                    });
                }

                $('#cdespecialidade').append(`<option selected="selected" value="${item.cdespecialidade}">${item.nmespecialidade}</option>`);
            }
        });
    }

    /* ----------- LISTA GRUPO DE USUARIOS -----------*/

    $.ajax({
        url: 'cadastrar_procedimento/loads/load_procedimento_grupoproc.php',
        type: 'POST',
        dataType: 'json',
        data: {
        },
        success: function (data) {

            var verifica = $('#cdgrupoproc').val();

            data.grupo.forEach(function (item) {
                if (verifica != `${item.cdgrupoproc}`) {
                    $('#cdgrupoproc').append(`<option value="${item.cdgrupoproc}">${item.nmgrupoproc}</option>`);
                }
            })
        }
    });

    /* ----------- LISTA TIPO DE PROCEDIMENTOS -----------*/

    $.ajax({
        url: 'cadastrar_procedimento/loads/load_procedimento_tpprocedimentos.php',
        type: 'POST',
        dataType: 'json',
        data: {
        },
        success: function (data) {

            var verifica = $('#cd_procedimento').val();

            data.tpproc.forEach(function (item) {
                if (verifica != `${item.CdProcedimento}`) {
                    $('#cd_procedimento').append(`<option value="${item.CdProcedimento}">${item.NmProcedimento}</option>`);
                }
            })
        }
    });

    /* ----------- LISTA ESPECIALIDADES -----------*/

    $.ajax({
        url: 'cadastrar_procedimento/loads/load_procedimento_especialidade.php',
        type: 'POST',
        dataType: 'json',
        data: {
        },
        success: function (data) {

            var verifica = $('#cdespecialidade').val();

            data.especialidade.forEach(function (item) {
                if (verifica != `${item.cdespecialidade}`) {
                    $('#cdespecialidade').append(`<option value="${item.cdespecialidade}">${item.nmespecialidade}</option>`);
                }
            })
        }
    });

    /* ----------- LISTA SERVICOS -----------*/

    $.ajax({
        url: 'cadastrar_procedimento/loads/load_procedimento_servico.php',
        type: 'POST',
        dataType: 'json',
        data: {
        },
        success: function (data) {

            var verifica = $('#servico').val();

            data.servico.forEach(function (item) {
                if (verifica != `${item.co_servico}`) {
                    $('#servico').append(`<option value="${item.co_servico}">${item.no_servico}</option>`);
                }
            })
        }
    });

    /* ----------- LISTA CLASSIFICACAO -----------*/
    $('#servico').change(function () {
        if ($('#servico').val() == "0") {
            $('#class').empty();
        }
        $('#option-class-inicial').remove();
        $.ajax({
            url: 'cadastrar_procedimento/loads/load_procedimento_classificacao.php',
            type: 'POST',
            dataType: 'json',
            data: {
                co_servico: $('#servico').val()
            },
            success: function (data) {

                var verifica = $('#class').val();
                var options = "";

                data.class.forEach(function (item) {
                    if (verifica != `${item.co_classificacao}`) {
                        options += (`<option value="${item.co_classificacao}">${item.no_classificacao}</option>`);
                        $('#class').html(options);
                    }
                });
            }
        });
    });

    $(document).on('click', '#submit-form', function () {
        if ($('#nm_especproc').val() != "" && $('#cdsus').val() != "" && $('#cd_procedimento').val() > 0) {

            const form = $('#commentForm').serialize();
            $.ajax({
                url: 'cadastrar_procedimento/submit.php',
                type: 'POST',
                cache: false,
                data: form,
                datatype: "json",
                error: function () {
                    alert('Erro ao tentar ação!');
                },
                success: function () {
                    confirm("Operação Realizada com sucesso!");
                    window.location.href = "index.php?i=4";
                }
            });
        }
    });
});

jQuery(function ($) {
    $("#cdsus").mask("99.99.99.999-9");
    $("#cid").mask("a99");
    $("#valor").maskMoney(
        {
            prefix: 'R$ ',
            allowNegative: true,
            thousands: '',
            decimal: ','
        }
    );
    $("#valorsus").maskMoney(
        {
            prefix: 'R$ ',
            allowNegative: true,
            thousands: '',
            decimal: ','
        }
    );
});

/* ----------- Adiciona ou remove as datas de vigencia de atualizacao dos agendamentos retroativos -----------*/

function isChecked() {

    if ($('#data-vigente').val() == 0) {
        if (document.getElementById('data-vigente').checked) {
            $('#modal-data').modal('show');

            $('#confirmar-data').click(function () {

                var dataEscolhida = "";

                let dataIni = $('#dataini').val();
                let dataFim = $('#datafim').val();

                let dataFormatadaIni = dataIni.split('-').reverse().join('/');;
                let dataFormatadaFim = dataFim.split('-').reverse().join('/');;

                if ($('#dataini').val() <= 0) {
                    dataEscolhida += '<p style="text-align:center" id="data-ini-escolhida" name="data-ini-escolhida" value="">Data Inicial: --/--/----</p>';
                } else {
                    dataEscolhida += '<p style="text-align:center" id="data-ini-escolhida" name="data-ini-escolhida" value="' + dataFormatadaIni + '">Data Inicial: ' + dataFormatadaIni + '</p>';
                }

                if ($('#datafim').val() <= 0) {
                    dataEscolhida += '<p style="text-align:center" id="data-fim-escolhida" name="data-fim-escolhida" value="">Data Final: --/--/----</p>';
                } else {
                    dataEscolhida += '<p style="text-align:center" id="data-fim-escolhida" name="data-fim-escolhida" value="' + dataFormatadaFim + '">Data Final: ' + dataFormatadaFim + '</p>';
                }
                $('#datas-de-vigencia').html(dataEscolhida);

                $('#data-vigente').val("1");
            });
        }
    } else {
        $('#data-ini-escolhida').remove();
        $('#data-fim-escolhida').remove();

        $('#data-vigente').val("0");
    }
};
