<?php   ini_set("display_errors",1);
require "../vendor/autoload.php";
?>
<!DOCTYPE html>
<html>

<head>
    <link id="favicon" rel="icon" type="image/png" sizes="64x64" href="ico_sitcon.png">
    <title>Iconsorcio | Gestão em Consórcio de Saúde </title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.1/sweetalert2.min.js" integrity="sha512-vCI1Ba/Ob39YYPiWruLs4uHSA3QzxgHBcJNfFMRMJr832nT/2FBrwmMGQMwlD6Z/rAIIwZFX8vJJWDj7odXMaw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="style/custom-bootstrap/css/custom.css">
    <link rel="stylesheet" href="login/css/login.css">

    <style>
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

        @media (max-width: 575.98px) {
            body {
                background: linear-gradient(180deg, #00629B 0%, #6BA4B8 74.48%) no-repeat;
                background-size: cover;
                height: 100vh;
                background-attachment: fixed;
                width: 100%;
            }

            #form_login {
                padding-top: 10%;
                padding-bottom: 25%;
            }

            .social-half {
                height: auto;
                padding-left: 30%;
                padding-bottom: 10%;
            }

            #icones button {
                max-width: 46px;
                max-height: 46px;
                display: inline-flex;
                justify-content: center;
                align-items: center;
            }

            #linha {
                display: flex;
                flex-direction: column-reverse;
            }

            .links {
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            }

        }
    </style>
</head>

