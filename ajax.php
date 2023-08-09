<?php

  include("conecta.php");



  $campo = $_GET["campo"];
  
  $sql = mysqli_query($db," SELECT * 
  FROM tbpaciente
  WHERE tbpaciente.NmPaciente = '$campo' ") or die (mysqli_error());

  if(mysqli_num_rows($sql)>0) { 
   
  }
	
?>
<?php 

  /*  $tabela = $_GET["tabela"];
  $OP = $_GET["OP"];

  $CdSolCons = $_GET["CdSolCons"];
  $CdPaciente = $_GET["CdPaciente"];

  switch($tabela){
    case 'confirmar' : {
	  if ($campo == 'marcado'){
	    $sql_1 = mysqli_query($db,"UPDATE `tbsolcons` SET `Status`='1' WHERE (`CdSolCons`='$OP')") or die (mysqli_error());
	    $sql_2 = mysqli_query($db,"UPDATE `tbagendacons` SET `Status`='2' WHERE (`CdSolCons`='$OP')") or die (mysqli_error());
	  }
	  if($campo == 'realizado'){
	    $sql_1 = mysqli_query($db,"UPDATE `tbsolcons` SET `Status`='1' WHERE (`CdSolCons`='$OP')") or die (mysqli_error());
	    $sql_2 = mysqli_query($db,"UPDATE `tbagendacons` SET `Status`='1' WHERE (`CdSolCons`='$OP')") or die (mysqli_error());
	  }
	}
	break;
    case 'NmPaciente' : {
	  $sql_paciente = mysqli_query($db,"UPDATE tbpaciente SET NmPaciente='$campo' WHERE (CdPaciente='$CdPaciente')") or die (mysqli_error());	   
	}
	break;
    case 'DtAgCons' : {
	  $sql_agenda  = mysqli_query($db,"UPDATE tbagendacons SET DtAgCons='".FormataDataBD($campo)."' WHERE (CdSolCons='$CdSolCons')");
	}
	break;
    case 'HoraAgCons' : {
	  $sql_agenda  = mysqli_query($db,"UPDATE tbagendacons SET HoraAgCons='$campo' WHERE (CdSolCons='$CdSolCons')");
	}
	break;
    case 'qts' : {
	  $sql_agenda  = mysqli_query($db,"UPDATE tbagendacons SET qts='$campo' WHERE (CdSolCons='$CdSolCons')");
	}
	break;
    case 'valor_pactuado' : {
	  $sql_agenda  = mysqli_query($db,"UPDATE tbagendacons SET valor_pactuado='".number_format(moeda($campo),2,',','.')."' WHERE (CdSolCons='$CdSolCons')");
	}
	break;
    case 'valor_n_pactuado' : {
	  $sql_agenda  = mysqli_query($db,"UPDATE tbagendacons SET valor_n_pactuado='".number_format(moeda($campo),2,',','.')."' WHERE (CdSolCons='$CdSolCons')");
	}
	break;
    case 'select_forne' : {
	  $sql_agenda  = mysqli_query($db,"UPDATE tbagendacons SET CdForn='".$campo."' WHERE CdSolCons='$OP'");
	}
	break;
    case 'select_forne' : {
	  $sql_agenda  = mysqli_query($db,"UPDATE tbagendacons SET CdForn='".$campo."' WHERE CdSolCons='$OP'");
	}
	break;
    case 'CdEspecProc' : {
	  $sql_agenda = mysqli_query($db,"UPDATE tbsolcons SET CdEspecProc='".$campo."' WHERE CdSolCons='$OP'");
	}
	break;
    case 'pactuacao' : {
	  if($campo == 0){
	    $sql_agenda = mysqli_query($db,"UPDATE tbsolcons SET pactuacao='1' WHERE CdSolCons='$OP'");
	  }
	  if($campo == 1){
	    $sql_agenda = mysqli_query($db,"UPDATE tbsolcons SET pactuacao='0' WHERE CdSolCons='$OP'");
	  }
	}
	break;
  }
  */
  
?>