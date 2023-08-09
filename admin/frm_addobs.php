<?php 

	define("DIRECT_ACCESS",  true);

   require_once("verifica.php");
   //verifica se o usuario tem permiss�o para acessar a pagina
   if ((int)$_SESSION["CdTpUsuario"] != 1 && (int)$_SESSION["CdTpUsuario"] != 2 && (int)$_SESSION["CdTpUsuario"] != 3 && (int)$_SESSION["CdTpUsuario"] != 4)	
   {
		echo '<script language="JavaScript" type="text/javascript"> 
			window.location.href="index.php?p=inicial";				
		  </script>';	
   }
?>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
<?php
	
   //recebe as variaveis de identificacao
   $CdSolCons = (int)$_GET["id"];
   $Acao      = $_GET["acao"];
   
   //consulta os dados
   if ($CdSolCons === 0){
	   	   
	   if ($_SESSION["CdOrigem"] > 0)
	   		$pg_destino = 'lista_solagd';
   	   else
	   		$pg_destino = 'lista_agendamento';
			
	   echo '<script language="JavaScript" type="text/javascript"> 
				alert("Solicitação não encontrada.");
				window.location.href="../index.php?p='.$pg_destino.'";					
		  </script>';
		  
   }else{
	   switch($Acao){
	   		case "edit": $botao = 'Salvar';
						 break;
			case "del": $botao = 'Cancelar Solicitação';
						break;
	   }
	   		
	   require("../conecta.php");
	   $sql = "SELECT Protocolo,sc.CdSolCons,sc.CdPaciente,NmPaciente,NmEspec,NmEspecProc,Obs,Obs1,Urgente
	   		   FROM tbsolcons sc INNER JOIN tbpaciente p ON sc.CdPaciente=p.CdPaciente
			   					 INNER JOIN tbespecialidade e ON sc.CdEspec=e.CdEspec
								 INNER JOIN tbespecproc ep ON sc.CdEspecProc=ep.CdEspecProc
			   WHERE sc.CdSolCons=$CdSolCons";
			   
	   $qry = mysqli_query($db,$sql) or die('Ocorreu um erro de n�mero: '.mysqli_errno().', frm_addobs:select dados. Tente executar novamente a tarefa e se o erro persistir, contate o administrador do sistema informando essa mensagem. Copie essa mensagem!');
	   
	   if (mysqli_num_rows($qry) === 1)
	   		$dados = mysqli_fetch_array($qry);
	   
?>
<form method="POST" action="regn_addobs.php">

<div id="frms">

		<table width="100%" border="0" bgcolor="#FFFFFF" cellspacing="5">
		  <tr>
		      <td height="40" colspan=2 align="center">
		      <h4>Consultas e Exame Especializados - Observa&ccedil;&atilde;o</h4></td>
		  </tr>
          <tr>
		      <td width="309">Prot&oacute;colo:</td>
		      <td width="787"><?php echo $dados["Protocolo"]; ?></td>
		  </tr>
          <tr>
		      <td width="309">C&oacute;digo da Solicita&ccedil;&atilde;o:</td>
		      <td width="787"><?php echo $dados["CdSolCons"]; ?></td>
		  </tr>
          <tr>
		      <td width="309">CIH:</td>
		      <td><?php echo $dados["CdPaciente"]; ?></td>
		  </tr>
		  <tr>
		      <td>Paciente:</td>
	          <td><?php echo $dados["NmPaciente"]; ?></td>
		  </tr>
          <tr>
		      <td>Especialidade:</td>
	          <td><?php echo $dados["NmEspec"]; ?></td>
		  </tr>
          <tr>
		      <td width="309">Especifica&ccedil;&atilde;o:</td>
		      <td><?php echo $dados["NmEspecProc"]; ?></td>
		  </tr>  
           <tr>
		      <td width="309">Solicita&ccedil;&atilde;o:</td>
		      <td><?php if($dados["Urgente"]) echo 'Urgente'; else echo 'Normal'; ?></td>
		  </tr>        
          <tr>
          	   <td>Observa&ccedil;&atilde;o da Prefeitura:</td>
          	   <td>
               		<textarea name="obs" id="obs" style="width:500px; height:80px" readonly="readonly"><?php echo $dados["Obs"]; ?></textarea>
               </td>
          </tr>
          <tr>
          	   <td>Observa&ccedil;&atilde;o do Consa&uacute;de:</td>
          	   <td>
               		<textarea name="obs1" id="obs1" style="width:500px; height:80px"><?php echo $dados["Obs1"]; ?></textarea>
               </td>
          </tr>
          <tr>
          	   <td colspan="2">
				   <?php 
				   		$sql = "SELECT s.Status as StatusSol,a.Status 
								FROM tbsolcons s INNER JOIN tbagendacons a ON s.CdSolCons=a.CdSolCons
								WHERE s.CdSolCons=$CdSolCons AND 
								      CdForn IS NOT NULL AND 
									  DtAgCons IS NOT NULL AND
									  HoraAgCons IS NOT NULL";
						$qry = mysqli_query($db,$sql) or die ('Ocorreu um erro de númer: '.mysqli_errno().', frm_addobs:select status. Tente executar novamente a tarefa e se o erro persistir, contate o administrador do sistema informando essa mensagem. Copie essa mensagem!');
						
						if (mysqli_num_rows($qry) == 1 && ($Acao == 'edit' || $Acao == 'del') ){
							$check = mysqli_result($qry,0,"Status");
							$check1 = mysqli_result($qry,0,"StatusSol");
							if ($check == 2 || $check1 == 2){
								$check       = 'checked="checked"';
								$disable_btn = 'disabled="disabled"'; 
							}
							if ($Acao == 'edit')
								echo "<input type=\"checkbox\" name=\"realizado\" value=\"1\" $check /> Realizado";	
						}
						
                   ?>
               </td>
          </tr>
		  <tr>
		      <td colspan="2" align="center" height="40">
              <?php if($Acao != ""){ ?>
		          		<input type="submit" value="<?php echo $botao; ?>" <?php echo $disable_btn; ?> />
			  <?php }  ?>
                  <input type="hidden" name="acao" value="<?php echo $Acao; ?>"  />
                  <input type="hidden" name="codigo" value="<?php echo $dados["CdSolCons"]; ?>"  />
		      </td>
		  </tr> 
		</table>

</div>

</form>

<?php } //fim else da consulta?>