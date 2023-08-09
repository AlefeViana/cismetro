$(document).ready(function () {

    $('#pac').select2({
        language: "pt-BR",
        ajax: {
            url: './conferencia/municipio/load/load_paciente.php',
            method: 'POST',
            data: function (params) {
                return {
                    q: params.term,
                    page: params.page || 1,
                    protocolo: +$('#protocolo').val() > 0 ? $('#protocolo').val() : null,
                    data: $('#data').val() != null ? $('#data').val() : null,
                    hora: $('#hora').val() != null ? $('#hora').val() : null,
                    fornecedor: +$('#forn').val() > 0 ? $('#forn').select2('data')[0].Cdforn : null,
                    profissional: +$('#prof').val() > 0 ? $('#prof').select2('data')[0].CdProf : null,
                    procedimento: +$('#proc').val() > 0 ? $('#proc').val() : null,
                }
            },
            processResults: function (data) {
                data = JSON.parse(data);

                return {
                    results: data.itens,
                    pagination: {
                        more: data.more
                    }
                }
            },
            cache: false,
        },
        placeholder: 'Selecione um paciente...',
        language: {
            inputTooShort: function () {
                return "Digite ao menos 3 caracteres para a busca";
            }
        },
        width: '100%'
    });

    $(document).on('change', '#forn', function () {
        $('#prof, #proc, #pac').empty();
    });

    $(document).on('change', '#prof', function () {
        $('#proc, #pac').empty();
    });

    $(document).on('change', '#proc, #protocolo, #data, #hora', function () {
        $('#pac').empty();
    });

    $(document).on('change', '#pac', function () {
        recarregar()
        clearInterval(timer)
    });

    var filtro_avancado = false;
    $(document).on('click', '#filtro_acancado', function () {
        if (!filtro_avancado) {

            limpar_campos()

            $('#forn').select2({
                language: "pt-BR",
                ajax: {
                    url: './conferencia/municipio/load/load_fornecedor.php',
                    method: 'POST',
                    data: function (params) {
                        return {
                            q: params.term,
                            page: params.page || 1,
                        }
                    },
                    processResults: function (data) {
                        data = JSON.parse(data);

                        return {
                            results: data.itens,
                            pagination: {
                                more: data.more
                            }
                        }
                    },
                    cache: false,
                },
                placeholder: 'Selecione um Fornecedor...',
                width: '100%'
            });

            $('#prof').select2({
                language: "pt-BR",
                ajax: {
                    url: './conferencia/municipio/load/load_profissional.php',
                    method: 'POST',
                    data: function (params) {
                        return {
                            q: params.term,
                            page: params.page || 1,
                            cdcredforn: +$('#forn').val() > 0 ? $('#forn').val() : null
                        }
                    },
                    processResults: function (data) {
                        data = JSON.parse(data);

                        return {
                            results: data.itens,
                            pagination: {
                                more: data.more
                            }
                        }
                    },
                    cache: false,
                },
                placeholder: 'Selecione um profissional...',
                minimumInputLength: 3,
                language: {
                    inputTooShort: function () {
                        return "Digite ao menos 3 caracteres para a busca";
                    }
                },
                width: '100%'
            });

            $('#proc').select2({
                language: "pt-BR",
                ajax: {
                    url: './conferencia/municipio/load/load_procedimento.php',
                    method: 'POST',
                    data: function (params) {
                        return {
                            q: params.term,
                            page: params.page || 1,
                            cdcredprof: +$('#prof').val() > 0 ? $('#prof').val() : null
                        }
                    },
                    processResults: function (data) {
                        data = JSON.parse(data);

                        return {
                            results: data.itens,
                            pagination: {
                                more: data.more
                            }
                        }
                    },
                    cache: false,
                },
                placeholder: 'Selecione um procedimento...',
                width: '100%'
            });

            $('#filtros_extra').slideDown("slow");

            filtro_avancado = true;
        } else {
            $('#filtros_extra').slideUp("slow");
            filtro_avancado = false;

            limpar_campos()
        }
    })

    $(document).on('click', '#limpar_filtros', function () {
        limpar_campos()
    })

    $('#listapac').DataTable({
        "language": {
            url: '//cdn.datatables.net/plug-ins/1.12.1/i18n/pt-BR.json'
        }
    })

    var timer;
    $(document).on('click', '#sinalizar', function () {

        clearInterval(timer);
        $('.presenca').css('background', 'red');
        $('.conferencia').attr('disabled', true);

        let cdsolcons = $(this).data('cdsolcons');
        let paciente = $(this).data('cdpac');
        let presenca = "#presenca_" + cdsolcons;
        let conferencia = "#conferencia_" + cdsolcons;

        $.ajax({
            url: './conferencia/municipio/submit/submit_recepcao.php',
            type: 'POST',
            dataType: 'json',
            data: {
                cdsolcons: cdsolcons,
                cdpaciente: paciente
            },
            success: function (resposta) {

                if (resposta) {
                    $(presenca).css('background', 'limegreen');

                    timer = setInterval(function () {
                        $.ajax({
                            url: './conferencia/municipio/load/load_confirmacao_presenca_prestador.php',
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                cdsolcons: cdsolcons
                            },
                            success: function (resposta) {

                                if (resposta) {
                                    clearInterval(timer)
                                    $(conferencia).attr('disabled', false);
                                } else {
                                    $(conferencia).attr('disabled', true);
                                }
                            }
                        })
                    }, 1500);

                } else {
                    $(conferencia).attr('disabled', true);
                    $(presenca).css('background', 'red');
                }

            }
        })
    })

    function recarregar() {
        $('#listapac').DataTable().destroy();

        $('#listapac').DataTable({
            "bLengthChange": false,
            "language": {
                url: '//cdn.datatables.net/plug-ins/1.12.1/i18n/pt-BR.json'
            },
            ajax: {
                url: './conferencia/municipio/load/load_conferencia.php',
                "method": "POST",
                "dataSrc": "data",
                data: {
                    data: $('#data').val() != null ? $('#data').val() : null,
                    hora: $('#hora').val() != null ? $('#hora').val() : null,
                    procedimento: +$('#proc').val() > 0 ? $('#proc').val() : null,
                    protocolo: +$('#protocolo').val() > 0 ? $('#protocolo').val() : null,
                    fornecedor: +$('#forn').val() > 0 ? $('#forn').select2('data')[0].Cdforn : null,
                    profissional: +$('#prof').val() > 0 ? $('#prof').select2('data')[0].CdProf : null,
                    paciente: $('#pac').val()
                },
            },
            columns: [
                { data: 'CdSolCons' },
                { data: 'NmPaciente' },
                { data: 'NmEspecProc' },
                {
                    data: { 'DtAgCons': 'DtAgCons' },
                    render: function (id) {

                        const data = moment(id.DtAgCons).format('DD/MM/YYYY');

                        return data;
                    },
                },
                {
                    data: { 'CdSolCons': 'CdSolCons', 'aghash': 'aghash', 'NmPaciente': 'NmPaciente' },
                    render: function (id) {
                        if (id.CdSolCons != null) {

                            var btn_acao = `
                                    <button class="btn btn-info receituario" id="receituario" style="color: white; margin-right: 10px; display: inline-block;" title="Receituario"
                                        data-cdsolcons="${id.CdSolCons}">Receituário</button>
                                    <button class="btn btn-primary sinalizar" id="sinalizar" style="color: white; margin-right: 10px; display: inline-block;" title="Sinalizar Presença"
                                        data-cdsolcons="${id.CdSolCons}"
                                        data-cdpac="${id.CdPaciente}">
                                    <div id="presenca_${id.CdSolCons}" class="presenca" style="width: 10px; height: 10px; margin-right: 5px; border-radius: 50%; background: red; display: inline-block;"></div>Sinalizar Presença</button>
                                    <button type="button" title="Video Conferencia" style="display: inline-block;" id="conferencia_${id.CdSolCons}" class="btn btn-success conferencia" disabled
                                        data-cdsolcons="${id.CdSolCons}" 
                                        data-hash="${id.aghash}"
                                        data-pac="${id.NmPaciente}"
                                        data-cdpac="${id.CdPaciente}"
                                    >Iniciar</button>`;
                        }
                        return btn_acao;
                    },
                },
            ]
        });
    }
});

$(document).on('click', '#receituario', function () {

    $.ajax({
        url: './conferencia/municipio/load/load_receituario.php',
        type: 'POST',
        dataType: 'json',
        data: {
            cdsolcons: $(this).data('cdsolcons')
        }, error: function (e) {
            Swal.fire({
                icon: 'error',
                title: "Erro!",
                text: "Houve uma falha durante o processo de execução!"
            })
        }, success: function (resposta) {
            if (resposta > 0) {
                open(`./prontuario_pac/receituario/imprimir_receituario.php?id=${resposta}`);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: "Receituário",
                    text: "Não existe nenhum receituario vinculado a este agendamento!"
                })
            }
        }
    })
})

function limpar_campos() {

    $('#data, #hora, #prof, #protocolo, #forn').empty();
    $('#data, #hora, #prof, #protocolo, #forn').val(null);
    $('#pac, #proc').val(0);
}

