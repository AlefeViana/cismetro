<?php 
	session_start();
	require_once("../funcoes.php");
    $Status = (isset($_REQUEST['Status']))?' WHERE tbfornecedor.`Status` ='.$_REQUEST['Status'].' ':' WHERE tbfornecedor.`Status` = 1 ';
    $qceae = (isset($_REQUEST['qceae']))?' AND tbfornecedor.ceae ='.$_REQUEST['qceae'].' '  :' AND tbfornecedor.ceae=0 ';
    $CdForn = (isset($_REQUEST['CdForn']))?' AND tbfornecedor.CdForn ='.$_REQUEST['CdForn'].' ':' ';
    if ($_SESSION['cdfornecedor']) {
    	$cdfornecedor = $_SESSION['cdfornecedor'];
    	$CdForn= ' AND tbfornecedor.CdForn ='.$cdfornecedor.' ';
    }

  	$sql = mysqli_query($db,"SELECT
						tbfornecedor.CdForn,
						UPPER(tbfornecedor.NmForn) AS NmForn,
						UPPER(tbfornecedor.NmReduzido) AS NmReduzido,
						tbfornecedor.`Status`
						FROM
						tbfornecedor
						$Status
						$qceae
						$CdForn
						ORDER BY NmForn ASC
						");
  //echo $qceae;

	$erro = "";
	if(mysqli_num_rows($sql) > 0)
		while($n = mysqli_fetch_array($sql))
			$fornecedor[] = array('CdForn' => $n['CdForn'],'NmForn' => $n['NmForn'], 'NmReduzido' =>$n['NmReduzido'],'Status' =>$n['Status']);
	else 
		$erro = "Nenhum fornecedor disponível!";

	$array = array('dados' => $fornecedor, 'erro' => $erro);
	echo json_encode($array);
?>