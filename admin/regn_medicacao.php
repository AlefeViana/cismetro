<?php 
$Medicacao = $_POST['medicacao'];

if ($Medicacao == ''){
    print "Preencha o campo Medicação!"; exit();
}

define("DIRECT_ACCESS", true);

include("../verifica.php");
//Abrindo Conexao com o banco de dados
require('../conecta.php');

//Utilizando o  mysqli_real_escape_string voce se protege o seu código contra SQL Injection.
$Medicacao = mysqli_real_escape_string($Medicacao);

$insert = mysqli_query($db,"insert into tbmedicacao (NmMedicacao) values ('{$Medicacao}')");
mysqli_close();
if($insert) {
    print "Cadastro Realizado!";
}else {
    print "Erro ao Cadastrar!";
}
?>