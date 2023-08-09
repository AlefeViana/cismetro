<?php 
	
	define("DIRECT_ACCESS",  true);

	// include("../verifica.php");
    require("../funcoes.php");
    use Stringy\Stringy as S;
    $erro = '';
    $sqlPref = mysqli_query($db,"SELECT NmCidade, CdPref FROM tbprefeitura WHERE status = 1 AND consorciado = 'S' ORDER BY NmCidade");
    if (mysqli_num_rows($sqlPref) > 0){
        while ($Cidade = mysqli_fetch_array($sqlPref))
        $Cidades[] = array('CdPref' => $Cidade["CdPref"], 'NmCidade' =>(String)S::create($Cidade["NmCidade"])->titleize(["de", "da", "do"]));
    }else{
        $erro = "Nenhuma unidade disponível!";
    }

        $array = array('Cidades' => $Cidades, 'erro' => $erro);
        echo json_encode($array);
?>