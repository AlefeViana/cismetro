<?php
/**
 * Created by PhpStorm.
 * User: Juarez
 * Date: 31/07/14
 * Time: 16:07
 */
define("DIRECT_ACCESS",  true);
require_once("verifica.php");
require("../conecta.php");

$cdforn = (int)$_GET['cdforn'];

    echo $sql = "SELECT p.NmProcedimento,ep.NmEspecProc,fe.CdForn,fe.`Status`,ep.CdEspecProc
            FROM
            tbprocedimento AS p
            INNER JOIN tbespecproc AS ep ON p.CdProcedimento = ep.CdProcedimento
            INNER JOIN tbfornespec AS fe ON ep.CdEspecProc = fe.CdEspec
            LEFT JOIN tbespecprocsub AS sub ON ep.CdEspecProc = sub.CdEspecPai
            WHERE fe.`Status` = 1 AND p.CdProcedimento = 40 AND ep.grupoceae = 0 AND sub.CdEspecFilho is NULL AND fe.CdForn = $cdforn";

    $qry = mysqli_query($db,$sql) or die (mysqli_error());

    if (mysqli_num_rows($qry) > 0){
        echo '<option value="">Selecione um procedimento...</option>';
        while ($dados = mysqli_fetch_array($qry)){
            echo '<option value="'.$dados['CdEspecProc'].'">'.utf8_encode($dados['NmProcedimento']." ".$dados['NmEspecProc']).'</option>';
        }  //fim do while
    } //fim do if
    else {
        echo "<option value=''> Nenhuma especifica&ccedil;&atilde;o encontrada  </option>";
    }


mysqli_close($db);
mysqli_free_result($qry);