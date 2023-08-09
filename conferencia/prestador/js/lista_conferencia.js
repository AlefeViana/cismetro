function geraTabela(data = null, hora = null, procedimento = null, protocolo = null, fornecedor = null, profissional = null, paciente = null, cdsolcons = null) {

    $('#listapac').DataTable({
        "bLengthChange": false,
        "bDestroy": true,
        stateSave: true,
        "language": {
            url: '//cdn.datatables.net/plug-ins/1.12.1/i18n/pt-BR.json'
        },
        ajax: {
            url: './conferencia/prestador/load/load_conferencia.php',
            "method": "POST",
            "dataSrc": "data",
            data: {
                data:           data            != null ? data          : null,
                hora:           hora            != null ? hora          : null,
                procedimento:   procedimento    != null ? procedimento  : null,
                protocolo:      protocolo       != null ? protocolo     : null,
                fornecedor:     fornecedor      != null ? fornecedor    : null,
                profissional:   profissional    != null ? profissional  : null,
                paciente:       paciente        != null ? paciente      : null,
                cdsolcons:      cdsolcons       != null ? cdsolcons     : null
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
                data: { 'CdSolCons': 'CdSolCons', 'aghash': 'aghash', 'NmPaciente': 'NmPaciente', 'tipoatend': 'tipoatend' },
                render: function (id) {

                    var btn_acao = '';

                    if (id.CdSolCons != null) {

                        if (id.tipoatend == 'T' && id.aghash != null) {
                            btn_acao += `<button type="button" title="Video Conferencia" style="display: inline-block;" id="conferencia_${id.CdSolCons}" class="btn btn-success conferencia"
                                            data-cdsolcons="${id.CdSolCons}" 
                                            data-hash="${id.aghash}"
                                            data-prof="${id.NmProf}"
                                            data-cdpac="${id.CdPaciente}"
                                        >Iniciar Atendimento</button>`;
                        } else {
                            btn_acao += `<button type="button" title="Video Conferencia" style="display: inline-block;"  class="btn btn-warning conferencia_agenda" data-cdsolcons="${id.CdSolCons}">Agenda Fornecedor</button>`;
                        }
                    }

                    return btn_acao;
                },
            },
        ]
    });
}

function recarregar() {
    $('#listapac').DataTable().destroy();

    let data = $('#data').val() != null ? $('#data').val() : null;
    let hora = $('#hora').val() != null ? $('#hora').val() : null;
    let procedimento = +$('#proc').val() > 0 ? $('#proc').val() : null;
    let protocolo = +$('#protocolo').val() > 0 ? $('#protocolo').val() : null;
    let fornecedor = +$('#forn').val() > 0 ? $('#forn').select2('data')[0].Cdforn : null;
    let profissional = +$('#prof').val() > 0 ? $('#prof').select2('data')[0].CdProf : null;
    let paciente = $('#pac').val()

    geraTabela(data, hora, procedimento, protocolo, fornecedor, profissional, paciente, null);
}

$(document).ready(function () {

    $(document).on('click', '.conferencia_agenda', function(){
        let cdsolcons = $(this).data('cdsolcons');
        window.open(`./index.php?i=14&search%5Bscope%5D=&search%5Binput%5D=${cdsolcons}&search%5Bfilters%5D=id`, "_self");
        
    })


    function limpar_campos() {

        $('#data, #hora, #prof, #protocolo, #forn').empty();
        $('#data, #hora, #prof, #protocolo, #forn').val(null);
        $('#pac, #proc').val(0);
    }

    if ($('#agenda_fornecedor').val() > 0) {
        geraTabela(null, null, null, null, null, null, null, $('#agenda_fornecedor').val());

        let id = '#conferencia_' + $('#agenda_fornecedor').val();

        let tempo_espera;
        tempo_espera = setTimeout(function () {
            $(`${id}`).trigger('click');

            clearTimeout(tempo_espera);
        }, 1000);

    } else {
        geraTabela(null, null, null, null, null, null, null, null);
    }

    $('#pac').select2({
        language: "pt-BR",
        ajax: {
            url: './conferencia/prestador/load/load_paciente.php',
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
                    url: './conferencia/prestador/load/load_fornecedor.php',
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
                    url: './conferencia/prestador/load/load_profissional.php',
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
                    url: './conferencia/prestador/load/load_procedimento.php',
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
            url: './conferencia/prestador/submit/submit_recepcao.php',
            type: 'POST',
            dataType: 'json',
            data: {
                cdsolcons: cdsolcons,
                cdpaciente: paciente
            },
            success: function (resposta) {

                if (resposta) {
                    $(conferencia).attr('disabled', false);
                    $(presenca).css('background', 'limegreen');
                } else {
                    $(conferencia).attr('disabled', true);
                    $(presenca).css('background', 'red');
                }

            }
        })
    })
});

