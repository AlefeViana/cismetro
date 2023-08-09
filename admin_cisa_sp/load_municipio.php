<?php 
	$html = header('Content-Type: text/html; charset=iso-8859-1'); 
	require("../funcoes.php");

	if (isset($_GET['fat'])) {
		$sql = "SELECT pref.CdPref,pref.NmCidade,pref.`Status`,pref.consorciado
				FROM tbprefeitura AS pref
				WHERE pref.`Status` = 1
				AND pref.consorciado = 'S'
				AND pref.CdPref NOT IN (SELECT tbtetoppinew.cdprefeitura FROM tbtetoppinew WHERE tbtetoppinew.idgenfat=$_GET[fat])
				ORDER BY pref.NmCidade
				";
		
		$qry = mysqli_query($db,$sql) or die (mysqli_error());
		if (mysqli_num_rows($qry) > 0){
			echo ($_GET[r]==1)?"<option value=''>Selecione</option><option value='t'>Todos</option>":"<option value=''>Selecione</option>";
			while ($dados = mysqli_fetch_array($qry)){
				if (isset($_GET['esc'])) {
					if ($dados['CdPref'] != $_GET['esc']) {
						echo '<option value="'.$dados['CdPref'].'">'.$dados['NmCidade'].'</option>';
					}
				}else{
					echo '<option value="'.$dados['CdPref'].'">'.$dados['NmCidade'].'</option>';
				}
			}
		}
	}elseif (isset($_GET['trans'])) {
		$sql = "SELECT pref.CdPref,
				pref.NmCidade,
				pref.`Status`,
				pref.consorciado
				FROM tbprefeitura AS pref
				WHERE pref.`Status` = 1 AND pref.consorciado = 'S'
				ORDER BY pref.NmCidade";
		
		$qry = mysqli_query($db,$sql) or die (mysqli_error());
		if (mysqli_num_rows($qry) > 0){
			echo ($_GET['r']==1)?"<option value=''>Selecione</option><option value='t'>Todos</option>":"<option value=''>Selecione</option>";
			while ($dados = mysqli_fetch_array($qry)){
				if (isset($_GET['trans'])) {
					if ($dados['CdPref'] == $_GET['trans']) {
						echo '<option value="'.$dados['CdPref'].'" selected >'.$dados['NmCidade'].'</option>';
					}else{
						echo '<option value="'.$dados['CdPref'].'">'.$dados['NmCidade'].'</option>';
					}
				}else{
					echo '<option value="'.$dados['CdPref'].'">'.$dados['NmCidade'].'</option>';
				}
			}
		}
	}else{
		$sql = "SELECT pref.CdPref,
				pref.NmCidade,
				pref.`Status`,
				pref.consorciado
				FROM tbprefeitura AS pref
				WHERE pref.`Status` = 1
				ORDER BY pref.NmCidade";
		
		$qry = mysqli_query($db,$sql) or die (mysqli_error());
		if (mysqli_num_rows($qry) > 0){
			echo ($_GET['r']==1)?"<option value=''>Selecione</option><option value='t'>Todos</option>":"<option value=''>Selecione</option>";
			while ($dados = mysqli_fetch_array($qry)){
				if (isset($_GET['esc'])) {
					if ($dados['CdPref'] != $_GET['esc']) {
						echo '<option value="'.$dados['CdPref'].'">'.$dados['NmCidade'].'</option>';
					}
				}else{
					echo '<option value="'.$dados['CdPref'].'">'.$dados['NmCidade'].'</option>';
				}
			}
		}
	}
	mysqli_close();
	mysqli_free_result($qry);
?>