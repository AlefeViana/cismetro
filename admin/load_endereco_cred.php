<?php 
	
	define("DIRECT_ACCESS",  true);

	include("../verifica.php");
	require_once("../funcoes.php");

	require "../../vendor/autoload.php";
    use Stringy\Stringy as S;
    
    $CdForn = $_REQUEST['CdForn'];
    $cdprof = $_REQUEST['cdprof'];

  	$sql = mysqli_query($db,"SELECT pa.CdCredProfLocal,pa.NomeFantasia,pa.Cidade,pa.Bairro,pa.Numero,pa.Logradouro
                             FROM tbcredfornecedor c 
                             INNER JOIN tbcredprofissional p on p.CdCredForn = c.CdCredForn
                             INNER JOIN tbcredprofissionallocalatend pa on pa.CdCredProf = p.CdCredProf
                             WHERE c.CdForn = $CdForn and p.CdProf = $cdprof");

	$erro = "";
	if(mysqli_num_rows($sql) > 0)
		while($n = mysqli_fetch_array($sql))
		{
            $NmEspecProc   = (String)S::create($n['NomeFantasia'])->titleize(["de", "da", "do"]);
            $Cidade   = (String)S::create($n['Cidade'])->titleize(["de", "da", "do"]);
            $Bairro   = (String)S::create($n['Bairro'])->titleize(["de", "da", "do"]);
            $Logradouro   = (String)S::create($n['Logradouro'])->titleize(["de", "da", "do"]);
			$endereco[] = array('CdCredProfLocal' => $n['CdCredProfLocal'],'NomeFantasia' => $NomeFantasia,'Cidade' =>$n['Cidade'],'Bairro' =>$n['Bairro'],'Logradouro' =>$n['Logradouro'],'Numero' =>$n['Numero']);
		}
	else 
		$erro = "Nenhuma endereço de atendimento disponível!";

	$array = array('dados' => $endereco, 'erro' => $erro);
	echo json_encode($array);
?>