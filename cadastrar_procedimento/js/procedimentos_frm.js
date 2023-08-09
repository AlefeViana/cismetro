$(document).ready(function () {
    $('#cd_procedimento').select2();
    $('#selectproc').select2({ width: '100%' });
    $('#cdespecialidade').select2();
    $('#cdgrupoproc').select2();
    $('#servico').select2();
    $('#class').select2();
    $("#commentForm").validate();

    $('#dataini').attr({
        "min": min()
    })

    var valorOld_mun = null;
    var valorOld_sus = null;

    if ($('#acao').val() == 'e') {
        $("#submit-form").prop('disabled', false);
    }

    /* ----------- LISTA PROCEDIMENTOS COM CODIGO SUS JA CADASTRADO -----------*/

    // $('#cdsus').focusout(function () {
    //     $(this).val();
    //     $("#submit-form").prop('disabled', true);

    //     $('#tituloMenu').text('Validando código SUS...');
    //     $('#carregando_op').css({ "display": "inline-block" });

    //     $.ajax({
    //         url: 'cadastrar_procedimento/loads/load_procedimento_cdsus.php',
    //         type: 'POST',
    //         dataType: 'json',
    //         data: {
    //             cdsus: $('#cdsus').val()
    //         },
    //         error: function () {
    //             $("#submit-form").prop('disabled', false);
    //         },
    //         success: function (data) {
    //             const {
    //                 dados
    //             } = data;

    //             var options = '';
    //             var select = 'selected';

    //             if (dados.length > 0) {

    //                 $('#carregando_op').css({ "display": "none" });

    //                 options += '<option value="0"' + select + '>Selecione uma opção...</option>';

    //                 data.dados.forEach(function (item) {
    //                     options += '<option value="' + `${item.CdEspecProc}` + '">' + `${item.NmEspecProc}` + ' | Valor: R$' + `${item.valor}` + ' | Valor SUS: R$' + `${item.valorsus}` + '</option>';
    //                 })

    //                 options += '<option value="0">Desejo realizar um novo cadastro com código SUS repetido</option>';
    //                 $('#selectproc').html(options);

    //                 $('#modal-mensagem').modal('show');

    //                 $('#selectproc').change(function () {
    //                     var valor = $('#selectproc').val();
    //                     if (valor > 0) {
    //                         $('#confirmar-edicao').attr('onclick', "window.location.href='index.php?i=4&s=e&id=" + valor + "&acao=edit'");
    //                     } else {
    //                         $('#confirmar-edicao').attr('onclick', "");
    //                         $("#submit-form").prop('disabled', false);
    //                     }
    //                 });

    //             } else {
    //                 $("#submit-form").prop('disabled', false);
    //             }
    //         }
    //     })
    // });
    $("#submit-form").prop('disabled', false);

    /* ----------- PREENCHE DADOS DO PROCEDIMENTO -----------*/

    if ($('#cdespecproc').val() > 0) {

        $('#carregando_op').css({ "display": "inline-block" });

        $.ajax({
            url: 'cadastrar_procedimento/loads/load_procedimento_preenche_dados_iniciais.php',
            type: 'POST',
            dataType: 'json',
            data: {
                CdEspecProc: $('#cdespecproc').val()
            },
            success: function (data) {

                $('#carregando_op').css({ "display": "none" });

                const item = data.espec.dados[0];

                data.espec.dados.forEach(function (item) {
                    $('#nm_especproc').val(`${item.NmEspecProc}`);
                    $('#cdsus').val(`${item.cdsus}`);
                    $('#valorsus').val('R$ ' + `${item.valorsus}`);
                    $('#valor').val('R$ ' + `${item.valor}`);
                    $('#desc_sus').val(`${item.desc_sus}`);
                    $('#cid').val(`${item.cid}`);
                    $('#tempo_plantao').val(`${item.tempo_plantao}`);
                    $('#cdgrupoproc').val(`${item.cdgrupoproc}`);
                    $("#filiacao").val(`${item.principal}`);
                    $("#quemAgendar").val(`${item.quemAgendar}`);
                    $("#ppi").val(`${item.ppi}`);
                    $("#bpa").val(`${item.bpa}`);
                    $("#status").val(`${item.status}`);
                    $("#preparo").val(`${item.nmpreparo}`);

                    valorOld_mun = `${item.valor}`;
                    valorOld_sus = `${item.valorsus}`;
                });

                if (`${item.cdgrupoproc}` == 0) {
                    $('#cdgrupoproc').val(0);
                } else {
                    $('#cdgrupoproc').append(`<option selected="selected" value="${item.cdgrupoproc}">${item.nmgrupoproc}</option>`);
                }

                if (`${item.CdProcedimento}` == 0) {
                    $('#cd_procedimento').val(0);
                } else {
                    $('#cd_procedimento').append(`<option selected="selected" value="${item.CdProcedimento}">${item.NmProcedimento}</option>`);
                }

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
                if (`${item.cdespecialidade}` == 0) {
                    $('#cdespecialidade').val(0);
                } else {
                    $('#cdespecialidade').append(`<option selected="selected" value="${item.cdespecialidade}">${item.nmespecialidade}</option>`);
                }
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

    function numberify(value) {
        return parseFloat(
            value
                .trim()
                .replace(/^R\$ +/, '')
        ).toFixed(2)
    }

    $(document).on('click', '#submit-form', function () {

        if ($('#nm_especproc').val() != "" && $('#cd_procedimento').val() > 0 && $('#cdsus').val() != ""
            && $('#filiacao').val() != "" && $('#cdespecialidade').val() > 0) {

            Swal.fire({
                icon: 'info',
                title: "Salvar Procedimento",
                text: "Deseja finalizar o cadastro do procedimento?",
                showCancelButton: true,
                confirmButtonText: 'Salvar',
                cancelButtonText: 'Cancelar',
                cancelButtonColor: '#f54545',
                confirmButtonColor: '#28a745',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {

                    if ($('#valor').val() == "") {
                        $('#valor').val('R$ 0.00');
                    }
                    if ($('#valorsus').val() == "") {
                        $('#valorsus').val('R$ 0.00');
                    }

                    $('#tituloMenu').text('Salvando Procedimento...');
                    $('#carregando_op').css({ "display": "inline-block" });

                    $.ajax({
                        url: './cadastrar_procedimento/submit.php',
                        type: 'POST',
                        cache: false,
                        datatype: "json",
                        data: {
                            cdespecproc: $('#cdespecproc').val(),
                            nm_especproc: $('#nm_especproc').val(),
                            cd_procedimento: $('#cd_procedimento').val(),
                            desc_sus: $('#desc_sus').val(),
                            ppi: $('#ppi').val(),
                            bpa: $('#bpa').val(),
                            cdespecialidade: $('#cdespecialidade').val(),
                            cdgrupoproc: $('#cdgrupoproc').val(),
                            nmpreparo: $('#preparo').val(),
                            cid: $('#cid').val(),
                            tempo_plantao: $('#tempo_plantao').val(),
                            servico: $('#servico').val(),
                            class: $('#class').val(),
                            filiacao: $('#filiacao').val(),
                            quemAgendar: $('#quemAgendar').val(),
                            cdsus: $('#cdsus').val(),
                            status: $('#status').val(),
                            periodo: $('#periodo').val(),
                            preconsulta: $('#preconsulta').val(),

                            valorOld: (($('#acao').val() == 'n') ? $('#valor').val() : valorOld_mun),

                            dataini: $('#dataini').val(),
                            datafim: $('#datafim').val(),

                            valor: numberify($('#valor').val()),
                            valorsus: numberify($('#valorsus').val()),
                            acao: $('#acao').val(),
                        },
                        error: function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Falha no Processo',
                                text: 'Erro ao tentar ação!',
                                showConfirmButton: true,
                                confirmButtonText: 'Prosseguir',
                                confirmButtonColor: '#28a745',
                            })
                        },
                        success: function (resposta) {
                            var resposta = JSON.parse(resposta);

                            $('#carregando_op').css({ "display": "none" });

                            if (resposta.status) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Concluído!',
                                    text: resposta.msg,
                                    showCancelButton: false,
                                    confirmButtonText: 'Prosseguir',
                                    confirmButtonColor: '#198754'
                                }).then(() => {
                                    if (resposta.cdlogespec) {
                                        if ($('#acao').val() == 'n') {
                                            myFunction(resposta.cdlogespec);
                                            window.location.href = "index.php?i=4"
                                        }
                                        else if (valorOld_mun != numberify($('#valor').val()) && $('#acao').val() == 'e' || valorOld_sus != numberify($('#valorsus').val()) && $('#acao').val() == 'e') {
                                            myFunction(resposta.cdlogespec);
                                            window.location.href = "index.php?i=4";
                                        } else {
                                        }
                                    }else{
                                        window.location.href = "index.php?i=4";
                                    }
                                });
                            } else {
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Licitação não encontrada!',
                                    text: resposta.msg,
                                    showCancelButton: false,
                                    confirmButtonText: 'Prosseguir',
                                    confirmButtonColor: '#198754'
                                }).then(() => {
                                    window.location.href = "index.php?i=4";
                                });
                            }
                        }
                    });
                }
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Atenção!',
                text: 'Preencha os campos obrigatórios!',
                showConfirmButton: true,
                confirmButtonText: 'Prosseguir',
                confirmButtonColor: '#28a745',
            })
        }
    });
});

