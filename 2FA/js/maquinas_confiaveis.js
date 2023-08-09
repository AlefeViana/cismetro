// Preenche a tabela com os dados de acesso registrados no sistema
$.ajax({
    url: './2FA/load/load_maquinas_confiaveis.php',
    type: 'post',
    data: {},
    success: function (response) {
        var maquinas = JSON.parse(response);
        var tabela = $('#tabela_maquinas');

        if (maquinas.mc == null) {
            var linha = $('<tr>');
            linha.append($('<td>').attr('colspan', '5').text('Nenhuma máquina confiável encontrada!'));
            tabela.append(linha);
        } else {
            maquinas.mc.forEach(function (maquina) {
                var linha = $('<tr>');
                linha.append($('<td>').text(maquina.id));
                linha.append($('<td>').text(maquina.navegador));
                linha.append($('<td>').text(maquina.acesso));
                linha.append($('<td>').html(`<button id="excluir" data-maquina="${maquina.id}" type="button" class="btn btn-danger btn-sm">Desconectar</button>`));
                tabela.append(linha);

            });
        }
    }
});

// Adicionar evento de clique ao botao Excluir
$(document).on('click', '#excluir', function () {
    $(this).closest('tr').remove();

    $.ajax({
        url: './2FA/submit/submit.php',
        type: 'post',
        data: {
            acao: 'excluir',
            CdMaquina: $(this).data('maquina')
        },
        success: function (response) {
            var response = JSON.parse(response);

            if (response.erro) {
                icon = 'error';
                title = 'Falha'
            } else {
                icon = 'success';
                title = 'Sucesso';
            }

            swal.fire({
                title: title,
                text: response.msg,
                icon: icon,
                showCancelButton: false,
                confirmButtonText: 'Prosseguir'
            });
        }
    });
});

$(document).on('click', '#sms, #email', function () {
    let acao = $(this).data('acao');
    $('#sms, #email').attr('disabled', true);

    var tempoRestante = 60; // tempo restante em segundos
    var intervalo = setInterval(function () {
        document.getElementById("contador").innerHTML = "Enviar novamente em: " + tempoRestante + " segundos"; // atualiza o valor do span
        tempoRestante--; // subtrai um segundo do tempo restante
        if (tempoRestante < 0) {
            clearInterval(intervalo); // para a contagem regressiva quando o tempo restante for zero
            $('#sms, #email').attr('disabled', false);
            $('#contador').empty();
        }
    }, 1000); // atualiza o valor do span a cada segundo

    $.ajax({
        type: "POST",
        url: "./load/load_envio_codigo.php",
        data: {
            acao: acao,
            login: $('#get_login').val()
        },
        dataType: "json",
        success: function (response) {
            if (response.Status) {

                Swal.fire({
                    title: 'Enviado com sucesso!',
                    text: `O código de verificação de ${response.nome} foi enviado para: ${response.censura}`,
                    icon: 'success',
                    showCancelButton: false,
                    confirmButtonText: 'Prosseguir'
                });
            } else {
                Swal.fire({
                    title: 'Código não foi enviado!',
                    text: `Código não pode ser enviado, por favor contate o suporte SITCON`,
                    icon: 'error',
                    showCancelButton: false,
                    confirmButtonText: 'Prosseguir'
                });
            }
        }
    })
});

let requesting = false;

$(document).on('keyup', '#2fa', function () {
    if (requesting) return;
    var codigo = $(this).val();
    if (codigo.length === 7) {
        requesting = true;
        $.ajax({
            type: "POST",
            url: "./submit/submit.php",
            data: {
                acao: 'novo',
                login: $('#get_login').val(),
                codigo: $('#2fa').val()
            },
            dataType: "json",
            success: function (response) {

                if (response.erro) {
                    icon = 'error';
                    title = 'Falha'

                    Swal.fire({
                        title: title,
                        text: response.msg,
                        icon: icon,
                        showCancelButton: false,
                        confirmButtonText: 'Tentar novamente!'
                    });

                } else {
                    icon = 'success';
                    title = 'Sucesso';

                    Swal.fire({
                        title: title,
                        text: response.msg,
                        icon: icon,
                        showCancelButton: false,
                        confirmButtonText: 'Prosseguir'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "../index.php?p=inicial";
                        }
                    })
                }

                requesting = false;
            },
            finally: () => { requesting = false; }
        })
    }
});

$(document).on('click', '#lock-icon', () => {
    $('#2fa').focus();
});