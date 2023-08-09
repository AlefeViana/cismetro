<?php 
	require("conecta.php");
	require("funcoes.php");
	
	$CdSolCons = $_GET['id'];
	
	
	$sql = mysqli_query($db," 
	SELECT tbagendacons.CdSolCons, tbagendacons.DtAgCons, tbagendacons.HoraAgCons, tbfornecedor.NmForn, 
tbsolcons.CdPaciente, tbpaciente.NmPaciente, tbespecproc.NmEspecProc, tbagendacons.protocolopac,
tbpaciente.NmPaciente, tbpaciente.RG,  tbfornecedor.Logradouro,tbfornecedor.Numero, tbfornecedor.Bairro, tbfornecedor.CdCidade, tbprefeitura.NmCidade,
tbprocedimento.NmProcedimento
FROM tbagendacons, tbsolcons, tbfornecedor, tbpaciente, tbespecproc, tbprefeitura, tbprocedimento
WHERE tbsolcons.CdSolCons = $CdSolCons
AND tbfornecedor.CdForn = tbagendacons.CdForn
AND tbpaciente.CdPaciente = tbsolcons.CdPaciente
AND tbespecproc.CdEspecProc = tbsolcons.CdEspecProc
AND tbsolcons.CdSolCons = tbagendacons.CdSolCons
AND tbprefeitura.CdPref = tbfornecedor.CdCidade
AND tbprocedimento.CdProcedimento = tbespecproc.CdProcedimento
") or die (mysqli_error());

	$lin = mysqli_fetch_array($sql);
	$data = FormataDataBR($lin['DtAgCons']);

?>


<a target="_self" HREF="javaScript:window.print()">Imprimir</a>
<table width="522" border="1" style="font-family:Arial, Helvetica, sans-serif">
  <tr>
    <td colspan="2"><center> <img src="imagens/consaude_online.png" width="252" height="74"> </center> </td>
  </tr>
  <tr>
    <td width="163"><strong>Data:</strong></td>
    <td width="343"> <?php echo $data;   ?></td>
  </tr>
  <tr>
    <td width="163"><strong>Hora:</strong></td>
    <td width="343"> <?php echo $lin['HoraAgCons']   ?></td>
  </tr>
  
  <tr>
    <td><strong>Local:</strong></td>
    <td> <?php  echo $lin['NmForn']; ?>  </td>
  </tr>
  <tr>
    <td><strong>Endereço:</strong></td>
    <td> <?php  echo $lin['Logradouro']."  "; 
	echo $lin['Numero']." "; 
	echo $lin['Bairro']." "; 
	echo $lin['NmCidade']."  MG "; ?>  </td>
  </tr>
  
  <tr>
    <td><strong>Procedimento:</strong></td>
    <td> <?php echo $lin['NmProcedimento']   ?></td>
  </tr>
  <tr>
    <td><strong>Especificação:</strong></td>
    <td> <?php echo $lin['NmEspecProc']   ?></td>
  </tr>
</table>
<br />
<table width="522" border="1"  style="font-family:Arial, Helvetica, sans-serif">
  <tr>
    <td><strong>Protocolo Paciente:</strong></td>
    <td width="347"> <?php echo $lin['protocolopac']   ?></td>
  </tr>
  <tr>
    <td width="159"><strong>Nome do Paciente</strong></td>
    <td> <?php echo $lin['NmPaciente']   ?></td>
  </tr>
  <tr>
    <td><strong>Doc. Identidade</strong></td>
    <td> <?php echo $lin['RG']   ?></td>
  </tr>
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td height="312" colspan="4" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4">* Apresentar esta guia no Fornecedor.</td>
  </tr>
</table>
