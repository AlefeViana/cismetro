$(document).ready(function () {

    function alertaDC(params) {
        const urlParam = {};
        location.search.slice(1).split('&').forEach((parte) => (urlParam[parte.split("=")[0]] = parte.split("=")[1]));
        
        return urlParam;
    }

    if(alertaDC().dc == 's'){
        alert(`Usuário ficou inativo por muito tempo. A sessão expirou.`);
        var urlAtual = window.location.href;
        var novaUrl = urlAtual.substring(0, urlAtual.lastIndexOf("/") + 1) + "frm_login.php";
        window.location.href = novaUrl;
    }

    if(alertaDC().dc == 'd'){
        alert(`Atenção! sua conta foi acessada em outra maquina. Essa sessão foi encerrada.`);
        var urlAtual = window.location.href;
        var novaUrl = urlAtual.substring(0, urlAtual.lastIndexOf("/") + 1) + "frm_login.php";
        window.location.href = novaUrl;
    }

    $('.mun_consorcio').hide();
    $('.forn_consorcio').hide();
    $('#usuario').blur(function (e) {
        e.preventDefault();
        const user = $(this).val();
        const valida_mun = 'MUN.';
        const valida_fornecedor = 'FORN.';
        $('#aviso').empty();
        if (user.toUpperCase().includes(valida_mun.toUpperCase()) && user != '') {
            $('.mun_consorcio').show();
            $('#cdmun').html('');
            $('#cdmun').select2();
            $('#cdmun ~ * .select2-selection').css('padding-left', '2rem').css('margin-left', '0');
            $.post("login/load_municipio.php", response => {
                $('#cdmun').append('<option value="" > Selecione o município </option>');
                Object.keys(response.Cidades).forEach(function (item) {
                    console.log(response.Cidades)
                    $('#cdmun').append('<option value="' + response.Cidades[item].CdPref + '">' + response.Cidades[item].NmCidade + '</option>');
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
                Object.keys(response.Fornecedores).forEach(function (item) {
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
    $('#cdmun').change(function (e) {
        e.preventDefault();
        const municipio = $(this).val();
        const user = $('#usuario').val();
        $('#aviso').empty();
        $.ajax({
            type: "POST",
            url: "login_valida.php",
            data: {
                municipio: municipio,
                user: user
            },
            dataType: "json",
            success: function (response) {

            }
        });

    });
    $('#cdforn').change(function (e) {
        e.preventDefault();
        const cdforn = $(this).val();
        const user = $('#usuario').val();
        $('#aviso').empty();
        $.ajax({
            type: "POST",
            url: "login_valida.php",
            data: {
                cdforn: cdforn,
                user: user
            },
            dataType: "json",
            success: function (response) {

            }
        });

    });

    $(document).on('click', "#submit", function (event) {
        event.preventDefault();
        var formData = $("#form_login").serialize();
        $('#aviso').empty();
        $.ajax({
            type: "POST",
            url: "login_valida.php",
            data: formData,
            success: function (data) {
                var data = JSON.parse(data);
                if (data.status) {
                    if (!data.autenticacao) {
                        window.location.href = "../../cismetro/2FA/autenticacao_confirmacao.php?login=" + $('#usuario').val();
                    } else {
                        $('#aviso').empty();
                        window.location.href = "./index.php";
                    }
                } else {
                    $('#aviso').append(`<div class="alert alert-danger" role="alert" style="width: 100%;">${data.msg}</div>`)
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error("Error submitting form: ", errorThrown);
            }
        });
    });
});