<?php 
	include "conecta.php";
	
	$sql = mysqli_query($db," SELECT * FROM `tbagenda_fornecedor` ") or die (mysqli_error());
	
	while($lin = mysqli_fetch_array($sql))
	{
		
		echo $lin['status']."<br>";
		
			
	}
	

?>