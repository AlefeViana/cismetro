$(document).ready(function () {

    comandos();

    var status = false;
    $(document).on('click', '.conferencia', function () {

        if (!status) {
            var pac = $(this).data('pac');
            var cdsolcons = $(this).data('cdsolcons');

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
                        url: './conferencia/municipio/load/load_verifica_protocolo.php',
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

                                $('#filtros').slideUp("slow");
                                $('#bloco').slideDown("slow");

                                BindEvent();
                                $('#btnScreenShareCustom').prop('disabled', false);
                                $('#btnCustomCamera').prop('disabled', false);
                                $('#btnHangup').prop('disabled', false);
                                $('#btnCustomMic').prop('disabled', false);
                                $('#legenda').hide();
                                StartMeeting(resposta.hash, pac);

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

    $(document).on('click', '#btnHangup', function () {
        $('#meet').empty();
        comandos();
        $('#filtros').slideDown("slow");
        $('#bloco').slideUp("slow");
        status = false;
    });
});

function comandos() {

    let text = '';

    text += `   <div id="legenda">Nenhum atendimento em andamento.</div>
                <div id="toolbox" class="toolbox" style="margin: auto 0; text-align: center;">
                    <button class="btn btn-secondary"    disabled    id='btnScreenShareCustom'   style="width: 130px">   Bate Papo          </button>
                    <button class="btn btn-primary  "    disabled    id='btnCustomCamera'        style="width: 150px">   Ligar Câmera       </button>
                    <button class="btn btn-danger   "    disabled    id='btnHangup'              style="width: 130px">   Encerrar           </button>
                    <button class="btn btn-primary  "    disabled    id='btnCustomMic'           style="width: 165px">   Ligar Microfone    </button>
                </div>`;

    $('#meet').append(text);
}

function BindEvent() {

    $("#btnHangup").on('click', function () {
        apiObj.executeCommand('hangup');
    });
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

function StartMeeting(hash, pac) {

    const domain = 'meet.jit.si';
    const options = {
        roomName: hash,
        width: '100%',
        height: 550,
        parentNode: document.querySelector('#meet'),
        DEFAULT_REMOTE_DISPLAY_NAME: 'New User',
        userInfo: {
            displayName: pac
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
                $("#btnCustomCamera").text('Ligar Video');
            else
                $("#btnCustomCamera").text('Desligar Video');
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
