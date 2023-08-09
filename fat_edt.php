<?php 
/* Inclui as Bibliotecas JS */

include "incjs.php";

?>
<?php 

/* ==================================================== Atualiza Faturamento */
$ac = $_GET['ac'];

if($ac=="att")
{

	$CdPaciente = $_POST['CdPaciente'];
	$NmPaciente = $_POST['NmPaciente'];
	
	$CdSolCons = $_POST['CdSolCons'];
	$HoraAgCons=$_POST['HoraAgCons'];
	$DtAgCons=$_POST['DtAgCons'];
	$DtAgCons = FormataDataBD($DtAgCons);
	$select_forne = $_POST['select_forne'];
	$Valor=$_POST['Valor'];
	$valor_sus=$_POST['valor_sus'];
	$qts=$_POST['qts'];
	$obsc=$_POST['obsc'];
	
	
	$sql = mysqli_query($db,"UPDATE tbpaciente SET NmPaciente='$NmPaciente' WHERE (CdPaciente='$CdPaciente')") or die (mysqli_error());
	
	$sql  = mysqli_query($db,"UPDATE tbagendacons SET CdForn='$select_forne', DtAgCons='$DtAgCons', 
	HoraAgCons='$HoraAgCons', Valor='$Valor', valor_sus='$valor_sus', qts='$qts', obs='$obsc'
	WHERE (CdSolCons='$CdSolCons')");
	


	if($sql)
	{
		echo '<script language="JavaScript" type="text/javascript"> 
			  alert("Procedimento atualizado com sucesso!");
			  window.location.href="index.php?i=27";				
			  </script>';		
	}


}


/*======================================================Cancelamento */ 
	
	
	
	if($ac=="canc")
	{
		$cd = $_GET['cd'];
		$op= $_GET['op'];

		$sql = mysqli_query($db,"UPDATE tbsolcons SET Status='2' WHERE (CdSolCons='$cd')") or die (mysqli_error());
		
		
			// estorna saldo 
			$sql1 = mysqli_query($db,"SELECT tbsolcons.CdSolCons, tbprefeitura.CdPref, tbagendacons.valor 
			FROM tbagendacons, tbsolcons, tbpaciente, 
			tbprefeitura,tbbairro
			WHERE tbsolcons.CdSolCons = tbagendacons.CdSolCons
			AND tbsolcons.CdPaciente = tbpaciente.CdPaciente
			AND tbprefeitura.CdPref = tbbairro.CdPref
			AND tbbairro.CdBairro = tbpaciente.CdBairro
			AND tbsolcons.CdSolCons=$cd ");
			
			$l = mysqli_fetch_array($sql1);
			
			$valor = $l['valor'];
			$CdPref = $l['CdPref'];
		
			$sql3 = mysqli_query($db,"INSERT INTO `tbmovimentacao` (CdPref, CdUsuario, CdSolCons, TpMov, Credito)
			VALUES ('$CdPref', '$_SESSION[CdUsuario]', '$cd', '2', '$valor')"); 
		
		if($sql)
				{
					echo '<script language="JavaScript" type="text/javascript"> 
						alert("Cancelamento realizado com sucesso");
						window.location.href="?i=27";				
					  </script>';
				}
		
	}
    ?>
    
    
    <h1>  <img src="imagens/marcado.gif" width="10" height="10" />  Controle &raquo; Remarcar <strong>(Procedimentos Marcados ) </strong></h1>
<script type="text/javascript"> 
jQuery(function($){
	$("input[id=DtAgCons]").mask("99/99/9999");
	$("input[id=HoraAgCons]").mask("99:99");
	$("input[id=valor_sus]").maskMoney({symbol:"R$",decimal:",",thousands:"."});
	$("input[id=valor]").maskMoney({symbol:"R$",decimal:",",thousands:"."}); 
});
</script>

<div id="pnl_pesq" style="clear:both; height:50px;" >
<form action="index.php?i=27&b=1" method="post">
        <input type="text" name="pesq" value="Pesquisar..." onfocus="if(this.value=='Pesquisar...')this.value='';" onblur="if(this.value=='')this.value='Pesquisar...';" style=" float:left; padding:8px; border:#CCCCCC solid 1px; width:200px; font-style:italic; background:url(img/icon_lupa.jpg) no-repeat; padding-left:25px; " />
        <select name="cbopesq" style="float:left; width:130px; height:35px; margin-left:5px; border:#999999 solid 1px; " >
          <option value="1" <?php if ($cbopor == 1) echo 'selected="selected"';?> >C&oacute;digo</option>
       <!--    <option value="2" <?php if ($cbopor == 2 || $cbopor == "") echo 'selected="selected"';?> >Nome do Paciente</option>
          <option value="3" <?php if ($cbopor == 3 || $cbopor == "") echo 'selected="selected"';?> >Data de Nascimento</option>-->
        </select>	
        <input type="submit" value="Buscar" name="btnpesq" style="margin-left:5px; padding:8px; background:#FFFFFF; border:#CCCCCC solid 1px; cursor:pointer" />                 
        </form>
</div>
<?php 
	$b = $_GET['b'];
	
	if($b==1)
	{
	$cbopesq = $_POST['cbopesq'];
	$pesq = $_POST['pesq'];
	
	$sql = mysqli_query($db,"SELECT sc.CdSolCons,DtAgCons,HoraAgCons,p.CdPaciente,sc.retorno,sc.pactuacao,ac.qts,
	p.NmPaciente,ac.Valor,p.DtNasc,ac.CdUsuario, u.Login, ac.obs as obsc,
	ac.CdForn,pr.NmCidade,sc.Protocolo,sc.DtInc,pr.CdPref,ep.CdEspecProc,
	NmEspecProc,f.NmForn,Obs1,sc.Status,ac.Status as StatusAg,Urgente,NmReduzido,sc.Obs, Pa.NmProcedimento,ac.valor_sus
	FROM tbsolcons sc INNER JOIN tbpaciente p ON sc.CdPaciente=p.CdPaciente 
	INNER JOIN tbbairro b ON b.CdBairro=p.CdBairro
	INNER JOIN tbprefeitura pr ON b.CdPref=pr.CdPref
	INNER JOIN tbespecproc ep ON sc.CdEspecProc=ep.CdEspecProc 
	INNER JOIN tbprocedimento Pa ON ep.CdProcedimento = Pa.CdProcedimento
	LEFT JOIN tbagendacons ac ON sc.CdSolCons=ac.CdSolCons
	LEFT JOIN tbfornecedor f ON ac.CdForn=f.CdForn
	LEFT JOIN tbusuario u ON ac.CdUsuario = u.CdUsuario
	WHERE sc.Status='1' AND ac.Status='1'
	AND sc.CdSolCons = '$pesq'
	ORDER BY sc.CdSolCons");
	
	$l = mysqli_fetch_array($sql);
	
		$CdFornac = $l[CdForn];
		
		$n = mysqli_num_rows($sql);
	}

?>

<script language="javascript" t type="text/ecmascript"> 
$(document).ready(function() {
	$("#commentForm").validate();

	$("#cd_paciente").change(function(){
		$("#cih").val($("#cd_paciente").val());
	});
	
	$("#cih").blur(function(){					 
		$("#cd_paciente").val($("#cih").val());
	});
	
	
	
	$("#linha_espec").hide();
	
	
	$("#cd_proc").change(function(){
		if($("#cd_proc").val() == 1 || $("#cd_proc").val() == ""){
			$("#linha_espec").hide();
			$("#cd_especificacao").removeClass("required");
		}else{	
			$("#linha_espec").show();
			$("#cd_especificacao").addClass("required");
		}
	});
	
	$('#cd_proc').change(function(){								  
		$('#cd_especificacao').attr("disabled","disabled");						  
		$('#cd_especificacao').load('admin/load_especif.php?cdproc='+$('#cd_proc').val() );
		$('#cd_especificacao').removeAttr("disabled");
	});
	
	
	
});
</script>

<table  id='table'>
  <tr>
    <th width="112"> Código    </th>
    <td width="192"> <input type="text" name="CdSolCons" id="CdSolCons" value="<?php echo $l[CdSolCons] ?>" /> </td>
  </tr>
  <tr>
    <th width="112"> CIH    </th>
    <td width="192"> <input type="text" name="CdPaciente" id="CdPaciente" value="<?php echo $l[CdPaciente] ?>" /> </td>
  </tr>
  <tr>
    <th> Paciente    </th>
    <td> <input type="text" class="gr" name="NmPaciente" id='NmPaciente' value="<?php echo $l[NmPaciente] ?>" />   </td>
  </tr>
  <tr>
    <th> Data    </th>
    <td> <input type="text"  name="DtAgCons" id='DtAgCons' value="<?php echo $l[DtAgCons] ?>"   />   </td>
  </tr>
  <tr>
    <th> Hora    </th>
    <td> <input type="text"  name="HoraAgCons" id='HoraAgCons' value="<?php echo $l[HoraAgCons] ?>"   />   </td>
  </tr>
  <tr>
    <th> Procedimento </th>
    <td>  <select name="cd_proc" class="required" id="cd_proc" style="height:30px;">
                <option value="">Selecione um procedimento</option>
                    <?php                            
                        
                        $sql = "SELECT CdProcedimento, NmProcedimento
                                FROM tbprocedimento 
                                WHERE Status = '1'
                                ORDER BY NmProcedimento";
                        
                        require("conecta.php");
                        $qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=lista_solagd','frm_solagd:select dados procedimento'));
                        if (mysqli_num_rows($qry) > 0){
                            while ($dados = mysqli_fetch_array($qry)){	
                                echo '<option value="'.$dados["CdProcedimento"].'">'.$dados["NmProcedimento"].'</option>';
                            }
                        } 
                        @mysqli_close();
                        @mysqli_free_result($qry); 
                      ?>	 
           </select>
        </td>
  </tr>
  <tr>
    <th> Especificação    </th>
    <td><select name="cd_especificacao" id="cd_especificacao"  class="required" style="height:30px;">
                        <option value="">Selecione</option>
                    </select>     
     </td>
  </tr>
  <tr>
    <th> Fornecedor    </th>
    <td> <select name="cd_especificacao" id="cd_especificacao"  class="required" style="height:30px;">
                        <option value="">Selecione</option>
                    </select>   </td>
  </tr>
</table>
<div id="btns">
	<input type="submit" value="Remarcar"/> 

</div>





























<?php
/*	if(($n==0) and ($b==1))
	{
		echo "Informe um código de um procedimento realizado";	
	}
 
	if($n>0)
	{
?>
<form action='?i=27&ac=att' method='post'>

<h1 style="margin-bottom:2px;"> <strong>Solicitação</strong></h1>
<table  id='table'>
  <tr>
    <td height="30">Código </td>
    <td> <input type="text" name="CdSolCons" id="CdSolCons" value="<?php echo $l[CdSolCons] ?>"   />   </td>
  </tr>
  <tr>
    <td height="32">Código do Paciente </td>
    <td> <input type="text" name="CdPaciente" id='CdPaciente' value="<?php echo $l[CdPaciente] ?>"   />   </td>
  </tr>
  <tr>
    <td height="34">Paciente </td>
    <td> <input type="text" name="NmPaciente" id='NmPaciente' value="<?php echo $l[NmPaciente] ?>"  class="gr" style="text-transform:uppercase; height:20px; font-size:15	px;" />   </td>
  </tr>
  <tr>
    <td>Procedimento </td>
    <td> 
   	<?php  echo $l[NmProcedimento]; ?>
   
   </td>
  </tr>
  <tr>
    <td>Especificação </td>
    <td>   	<?php  echo $l[NmEspecProc]; ?>
   </td>
  </tr>
  <tr>
    <td>Urgente </td>
    <?php 
		$Urgente = $l[Urgente];
		if($Urgente ==1)
		{ 	$checked = "checked='checked'";	$vl = 1; }
		if($Urgente =="")
		{ 	$checked = " ";	 $vl = 0; }
	?>    
    <td> <input type="checkbox" name="urgente"  disabled="disabled" value="<?php echo $vl ?>" <?php echo $checked ?> />   </td>
  </tr>
  <tr>
    <td>Pactuação </td>
    <?php 
		$pactuacao = $l[pactuacao];
		if($pactuacao ==1)
		{ 	$checked = "checked='checked'";	$vl = 1; }
		if($pactuacao =="")
		{ 	$checked = " ";	 $vl = 0; }
	?>    
    <td> <input type="checkbox" name="pactuacao" disabled="disabled" value="<?php echo $vl ?>" <?php echo $checked ?> />   </td>
  </tr>
  <tr>
    <td>Retorno </td>
    <?php 
		$retorno = $l[retorno];
		if($retorno ==1)
		{ 	$retorno = "checked='checked'";	$vl = 1; }
		if($retorno	 =="")
		{ 	$checked = " ";	 $vl = 0; }
	?>    
    <td>  <input type="checkbox" name="retorno" disabled="disabled" value="<?php echo $vl ?>"  <?php echo $checked ?> />  </td>
  </tr>
  <tr>
    <td>Observações <strong>Municipio</strong> </td>
    <td> <label> <textarea name="obs" id="obs" disabled="disabled" style="width:500px; height:80px"> <?php echo $l[Obs];?></textarea></label>  </td>
  </tr>
</table>
<h1 style="margin-bottom:2px;"> <strong>Agendamento</strong> 
	<span>	
	<a href="?i=27&b=1&ac=canc&cd=<?php echo $l[CdSolCons] ?>"> 
		<strong><img src="img/icon_cancelar.png" width="29" height="26"  onclick=" return confirm('Tem certeza que deseja canelar o procedimento selecionado?')" /></strong> 
	</a> 
	</span>
</h1>


<table  id='table'>
  <tr>
    <th width="155">Data </th>
    <th colspan="3">Hora </th>
    <th width="102" colspan="3">Fornecedor </th>
  </tr>
  <tr>
    <td style="text-align:center" height="44"> <?php $DtAgCons = FormataDataBR($l[DtAgCons]); ?> 
	<input type='text' style="text-align:center; font-size:15px; width:80px;" name="DtAgCons" id="DtAgCons" value='<?php echo $DtAgCons ?>' /> </td>
    <td colspan="3" style="text-align:center" > <?php $HoraAgCons = $l[HoraAgCons]; ?> <input type='text' name='HoraAgCons' style="text-align:center; font-size:15px; width:50px;" id='HoraAgCons'   value='<?php echo $HoraAgCons ?>' /> </td>
    <td colspan="3">  
      <?php $sql = "SELECT f.CdForn,f.NmForn,f.NmReduzido
				FROM tbfornecedor f INNER JOIN tbfornespec fe ON f.CdForn=fe.CdForn
				WHERE fe.CdEspec = $l[CdEspecProc]";
   
				   $qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=inicial','lista_agendamento:consulta forn'));
				   echo "<label class='gr'><select id=\"select_forne\" name=\"select_forne\" class='required' $disabled >
                    	<option value=''>Selecione um Fornecedor</option>";
				   if(mysqli_num_rows($qry) > 0)				
					   while($local = mysqli_fetch_array($qry)){
						   if ($local["CdForn"] == $CdFornac)
								echo "<option value=\"$local[CdForn]\" title=\"$local[NmForn]\" selected=\"selected\"> &raquo; $local[NmReduzido]</option>";
						   else
								echo "<option value=\"$local[CdForn]\" title=\"$local[NmForn]\">$local[NmForn] </option>";
					   }					
				   echo "</select>
				   </label>";	 
		?> </td>
 <tr>
</table>
<table id='table'>
  <th width="144">Valor </th>
    <th width="144">Valor SUS </th>
    <th width="186">Quantidade</th>
    <th width="144">Diferen&ccedil;a</th>
  </tr>
  <tr>
    <td style="text-align:center"> <input type="text" style="font-size:20px; width:100px;" name='Valor' id='valor' value="<?php echo $l[Valor] ?>"  /> </td>
    <td style="text-align:center"> <input type="text" style="font-size:20px; width:100px;" name='valor_sus' id='valor_sus' value="<?php echo $l[valor_sus] ?>" /> </td>
    
    
    <td style="text-align:center"> <input type="text" style="font-size:20px; width:100px;" id='qts' name='qts' value="<?php echo $l[qts] ?>" /> </td>
    <td style="text-align:center"> <input type="text" style="font-size:20px; width:100px;" value="" disabled="disabled" /> </td>
  </tr>
  <tr>
    <td colspan="4"><p>&nbsp;</p>
      <p>Observações <strong>Consórcio<br />
        </strong>      
        <textarea name="obsc" id="obsc" style="width:500px; height:80px"> <?php echo $l[obsc];?></textarea>
    </p></td>
  </tr>
</table>

<div id="btns"> 
	<input type="submit" value="Salvar" />
</div>

</form> 
<?php 
	}
	else { echo "<div id='alert'> Nenhum Procedimento selecionado </div>"; }
	*/
?>
