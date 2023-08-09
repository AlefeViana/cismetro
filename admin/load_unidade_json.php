<?php 
	
	define("DIRECT_ACCESS",  true);

	include("../verifica.php");
    require("../funcoes.php");
    use Stringy\Stringy as S;

    $erro = "";
    if(is_array($_POST['CdPref'])){
        $CdPref = implode(",", $_POST['CdPref']);
    }else{
        $CdPref = $_POST['CdPref'];
    }
    

    $condunidade = ($_SESSION['cdunidade'] > 0) ?  " AND forne.CdForn in ( ".$_SESSION['cdunidade'] .")" : " AND forne.CdCidade in (".$CdPref.")";

		$sql =  "SELECT forne.CdForn, forne.NmForn
				 FROM tbfornecedor_mun AS forne
                 INNER JOIN tbprefeitura as pref ON forne.CdCidade = pref.CdPref
				 WHERE pref.`Status` = 1 AND pref.consorciado = 'S' AND forne.Status = 1
                 $condunidade
                 ORDER BY forne.NmForn
				";	
        // echo $sql;
        $sql = mysqli_query($db,$sql);
		if (mysqli_num_rows($sql) > 0){
			while ($dados = mysqli_fetch_array($sql))
            $unidade[] = array('UBS' => $dados["CdForn"], 'NmUnidade' =>(String)S::create($dados["NmForn"])->titleize(["de", "da", "do"]));
		}else{
            $erro = "Nenhuma unidade disponível!";
        }

        $array = array('dados' => $unidade, 'erro' => $erro);
        echo json_encode($array);
?>