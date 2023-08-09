<?php 

	require_once("../../funcoes.php");

	require "../../../vendor/autoload.php";
	use Stringy\Stringy as S;

    $Status = (isset($_REQUEST['Status']))?' AND fl.`status` ='.$_REQUEST['Status'].' ':'';
    // $qceae = (isset($_REQUEST['qceae']))?' AND tbfornecedor.ceae ='.$_REQUEST['qceae'].' '  :' AND tbfornecedor.ceae=0 ';
    $CdForn = (isset($_REQUEST['CdForn']))?' AND f.CdForn ='.$_REQUEST['CdForn'].' ':' ';
    if ($_SESSION['cdfornecedor']) {
    	$cdfornecedor = $_SESSION['cdfornecedor'];
    	$CdForn= ' AND f.CdForn ='.$cdfornecedor.' ';
    }

	$sql = mysqli_query($db,"	SELECT f.CdForn,CONCAT_WS(' | ',f.Nome,f.CNPJ) as NmForn,CONCAT_WS(' | ',f.NomeFantasia,f.CNPJ) AS NmReduzido, fl.`status` as Status
								FROM tblctlicitacao l
								INNER JOIN tblctfornecedor_licitacao fl on fl.cdlicitacao = l.cdlicitacao AND fl.andamento = 1 and fl.`status` = 1
								INNER JOIN tbcredfornecedor f on f.CdForn = fl.cdforn
								WHERE CURDATE() BETWEEN l.dtinicio and l.dtfim $Status $CdForn $qceae
								GROUP BY f.CdForn
								ORDER BY f.Nome
	");
  	//var_dump($sql);

	$erro = "";
	if(mysqli_num_rows($sql) > 0)
		while($n = mysqli_fetch_array($sql))
		{
			// $nmforn   = (String)S::create($n['NmForn'])->titleize(["de", "da", "do"]);
			// $nmreforn = (String)S::create($n['NmReduzido'])->titleize(["de", "da", "do"]);
			$fornecedor[] = array('CdForn' => $n['CdForn'],'NmForn' => $n['NmForn'], 'NmReduzido' => $n['NmReduzido'],'Status' =>$n['Status']);
		}
	else 
		$erro = "Nenhum fornecedor disponível!";

	$array = array('dados' => $fornecedor, 'erro' => $erro);
	echo json_encode($array);
?>