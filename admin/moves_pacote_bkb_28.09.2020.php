<?php

//caio - 2020-06-25
@session_start();

require_once "../funcoes.php";
require_once "../../vendor/autoload.php";

$now = \Carbon\Carbon::now();

$today = $now->format('Y-m-d');

$msg = new \Plasticbrain\FlashMessages\FlashMessages();

if(!$_SESSION['CdUsuario']){
	$msg->error("Not authenticated" , './../frm_login.php');
}


if (isset($_REQUEST["idcombo"]) && isset($_REQUEST["ac"])) {
	

    $action = $_REQUEST["ac"];

    $redirect_back = "../index.php?i=6&s=$s&op=$_GET[op]&pag=$_GET[pag]&pesq=$_GET[pesq]&cbopesq=$_GET[cbopesq]";

    $combo_id = $_REQUEST["idcombo"];

    switch ($action) {
        case "conf":
            echo $sql = "SELECT ac.DtAgCons, sc.CdSolCons from tbsolcons sc 
						  INNER JOIN tbagendacons ac on sc.CdSolCons = ac.CdSolCons
						  WHERE sc.idcombo = '$combo_id' and sc.`Status` = 1 and ac.`Status` = 1 ";

			
			$query = mysqli_query($db, $sql);

			
			
			$aux = false;

            if (!mysqli_num_rows($query)) {
				$msg->error(
                    'Nenhuma informação encontrada!',
                    '../index.php?i=6&s=$s&op=2&pag=$_GET[pag]&pesq=$_GET[pesq]&cbopesq=$_GET[cbopesq]'
				);
				
				die();
			
			}

			$errors = 0;

			while ($row = mysqli_fetch_array($query)) {
				$agendamento_data = $row['DtAgCons'];
				if (\Carbon\Carbon::parse($agendamento_data)->lte($today) ) {

					
					$done = set_realizado($row['CdSolCons'], true);
					
					### CONTROLE ###
					$log = mysqli_query(
						$db,
						"INSERT INTO tbauditoria (descr,dtalt,usralt,cdag) VALUES ('Confirmação','" .
							date("Y-m-d H:i:s") .
							"','$_SESSION[CdUsuario]','$row[CdSolCons]')"
					) or die("Error: log");

					$msg->success("Registro #".$row["CdSolCons"]." canceladas com sucesso!");
					
				}

				else{
					
					$error++;
					$msg->error("Não foi possível confirmar as agendas! A data das agendas é maior que a data atual!");
				}
			};
			
            if ($errors > 0 ) {
				$msg->success(
                    "{$erros} agenda(s) não foram confirmadas. Caso o problema persista, entre em contato com o suporte.",
                    "../index.php?i=6&s=$s&op=2&pag=$_GET[pag]&pesq=$_GET[pesq]&cbopesq=$_GET[cbopesq]"
                );                
			} 
			else
				$msg->success(
					"Agendas confirmadas com sucesso!",
					"../index.php?i=6&s=$s&op=2&pag=$_GET[pag]&pesq=$_GET[pesq]&cbopesq=$_GET[cbopesq]"
				);

            break;

        case "canc":
            switch ($_GET["op"]) {
                case '1':
                    $filtro = " AND sc.`Status` = 1 AND ac.`Status` is null ";
                    break;
                case '2':
                    $filtro = " AND sc.`Status` = 1 AND ac.`Status` = 1 ";
                    break;
                case '3':
                    $filtro = " AND sc.`Status` = 1 AND ac.`Status` = 2 ";
                    break;
            }
            $query_1001 = "SELECT sc.CdSolCons from tbsolcons sc 
				LEFT JOIN tbagendacons ac on sc.CdSolCons = ac.CdSolCons
				WHERE sc.idcombo = '$combo_id' $filtro";

            $sql = mysqli_query($db, $query_1001);
            $aux = false;
            if (mysqli_num_rows($sql) > 0) {
                while ($n = mysqli_fetch_array($sql)) {
                    set_canc($n['CdSolCons'], 0, 0);
                    $aux = true;
                };
            }

            if ($aux) {
                $msg->success(
                    "Agendas canceladas com sucesso!",
                    $redirect_back
                );
            } else {
                $msg->error("Erro ao cancelar as agendas", $redirect_back);
            }

            break;

        case "falt":
            switch ($_GET["op"]) {
                //case '1': $filtro = " AND sc.`Status` = 1 AND ac.`Status` is null "; break;
                case '2':
                    $filtro = " AND sc.`Status` = 1 AND ac.`Status` = 1 ";
                    break;
                //case '3': $filtro = " AND sc.`Status` = 1 AND ac.`Status` = 2 "; break;
            }
            $query = mysqli_query(
                $db,
                "SELECT sc.CdSolCons from tbsolcons sc 
								      LEFT JOIN tbagendacons ac on sc.CdSolCons = ac.CdSolCons
								      WHERE sc.idcombo = '$combo_id' $filtro"
            );
            $aux = false;

            if (mysqli_num_rows($query) > 0) {
                while ($n = mysqli_fetch_array($query)) {
                    $sql = "UPDATE tbsolcons SET `Status` = 'F' WHERE CdSolCons = $n[CdSolCons]";
                    //contratoRemMovimentacao($cd);
                    //cotaRemMovimentacao($cd);
                    mysqli_query($db, $sql) or
                        die("Erro 876 - Fila de procedimentos");
                    $sql = mysqli_query(
                        $db,
                        "INSERT INTO tblogag (tipo,dtalt,cdag) VALUES('Falta','" .
                            date("Y-m-d H:i:s") .
                            "','$n[CdSolCons]')"
                    );
                    ### CONTROLE ###
                    ($sqlag = mysqli_query(
                        $db,
                        "INSERT INTO tbauditoria (descr,dtalt,usralt,cdag) VALUES ('Falta','" .
                            date("Y-m-d H:i:s") .
                            "','$_SESSION[CdUsuario]','$n[CdSolCons]')"
                    )) or die("Erro ao tentar incluir LOG!");

                    contratoRemMovimentacao($n['CdSolCons']); //ESTORNO CONTRATO
                    $sql_movimentação = mysqli_query(
                        $db,
                        "UPDATE tbsldmovimentacao SET `status` = 0 WHERE cdsolcons = $n[CdSolCons]"
                    );
                    $aux = true;
                };
            }
            if ($aux) {
                $msg->success(
                    "Faltas aplicadas com sucesso!",
                    "../index.php?i=" .
                        (isset($_GET["agforn"]) ? $_GET["agforn"] : 6) .
                        "&op=$_GET[op]&pag=$_GET[pag]&pesq=$_GET[pesq]&cbopesq=$_GET[cbopesq]"
                );
            } else {
                $msg->error(
                    'Erro ao aplicar falta as agendas!',
                    "../index.php?i=" .
                        (isset($_GET["agforn"]) ? $_GET["agforn"] : 6) .
                        "&op=$_GET[op]&pag=$_GET[pag]&pesq=$_GET[pesq]&cbopesq=$_GET[cbopesq]"
                );
            }

            break;

        case "recep":
            $usr = $_SESSION['CdUsuario'];
            $dt = date('Y/m/d');
            $hr = date('H:i:s');
            $pr = $_GET['pr'];
            $st = 'T';

            $sqlag = "SELECT ac.DtAgCons, sc.CdSolCons from tbsolcons sc 
						  INNER JOIN tbagendacons ac on sc.CdSolCons = ac.CdSolCons
						  WHERE sc.idcombo = '$combo_id' and sc.`Status` = 1 and ac.`Status` = 1 ";

            ($sqlag = mysqli_query($db, $sqlag)) or die("Erro 900");
            $aux = false;

            if (mysqli_num_rows($sqlag) > 0) {
                while ($lag = mysqli_fetch_array($sqlag)) {
                    $sql = "INSERT INTO `tbtriagem` (CdSolCons, UsrTriagem, DtTriagem, HrTriagem, Prioridade, Status)
						VALUES ('$lag[CdSolCons]', '$usr', '$dt', '$hr', '$pr', '$st')";

                    ($query = mysqli_query($db, $sql)) or die(mysqli_error());
                };
            }

            if (!$query) {
                $msg->error("Operação nao pôde ser concluída");
                die();
            }

            $msg->success("Registro recepcionado com sucesso", $redirect_back);

            break;

        case "voltar":
            switch ($_GET["op"]) {
                case '1':
                    $filtro = " AND sc.`Status` = 1 AND ac.`Status` is null ";
                    break;
                case '2':
                    $filtro = " AND sc.`Status` = 1 AND ac.`Status` = 1 ";
                    break;
                case '3':
                    $filtro = " AND sc.`Status` = 1 AND ac.`Status` = 2 ";
                    break;
                case '4':
                    $filtro = " AND sc.`Status` = 2 ";
                    break;
                case '8':
                    $filtro = " AND sc.`Status` = 'F' ";
                    break;
            }
            $listaN = '';
            ($sql = mysqli_query(
                $db,
                "SELECT sc.CdSolCons from tbsolcons sc 
								    LEFT JOIN tbagendacons ac on sc.CdSolCons = ac.CdSolCons
								    WHERE sc.idcombo = '$combo_id' $filtro"
            )) or die('moves_pacote - ' . mysqli_error());

            if (mysqli_num_rows($sql) > 0) {
                while ($n = mysqli_fetch_array($sql)) {
                    $cons = getValuesAgendamento($n['CdSolCons']);

                    if ($cons['Status'] == '2' || $cons['Status'] == 'F') {
                        if (!verificaMovContrato($n['CdSolCons'])) {
                            $contrato = contratoCerto(
                                $cons['CdEspecProc'],
                                $cons['CdForn'],
                                $cons['DtAgCons']
                            );
                            $nomeEspec = nomeEspecificacao(
                                $cons['CdEspecProc']
                            );
                            if (!$contrato) {
                                $auxvoltar = 1;
                                $listaN .= '| ' . $nomeEspec . ' ';
                            } else {
                                contratoAddMovimentacao(
                                    $n['CdSolCons'],
                                    $contrato,
                                    $_SESSION["CdUsuario"]
                                );
                                $auxvoltar = 0;
                            }
                        } else {
                            $auxvoltar = 0;
                        }
                    } else {
                        $auxvoltar = 0;
                    }

                    if (!$auxvoltar) {
                        $usr = (int) $_SESSION["CdUsuario"];
                        $dt = date("Y-m-d") . " " . date("H:i:s");

                        ($sc = mysqli_query(
                            $db,
                            "UPDATE tbsolcons SET Status='1',usrret='$usr',dtret='$dt',cdmotcanc=0 WHERE CdSolCons = $n[CdSolCons]"
                        )) or die("Erro ao voltar");
                        ($ag = mysqli_query(
                            $db,
                            "UPDATE tbagendacons SET Status='1' WHERE CdSolCons = $n[CdSolCons]"
                        )) or die("Erro ao voltar");
                    }
                };
            }
            if ($listaN != '') {
                $msg->error(
                    "Os procedimentos listados não possuem contratos ou chegaram ao fim, na data informada: " .
                        $listaN .
                        "!",
                    $redirect_back
                );
            } else {
                $msg->success(
                    'Agendas retornadas com sucesso!',
                    $redirect_back
                );
            }

            break;

        case "aceitar":
            $sql = mysqli_query(
                $db,
                "SELECT sc.CdSolCons from tbsolcons sc 
								    LEFT JOIN tbagendacons ac on sc.CdSolCons = ac.CdSolCons
								    WHERE sc.idcombo = '$combo_id' AND sc.`Status` = 1 AND ac.`Status` = 1"
            );

            if (mysqli_num_rows($sql) > 0) {
                while ($n = mysqli_fetch_array($sql)) {
                    ($sql2 = mysqli_query(
                        $db,
                        "UPDATE `tbagendacons` SET `aceite`='1' WHERE (`CdSolCons`='$n[CdSolCons]')"
                    )) or die(mysqli_error());
                };
            }

            if ($sql2) {
                $msg->success("Agendas aceitas com sucesso!", $redirect_back);
            } else {
                $msg->error("Erro ao aceitar as agendas!", $redirect_back);
            }

            break;

        case "rejeitar":
            $sql = mysqli_query(
                $db,
                "SELECT sc.CdSolCons from tbsolcons sc 
								    LEFT JOIN tbagendacons ac on sc.CdSolCons = ac.CdSolCons
								    WHERE sc.idcombo = '$combo_id' AND sc.`Status` = 1 AND ac.`Status` = 1"
            );

            if (mysqli_num_rows($sql) > 0) {
                while ($n = mysqli_fetch_array($sql)) {
                    ($sql2 = mysqli_query(
                        $db,
                        "UPDATE `tbagendacons` SET `aceite`='0' WHERE (`CdSolCons`='$n[CdSolCons]')"
                    )) or die(mysqli_error());
                };
            }

            if ($sql2) {
                $msg->success(
                    "Agendas rejeitadas com sucesso!",
                    $redirect_back
                );
            } else {
                $msg->error("Erro ao rejeitar as agendas!", $redirect_back);
            }

            break;

        default:
            # code...
            break;
    }
}

?>
