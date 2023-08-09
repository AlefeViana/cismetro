<?php 

	define("DIRECT_ACCESS",  true);
	
	require("verifica.php");

//consaude
	if ($_SESSION["CdTpUsuario"] == 1){
		
		$timestamp = strtotime("+120 days");
		$hoje = date('Y-m-d');
		$data_fim = date('Y-m-d', $timestamp);
		
		$sql = "SELECT f.CdForn,NmReduzido,DtValidade,CdContrato,Descricao 
				FROM tbfornecedor f INNER JOIN tbcontrato c ON f.CdForn=c.CdForn
				WHERE DtValidade BETWEEN '$hoje' AND '$data_fim'
				ORDER BY DtValidade";
		require("conecta.php");
		$qry = mysqli_query($db,$sql) or die ('Erro ao verificar contrato com fornecedores '.mysqli_error());
		
		if (mysqli_num_rows($qry) > 0){
			echo '<br /><br /><img src="./imagens/Warning.png" />&nbsp;&nbsp;';
			echo 'Contratos vencendo!<br /><br />
				<table width="100%" id="grid">
						<tr style="text-align:center; color:#000; font-weight:bold;">
					 <td>Cod. Fornecedor</td><td>Fornecedor</td><td>Vencimento</td><td>Descri&ccedil;&atilde;o</td></tr>';
			//cor da tabela
         	$cortb = "linha2";		 
			while($dados = mysqli_fetch_array($qry)){
			   if ($cortb == "linha2"){
                   $cortb = "linha1";
               }
               else{
                   $cortb = "linha2";
               }
				$dados["DtValidade"] = explode('-',$dados["DtValidade"]);
				$dados["DtValidade"] = $dados["DtValidade"][2].'/'.$dados["DtValidade"][1].'/'.$dados["DtValidade"][0];
				echo "<a href='index.php?p=frm_cadcontrato&acao=edit&id=$dados[CdContrato]'>
						<tr class='$cortb'><td align='center'>".$dados["CdForn"].'</td><td>'.$dados["NmReduzido"].'</td><td align="center">'.$dados["DtValidade"].'</td><td>'.$dados["Descricao"].'</td></tr></a>';
			}
			echo '</table>';
		}			
	}
	if ($_SESSION["CdTpUsuario"] == 3 || $_SESSION["CdTpUsuario"] == 4)
		{
			$sql = "SELECT p.CdPref,NmCidade,SUM(Credito)-SUM(Debito) as Saldo 
					FROM tbprefeitura p LEFT JOIN tbmovimentacao m ON p.CdPref=m.CdPref 
					WHERE p.CdPref = $_SESSION[CdOrigem]
					GROUP BY p.CdPref,NmCidade";
			require("conecta.php");
			$qry = mysqli_query($db,$sql) or die ('Erro ao verificar contrato com fornecedores '.mysqli_error());
			if (mysqli_num_rows($qry) > 0){
				$alert = '';
				if(mysqli_result($qry,0,'Saldo') <= 0){
					$cor = '<label style="color:#F00; font-size:18px; font-weight:bold">';
					$alert = '<p><img src="./imagens/Warning.png" /></p>';
					$saldo = mysqli_result($qry,0,'Saldo');
				}else
				{
					$cor = '<label style="color:#93DB70; font-size:14px; font-weight:bold">';
					$saldo = mysqli_result($qry,0,'Saldo');
				}
					
				echo $alert;	
				echo "<p><b>Saldo atual:</b> R$ ".$cor.number_format($saldo,2,',','.')."</label></p>";
			}
		}
		
		@mysqli_free_result($query);
		@mysqli_close();
//echo $_SESSION["NmUsuario"]."!<br />"; 
//echo "Codigo ".$_SESSION["CdUsuario"]."!<br />"; 
//echo "TpUsuario ".$_SESSION["CdTpUsuario"]."!<br />"; 
//echo "CdOrigem ".$_SESSION["CdOrigem"]."!<br />"; 


?>