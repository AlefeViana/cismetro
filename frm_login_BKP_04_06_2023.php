<?php

require "../vendor/autoload.php";

@session_start();
$msg = new \Plasticbrain\FlashMessages\FlashMessages();

if (isset($_SESSION['CdUsuario']) and $_SESSION['CdUsuario']) {
    $msg->info('Você está autenticado como usuário ' . $_SESSION['NmUsuario'] . ' .', "index.php");
};

?>
<!DOCTYPE html>
<html>

<head>
    <link id="favicon" rel="icon" type="image/png" sizes="64x64" href="ico_sitcon.png">
    <title>Iconsorcio | Gestão em Consórcio de Saúde </title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <!-- <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css"> -->
    <link rel="stylesheet" href="style/custom-bootstrap/css/custom.css">
    <style>
        body {
            background: linear-gradient(180deg, #00629B 0%, #6BA4B8 74.48%) no-repeat;
            height: 100vh;
        }

        .social-half {
            height: 9vh;
            width: max-content;
            top: 0;
            right: 0;
        }

        @media (min-width: 576px) {
            body {
                background: url('img/pattern.png') no-repeat, linear-gradient(180deg, #00629B 0%, #6BA4B8 74.48%) no-repeat;
                background-size: 50% 100%, 50% 100%;
                background-position: 0%, 100%;
                height: 100vh;
            }

            .social-half {
                height: 80vh;
            }
        }
    </style>
</head>

<body>
    <!-- <button style="position: fixed; top: 0; left: 0;" onclick="report()">Click me</button> -->
    <div class="container-fluid">
        <div class="row">
            <div class="p-fixed p-sm-static d-none d-sm-flex col-sm-6 social-half" style="flex-direction: column; justify-content: flex-end; align-items: center;">
                <div class="mb-1 mb-sm-5" style="display: flex; justify-content: center; gap: 2rem">
                    <a href="https://www.instagram.com/sitconsistemas/" target="_blank"><button class="instagram btn btn-light shadow" style="height: 3.5rem; width: 3.5rem; border-radius: 20%"><img src="img/index-novo/instagram.svg" alt="Logo Instagram" /></button></a>
                    <a href="https://www.facebook.com/sitconsistemas/" target="_blank"><button class="instagram btn btn-light shadow" style="height: 3.5rem; width: 3.5rem; border-radius: 20%"><img src="img/index-novo/facebook.svg" alt="Logo Facebook" /></button></a>
                </div>
                <div class="text-white">É prestador de serviços?</div>
                <a class="text-white text-decoration-none font-weight-bold" href="login_credenciando.php">Credencie-se</a>
            </div>
            <div class="col-12 col-sm-6 d-flex" style="height: 80vh; flex-direction: column; justify-content: center; align-items: center; gap: 2rem">
                <form method="POST" action="login_valida.php">


                    <img src="img/index-novo/iconsorcio_logo_branco.svg" class="mb-5" alt="Logo iconsorcio" style="max-width: 80%;" />

                    <div class="mb-3" style="position: relative;">
                        <input autofocus type="text" style="width: 100%;" class="form-control pl-5 py-3 <?php echo $invalid ? "is-invalid" : "" ?></div>" name="usuario" id="usuario" placeholder="Usuário" aria-label="Username" aria-describedby="basic-addon1" required>
                        <div class="material-icons" style="left: 0.75rem; top: 50%; transform: translateY(-50%); position: absolute; color: rgba(196, 196, 196, 1);">person</div>
                    </div>

                    <div class="mb-3" style="position: relative">
                        <input type="password" style="width: 100%;" class="form-control pl-5 py-3 <?php echo $invalid ? "is-invalid" : "" ?>" name="senha" placeholder="Senha" aria-label="Password" aria-describedby="basic-addon2" required>
                        <div class="material-icons" style="left: 0.75rem; top: 50%; transform: translateY(-50%); position: absolute; color: rgba(196, 196, 196, 1);">lock</div>
                    </div>

                    <?php $invalid = $msg->hasErrors() ? true : false;
                    $msg->hasMessages() ? $msg->display() : ""; ?>

                    <div class="mt-3 mb-3 mun_consorcio" style="position: relative;">
                        <select name="cdmun" class="form-control" id="cdmun" style="width: 100%;" class="pl-5 py-3 <?php echo $invalid ? "is-invalid" : "" ?></div>"></select>
                        <div class="material-icons" style="left: 0.75rem; top: 50%; transform: translateY(-50%); position: absolute; color: rgba(196, 196, 196, 1);">location_city</div>
                    </div>

                    <div class="mb-3 forn_consorcio" style="position: relative;">
                        <select name="cdforn" class="form-control" id="cdforn" style="width: 100%;" class="pl-5 py-3 <?php echo $invalid ? "is-invalid" : "" ?></div>"></select>
                        <div class="material-icons" style="left: 0.75rem; top: 50%; transform: translateY(-50%); position: absolute; color: rgba(196, 196, 196, 1);">business</div>
                    </div>

                    <div class="mt-4" style="display: grid; grid-template-columns: 1fr 10rem; align-items: center;">
                        <a href="https://cismetro.sitcon.com.br/cismetro/pg/modesquecisenha/esqueci_a_senha.php" class="text-white" style="justify-self: start;"  tabindex="-1">Esqueceu a senha?</a>
                        <button type="submit" class="btn btn-block btn-lg btn-warning shadow">Entrar</button>
                    </div>
                </form>
            </div>
        </div>
        <!--row-->
    </div>
    <!-- <div class="div-question-circle"><a href="#question"><i class="fa fa-question-circle question-circle" aria-hidden="true"></i></a></div>
	<div id="question" class="quest">	
		<a href="#fechar" title="Fechar" class="fechar">x</a>
		<h2><img src="http://iconsorciosaude3.com.br/suporte/img/logo.png" alt="" style="width: 200px;"></h2>
		<p>Av. Zita Soares de Oliveira, 212 <br/>Sala 602, Centro, Ipatinga-MG</p>
		<p>Contato: (31)3822-4656 - atendimento@sitcon.com.br</p>
		<p>Suporte: <a href="http://suporte.sitcon.com.br" target="_blank">suporte.sitcon.com.br</a></p>
	</div> -->
    <script>
        $(document).ready(function() {
            $('.mun_consorcio').hide();
            $('.forn_consorcio').hide();
            $('#usuario').blur(function(e) {
                e.preventDefault();
                const user = $(this).val();
                const valida_mun = 'MUN.';
                const valida_fornecedor = 'FORN.';
                if (user.toUpperCase().includes(valida_mun.toUpperCase()) && user != '') {
                    $('.mun_consorcio').show();
                    $('#cdmun').html('');
                    $('#cdmun').select2();
                    $('#cdmun ~ * .select2-selection').css('padding-left', '2rem').css('margin-left', '0');
                    $.post("login/load_municipio.php", response => {
                        Object.keys(response.Cidades).forEach(function(item) {
                            console.log(response.Cidades)
                            $('#cdmun').append('<option value="" > Selecione o município </option><option value="' + response.Cidades[item].CdPref + '">' + response.Cidades[item].NmCidade + '</option>');
                        });
                    }, 'json');
                } else {
                    $('.mun_consorcio').hide();
                    if ($('#cdmun').hasClass("select2-hidden-accessible")) {
                        $('#cdmun').select2('destroy');
                    }
                }
                if (user.toUpperCase().includes(valida_fornecedor.toUpperCase()) && user != '') {
                    $('#cdforn').html('');
                    $.post("login/load_fornecedor.php", response => {
                        Object.keys(response.Fornecedores).forEach(function(item) {
                            $('#cdforn').append('<option value="' + response.Fornecedores[item].CdForn + '">' + response.Fornecedores[item].Nome + '</option>');
                        });
                    }, 'json');
                    $('.forn_consorcio').show();
                    $('#cdforn').select2();
                    $('#cdforn ~ * .select2-selection').css('padding-left', '2rem').css('margin-left', '0');
                } else {
                    $('.forn_consorcio').hide();
                    if ($('#cdforn').hasClass("select2-hidden-accessible")) {
                        $('#cdforn').select2('destroy');
                    }
                }
            });
            $('#cdmun').change(function(e) {
                e.preventDefault();
                const municipio = $(this).val();
                const user = $('#usuario').val();
                $.ajax({
                    type: "POST",
                    url: "login_valida.php",
                    data: {
                        municipio: municipio,
                        user: user
                    },
                    dataType: "json",
                    success: function(response) {

                    }
                });

            });
            $('#cdforn').change(function(e) {
                e.preventDefault();
                const cdforn = $(this).val();
                const user = $('#usuario').val();
                $.ajax({
                    type: "POST",
                    url: "login_valida.php",
                    data: {
                        cdforn: cdforn,
                        user: user
                    },
                    dataType: "json",
                    success: function(response) {

                    }
                });

            });
        });
    </script>
</body>
<style type="text/css">
    body {
        padding-top: 4rem;
    }

    .link-group {
        float: right;
        margin-top: -8px;
    }

    .link {
        color: #61058e;
    }

    .question-circle {
        color: white;
        font-size: 25px;
    }

    .question-circle:hover {
        color: orange;
        cursor: help;
    }

    .div-question-circle {
        position: absolute;
        right: 30px;
        top: 30px;
    }

    .quest {
        opacity: 0;
        pointer-events: none;
    }

    .quest:target {
        opacity: 1;
        pointer-events: auto;
    }

    #question {
        width: 250px;
        position: absolute;
        padding: 15px 20px;
        background: #fff;
        right: 43px;
        top: 45px;
        border-radius: 10px;
        transition: opacity 400ms ease-in;
        z-index: 3;
    }

    .fechar {
        position: absolute;
        width: 30px;
        right: -15px;
        top: -20px;
        text-align: center;
        line-height: 30px;
        margin-top: 5px;
        background: #ff4545;
        border-radius: 50%;
        font-size: 16px;
        color: #8d0000;
    }
</style>

</html>

<?php session_destroy(); ?>