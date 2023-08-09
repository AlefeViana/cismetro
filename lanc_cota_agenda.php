<style> 
 
/* Padrão de Tabela */
	#table { width:100%  }

	#table td { background:#FFFFFF;  padding-left:4px; font-size:13px; border-bottom:#DFEAF3 solid 1px; padding:2px;   }
	#table tr:hover td { background:#F1F6FA;}	
	#table th { background:#ECF2F9;  padding:5px; }
	
	 * {
 	 font-family:"Arial Narrow", Helvetica, sans-serif; 
	 font-size:13px; 
	 color:#333333;
	 padding:0px;
	 margin:0px;
	 list-style:none; }
	 
</style>
<div  style="margin:10px;">
<?php 
require ('conecta.php');
require ('funcoes.php');
	
	$sql = mysqli_query($db," SELECT
	tbcota.cdcota,
	tbprocedimento.NmProcedimento,
	tbespecproc.NmEspecProc,
	tbcota.dttermino,
	tbcota.dtinicio,
	tbfornecedor.NmForn,
	Sum(tbcotam.qts) AS total,
	tbespecproc.CdEspecProc,
	tbprocedimento.CdProcedimento,
	tbfornecedor.CdForn
	FROM
	tbcota
	INNER JOIN tbespecproc ON tbespecproc.CdEspecProc = tbcota.CdEspecProc
	INNER JOIN tbprocedimento ON tbprocedimento.CdProcedimento = tbespecproc.CdProcedimento
	INNER JOIN tbfornecedor ON tbcota.CdForn = tbfornecedor.CdForn
	INNER JOIN tbcotam ON tbcotam.cdcota = tbcota.cdcota
	INNER JOIN tbprefeitura ON tbprefeitura.CdPref = tbcotam.cdpref
	WHERE tbcota.cdcota ='$_GET[id]'
	GROUP BY tbcota.CdEspecProc, dtinicio, dttermino, tbfornecedor.NmForn
	
	ORDER BY tbcota.CdEspecProc, dtinicio, dttermino, tbfornecedor.NmForn

    ") or die (mysqli_error());
	
	
  echo "<table id='table'>
  <tr>
	<th style='text-align:center'> Código </th>
	<th> Fornecedor </th>
    <th> Especificação </th>
    <th> Período </th>
    <th style='text-align:center'> QTDE  LIBERADA  </th>
    <th style='text-align:center'>  AGUARDANDO  </th>
    <th style='text-align:center'>  MARCADO  </th>
    <th style='text-align:center'>  REALIZADO  </th>
    <th style='text-align:center'>  DISPONÍVEL  </th>
  </tr>";
	while($lin = mysqli_fetch_array($sql))
	{	
			  $dtinicio =  FormataDataBr($lin[dtinicio]);
			  $dttermino = FormataDataBr($lin[dttermino]);
	  
			  // AGUARDANDO 
			  $sql_agenda = mysqli_query($db,"
			  SELECT count(*) as A
			  FROM tbagenda_fornecedor
			  WHERE tbagenda_fornecedor.cdespecificacao = '$lin[CdEspecProc]'
			  AND tbagenda_fornecedor.cdprocedimento = '$lin[CdProcedimento]'
			  AND tbagenda_fornecedor.cdfornecedor = '$lin[CdForn]'
			  AND tbagenda_fornecedor.`data` BETWEEN '$lin[dtinicio]' AND '$lin[dttermino]'
			  AND tbagenda_fornecedor.`status` = 'A'
			  ") or die (mysqli_error());
			  
			 $l1 = mysqli_fetch_array($sql_agenda);
			 $A = $l1['A'];
		
		 	 // MARCADO 
			  $sql_agenda = mysqli_query($db,"
			  SELECT count(*) as M
			  FROM tbagenda_fornecedor
			  WHERE tbagenda_fornecedor.cdespecificacao = '$lin[CdEspecProc]'
			  AND tbagenda_fornecedor.cdprocedimento = '$lin[CdProcedimento]'
			  AND tbagenda_fornecedor.cdfornecedor = '$lin[CdForn]'
			  AND tbagenda_fornecedor.`data` BETWEEN '$lin[dtinicio]' AND '$lin[dttermino]'
			  AND tbagenda_fornecedor.`status` = 'M'
			  ") or die (mysqli_error());
			  
			 $l1 = mysqli_fetch_array($sql_agenda);
			 $M = $l1['M'];
		
			 // REALIZADO 
			  $sql_agenda = mysqli_query($db,"
			  SELECT count(*) as R
			  FROM tbagenda_fornecedor
			  WHERE tbagenda_fornecedor.cdespecificacao = '$lin[CdEspecProc]'
			  AND tbagenda_fornecedor.cdprocedimento = '$lin[CdProcedimento]'
			  AND tbagenda_fornecedor.cdfornecedor = '$lin[CdForn]'
			  AND tbagenda_fornecedor.`data` BETWEEN '$lin[dtinicio]' AND '$lin[dttermino]'
			  AND tbagenda_fornecedor.`status` = 'R'
			  ") or die (mysqli_error());
			  
			 $l1 = mysqli_fetch_array($sql_agenda);
			 $R = $l1['R'];
	 
			 $total = (($lin["total"])-($A+$M+$R));
	  
	  echo "<tr>

		<td style='text-align:center'> $lin[cdcota] </td>
		<td> $lin[NmForn] </td>
		<td> $lin[NmProcedimento]  $lin[NmEspecProc] </td>
		<td> $dtinicio à $dttermino </td>
		<td style='text-align:center'> $lin[total]  </td>";
		
		echo"
		<td style='text-align:center'>   $A  </td>
		<td style='text-align:center'>   $M  </td>
		<td style='text-align:center'>   $R  </td>
		<td style='text-align:center'>   $total </td>
		
	  </tr>";
	}
	echo "</table>";
?>	
		
	
	
	

<?php 

	
	$sql = mysqli_query($db,"  SELECT tbcota.cdcota, tbcota.CdEspecProc, tbcota.CdForn, tbcota.dtinicio, tbcota.dttermino, 
 tbcotam.cdpref, tbcotam.qts, tbprefeitura.CdPref, tbprefeitura.NmCidade
	FROM tbcotam, tbcota, tbprefeitura
	WHERE tbcota.cdcota = tbcotam.cdcota
	AND tbprefeitura.CdPref = tbcotam.cdpref
	AND tbcota.cdcota = $_GET[id]
	ORDER BY tbprefeitura.NmCidade
	 ") or die (mysqli_error());
	
	
  echo "<table id='table'>
  <tr>
	<th style='text-align:center'> Municipio  </th>
    <th style='text-align:center'> QTDE  LIBERADA  </th>
    <th style='text-align:center'>  AGUARDANDO  </th>
    <th style='text-align:center'>  MARCADO  </th>
    <th style='text-align:center'>  REALIZADO  </th>
    <th style='text-align:center'>  DISPONÍVEL  </th>
  </tr>";
	while($lin = mysqli_fetch_array($sql))
	{	
			  $dtinicio =  FormataDataBr($lin[dtinicio]);
			  $dttermino = FormataDataBr($lin[dttermino]);
	  
			  // AGUARDANDO 
			  $sql_agenda = mysqli_query($db,"
			  SELECT count(*) as A
			  FROM tbagenda_fornecedor
			  WHERE tbagenda_fornecedor.cdespecificacao = '$lin[CdEspecProc]'
			  AND tbagenda_fornecedor.cdfornecedor = '$lin[CdForn]'
			  AND tbagenda_fornecedor.`data` BETWEEN '$lin[dtinicio]' AND '$lin[dttermino]'
			  AND tbagenda_fornecedor.`status` = 'A'
			  AND tbagenda_fornecedor.cdpref ='$lin[CdPref]'
			  ") or die (mysqli_error());
			  
			 $l1 = mysqli_fetch_array($sql_agenda);
			 $A = $l1['A'];
		
		 	 // MARCADO 
			  $sql_agenda = mysqli_query($db,"
			  SELECT count(*) as M
			  FROM tbagenda_fornecedor
			  WHERE tbagenda_fornecedor.cdespecificacao = '$lin[CdEspecProc]'
			  AND tbagenda_fornecedor.cdfornecedor = '$lin[CdForn]'
			  AND tbagenda_fornecedor.`data` BETWEEN '$lin[dtinicio]' AND '$lin[dttermino]'
			   AND tbagenda_fornecedor.cdpref ='$lin[CdPref]'
			  AND tbagenda_fornecedor.`status` = 'M'
			  ") or die (mysqli_error());
			  
			 $l1 = mysqli_fetch_array($sql_agenda);
			 $M = $l1['M'];
		
			 // REALIZADO 
			  $sql_agenda = mysqli_query($db,"
			  SELECT count(*) as R
			  FROM tbagenda_fornecedor
			  WHERE tbagenda_fornecedor.cdespecificacao = '$lin[CdEspecProc]'
			  AND tbagenda_fornecedor.cdfornecedor = '$lin[CdForn]'
			  AND tbagenda_fornecedor.`data` BETWEEN '$lin[dtinicio]' AND '$lin[dttermino]'
			   AND tbagenda_fornecedor.cdpref ='$lin[CdPref]'
			  AND tbagenda_fornecedor.`status` = 'R'
			  ") or die (mysqli_error());
			  
			 $l1 = mysqli_fetch_array($sql_agenda);
			 $R = $l1['R'];
	 
			 $qts = (($lin["qts"])-($A+$M+$R));
	 
	  echo "<tr>

		<td > $lin[NmCidade] </td>
		<td style='text-align:center'> $lin[qts]  </td>";
		
		echo"
		<td style='text-align:center'>   $A  </td>
		<td style='text-align:center'>   $M  </td>
		<td style='text-align:center'>   $R  </td>
		<td style='text-align:center'>   $qts </td>
		
	  </tr>";
	}
	echo "</table>";
?>	
		
</div>