<?php
/**
 * Created by PhpStorm.
 * User: Juarez
 * Date: 31/07/14
 * Time: 16:07
 */
require_once("verifica.php");
require("../conecta.php");

$cdespec = (int)$_GET['cdespec'];

$sql = "SELECT ep.CdEspecProc,ep.valor
        FROM
        tbespecproc AS ep
        WHERE ep.CdEspecProc = '$cdespec' ";

$qry = mysqli_query($db,$sql) or die (mysqli_error());

$l = mysqli_fetch_object($qry);

$l->valor = str_replace(".",",",$l->valor);

echo "<script language=\"JavaScript\" type=\"text/JavaScript\">
        //alert('valor: $l->valor');
        document.getElementById(\"valor\").value = '$l->valor';
      </script>";

mysqli_close($db);
mysqli_free_result($qry);