<body>
    
    <div class="container-fluid">
        <div class="row" id="linha">
            <div class="p-fixed d-sm-flex col-sm-6 social-half" style="flex-direction: column; justify-content: flex-end; align-items: center;">
                <div class="mb-1 mb-sm-5" id="icones" style="display: flex; justify-content: center; gap: 2rem">
                    <a href="https://www.instagram.com/sitconsistemas/" target="_blank"><button class="instagram btn btn-light shadow" style="height: 3.5rem; width: 3.5rem; border-radius: 20%"><img src="img/index-novo/instagram.svg" alt="Logo Instagram" /></button></a>
                    <a href="https://www.facebook.com/sitconsistemas/" target="_blank"><button class="instagram btn btn-light shadow" style="height: 3.5rem; width: 3.5rem; border-radius: 20%"><img src="img/index-novo/facebook.svg" alt="Logo Facebook" /></button></a>
                </div>
                <div class="links d-flex justify-content-arround">
                    <!-- <div class="d-flex flex-column align-items-start">
                        <div class="text-white">É prestador de serviços?</div>
                        <a class="text-white text-decoration-none font-weight-bold" href="login_credenciando.php">Credencie-se</a>
                    </div> -->
                    <!-- <div class="d-flex flex-column align-items-start">
                        <div class="text-white">É paciente?</div>
                        <a class="text-white text-decoration-none font-weight-bold" _blank href="http://ec2-100-24-26-89.compute-1.amazonaws.com/cismetro/pg/modcadpaciente/cadpaciente.php">Crie seu acesso</a>
                    </div> -->
                    <!-- <div class="d-flex flex-column align-items-start">
                        <div class="text-white">Precisa de Suporte?</div>
                        <a class="text-white text-decoration-none font-weight-bold" _blank href="https://iconsorciosaude3.com.br/suporte/index.php">Contatar Suporte</a>
                    </div> -->
                </div>

            </div>
            <div class="col-12 col-sm-6 d-flex" style="height: 80vh; flex-direction: column; justify-content: center; align-items: center; gap: 2rem">
                
                <form method="POST" id="form_login" action="login_valida.php">

                    <img src="img/index-novo/iconsorcio_logo_branco.svg" class="mb-5" alt="Logo iconsorcio" style="max-width: 80%;" />
                    <div class="h2 text-white font-weight-bold pb-4">Credenciamento</div>

                    <div class="mb-3" style="position: relative;">
                        <input autofocus type="text" style="width: 100%;" class="form-control pl-5 py-3 CNPJ" name="CNPJ" id="CNPJ" placeholder="CNPJ" aria-label="Username" aria-describedby="basic-addon1" required>
                        <div class="material-icons" style="left: 0.75rem; top: 50%; transform: translateY(-50%); position: absolute; color: rgba(196, 196, 196, 1);">person</div>
                    </div>

                    <div class="mb-3" style="position: relative">
                        <input type="email" style="width: 100%;" class="form-control pl-5 py-3 email" name="email" placeholder="E-mail" aria-label="Password" aria-describedby="basic-addon2" required>
                        <div class="material-icons" style="left: 0.75rem; top: 50%; transform: translateY(-50%); position: absolute; color: rgba(196, 196, 196, 1);">email</div>
                    </div>

                    <div class="mt-3 mb-3" style="position: relative;">
                        <select name="credenciamento" class="form-control credenciamento" id="credenciamento" style="width: 100%;" class="pl-5 py-3"> 
                             
                        </select>
                        <div class="material-icons" style="left: 0.75rem; top: 50%; transform: translateY(-50%); position: absolute; color: rgba(196, 196, 196, 1);">work</div>
                    </div>

                    <div class="mb-3 edital_consorcio" style="position: relative; padding: 0 140px 0 140px;">
                        <!-- <select name="cdforn" class="form-control" id="cdforn" style="width: 100%;" class="pl-5 py-3"></select>
                        <div class="material-icons" style="left: 0.75rem; top: 50%; transform: translateY(-50%); position: absolute; color: rgba(196, 196, 196, 1);">business</div> -->

                        <a href="" id="edital" class="btn btn-block btn-lg py-3" style="color: #fff; border: 1px solid #007BFF; background: #007BFF;" target="_blank"> Visualizar Edital</a>
                        <div class="material-icons" style="left: 9.35rem; top: 50%; transform: translateY(-50%); position: absolute; color: rgb(255, 255, 255);">visibility</div>
                    </div>

                    <div class="mt-4" style="display: grid; grid-template-columns: 1fr 15rem; grid-column-gap: 20px;">
                        <!-- <a href="pg/modesquecisenha/esqueci_a_senha.php" class="text-white" style="justify-self: start;" tabindex="-1">Esqueceu a senha?</a> -->
                        <a href="./frm_login.php" class="btn btn-block btn-lg d-flex justify-content-center align-items-center" style="border: 1px solid #00629B; color: #00629B; background: transparent; font-style: normal; font-weight: 600; font-size: 20px; line-height: 100%;">Voltar</a>
                        <button id="submit" class="btn btn-lg btn-warning shadow py-2" style="border: 1px solid #F55C05; background: #F55C05; font-style: normal; font-weight: 600; font-size: 20px;">Me Credenciar</button>
                    </div>
                    <div class="mt-4">
                        <div id="aviso"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<script>
    $(document).ready(function () {
        $('#credenciamento').select2();
        $('#credenciamento ~ * .select2-selection').css('padding-left', '2rem').css('margin-left', '0');
        $.ajax({
            type: "POST",
            url: "./load_credenciamento.php",
            dataType: "json",
            error:function (e) {
                $('#credenciamento').html('<option value="">Nenhum credenciamento em aberto</option> ');
            },
            beforeSend:function () {
                $('#credenciamento').html('<option value="">Carregando aguarde...</option> ');
            },
            success: function (response) {
                // console.log(response);
                if(response.dados.length > 0){
                    
                    $('#credenciamento').html('<option value="">Selecionar Credenciamento</option>');
                    for(i = 0;  i < response.dados.length; i++){
                        $('#credenciamento').append('<option value="'+response.dados[i].cdlicitacao+'" data-edital="'+response.dados[i].lictanexo+'">'+response.dados[i].descricao+'</option>');
                    }
                }else{
                    $('#credenciamento').html('<option value="">Nenhum credenciamento em aberto</option> ');
                }
                
            }
        });
        $('.forn_consorcio').hide();
        $('#credenciamento').change(function (e) { 
            e.preventDefault();
            if($(this).val() > 0){
                $('.forn_consorcio').show();
                $('#edital').val(this.data(edital));
            }else{
                $('.forn_consorcio').hide();
            }
        });
    });
</script>

</body>

</html>