 <link rel="stylesheet" type="text/css" href="css/geral.css"/>
 <style>   
	body { background:#FFF    }
	#proc { padding:10px;    }
	#proc div { border-bottom:#666 dashed 1px;  padding:3px;   }
	#proc div li { padding:2px;    }
	#proc h1 {font-size:12px;   }
 </style>
 <table width="100%" border="0">
   <tr>
     <th>Lista de procedimentos sem cota lan&ccedil;ada no m&ecirc;s de 
	 <?php 
		switch (date("m")) {
				case "01":    $mes = Janeiro;     break;
				case "02":    $mes = Fevereiro;   break;
				case "03":    $mes = Março;       break;
				case "04":    $mes = Abril;       break;
				case "05":    $mes = Maio;        break;
				case "06":    $mes = Junho;       break;
				case "07":    $mes = Julho;       break;
				case "08":    $mes = Agosto;      break;
				case "09":    $mes = Setembro;    break;
				case "10":    $mes = Outubro;     break;
				case "11":    $mes = Novembro;    break;
				case "12":    $mes = Dezembro;    break; 
		 }	 echo $mes;
	  ?></th>
   </tr>

 <?php
 	include("conecta.php"); 
 	$sql = "SELECT ep.NmEspecProc
			FROM
			tbespecproc AS ep
			Left Join tbcota AS c ON ep.CdEspecProc = c.CdEspecProc
			WHERE MONTH(c.dtinicio) <> ".date("m")." OR c.dtinicio is NULL
			ORDER BY ep.cdgrupoproc ASC";
	$sql = mysqli_query($db,$sql) or die("Erro ao tentar selecionar procedimentos!");
	
	while($l = mysqli_fetch_array($sql)){
		echo "<tr>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;$l[NmEspecProc]</td>
			  </tr>";
	} 
 ?>

 </table>
 