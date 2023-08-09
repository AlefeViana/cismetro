<?php
require 'conecta.php';
require 'funcoes.php';

session_start();

// ***** Buscar o subitem do Sistema. *****
$querySub = $db->prepare("SELECT * FROM tbsubitem WHERE cdsubitem = ?");
$querySub->bind_param('s', $_GET['i']);
$querySub->execute();
$dadoSub = $querySub->get_result()->fetch_assoc();
echo  "CdSubItem: " . $dadoSub['cdsubitem'] . "<br>";
echo  "CdItem: " . $dadoSub['cditem'] . "<br>";
echo  "Arquivo: " . $dadoSub['arquivo'] . "<br>";
?>
<br> ************************************************************************************* <br>
<?php
// ***** Parte para executar consultas no banco sem acesso ao TS *****


?>