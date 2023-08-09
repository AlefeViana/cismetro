<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!DOCTYPE html>
<html>

<style>
    #sms,
    #email {
        font-size: 16px;
        font-weight: bold;
        text-align: center;
        width: 40%;
    }

    p {
        text-align: justify;
        color: white;
        font-weight: bold;
    }

    /* Estilos gerais */
    body {
        font-family: sans-serif;
        background-color: #2b2b2b;
        color: #ffffff;
    }

    img {
        max-width: 80%;
        display: block;
        margin: 0 auto;
    }

    .mt-4 {
        margin-top: 1rem;
    }

    .mb-3 {
        margin-bottom: 0.5rem;
    }

    .btn {
        border-radius: 0.5rem;
        padding: 0.5rem 1rem;
        font-size: 1rem;
        margin-right: 1rem;
    }

    .form-control {
        border-radius: 0.5rem;
        border: none;
        background-color: #ffffff;
        color: #2b2b2b;
        font-size: 1rem;
        padding: 0.5rem 1rem;
        box-shadow: none;
    }

    .material-icons {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: rgba(196, 196, 196, 1);
    }

    a {
        color: #ffffff;
        text-decoration: none;
        font-size: 1rem;
        margin-top: 1rem;
    }

    /* Estilos para telas maiores */
    @media screen and (min-width: 768px) {
        form {
            max-width: 50%;
            margin: 0 auto;
        }

        .mt-4 {
            margin-top: 2rem;
        }

        .mb-3 {
            margin-bottom: 1rem;
        }

        .btn {
            font-size: 1.5rem;
            padding: 1rem 2rem;
            margin-right: 2rem;
        }

        .form-control {
            font-size: 1.5rem;
            padding: 1rem 2rem;
        }

        .material-icons {
            font-size: 1.5rem;
        }

        a {
            font-size: 1.5rem;
            margin-top: 2rem;
        }
    }
</style>

<head>
    <meta charset='utf-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <script src="js/maquinas_confiaveis.js"></script>
    <title>Autenticação de 2 Fatores</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='style.css'>
    <link id="favicon" rel="icon" type="image/png" sizes="64x64" href="../ico_sitcon.png">
    <link rel="stylesheet" href="../style/custom-bootstrap/css/custom.css">
</head>

<body>
    <input type="hidden" id="get_login" value="<?php echo $_GET['login'] ?>"></input>
    <!-- <button style="position: fixed; top: 0; left: 0;" onclick="report()">Click me</button> -->
    <div class="container-fluid">
        <div class="row">
            <div class="p-fixed p-sm-static d-none d-sm-flex col-sm-6 social-half" style="flex-direction: column; justify-content: flex-end; align-items: center;">
                <div class="mb-1 mb-sm-5" style="display: flex; justify-content: center; gap: 2rem">
                    <a href="https://www.instagram.com/sitconsistemas/" target="_blank"><button class="instagram btn btn-light shadow" style="height: 3.5rem; width: 3.5rem; border-radius: 20%"><img src="../img/index-novo/instagram.svg" alt="Logo Instagram" /></button></a>
                    <a href="https://www.facebook.com/sitconsistemas/" target="_blank"><button class="instagram btn btn-light shadow" style="height: 3.5rem; width: 3.5rem; border-radius: 20%"><img src="../img/index-novo/facebook.svg" alt="Logo Facebook" /></button></a>
                </div>
            </div>
            <div class="col-12 col-sm-6 d-flex" style="height: 80vh; flex-direction: column; justify-content: center; align-items: center; gap: 2rem">
                <form onsubmit="javascript: return false;" style="max-width: 50%;">
                    <img style="text-align: center;" src="../img/index-novo/iconsorcio_logo_branco.svg" class="mb-5" alt="Logo iconsorcio" style="max-width: 80%;" />
                    <div class="mt-4">
                        <p style="font-size: 18px;">Detectamos que você está tentando acessar sua conta de um dispositivo não reconhecido. Para garantir a segurança da sua conta, enviaremos um código de verificação por meio de um dos métodos abaixo.</p>
                    </div>

                    <div class="d-flex justify-content-center align-items-center mt-4 mb-5" style="margin-left: 10%;">
                        <button type="button" data-acao="sms" id="sms" class="btn btn-warning shadow mr-2">SMS</button>
                        <button type="button" data-acao="email" id="email" class="btn btn-warning shadow">E-mail</button>
                    </div>
                    <p class="text-center" style="margin-top: -5%;">Selecione uma das opções de envio clicando em um dos botões e informe o código que será enviado para você.</p>

                    <div class="mt-4" style="margin-bottom: 5%; text-align: center;">
                        <span id="contador" style="color: white;"></span>
                    </div>

                    <div class="mb-3" style="position: relative;">
                        <input autofocus type="text" style="width: 100%;" class="form-control pl-5 py-3" oninput="this.value = this.value.toUpperCase()" pattern="[A-Z0-9]{1,7}" maxlength="7" name="2fa" id="2fa" placeholder="Código de verificação" aria-label="2fa" aria-describedby="basic-addon1" required>
                        <div id="lock-icon" for="2fa" class="material-icons" style="left: 0.75rem; top: 50%; transform: translateY(-50%); position: absolute; color: rgba(196, 196, 196, 1); cursor: pointer;">lock</div>
                    </div>

                    <div class="mt-4" style="display: grid; grid-template-columns: 1fr 10rem; align-items: center;">
                        <a href="../frm_login.php" class="text-white" style="justify-self: start;" tabindex="-1">Voltar</a>
                    </div>

                </form>
            </div>
        </div>
        <!--row-->
    </div>
</body>

</html>