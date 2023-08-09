<?php 
$Doenca = $_POST['frm_edtdoenca'];

if ($Doenca == ''){
    print "Preencha o campo Doença!"; exit();
}

define("DIRECT_ACCESS", true);

include("verifica.php");
//Abrindo Conexao com o banco de dados
require('../conecta.php');

//Utilizando o  mysqli_real_escape_string voce se protege o seu código contra SQL Injection.
$Doenca = mysqli_real_escape_string($Doenca);

$insert = mysqli_query($db,"insert into tbdoenca (NmDoenca,TpDoenca) values ('{$Doenca}',1)");
mysqli_close();
if($insert) {
    print "Cadastro Realizado!";
}else {
    print "Erro ao Cadastrar!";
}
?>