<script src='https://meet.jit.si/external_api.js'></script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="./conferencia/municipio/js/conferencia.js"> </script>
<script src="./conferencia/municipio/js/lista_conferencia.js"> </script>

<?php

require_once("verifica.php");
require("admin/function_trata_erro.php");
require_once("conecta.php");

?>

<html>

<head>
    <style>
        #meet {
            position: relative;
            background-image: linear-gradient(180deg, #D3D3D3 20%, #C0C0C0 50%, #808080 100%);
            width: 70%;
            height: 602px;
            border: 1px gray solid;
            margin-left: auto;
            margin-right: auto;
        }

        #bloco {
            border: 1px rgba(138, 138, 138, 0.404) solid;
            border-radius: 5px;
            margin-bottom: 20px;
            background-color: #1C1C1C;
        }

        #legenda {
            display: block;
            color: rgba(22, 22, 22);
            font-size: 20px;
            font-weight: bold;
            padding: 7px 0 7px 0;
            text-align: center;
            background-color: rgba(52, 52, 52, 0.212);
        }

        .toolbox {
            position: absolute;
            bottom: 0px;
            padding-top: 5px;
            width: 100%;
            height: 50px;
            background-color: #1C1C1C;
        }
    </style>
</head>

<body>


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

    <div class="col-12" id="bloco" id="container" style="display: none">
        <div class="row">
            <div id="meet">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <table class="table table-hover" id="listapac">
                <thead>
                    <tr>
                        <th class="col-1">ID</th>
                        <th class="col-3">Paciente</th>
                        <th class="col-4">Especificação</th>
                        <th class="col-1">Data</th>
                        <th class="col-3">Ações</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>