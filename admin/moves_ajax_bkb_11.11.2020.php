<?php

@session_start();

require_once "../funcoes.php";
require_once "../../vendor/autoload.php";

$msg = new \Plasticbrain\FlashMessages\FlashMessages();
if(!$_SESSION['CdUsuario']){
	$msg->error("Not authenticated" , './../frm_login.php');
}



if (isset($_POST['cdag']) && isset($_POST['action'])) {

    $cd = $_POST['cdag'];
    $action = $_POST['action'];

    switch ($action) {
        case "conf":
            $sqlag = "SELECT tbagendacons.DtAgCons FROM tbagendacons WHERE tbagendacons.CdSolCons = $cd";
            ($sqlag = mysqli_query($db, $sqlag)) or die("Erro 900");
            $lag = mysqli_fetch_array($sqlag);
            $hoje = date("Y-m-d");

            if ($lag[DtAgCons] <= $hoje) {
                set_realizado($cd, true);
                ### CONTROLE ###
                ($sqlag = mysqli_query(
                    $db,
                    "INSERT INTO tbauditoria (descr,dtalt,usralt,cdag) VALUES ('Confirmação','" .
                        date("Y-m-d H:i:s") .
                        "','$_SESSION[CdUsuario]','$cd')"
                )) or die("Erro ao tentar incluir LOG!");

                echo "1";
            } else {
                echo "0";
            }

            break;

        case "canc":
            set_canc($cd, 0, 0);
            echo "1";

            break;

        case "falt":
            $sql = "UPDATE tbsolcons SET `Status` = 'F' WHERE CdSolCons = $cd";
            //contratoRemMovimentacao($cd);
            //cotaRemMovimentacao($cd);
            mysqli_query($db, $sql) or die("Erro 876 - Fila de procedimentos");
            $sql = mysqli_query(
                $db,
                "INSERT INTO tblogag (tipo,dtalt,cdag) VALUES('Falta','" .
                    date("Y-m-d H:i:s") .
                    "','$cd')"
            );
            ### CONTROLE ###
            ($sqlag = mysqli_query(
                $db,
                "INSERT INTO tbauditoria (descr,dtalt,usralt,cdag) VALUES ('Falta','" .
                    date("Y-m-d H:i:s") .
                    "','$_SESSION[CdUsuario]','$cd')"
            )) or die("Erro ao tentar incluir LOG!");
            /*	  			
				// estorna saldo 
					$sql1 = mysqli_query($db,"SELECT tbsolcons.CdSolCons, tbprefeitura.CdPref, tbagendacons.valor 
					FROM tbagendacons, tbsolcons, tbpaciente, 
					tbprefeitura,tbbairro
					WHERE tbsolcons.CdSolCons = tbagendacons.CdSolCons
					AND tbsolcons.CdPaciente = tbpaciente.CdPaciente
					AND tbprefeitura.CdPref = tbbairro.CdPref
					AND tbbairro.CdBairro = tbpaciente.CdBairro
					AND tbsolcons.CdSolCons=$cd ");
					
					$l = mysqli_fetch_array($sql1);
					
					$valor = $l['valor'];
					$CdPref = $l['CdPref'];*/

            $sql3 = mysqli_query(
                $db,
                "INSERT INTO `tbmovimentacao` (CdPref, CdUsuario, CdSolCons, TpMov, Credito)
					VALUES ('$CdPref', '$_SESSION[CdUsuario]', '$cd', 'C', '$valor')"
            );

            echo "1";
            break;

        case "recep":
            $usr = $_SESSION['CdUsuario'];
            $dt = date('Y/m/d');
            $hr = date('H:i:s');
            $pr = $_GET['pr'];
            $st = 'T';

            ($sql = mysqli_query(
                $db,
                "INSERT INTO `tbtriagem` (CdSolCons, UsrTriagem, DtTriagem, HrTriagem, Prioridade, Status)
						VALUES ('$cd', '$usr', '$dt', '$hr', '$pr', '$st')"
            )) or die(mysqli_error());

            echo $sql ? "1" : "0";

            break;

        case "voltar":
            $usr = (int) $_SESSION["CdUsuario"];
            $dt = date("Y-m-d") . " " . date("H:i:s");

            ($sc = mysqli_query(
                $db,
                "UPDATE tbsolcons SET Status='1',usrret='$usr',dtret='$dt',cdmotcanc=0 WHERE CdSolCons = $cd"
            )) or die("Erro ao voltar");
            ($ag = mysqli_query(
                $db,
                "UPDATE tbagendacons SET Status='1' WHERE CdSolCons = $cd"
            )) or die("Erro ao voltar");

            echo $sc && $ag ? "1" : "0";

            break;

        default:
            # code...
            break;
    }
}
?>
