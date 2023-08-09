// FUNCAO QUE REALIZA A CONSTRUCAO DA VIDEO CONFERENCIA
function StartMeeting(hash, prof) {

    const domain = 'meet.jit.si';
    const options = {
        roomName: hash,
        width: '100%',
        height: 550,
        parentNode: document.querySelector('#meet'),
        DEFAULT_REMOTE_DISPLAY_NAME: 'New User',
        userInfo: {
            displayName: prof
        },
        configOverwrite: {
            doNotStoreRoom: true,
            startVideoMuted: 0,
            startWithVideoMuted: true,
            startWithAudioMuted: true,
            enableWelcomePage: false,
            prejoinPageEnabled: false,
            disableRemoteMute: true,
            remoteVideoMenu: {
                disableKick: true
            },
        },
        interfaceConfigOverwrite: {
            filmStripOnly: false,
            SHOW_JITSI_WATERMARK: false,
            SHOW_WATERMARK_FOR_GUESTS: false,
            DEFAULT_REMOTE_DISPLAY_NAME: 'New User',
            TOOLBAR_BUTTONS: []
        },
        onload: function () {
            $('#joinMsg').hide();
            $('#container').show();
            $('#toolbox').show();
        }
    };

    apiObj = new JitsiMeetExternalAPI(domain, options);

    apiObj.addEventListeners({
        readyToClose: function () {
            $('#meet').empty();
        },

        audioMuteStatusChanged: function (data) {
            if (data.muted)
                $("#btnCustomMic").text('Ligar Microfone');
            else
                $("#btnCustomMic").text('Desligar Microfone');
        },
        videoMuteStatusChanged: function (data) {
            if (data.muted)
                $("#btnCustomCamera").text('Ligar Câmera');
            else
                $("#btnCustomCamera").text('Desligar Câmera');
        },
        tileViewChanged: function (data) {

        },
        screenSharingStatusChanged: function (data) {
            if (data.on)
                $("#btnScreenShareCustom").text('Stop SS');
            else
                $("#btnScreenShareCustom").text('Start SS');
        },
        participantJoined: function (data) {
            console.log('participantJoined', data);
        },
        participantLeft: function (data) {
            console.log('participantLeft', data);
        },
        participantRoleChanged: function (event) {
            if (event.role === "moderator") {
                apiObj.executeCommand('password', 'The Password');
            }
        },
        passwordRequired: function () {
            apiObj.executeCommand('password', 'The Password');
        }
    });

    apiObj.executeCommand('subject', 'New Room 2');
};

// FUNCAO QUE INSERE OS BOTOES DA VIDEO CONFERENCIA
function comandos() {

    let text = '';
    let text_2 = '';

    text += `   <div id="legenda">Nenhum atendimento em andamento.</div>
                <div id="toolbox" class="toolbox" style="margin: auto 0; text-align: center;">
                    <button class="btn btn-secondary"    disabled    id='btnScreenShareCustom'   style="width: 130px">   Bate Papo  </button>
                    <button class="btn btn-primary  "    disabled    id='btnCustomCamera'        style="width: 150px">   Câmera     </button>
                    <button class="btn btn-danger   "    disabled    id='btnHangup'              style="width: 130px">   Encerrar   </button>
                    <button class="btn btn-primary  "    disabled    id='btnCustomMic'           style="width: 165px">   Microfone  </button>
                </div>`;

    text_2 += ` <div id="toolbox" class="toolbox" style="margin: auto 0; text-align: center;">
                    <button class="btn btn-primary col-3" id='evolucao'     data-acao="evolucao">   Evolução Clinica</button>
                    <button type="button" class="btn btn-primary prontuario"><i class="fas fa-clipboard-list" title="Prontuário"></i></button>
                    <button class="btn btn-primary col-3" id='receituario'  data-acao="receituario">Receituário</button>
                </div>`;

    $('#text_pac').append(text_2);
    $('#meet').append(text);
}

// FUNCAO QUE EXECUTA OS COMANDOS DOS BOTOES DA VIDEO CONFERENCIA
function BindEvent() {

    $("#btnCustomMic").on('click', function () {
        apiObj.executeCommand('toggleAudio');
    });
    $("#btnCustomCamera").on('click', function () {
        apiObj.executeCommand('toggleVideo');
    });
    $("#btnScreenShareCustom").on('click', function () {
        apiObj.executeCommand('toggleChat');
    });
}

/* ======================================================================= READY ======================================================================= */

