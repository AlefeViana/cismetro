
<?php
	
	//parametros para pesquisa
	
	$pesq = $_GET["pesq"];
	$cbopesq = (int)$_GET["cbopesq"];	

	
	
	require ("conecta.php");

	$sql = "SELECT sc.CdSolCons,DtAgCons,HoraAgCons,p.CdPaciente,sc.remarcado,
			p.NmPaciente,ac.Valor,p.DtNasc,ac.CdUsuario, u.Login,
			ac.CdForn,pr.NmCidade,sc.Protocolo,sc.DtInc,pr.CdPref,ep.CdEspecProc,
			NmEspecProc,f.NmForn,Obs1,sc.Status,ac.Status as StatusAg,Urgente,NmReduzido,sc.Obs, ac.obs as obsac, Pa.NmProcedimento
			FROM tbsolcons sc INNER JOIN tbpaciente p ON sc.CdPaciente=p.CdPaciente 
			INNER JOIN tbbairro b ON b.CdBairro=p.CdBairro
			INNER JOIN tbprefeitura pr ON b.CdPref=pr.CdPref
			INNER JOIN tbespecproc ep ON sc.CdEspecProc=ep.CdEspecProc 
			INNER JOIN tbprocedimento Pa ON ep.CdProcedimento = Pa.CdProcedimento
			LEFT JOIN tbagendacons ac ON sc.CdSolCons=ac.CdSolCons
			LEFT JOIN tbfornecedor f ON ac.CdForn=f.CdForn
			LEFT JOIN tbusuario u ON ac.CdUsuario = u.CdUsuario
			"; 								
	
	
	
	/*if($pesq != ''){
		
		
		$pesq = mysqli_real_escape_string($pesq);
		
		switch($cbopesq)
		{
			case 1: $sql .= " WHERE nmtpprofsaude like '%$pesq%'";
					break;
			case 2: $sql .= " WHERE pr.nmpessoa like '%$pesq%'";		
					break;
			case 3: $sql .= " WHERE p.nmpessoa like '%$pesq%'";		
					break;					
		}
	}*/
		
	$qry = mysqli_query($db,$sql) or die (mysqli_error());
	$array = array();
	if (mysqli_num_rows($qry) > 0)
		while($d = mysqli_fetch_array($qry)){
			$dtag = $d["DtAgCons"].' '.$d["HoraAgCons"];	
			$Data = strtotime($DtAgCons);
			$DataTerm = strtotime("+10 minutes",$Data);
			$DataT	  = date('Y-m-d',$DataTerm);
			$HoraT	  = date('H:i',$DataTerm);
			
			$Urgente = '';
			
			/*
			switch($d["status"])
			{
				case 'AG': $cor = 'ag_aguardando';
						  if ($d["urgente"] == '1')
						  		$Urgente = '<< Urgente >> ';
						  break;
				case 'R': $cor = '';
						  break;
				case 'C': $cor = 'ag_cancelado';
						  break;		  
			} */
			
			
			// AGENDADO 
			if($d[Status]=="1")
			{
				if($d[StatusAg]=="1")
				{ 
				  $cor = "ag_aguardando";
				}
				if($d[StatusAg]=="2")
				{ 
				  $cor = "ag_realizado";
				}
				
				
			}
			else {
				$cor = "ag_cancelado";
				
				
				}
			
			
		
			
			
			
			
				
			$array[] = array(
							'id' => $d[CdPaciente],
							// 'title' => utf8_encode($Urgente.$d[nmpessoa]."-".$d[].' - Paciente: '.$d[nmpessoa].' - Especialidade: '.$d[nmpessoa]),
							'title' => utf8_encode($d[NmCidade].' -  '.'PACIENTE: '.$d[NmPaciente].'- FORNECEDOR: '.$d[NmForn]),
							'start' => $d[DtAgCons].' '.$d[HoraAgCons],
							//'end' => $DataT.' '.$HoraT,
							'allDay' => false,
							// 'url' => "javascript:window.location.href='?i=19&ag=ag&cd=$d[cdagenda]'", 							
							'className' => $cor,
							'description' => utf8_encode($d[CdSolCons])
					   );
		}
	
	echo json_encode($array);
	
	mysqli_close();
	mysqli_free_result($qry);
	
	
	
	
	//<a href='procedimento.php?cd=$l[cdagenda]&keepThis=true&TB_iframe=true&height=480&width=650' title='Procedimento &raquo $l[cdagenda]' class='thickbox'> 
	
	
	/*echo json_encode(array(
	
		array(
			'id' => 111,
			'title' => "Event1",
			'start' => "$year-$month-10",
			'url' => "http://yahoo.com/"
		),
		
		array(
			'id' => 222,
			'title' => "Event2",
			'start' => "$year-$month-20",
			'end' => "$year-$month-22",
			'url' => "http://yahoo.com/"
		)
	
	));
*/
?>
