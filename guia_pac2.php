<?php
session_start();
require("conecta.php");
include 'funcoes.php';

require "../vendor/autoload.php";

use Stringy\Stringy as S;

$nomeUsuer = $_SESSION['NmUsuario'];
$data_agora = date('d/m/Y H:i:s');
function possuiAlteracao($cdsolcons)
{

	$query = "SELECT COUNT(cdlogusr) AS qtd FROM tbusralt WHERE cdag = $cdsolcons";
	$result = mysqli_query($GLOBALS['db'], $query);
	$dado = mysqli_fetch_array($result, MYSQLI_ASSOC);
	return $dado['qtd'];
}

$id = $_GET['id'];

// Atualiza status guia
//$sql_att_guia = mysqli_query($db,"UPDATE `tbagendacons` SET `impressaoguia`='S' WHERE (`CdSolCons`='$id')") or die (mysqli_error());


$sql = mysqli_query($db, "SELECT  saldo.`Desc`,af.obs as obsAgenda, af.cdendc,ac.cdEndereco,pe.Logradouro, pe.Telefone as TelProf ,pe.Numero as crednumero,pe.Bairro as bairrocred,
pe.Cidade,pe.Estado,af.cbo,sc.CdSolCons,DtAgCons,HoraAgCons, p.CdPaciente,p.RG,
p.NmMae, f.CdForn,f.Bairro,f.logradouro, f.Numero, f.CdCidade, ep.nmpreparo, p.NmPaciente, 
ac.valor,ac.valormed, p.DtNasc, ac.CdUsuario, u.Login, u.NmUsuario, ac.CdForn, pr.NmCidade,sc.Protocolo, sc.DtInc,pr.CdPref, 
ep.CdEspecProc, ep.CdProcedimento, ac.qts, ac.protocolopac, NmEspecProc, f.NmForn, f.NmReduzido as nmred,sc.Status, ac.Status as StatusAg,Urgente,NmReduzido, 
f.cns,sc.Obs, ac.obs,ep.cdsus, Pa.NmProcedimento, sc.Obs1, p.DtNasc, p.csus, f.Compl, p.Celular, prof.cdprof, 
prof.nmprof, e.UF
	FROM tbsolcons sc INNER JOIN tbpaciente p ON sc.CdPaciente=p.CdPaciente 
	INNER JOIN tbbairro b ON b.CdBairro=p.CdBairro
	INNER JOIN tbprefeitura pr ON b.CdPref=pr.CdPref
	INNER JOIN tbestado e ON pr.CdEstado = e.CdEstado
	INNER JOIN tbespecproc ep ON sc.CdEspecProc=ep.CdEspecProc 
	INNER JOIN tbprocedimento Pa ON ep.CdProcedimento = Pa.CdProcedimento
	INNER JOIN tbagendacons ac ON sc.CdSolCons=ac.CdSolCons
  LEFT JOIN tbagenda_fornecedor af on af.cdagenda_fornecedor = ac.cdagenda_fornecedor
	LEFT JOIN tbcredprofissionallocalatend pe on (pe.CdCredProfLocal = ac.cdEndereco OR af.cdendc = pe.CdCredProfLocal)					 
	LEFT JOIN tbfornecedor f ON ac.CdForn=f.CdForn
	LEFT JOIN tbprofissional prof ON ac.cdprof = prof.cdprof
	LEFT JOIN tbusuario u ON ac.CdUsuario = u.CdUsuario
  LEFT JOIN tbsldmovimentacao mov on mov.cdsolcons = sc.CdSolCons
	LEFT JOIN tbsldgensaldo saldo on saldo.cdsaldo = mov.cdsaldo
	WHERE sc.Status='1' AND ac.Status='1'
	AND sc.CdSolCons = '$id' ");

$l = mysqli_fetch_array($sql);

if (preg_match("/\(?\d{2}\)?\s?\d{5}\-?\d{4}/", $l['Celular'])) {
	$cel =  (string)$l['Celular'];
	$cel = substr_replace($cel, '(', 0, 0);
	$cel = substr_replace($cel, ')', 3, 0);
	//echo $cel;
	preg_match('/^\((\d{2})\)/', $cel, $DDD);
	//print_r($DDD[1]);
	$codigosDDD = array(
		11, 12, 13, 14, 15, 16, 17, 18, 19,
		21, 22, 24, 27, 28, 31, 32, 33, 34,
		35, 37, 38, 41, 42, 43, 44, 45, 46,
		47, 48, 49, 51, 53, 54, 55, 61, 62,
		64, 63, 65, 66, 67, 68, 69, 71, 73,
		74, 75, 77, 79, 81, 82, 83, 84, 85,
		86, 87, 88, 89, 91, 92, 93, 94, 95,
		96, 97, 98, 99
	);

	$valddd = 0;
	$i = 0;

	foreach ($codigosDDD as $valida_ddd => $valor) {

		if ($codigosDDD[$i] == $DDD[1]) {
			$valddd = 1;
			//print_r($l[Celular]." :".$codigosDDD[$i]." - ".$DDD[1].";");
		}
		$i++;
	}
	if ($valddd == 1) {
		//enviar_sms($l['Celular'], $l['NmPaciente'], $l['DtAgCons'], $l['HoraAgCons'], $l['NmEspecProc'], $l['NmReduzido'], $l['NmProcedimento']);
	}
}



//$sql_preparo = mysqli_query($db,"SELECT	tbespecproc.nmpreparo,	tbespecproc.CdEspecProc FROM tbespecproc WHERE tbespecproc.CdEspecProc = '$l[CdEspecProc]' ");
$sql_preparo = "SELECT tbfornespec.CdForn,tbfornespec.CdEspec,tbfornespec.preparo,tbfornespec.nmpreparo, tbfornespec.termo FROM tbfornespec WHERE tbfornespec.CdEspec = $l[CdEspecProc] AND tbfornespec.CdForn = $l[CdForn] ";

/*$sql_preparo = "SELECT
				tbfornespec.termo,
				tbfornespec.cdpreparo,
				tbpreparo.preparo AS nmpreparo
				FROM
				tbfornespec
				INNER JOIN tbpreparo ON tbfornespec.cdpreparo = tbpreparo.cdpreparo
				WHERE tbfornespec.CdForn = $l[CdForn] AND tbfornespec.CdEspec = $l[CdEspecProc]
				LIMIT 1 
				";*/
$sql_preparo = mysqli_query($db, $sql_preparo);
$lpreparo = mysqli_fetch_array($sql_preparo);

#Atualiza o Campo impresso para 'S'
if ($_SESSION['CdTpUsuario'] == 3)
	mysqli_query($db, "UPDATE tbsolcons SET tbsolcons.impresso = 'S' WHERE tbsolcons.CdSolCons = $id") or die(mysqli_error($db));

$codsol = explode("-", $l['DtAgCons']);
$ano = substr($codsol[0], -2);
if ($_SESSION['CdTpUsuario'] == 3)
	valida_autentificacao($l['CdSolCons'], $_SESSION['CdUsuario']);
$codigo_autentificacao = $codsol[1] . $l['CdEspecProc'] . '.' . $ano . $l['CdPaciente'] . $l['CdPref'] . $l['CdForn'] . '-' . $codsol[2] . '.' . $l['CdSolCons'];
//fazemos a inclusï¿½o do arquivo com a classe FPDF

$dadoscis = dados_consorcio('CIS');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8"/>
<title>Iconsorcio</title>
<link rel="shortcut icon" href="guia_pac/_favicon/favicon.ico" />
<link rel="stylesheet" type="text/css" href="guia_pac/_css/estilo.css"/>
<link rel="stylesheet" type="text/css" href="guia_pac/_css/paper.css"/>
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://kit.fontawesome.com/cc13449442.js" crossorigin="anonymous"></script>

	
</head>
<style> 
@media print{
    .imprimir{ 
       display: none; 
       }
       .A4{
          padding:0;
          margin:0;
          width: 100%;
			    border: none;
			    box-shadow: none;
			    margin-top: 0px;
			    min-height: none;
			    margin-left: 0px;
			    margin-bottom: 0px;
			    top: 0;
			    left: 0;
			   	bottom: 0;
          background-color: white;
	      }
}
.barcode {
    font-family: 'Libre Barcode 39';font-size: 50px;
}
</style>
<body class="A4">
<i style = "float: right; position:relative; top:20px; left:-50px" class='fa fa-print imprimir fa-2x' title="Imprimir" onClick="window.print();"></i>

<section class="sheet padding-10mm">

  <div class="header">
  <img src="guia_pac/_imagens/logo.jpg" width="100px" height="100px">
    <h1 style="font-size: 18px;"><?php echo $dadoscis['Titulo'].'-'.$dadoscis['Nome'];?></h1>
    <h2><?php echo $dadoscis['Cidade'] . '/' . $dadoscis['Estado'];  ?> </h2>
    <!-- <h2><?php echo 'Tel.:' . $dadoscis['Telefone'] .' CNPJ: '. $dadoscis['CNPJ'];?></h2> -->
  </div>

  <table class="table">
    <thead>
      <tr>
        <th colspan="6">Guia de Autorização</th>
      </tr>
    </thead>
    <tbody>
      <!-- <tr>
        <td><b>Código do Procedimento</b></td>
        <td><?php echo $l['CdSolCons'] ?></td>
        <td><b>Protocolo</b></td>
        <td colspan="3"><?php echo $l['protocolopac']?></td>
      </tr> -->
      <tr>
        <td><b>Município de Procedência</b></td>
        <td colspan="5"> <?php echo $l['NmCidade'] ?></td>
      </tr>
      <tr>
        <td><b>Paciente</b></td>
        <td><?php echo S::create($l['NmPaciente'])->titleize(["de", "da", "do"])  ?></td>
        <td><b>CIH</b></td>
        <td colspan="3"><?php echo $l['CdPaciente'] ?> </td>
      </tr>
	  <tr>
        <td><b>Data de Nascimento</b></td>
        <td colspan="5"><?php echo FormataDataBR($l['DtNasc']); ?></td>
      </tr>
	  <tr>
        <td><b>Cartão SUS</b></td>
        <td colspan="5"><?php echo $l['csus'] ?></td>
      </tr>
	  <tr>
        <td><b>Mãe</b></td>
        <td colspan="5"><?php echo $l['NmMae'] ?></td>
      </tr>
      <?php 
      
$sql_pac = mysqli_query($db, "SELECT tbpaciente.CdPaciente, tbpaciente.NmPaciente, tbpaciente.Telefone, tbpaciente.Celular,
tbpaciente.Logradouro,tbpaciente.Numero,
tbbairro.NmBairro, tbprefeitura.NmCidade, tbestado.NmEstado, tbestado.UF
FROM tbpaciente, tbbairro, tbprefeitura, tbestado
WHERE tbpaciente.CdBairro = tbbairro.CdBairro
AND tbbairro.CdPref = tbprefeitura.CdPref
AND tbprefeitura.CdEstado = tbestado.CdEstado
AND tbpaciente.CdPaciente = $l[CdPaciente]") or die(mysqli_error($db));
$ss = mysqli_fetch_array($sql_pac);
      
      ?>
	  <tr>
        <td><b>Telefone</b></td>
        <td><?php if ($ss['Telefone'] != '')
        	echo $tel = $ss['Telefone'];
        else echo $tel = '  -  ';?></td>
        
        <td><b>Celular</b></td>
        <td colspan="3">
    <?php    if ($ss['Celular'] != '')
	        echo $cel = $ss['Celular'];
            else echo $cel = '  -  '; ?>
        </td>
      </tr>
	  <tr>
   
        <td><b>Endereço</b></td>
        <td><?php echo  S::create($ss['Logradouro'])->titleize(["de", "da", "do"]) . ', Nº: ' . $ss['Numero']; ?></td>
        <td><b>Bairro</b></td>
        <td> <?php echo  S::create($ss['NmBairro'])->titleize(["de", "da", "do"]); ?></td>
		<td><b>Cidade</b></td>
        <td><?php echo S::create($ss['NmCidade'])->titleize(["de", "da", "do"]) . ' - ' . $ss['UF']; ?></td>
      </tr>
	<thead>
      <tr>
        <th colspan="6">Local do Atendimento</th>
      </tr>
    </thead>
	  <tr>
        <td><b>Fornecedor</b></td>
        <td colspan="4"><?php echo  S::create($l['nmred'])->titleize(["de", "da", "do"]);?></td>
        <td> <?php echo "Contato: ".$l['TelProf'] ?></td>
      </tr>
      <tr>
        <td><b>Profissional</b></td>
        <td colspan="5"><?php echo  S::create($l['nmprof'])->titleize(["de", "da", "do"]);?></td>
      </tr>
      <?php 
      

$sql2 = mysqli_query($db, "SELECT tbprefeitura.NmCidade, tbestado.UF
from tbprefeitura, tbfornecedor, tbestado 
where tbprefeitura.CdPref = tbfornecedor.CdCidade
AND tbprefeitura.CdEstado = tbestado.CdEstado
AND tbfornecedor.CdCidade = $l[CdCidade]
AND tbfornecedor.CdForn = $l[CdForn]");

$l2 = mysqli_fetch_array($sql2);      
      
      ?>
        <?php if($l['cdendc'] > 0 || $l['cdEndereco'] > 0) { ?>
	  <tr>
        <td><b>Endereço</b></td>
        <td><?php echo S::create($l['Logradouro'])->titleize(["de", "da", "do"]) . ', Nº: ' . $l['crednumero'];?></td>
        <td><b>Bairro</b></td>
        <td><?php echo  S::create($l['bairrocred'])->titleize(["de", "da", "do"]);?></td>
		<td><b>Cidade</b></td>
        <td><?php echo S::create($l['Cidade'])->titleize(["de", "da", "do"]) . ' - ' . $l['UF'];?></td>
      </tr>
      <?php } else { ?> 
        <tr>
        <td><b>Endereço</b></td>
        <td><?php echo S::create($l['logradouro'])->titleize(["de", "da", "do"]) . ', Nº: ' . $l['Numero'];?></td>
        <td><b>Bairro</b></td>
        <td><?php echo  S::create($l['Bairro'])->titleize(["de", "da", "do"]);?></td>
		<td><b>Cidade</b></td>
        <td><?php echo S::create($l2['NmCidade'])->titleize(["de", "da", "do"]) . ' - ' . $l2['UF'];?></td>
      </tr>
    <?php  } ?>
	  <tr>
          <?php $DtAgCons = $l['DtAgCons']; 
                $DtAgCons = explode('-', $DtAgCons);
                $DtAgCons  = $DtAgCons[2] . '/' . $DtAgCons[1] . '/' . $DtAgCons[0];
                $HoraAgCons = date('H:i', strtotime($l['HoraAgCons']));
          ?>
        <td><b>Data de Consulta/Exame</b></td>
        <td><?php echo  $DtAgCons; ?></td>
        <td><b>Horário</b></td>
        <td><?php echo  $HoraAgCons;?></td>
        <td><b>CBO</b></td>
        <td ><?php echo S::create($l['cbo'])->titleize(["de", "da", "do"])  ?></td>
      </tr>
	<thead>
      <tr>
        <th colspan="6">Procedimentos</th>
      </tr>
    </thead>
	  <tr>
    <?php $query = mysqli_query($db, "SELECT tipo FROM tbacrescimo as c WHERE c.CdSolCons = $id");
      if(mysqli_num_rows($query)){
      $tp = mysqli_fetch_array($query);
      if($tp['tipo'] == 'C'){
        $nome = "Contraste";
      }else if($tp['tipo'] == 'CS'){
        $nome  = "Contraste e Sedação";
      }else if($tp['tipo'] == "S"){
        $nome = "Sedação";
      }
       }else {
       $nome = "";
       }?>
        <td colspan="2"><?php echo  S::create($l['NmEspecProc'])->titleize(["de", "da", "do"]); echo " - "; ?><b><?= $nome ?></b></td>
        <td><b>Código Guia</b></td>
        <td colspan="1"> <?php echo $l['CdSolCons']; ?></td>
        <td colspan="2"><b>Protocolo: </b>
         <?php echo $l['protocolopac']; ?></td>
      </tr>
	   <!-- <tr>
        <td><b>Preparo</b></td>
        <td colspan="5"> <?php echo $lpreparo['preparo']; ?></td>
      </tr> -->
      

        <?php  
        if($l['CdProcedimento'] == 18 || $l['CdProcedimento'] == 24){
          echo '<td class ="barcode" colspan="6" height="50px">
                <span class ="signature-fisio">Assinatura do Profissional</span>    
                <span class ="signature-fisio">Assinatura do Paciente</span>
                </td>';
        }else{
          $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
          echo '<td class ="barcode" colspan="6" height="60px">';
          echo $generator->getBarcode($l['CdSolCons'], $generator::TYPE_CODE_128,2.5,60);
          echo '</td>';
        }
        ?>
      
      </tr>
      <tr>
      <td><b>Valor</b></td>
      <td colspan="5"><?php echo 'R$ '. $l['valormed']; ?></td>
      </tr>
      <tr>
      <td colspan="6"><b>Observação:</b> <?php echo $l['obsAgenda']; ?> </td>
      </tr>	
  
      <tr>
        <td colspan="6"><b>Declaro que me foram apresentadas as opções de locais para a realização da consulta/ procedimento/ exame dentro dos prestadores credenciados junto ao <?php echo mb_strtoupper(constant("CONNECTION_NAME"));?>, respeitando a logística do município.<b></td>
      </tr> 	
    </tbody>
  </table>
  <div class="assinatura ">  
    <span class ="signature">Autorização do Município</span>
    <span class ="signature">Assinatura do Profissional</span>    
    <span class ="signature">Assinatura do Paciente</span>
  </div>
  <div class="atencao"> 
  <p class="warning">Atenção: uso obrigatório de máscara.</p>
  <p class="warning">Obrigatória a apresentação do cartão SUS, pedido do exame e/ou encaminhamento guia de autorização para realizar o atendimento.</p>
  </div>
  <table class="rodape">
    <thead>
      <tr>
        <th colspan="6"> <?php echo $dadoscis['Titulo'].'-'.$dadoscis['Nome'] ?> </br> <?php 
        echo ' CNPJ: '. $dadoscis['CNPJ']. ' Tel.:' . $dadoscis['Telefone'] ?></th>
      </tr>
    </thead>
  </table>
  <div class="espec"> 
  <!-- <img src="guia_pac/_imagens/qr.png"> -->
  <img src="https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=http%3A%2F%2Fcismetro.sitcon.com.br/cismetro/guia_pac2.php?id=<?=$l['CdSolCons']?>&usr=<?=$_SESSION['CdUsuario']?>%2F&choe=UTF-8" title="Link to Google.com" />
  <p class="warning">Assinado eletronicamente por: <?php echo $_SESSION["NmUsuario"];
  $data = date('d/m/Y H:i:s');
  echo '  '.$data;
  ?></p>
  <p class="warning">Número do documento: 121442.20234882101-22.840568</p>
  <p class="warning">Agendado por: <?=$l['NmUsuario']?></p>
  </div>
</section>
</body>
</html>