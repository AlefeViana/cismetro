<?php 
	
	define("DIRECT_ACCESS",  true);

	include("../verifica.php");
	require("../funcoes.php");
    $condunidade = (isset($_REQUEST['cdunidade'])) ? " AND forne.CdCidade =".$_GET['cdunidade'] : "";
		 $sql = "SELECT forne.CdForn, forne.NmForn
				FROM tbfornecedor_mun AS forne
                INNER JOIN tbprefeitura as pref
                ON forne.CdCidade = pref.CdPref
				WHERE pref.`Status` = 1
				AND pref.consorciado = 'S'
                AND forne.Status = 1
                $condunidade
                ORDER BY forne.NmForn
				";	
		$qry = mysqli_query($db,$sql) or die (mysqli_error());
		if (mysqli_num_rows($qry) > 0){
			echo '<option value="0"> Todos </option>';
			while ($dados = mysqli_fetch_array($qry))
			echo '<option value="'.$dados['CdForn'].'">'.$dados['NmForn'].'</option>';				
		}else{
            echo "<option value=''> Nenhuma unidade encontrada </option>";
        }

	
	mysqli_close();
	mysqli_free_result($qry);
?>