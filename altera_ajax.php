<?php

  include("conecta.php");
  include("funcoes.php");

  $campo = $_GET["campo"];
  $cd = $_GET["cd"];
  $id = $_GET["id"];
  
  switch($id){
	  
	case 'DtAgCons':
	  $campo = FormataDataBD($campo);
	  $sql_agenda  = mysqli_query($db,"UPDATE tbagendacons SET DtAgCons='$campo' WHERE (CdSolCons='$cd')") or die (mysqli_error());
	break;

    case "HoraAgCons":
	  $sql = mysqli_query($db,"UPDATE tbagendacons SET HoraAgCons='$campo' WHERE (CdSolCons='$cd') ") or die (mysqli_error());
	break;
	
    case "CdForn":
	  $sql = mysqli_query($db,"UPDATE tbagendacons SET CdForn='$campo' WHERE (CdSolCons='$cd') ") or die (mysqli_error());
	break;
    
	case 'CdEspecProc': 
	  //$sql= mysqli_query($db,"UPDATE tbsolcons SET CdEspecProc='$campo' WHERE CdSolCons='$cd'");
	  
	  $proc = mysqli_query($db,"SELECT qts, valor, valor_sus
	  					   FROM tbagendacons, tbsolcons 
	  					   WHERE tbsolcons.CdEspecProc = $campo AND tbagendacons.CdSolCons=tbsolcons.CdSolCons GROUP BY tbsolcons.CdEspecProc") or die (mysqli_error());
	  
	  $l=mysqli_fetch_array($proc);
	  //$l[valor] =  number_format($l[valor], 2, ',', '.');
	  //fazer a consulta na tabela e pegar os valores. 
	  
	  echo "2|$l[qts]|$l[valor]|$l[valor_sus]";
	  
	break;
	
	case 'qts': 
	  $sql  = mysqli_query($db,"UPDATE tbagendacons SET qts='$campo' WHERE (CdSolCons='$cd')");
    break;  
	case 'valor': 
	  $campo = moeda($campo);
	  $sql  = mysqli_query($db,"UPDATE tbagendacons SET valor='$campo' WHERE (CdSolCons='$cd')");
    break;  
	case 'valor_sus': 
	  $campo = moeda($campo);
	  $sql_agenda  = mysqli_query($db,"UPDATE tbagendacons SET valor_sus='$campo' WHERE (CdSolCons='$cd')");
    break;
	case 'ppi': 
	  $sql_agenda  = mysqli_query($db,"UPDATE tbagendacons SET ppi='$campo' WHERE (CdSolCons='$cd')");
    break;  
	
	
	
	case 'conf':
	  switch($campo)
	  {
		case 'R': 
	  		$sql_agenda= mysqli_query($db,"UPDATE tbsolcons SET Status='1' WHERE (CdSolCons='$cd')") or die (mysqli_error());
	  		$sql_agenda = mysqli_query($db,"UPDATE tbagendacons SET `Status`='2' WHERE (CdSolCons='$cd')") or die (mysqli_error());
    	break;  
		case 'C': 
		  $sql_1 = mysqli_query($db,"UPDATE `tbsolcons` SET `Status`='2' WHERE (`CdSolCons`='$cd')") or die (mysqli_error());
		  $sql_2 = mysqli_query($db,"UPDATE `tbagendacons` SET `Status`='2' WHERE (`CdSolCons`='$cd')") or die (mysqli_error());
		break;  
		
		case 'M': 
		  $sql_1 = mysqli_query($db,"UPDATE `tbsolcons` SET `Status`='1' WHERE (`CdSolCons`='$cd')") or die (mysqli_error());
		  $sql_2 = mysqli_query($db,"UPDATE `tbagendacons` SET `Status`='1' WHERE (`CdSolCons`='$cd')") or die (mysqli_error());
		break;  
	  }
	break;
  }
?>





