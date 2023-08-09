
<?php
	
	//parametros para pesquisa
	
	$pesq = $_GET["pesq"];
	$cbopesq = (int)$_GET["cbopesq"];	

	//$cbopesq = 1;
	//conecta
	require('conecta.php');
	
	$sql = "SELECT * FROM tbagendacons"; 								
	
	 /* if($pesq != ''){
		
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
	} */
		
	$qry = mysqli_query($db,$sql) or die (mysqli_error());
	$array = array();
	 if (mysqli_num_rows($qry) > 0)
		while($d = mysqli_fetch_array($qry)){
			$DataCons = $d["data"].' '.$d["hora"];	
			$Data = strtotime($DataCons);
			$DataTerm = strtotime("+$d[duracao] minutes",$Data);
			$DataT	  = date('Y-m-d',$DataTerm);
			$HoraT	  = date('H:i',$DataTerm);
			
			$Urgente = '';
			/* switch($d["status"])
			{
				case 'A': $cor = 'ag_aguardando';
						  if ($d["urgente"] == '1')
						  		$Urgente = '<< Urgente >> ';
						  break;
				case 'R': $cor = '';
						  break;
				case 'E': $cor = 'ag_cancelado';
						  break;		  
			} 
			$cor = 'ag_aguardando';
		} 
	/*
		$array[] = array(
							'id' => $d[cdagenda],
							'title' => utf8_encode($Urgente.$d[nmtpprofsaude]."-".$d[nmmedico].' - Paciente: '.$d[nmpessoa].' - Especialidade: '.$d[nmespecialidade]),
							'start' => $d[data].' '.$d[hora],
							'end' => $DataT.' '.$HoraT,
							'allDay' => false,
							'url' => "javascript:window.location.href='?i=19&ag=ag&cd=$d[cdagenda]'", 							
						//	'className' => $cor,
							'description '=> utf8_encode($d[nmmedico])
					   );*/
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