function myFunction(CdLogEspec) {
    window.open("relatorio_proc.php?cdlogespec=" + CdLogEspec);
}

function redirecionamento() {

}

jQuery(function ($) {
    $("#cdsus").mask("99.99.99.999-9");
    $("#cid").mask("a99");
    $("#valor").maskMoney(
        {
            prefix: 'R$ ',
            allowNegative: true,
            thousands: '',
            decimal: '.'
        }
    );
    $("#valorsus").maskMoney(
        {
            prefix: 'R$ ',
            allowNegative: true,
            thousands: '',
            decimal: '.'
        }
    );
});

function dataAtual() {
    var data = new Date();
    var dia = String(data.getDate()).padStart(2, '0');
    var mes = String(data.getMonth() + 1).padStart(2, '0');
    var ano = data.getFullYear();

    var dataAtual;

    dataAtual = ano + '-' + mes + '-' + dia;
    return dataAtual;
};

// FUNCAO QUE LIMITA EM ATE 1 MES ANTERIOR A DATA ATUAL
function min() {

    var data = new Date();
    var mes = String(data.getMonth()).padStart(2, '0');
    var ano = data.getFullYear();

    var dataMin;

    dataMin = ano + '-' + mes + '-01';

    return dataMin;
};

// LIMITA A DATA FINAL DE ATUALIZACAO DE VALORES DE AGENDAMENTOS
$(document).on('change', '#dataini', function () {
    let dataFinal = moment($(this).val()).endOf('month');

    $('#datafim').val(null);

    $('#datafim').attr({
        "min": $(this).val(),
        "max": dataFinal.format('YYYY-MM-DD')
    })
})

