<?php

	//REQUIRE
	require('funcoes.php');
	require('../../fpdf/fpdf.php');
	require("../../conecta.php");
	
	// RECEBE PARAMÊTROS
	$dtinicio = $_POST['dtinicio'];
	$dttermino = $_POST['dttermino'];
	
	
	
	
	// DEFINE O CABEÇALHO  * OBSERVAR A ORIENTAÇÃO DO PAPEL
	require("cab.retrato.php");
	$pdf=new PDF();
	
	// INSERE A PÁGINA E DEFINE A ORIENTAÇÃO DO PAPEL 
	$pdf->AddPage(P);	
	
	// DEFINE MARGIN
	$pdf->SetLeftMargin(1);
	$pdf->SetTopMargin(1);
	
	
	
	// CABEÇALHO DO RELÁTORIO
	$pdf->SetFont('Arial','B',10);	
	$pdf-> 	MultiCell(208, 6, 'QUANTITATIVO DE PROCEDIMENTOS (ATENDIMENTOS REALIZADOS)',1, 'C');    
	$pdf-> 	MultiCell(208, 6, 'PERÍODO: '.$dtinicio.' À '. $dttermino,1, 'C');    
	$pdf->SetY(45);

	
	// INICIA OS DADOS DO CABEÇALHO
	$pdf->SetWidths(array(148,60)); srand(microtime()*1000000);
	
		
	
	// SELECIONA O PROCEDIMENTO 
	$sql_p = mysqli_query($db,"SELECT ep.cdsus,ep.CdEspecProc, ep.NmEspecProc, count(*) as total
	FROM tbsolcons sc INNER JOIN tbpaciente p ON sc.CdPaciente=p.CdPaciente 
	INNER JOIN tbbairro b ON b.CdBairro=p.CdBairro
	INNER JOIN tbprefeitura pr ON b.CdPref=pr.CdPref
	INNER JOIN tbespecproc ep ON sc.CdEspecProc=ep.CdEspecProc 
	INNER JOIN tbprocedimento Pa ON ep.CdProcedimento = Pa.CdProcedimento
	LEFT JOIN tbagendacons ac ON sc.CdSolCons=ac.CdSolCons
	LEFT JOIN tbfornecedor f ON ac.CdForn=f.CdForn
	LEFT JOIN tbusuario u ON ac.CdUsuario = u.CdUsuario
	WHERE sc.Status='1' AND ac.Status='2'
	AND ep.ppi='S'
	GROUP BY NmEspecProc");
	
	$total=0;
	 while($l = mysqli_fetch_array($sql_p)) 
	 
	 
	
	{
		$pdf->Row(array($l[NmEspecProc],$l[total]),1);
		
	$total=$l[total] + $total;
		
				
	}
	
	$pdf->SetWidths(array(148,60)); srand(microtime()*1000000);
	$pdf->Row(array(''),0);
	$pdf->Row(array('TOTAL DE PROCEDIMENTOS',$total),1);
	
	
	//	$pdf->Row(array( 'PROCEDIMENTO',$NmCidade[0],$NmCidade[1],$NmCidade[2],$NmCidade[3],$NmCidade[4],$NmCidade[5],'TOTAL'),1);

	
	
	
	
	
	
	
	
	/*
	$pdf->Row(array( 'PROCEDIMENTO','BELA VISTA DE MINAS','CATAS ALTAS','JOÃO MOLEVADE','RIO PIRACICABA','SÃO DOMINGOS','SÃO DOMINGOS','SÃO DOMINGOS'),1);
	
	*/
	
	
	
	
	
	
	
	
	
		
	$pdf->Output();
	mysqli_close();
?>