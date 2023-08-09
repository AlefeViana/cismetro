<?php 
	
   require('conecta.php');
 
	   
   $cd_pref = $_GET['cd_pref'];
   $dtinicio = FormataDataBd($_GET['dtinicio']);
   $dttermino = FormataDataBd($_GET['dttermino']);
   
   $sql = mysqli_query($db," 
   SELECT * FROM tbtetoppi
   WHERE dtinicio = '$dtinicio'
   AND dttermino= '$dttermino'
   AND cdpref = '$cd_pref' ")  or die (mysqli_error());
	
	$l = mysqli_fetch_array($sql);
	
	echo "<li style='font-size:40px;'> Teto PPI: R$ ".number_format($l[vltetoppi], 2, ',', '.')."</li>";
	
    	
	
	
	$sql2 = mysqli_query($db,"SELECT SUM(ac.valor_sus*qts) as totalvalor
	FROM tbsolcons AS sc 
	INNER JOIN tbpaciente AS p ON sc.CdPaciente = p.CdPaciente 
	INNER JOIN tbbairro AS b ON b.CdBairro = p.CdBairro 
	INNER JOIN tbprefeitura AS pr ON b.CdPref = pr.CdPref
	INNER JOIN tbespecproc AS ep ON sc.CdEspecProc = ep.CdEspecProc
	INNER JOIN tbprocedimento AS proc ON ep.CdProcedimento = proc.CdProcedimento
	LEFT JOIN tbagendacons AS ac ON sc.CdSolCons = ac.CdSolCons
	LEFT JOIN tbfornecedor AS f ON ac.CdForn = f.CdForn
	WHERE   DtAgCons BETWEEN '$l[dtinicio]' AND '$l[dttermino]' 
	AND	ac.ppi = 's'
	AND (sc.Status = '1' AND ac.Status = '2')
	AND sc.CdPref = '$l[cdpref]'
	ORDER BY ac.DtAgCons,  ac.HoraAgCons, p.NmPaciente
	");
	
	$l2 = mysqli_fetch_array($sql2);
	
	echo "<li style='font-size:40px;'> Total Valor SUS PPI: R$ ".number_format($l2[totalvalor], 2, ',', '.')."</li>";
	
	$dif = $l2[totalvalor]-$l[vltetoppi];
	
	
	echo "<li style='font-size:40px;'> Diferença R$: ".number_format($dif, 2, ',', '.')."</li>";







?>