$(document).ready(function () {

    $(document).on('click', '.prontuario', function () {
        window.open(`./index.php?i=47&met=p&CdPaciente=${$('#cdpaciente').val()}`, "_blank");
    })

    $('.mceIframeContainer').css('width', '100%').css('minHeight', '240px');
    comandos();

    // TRANSOFRMA O TEXTAREA SELECIONADO EM TINYMCE
    tinymce.init({
        selector: '#receituario_pac',
        height: "492px",
        resize: false,
        // FUNCAO EXECUTADA A CADA ALTERACAO DENTRO DO TINYMCE
        setup: function (receituario_pac) {
            receituario_pac.on('change', function () {

                if (acao == 'receituario') {
                    sessionStorage.setItem('receituario', tinymce.get('receituario_pac').getContent());
                } else if (acao == 'evolucao') {
                    sessionStorage.setItem('evolucao', tinymce.get('receituario_pac').getContent());
                }

                $.ajax({
                    url: './conferencia/prestador/submit/submit_receituario.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        acao: acao,
                        cdsolcons: $('#cdsolcons').val(),
                        profissional: 1,
                        texto: tinymce.get('receituario_pac').getContent(),
                        cdpaciente: $('#cdpaciente').val(),
                    }, error: function (e) {
                    }, success: function (data) {
                    }
                })
            });
        }
    });

    // FUNCAO QUE ENCERRA A VIDEO CONFERENCIA
    $(document).on('click', '#btnHangup', function () {
        Swal.fire({
            icon: 'info',
            title: "Encerrar Atendimento",
            text: "O Atendimento foi realizado?",
            showCancelButton: true,
            confirmButtonText: 'Sim, foi realizado',
            cancelButtonText: 'Não foi realizado!',
        }).then((result) => {
            if (result.value) {

                $.ajax({
                    url: './conferencia/prestador/submit/submit_atualiza_agendamento.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        cdsolcons: $('#cdsolcons').val()
                    }
                })

                apiObj.executeCommand('hangup');
                window.open(`./cismetro/index.php?i=357`, "_self");
            } else {
                apiObj.executeCommand('hangup');
            }

            $('#meet').empty();
            comandos();
            $('#filtros').slideDown("slow");
            $('#bloco').slideUp("slow");
            status = false;

            $('#pac').trigger('change');

        })
    });

    // FUNCAO QUE CARREGA OS DADOS DE RECEITUARIO E EVOLUCAO CLINICA DENTRO DO TEXTAREA TINYMCE
    var acao = '';
    $(document).on('click', '#evolucao, #receituario', function () {
        acao = $(this).data('acao');

        let codigo = $('#cdsolcons').val();

        tinymce.get('receituario_pac').setContent('');

        if (acao == 'receituario') {
            $('#titulo').text(`RECEITUÁRIO - ${codigo}`);
            tinymce.get('receituario_pac').setContent(sessionStorage.getItem('receituario'));
        } else if (acao == 'evolucao') {
            $('#titulo').text(`EVOLUÇÃO CLÍNICA - ${codigo}`);
            tinymce.get('receituario_pac').setContent(sessionStorage.getItem('evolucao'));
        }
    });

    // FUNCAO QUE INICIA A VIDEO CONFERENCIA
    var status = false;
    $(document).on('click', '.conferencia', function () {

        if (!status) {
            var hash = $(this).data('hash');
            var prof = $(this).data('prof');
            var cdsolcons = $(this).data('cdsolcons');

            $('#cdsolcons').val(cdsolcons);

            $('#titulo').text(`RECEITUÁRIO - ${cdsolcons}`);

            Swal.fire({
                icon: 'info',
                title: "Iniciar Atendimento",
                text: "Digite o protocolo do Agendamento:",
                input: 'text',
                showCancelButton: true,
                confirmButtonText: 'Enviar',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: './conferencia/prestador/load/load_verifica_protocolo.php',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            protocolo: result.value,
                            cdsolcons: cdsolcons
                        }, error: function (e) {
                            Swal.fire({
                                icon: 'error',
                                title: "Protocolo Incorreto!",
                                text: "Protocolo incompativel com o agendamento selecionado!"
                            })

                            status = false;
                        }, success: function (resposta) {
                            if (resposta.status) {

                                $('#cdpaciente').val(resposta.cdpaciente);

                                $('#filtros').slideUp("slow");
                                $('#bloco').slideDown("slow");

                                BindEvent();
                                $('#btnScreenShareCustom').prop('disabled', false);
                                $('#btnCustomCamera').prop('disabled', false);
                                $('#btnHangup').prop('disabled', false);
                                $('#btnCustomMic').prop('disabled', false);
                                $('#legenda').hide();

                                $.ajax({
                                    url: './conferencia/prestador/load/load_carrega_dados.php',
                                    type: 'POST',
                                    dataType: 'json',
                                    data: {
                                        cdsolcons: cdsolcons
                                    }, error: function (e) {
                                    }, success: function (resposta) {

                                        sessionStorage.setItem('receituario', resposta.receituario != null ? resposta.receituario : '');
                                        sessionStorage.setItem('evolucao', resposta.evolucao != null ? resposta.evolucao : '');

                                        $('#receituario').trigger('click');
                                    }
                                })

                                StartMeeting(hash, prof);

                                status = true;
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: "Protocolo Incorreto!",
                                    text: "Protocolo incompativel com o agendamento selecionado!"
                                })

                                status = false;
                            }
                        }
                    })
                }
            });
        }
    });
});


