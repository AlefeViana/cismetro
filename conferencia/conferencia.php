<?php

require_once("./verifica.php");
require_once("./conecta.php");

$destino = $_SESSION['CdTpUsuario'];

switch ($destino) {

    default:

        include "./conferencia/municipio/conferencia_mun.php";
        break;

    case 1:

        include "./conferencia/prestador/conferencia_prestador.php";
        break;

    case 3:

        include "./conferencia/municipio/conferencia_mun.php";
        break;

    case  5:

        include "./conferencia/prestador/conferencia_prestador.php";
        break;
}
