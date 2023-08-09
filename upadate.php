<?php


    $db = mysqli_connect('localhost', 'root', '') or die ("Nao foi possivel conectar ao banco de dados");
    mysqli_select_db('cismpei_prod',$db) or die (mysqli_error()); 

	set_time_limit(0);

	// ATUALIZA PREFEITURA 
	/* $sql = mysqli_query($db," SELECT * FROM tbsolcons  ");
	
	while ($lin = mysqli_fetch_array($sql))
	{
		
		// DESCOBRE CIDADE PACIENTE
		$sql2 = mysqli_query($db,"SELECT * FROM tbpaciente,tbprefeitura,tbbairro 
		WHERE tbpaciente.CdBairro = tbbairro.CdBairro 
		AND tbprefeitura.CdPref = tbbairro.CdPref
		AND tbpaciente.CdPaciente = $lin[CdPaciente]");
		
		$lin2 = mysqli_fetch_array($sql2);
		
		mysqli_query($db,"UPDATE `tbsolcons` SET `CdPref`='$lin2[CdPref]' 
		WHERE (`CdSolCons`='$lin[CdSolCons]')") or die (mysqli_error());
		
		
	} 
	
	$sql2 = mysqli_query ("SELECT * FROM tbsolcons  WHERE tbsolcons.CdPref IS NULL");
	echo mysqli_num_rows($sql2); */
	
	// ATUALIZA AGENDA FORNECEDOR 
	$sql = mysqli_query($db," SELECT * FROM tbagenda_fornecedor ");
	
	while($lin = mysqli_fetch_array($sql))
	{
		
		echo $lin['status']."<br />";	
		
		if($lin['status']==0)
		{
			$ss= mysqli_query($db," UPDATE `tbagenda_fornecedor` SET `status`='A' WHERE (`cdagenda_fornecedor`='$lin[cdagenda_fornecedor]')");	
			
		}
		else 
		{
			if($lin['status']==1)
			{
			$ss= mysqli_query($db," UPDATE `tbagenda_fornecedor` SET `status`='M' WHERE (`cdagenda_fornecedor`='$lin[cdagenda_fornecedor]')");
			}
		}
		
	}
	
	

 ?>