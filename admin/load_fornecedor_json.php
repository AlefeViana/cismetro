<?php 
	
	define("DIRECT_ACCESS",  true);

	include("../verifica.php");

	require_once("../funcoes.php");

	require "../../vendor/autoload.php";
	use Stringy\Stringy as S;

    $Status = (isset($_REQUEST['Status']))?' AND fl.`status` ='.$_REQUEST['Status'].' ':'';
    // $qceae = (isset($_REQUEST['qceae']))?' AND tbfornecedor.ceae ='.$_REQUEST['qceae'].' '  :' AND tbfornecedor.ceae=0 ';
    $CdForn = (isset($_REQUEST['CdForn']))?' AND f.CdForn ='.$_REQUEST['CdForn'].' ':' ';
    if ($_SESSION['cdfornecedor']) {
    	$cdfornecedor = $_SESSION['cdfornecedor'];
    	$CdForn= ' AND f.CdForn ='.$cdfornecedor.' ';
    }

  	// $sql = mysqli_query($db,"SELECT
	// 					tbfornecedor.CdForn,
	// 					UPPER(tbfornecedor.NmForn) AS NmForn,
	// 					UPPER(tbfornecedor.NmReduzido) AS NmReduzido,
	// 					tbfornecedor.`Status`
	// 					FROM
	// 					tbfornecedor
	// 					$Status
	// 					$qceae
	// 					$CdForn
	// 					ORDER BY NmReduzido ASC
	// 					");
	// $sql = "	SELECT f.CdForn,f.Nome AS NmForn,f.NomeFantasia AS NmReduzido, fl.`status` as Status, f.CNPJ
	// 							FROM tblctlicitacao l
	// 							INNER JOIN tblctfornecedor_licitacao fl on fl.cdlicitacao = l.cdlicitacao AND fl.andamento = 1 and fl.`status` = 1
	// 							INNER JOIN tbcredfornecedor f on f.CdForn = fl.cdforn
	// 							WHERE CURDATE() BETWEEN l.dtinicio and l.dtfim AND f.`Status` = 1 $Status $CdForn $qceae
	// 							GROUP BY f.CdForn
	// 							ORDER BY f.Nome
	// ";
	$sql = "SELECT
				fl.CdFornlct,
				f.CdForn,
				f.Nome AS NmForn,
				f.NomeFantasia AS NmReduzido,
				fl.`status` AS STATUS,
				f.CNPJ
			FROM
				tblctlicitacao l
			INNER JOIN tblctfornecedor_licitacao fl ON fl.cdlicitacao = l.cdlicitacao AND fl.andamento = 1 AND fl.`status` = 1
			INNER JOIN tbcredfornecedor f ON f.CdForn = fl.cdforn
			WHERE
				CURDATE() BETWEEN l.dtinicio
			AND l.dtfim
			AND f.`Status` = 1
			AND l.`status` = 1
			$Status $CdForn $qceae
			AND fl.CdFornlct not in (SELECT
				CdFornlct
			FROM
				tblctAnexos a
			INNER JOIN tblctTermos t on t.cdtermo = a.cdtermo
			WHERE a.`status` = 1
 			AND	a.CdFornlct = fl.CdFornlct
			AND (a.aprovacao = 2 OR (t.tpvalidacao = 'V' AND a.dataDoc <= CURDATE() AND a.dataDoc != '0000-00-00')
			) GROUP BY a.CdFornlct)
			GROUP BY
				f.CdForn
			ORDER BY
				f.Nome";
  	// var_dump($sql);
	$sql = mysqli_query($db,$sql);
	$erro = "";
	if(mysqli_num_rows($sql) > 0)
		while($n = mysqli_fetch_array($sql))
		{
			$nmforn   = (String)S::create($n['NmForn'])->titleize(["de", "da", "do"]);
			$nmreforn = (String)S::create($n['NmReduzido'])->titleize(["de", "da", "do"]);
			$fornecedor[] = array('CNPJ' => $n['CNPJ'],'CdForn' => $n['CdForn'],'NmForn' => $nmforn, 'NmReduzido' => $nmreforn,'Status' =>$n['Status']);
		}
	else 
		$erro = "Nenhum fornecedor disponível!";

	$array = array('dados' => $fornecedor, 'erro' => $erro);
	echo json_encode($array);
?>