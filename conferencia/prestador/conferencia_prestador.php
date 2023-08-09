<script src='https://meet.jit.si/external_api.js'></script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/jquery.tinymce.min.js" referrerpolicy="origin"></script>

<script src="./conferencia/prestador/js/conferencia.js"> </script>
<script src="./conferencia/prestador/js/lista_conferencia.js"> </script>

<?php

require_once("verifica.php");
require("admin/function_trata_erro.php");
require_once("conecta.php");

?>

<html>

<head>
    <style>
        #receituario_pac {
            display: inline-block;
            margin-right: 0px;
        }

        #meet {
            background-image: black;
            width: 60%;
            height: 602px;
            border: 1px gray solid;
            margin-left: 0px;
        }

        #bloco {
            border: 1px rgba(138, 138, 138, 0.404) solid;
            border-radius: 5px;
            margin-bottom: 20px;
            background-color: #1C1C1C;
        }

        #legenda{
            display: block;
            color: rgba(22, 22, 22);
            font-size: 20px;
            font-weight: bold;
            padding: 7px 0 7px 0;
            text-align: center;
            border-radius: 10px 0px 0px 10px;
            background-color: rgba(52, 52, 52, 0.212);
        }

        #titulo {
            display: block;
            color: white;
            font-size: 20px;
            font-weight: bold;
            padding: 7px 0 7px 0;
            text-align: center;
            background-color: #007BFF
        }

        .toolbox {
            position: absolute;
            bottom: 0px;
            padding-top: 5px;
            width: 95%;
            height: 50px;
            background-color: #1C1C1C;
        }

        .overlay {
            position: fixed;
            top: 50%;
            left: 75%;
            width: 350px;
            transform: translateX(-50%) translateY(-50%) scale(1.5);
            z-index: 50;
            text-align: center;
            padding: 1em 0 0;
            background-color: #d1ecf199;
        }
    </style>
</head>

<body>
    
    <input type="hidden" id="agenda_fornecedor" value="<?php echo $_GET['cdsolcons']; ?>"/>
    <input type="hidden" id="cdsolcons" value=""/>
    <input type="hidden" id="cdpaciente" value=""/>
    <!-- Filtros -->
    <div class="row my-3" id="filtros">
        <div class="col-md-12">
            <div class="row justify-content-start">
                <div class="col-12">
                    <fieldset>
                        <div class="col-4 row">
                            <div id="acao_filtro_acancado">
                                <button type="button" class="btn btn-primary" name="filtro_acancado" id="filtro_acancado" style="font-weight: bold; margin-bottom: 10px;">Filtro
                                    Avançado Paciente</button>
                            </div>
                        </div>

                        <div class="row" id="filtros_extra" style="display: none;">

                            <div class="col-12 form-group">

                                <div class="col-2" style="display: inline-block">
                                    <label>Protocolo:</label>
                                    <input type="text" class="form-control" id="protocolo" placeholder="Procotolo do agendamento...">
                                </div>

                                <div class="col-2" style="display: inline-block">
                                    <label>Data:</label>
                                    <input type="date" class="form-control" id="data">
                                </div>

                                <div class="col-2" style="display: inline-block">
                                    <label>Hora:</label>
                                    <input type="time" class="form-control" id="hora">
                                </div>
                            </div>

                            <div class="col-12 form-group">

                                <div class="col-3" style="display: inline-block">
                                    <label>Fornecedor:</label>
                                    <select class="form-control add-field select2-single w-100" name="forn" id="forn">
                                    </select>
                                </div>

                                <div class="col-3" style="display: inline-block">
                                    <label>Profissional:</label>
                                    <select class="form-control add-field select2-single w-100" name="prof" id="prof">
                                        <option selected="selected" value="0"></option>
                                    </select>
                                </div>

                                <div class="col-3" style="display: inline-block">
                                    <label>Procedimento:</label>
                                    <select class="form-control add-field select2-single w-100" name="cd_local" id="proc">
                                        <option selected="selected" value="0">Selecione o procedimento...</option>
                                    </select>
                                </div>

                            </div>
                            <div class="col-12 form-group">
                                <button type="button" class="btn btn-secondary" name="limpar_filtros" id="limpar_filtros" style="font-weight: bold; margin-bottom: 10px;">Limpar
                                    Filtros</button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-4 form-group">
                                <label>Paciente*:</label>
                                <select class="form-control add-field select2-single w-100" name="pac" id="pac">
                                    <option selected="selected" value="0">Selecione um Paciente...</option>
                                </select>
                            </div>

                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12" id="bloco" id="container" style="display: none;">
        <div class="row" style="margin-top: 10px;">
            <div id="meet" class="col-6">
            </div>
            <div id="text_pac" class="col-6">
                <p id="titulo"></p>
                <textarea id="receituario_pac"></textarea>
                <div id="carregando_op" style="display: none">
                    <div class="overlay">
                        <div class="row" id="loading">
                            <div class="col-12">
                                <div class="alert alert-info text-center" role="alert">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <strong id="tituloMenu" style="display: block;"> Processando... </strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <table class="table table-hover" id="listapac">
                <thead>
                    <tr>
                        <th class="col-1">ID</th>
                        <th class="col-4">Paciente</th>
                        <th class="col-4">Especificação</th>
                        <th class="col-1">Data</th>
                        <th class="col-2">Ações</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>