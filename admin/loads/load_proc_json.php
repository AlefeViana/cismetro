<?php 
	
	define("DIRECT_ACCESS",  true);

	// include("../verifica.php");
    require("../../funcoes.php");
    use Stringy\Stringy as S;

    $erro = "";

    $sql = mysqli_query($db,"SELECT tbprocedimento.CdProcedimento, tbprocedimento.NmProcedimento FROM tbprocedimento WHERE Status = 1");	

    if (mysqli_num_rows($sql) > 0){
        while ($dados = mysqli_fetch_array($sql))
        $procedimento[] = array('CdProcedimento' => $dados["CdProcedimento"], 'NmProcedimento' =>(String)S::create($dados["NmProcedimento"])->titleize(["de", "da", "do"]));
    }else{
        $erro = "Nenhuma unidade disponível!";
    }

    $array = array('dados' => $procedimento, 'erro' => $erro);
    echo json_encode($array);
?>