<?php 
	
	define("DIRECT_ACCESS",  true);

	// include("../verifica.php");
    require("../funcoes.php");
    use Stringy\Stringy as S;
    $erro = '';
    $sqlForn = mysqli_query($db,"SELECT Nome, CdForn, CNPJ FROM tbcredfornecedor WHERE Status = 1 AND CNPJ != '' ORDER BY Nome");
    if (mysqli_num_rows($sqlForn) > 0){
        while ($Fornecedor = mysqli_fetch_array($sqlForn))
        $Fornecedores[] = array('CdForn' => $Fornecedor["CdForn"], 'Nome' =>"".(String)S::create($Fornecedor["Nome"])->titleize(["de", "da", "do"])." | ". $Fornecedor["CNPJ"] ."");
    }else{
        $erro = "Nenhuma unidade disponível!";
    }

    $array = array('Fornecedores' => $Fornecedores, 'erro' => $erro);
    echo json_encode($array);
?>