/* ----------- Adiciona ou remove as datas de vigencia de atualizacao dos agendamentos retroativos -----------*/

function isChecked() {

    if ($('#data-vigente').val() == 0) {
        if (document.getElementById('data-vigente').checked) {
            $('#modal-data').modal('show');

            $('#confirmar-data').click(function () {

                if ($('#dataini').val() != "") {
                    var dataEscolhida = "";
                    var aux;

                    let dataIni = $('#dataini').val();
                    if ($('#datafim').val() <= 0) {
                        $('#datafim').val(dataAtual());
                    }
                    let dataFim = $('#datafim').val();

                    var data_1 = new Date($('#dataini').val());
                    var data_2 = new Date($('#datafim').val());

                    if (data_1 > data_2) {
                        aux = dataFim;
                        dataFim = dataIni;
                        dataIni = aux;
                        $('#dataini').val(dataIni);
                        $('#datafim').val(dataFim);
                    }

                    let dataFormatadaIni = dataIni.split('-').reverse().join('/');;
                    let dataFormatadaFim = dataFim.split('-').reverse().join('/');;

                    dataEscolhida += '<p style="text-align:center" id="data-ini-escolhida" name="data-ini-escolhida" value="' + dataFormatadaIni + '">Data Inicial: ' + dataFormatadaIni + '</p>';
                    dataEscolhida += '<p style="text-align:center" id="data-fim-escolhida" name="data-fim-escolhida" value="' + dataFormatadaFim + '">Data Final: ' + dataFormatadaFim + '</p>';

                    $('#datas-de-vigencia').html(dataEscolhida);

                    $('#data-vigente').val("1");
                } else {
                    $("#data-vigente").prop('checked', false);
                    alert("Escolha uma data inicial!");
                }
            });
            $('#cancelar').click(function () {
                $("#data-vigente").prop('checked', false);
            });
        }
    } else {
        $('#data-ini-escolhida').remove();
        $('#data-fim-escolhida').remove();

        $('#data-vigente').val("0");
    }
};
