<?php 
$Trat = $_POST['frm_edttrat'];

if ($Trat == ''){
    print "Preencha o campo Tratamento Ocular!"; exit();
}
//Abrindo Conexao com o banco de dados
require('../conecta.php');

//Utilizando o  mysqli_real_escape_string voce se protege o seu código contra SQL Injection.
$Trat = mysqli_real_escape_string($Trat);

$insert = mysqli_query($db,"insert into tbtratamento (NmTratamento) values ('{$Trat}')");
mysqli_close();
if($insert) {
    print "Cadastro Realizado!";
}else {
    print "Erro ao Cadastrar!";
}
?>