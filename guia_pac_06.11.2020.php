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
		$result = mysqli_query($GLOBALS['db'],$query);
		$dado = mysqli_fetch_array($result, MYSQLI_ASSOC);
		return $dado['qtd'];
	}

	$id = $_GET['id'];
	
	// Atualiza status guia
	//$sql_att_guia = mysqli_query($db,"UPDATE `tbagendacons` SET `impressaoguia`='S' WHERE (`CdSolCons`='$id')") or die (mysqli_error());
	
	
	$sql = mysqli_query($db,"SELECT af.cdendc,pe.Logradouro,pe.Numero as crednumero,pe.Bairro as bairrocred,pe.Cidade,pe.Estado,sc.CdSolCons,DtAgCons,HoraAgCons, p.CdPaciente,p.RG, p.NmMae, f.CdForn,f.Bairro,f.logradouro, f.Numero, f.CdCidade, ep.nmpreparo, p.NmPaciente, ac.Valor, p.DtNasc, ac.CdUsuario, u.Login, ac.CdForn, pr.NmCidade,sc.Protocolo, sc.DtInc,pr.CdPref, ep.CdEspecProc, ac.qts, ac.protocolopac, NmEspecProc, f.NmForn,sc.Status, ac.Status as StatusAg,Urgente, f.NmReduzido, f.cns,sc.Obs, ac.obs,ep.cdsus, Pa.NmProcedimento, sc.Obs1, p.DtNasc, p.csus, f.Compl, p.Celular, prof.cdprof, prof.nmprof, und.NmForn as NmUnidade, und.NmReduzido as NmRedUnidade, und.CdForn as CdUnidade, f.Telefone
							 FROM tbsolcons sc INNER JOIN tbpaciente p ON sc.CdPaciente=p.CdPaciente 
							 INNER JOIN tbbairro b ON b.CdBairro=p.CdBairro
							 INNER JOIN tbprefeitura pr ON b.CdPref=pr.CdPref
							 INNER JOIN tbespecproc ep ON sc.CdEspecProc=ep.CdEspecProc 
							 INNER JOIN tbprocedimento Pa ON ep.CdProcedimento = Pa.CdProcedimento
							 LEFT JOIN tbagendacons ac ON sc.CdSolCons=ac.CdSolCons
							 LEFT JOIN tbagenda_fornecedor af on af.cdagenda_fornecedor = ac.cdagenda_fornecedor
							 LEFT JOIN tbcredprofissionallocalatend pe on pe.CdCredProfLocal = af.cdendc
							 LEFT JOIN tbfornecedor f ON ac.CdForn=f.CdForn
							 LEFT JOIN tbprofissional prof ON ac.cdprof = prof.cdprof
							 LEFT JOIN tbusuario u ON ac.CdUsuario = u.CdUsuario
							 LEFT JOIN tbfornecedor_mun und ON sc.CdUnid = und.CdForn
							 WHERE sc.Status='1' AND ac.Status='1'
							 AND sc.CdSolCons = '$id' ");

	$l = mysqli_fetch_array($sql);

	if(preg_match("/\(?\d{2}\)?\s?\d{5}\-?\d{4}/", $l['Celular']))
	{
			$cel =  (string)$l['Celular'];
			$cel = substr_replace($cel, '(', 0, 0);
			$cel = substr_replace($cel, ')', 3, 0);
			//echo $cel;
			preg_match('/^\((\d{2})\)/', $cel, $DDD);
			//print_r($DDD[1]);
			$codigosDDD = array(11, 12, 13, 14, 15, 16, 17, 18, 19,
		    21, 22, 24, 27, 28, 31, 32, 33, 34,
		    35, 37, 38, 41, 42, 43, 44, 45, 46,
		    47, 48, 49, 51, 53, 54, 55, 61, 62,
		    64, 63, 65, 66, 67, 68, 69, 71, 73,
		    74, 75, 77, 79, 81, 82, 83, 84, 85,
		    86, 87, 88, 89, 91, 92, 93, 94, 95,
		    96, 97, 98, 99);

			$valddd = 0;
			$i = 0;
			
		    foreach ($codigosDDD as $valida_ddd => $valor) {
				
		    	if ($codigosDDD[$i] == $DDD[1]){
		    		$valddd = 1;
		    		//print_r($l[Celular]." :".$codigosDDD[$i]." - ".$DDD[1].";");
		    	}
		    $i++;
		    }
		    if ($valddd == 1) {
		    	enviar_sms($l['Celular'],$l['NmPaciente'],$l['DtAgCons'],$l['HoraAgCons'],$l['NmEspecProc'],$l['NmReduzido'],$l['NmProcedimento']);
		    }
	}
	
	//$sql_preparo = mysqli_query($db,"SELECT	tbespecproc.nmpreparo,	tbespecproc.CdEspecProc FROM tbespecproc WHERE tbespecproc.CdEspecProc = '$l[CdEspecProc]' ");
	$sql_preparo = "SELECT tbfornespec.CdForn,tbfornespec.CdEspec,tbfornespec.preparo, tbfornespec.termo FROM tbfornespec WHERE tbfornespec.CdEspec = $l[CdEspecProc] AND tbfornespec.CdForn = $l[CdForn] ";
	
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
	$sql_preparo = mysqli_query($db,$sql_preparo) or die("Erro ao consultar preparo");
	$lpreparo = mysqli_fetch_array($sql_preparo);
	
		#Atualiza o Campo impresso para 'S'
		if($_SESSION['cdgrusuario']==3)
			mysqli_query($db,"UPDATE tbsolcons SET tbsolcons.impresso = 'S' WHERE tbsolcons.CdSolCons = $id") or die(mysqli_error());
		
		$codsol = explode("-", $l['DtAgCons']);
		$ano = substr($codsol[0], -2);
		if($_SESSION['CdTpUsuario'] == 3)
			valida_autentificacao($l['CdSolCons'],$_SESSION['CdUsuario']);
		$codigo_autentificacao = $codsol[1].$l['CdEspecProc'].'.'.$ano.$l['CdPaciente'].$l['CdPref'].$l['CdForn'].'-'.$codsol[2].'.'.$l['CdSolCons'];
//fazemos a inclusï¿½o do arquivo com a classe FPDF
require('relatorios/fpdf/fpdf.php');




//criamos uma nova classe, que serï¿½ uma extensï¿½o da classe FPDF
//para que possamos sobrescrever o mï¿½todo Header()
//com a formataï¿½ï¿½o desejada

class PDF extends FPDF

{

	###### CÃ“DIGO DE BARRAS ######
function Code39($x, $y, $code, $ext = true, $cks = false, $w = 0.4, $h = 20, $wide = false) {

	//Display code
	$this->SetFont('Arial', '', 10);
	$this->Text($x, $y+$h+4, $code);

	if($ext) {
		//Extended encoding
		$code = $this->encode_code39_ext($code);
	}
	else {
		//Convert to upper case
		$code = strtoupper($code);
		//Check validity
		if(!preg_match('|^[0-9A-Z. $/+%-]*$|', $code))
			$this->Error('Invalid barcode value: '.$code);
	}

	//Compute checksum
	if ($cks)
		$code .= $this->checksum_code39($code);

	//Add start and stop characters
	$code = '*'.$code.'*';

	//Conversion tables
	$narrow_encoding = array (
		'0' => '101001101101', '1' => '110100101011', '2' => '101100101011',
		'3' => '110110010101', '4' => '101001101011', '5' => '110100110101',
		'6' => '101100110101', '7' => '101001011011', '8' => '110100101101',
		'9' => '101100101101', 'A' => '110101001011', 'B' => '101101001011',
		'C' => '110110100101', 'D' => '101011001011', 'E' => '110101100101',
		'F' => '101101100101', 'G' => '101010011011', 'H' => '110101001101',
		'I' => '101101001101', 'J' => '101011001101', 'K' => '110101010011',
		'L' => '101101010011', 'M' => '110110101001', 'N' => '101011010011',
		'O' => '110101101001', 'P' => '101101101001', 'Q' => '101010110011',
		'R' => '110101011001', 'S' => '101101011001', 'T' => '101011011001',
		'U' => '110010101011', 'V' => '100110101011', 'W' => '110011010101',
		'X' => '100101101011', 'Y' => '110010110101', 'Z' => '100110110101',
		'-' => '100101011011', '.' => '110010101101', ' ' => '100110101101',
		'*' => '100101101101', '$' => '100100100101', '/' => '100100101001',
		'+' => '100101001001', '%' => '101001001001' );

	$wide_encoding = array (
		'0' => '101000111011101', '1' => '111010001010111', '2' => '101110001010111',
		'3' => '111011100010101', '4' => '101000111010111', '5' => '111010001110101',
		'6' => '101110001110101', '7' => '101000101110111', '8' => '111010001011101',
		'9' => '101110001011101', 'A' => '111010100010111', 'B' => '101110100010111',
		'C' => '111011101000101', 'D' => '101011100010111', 'E' => '111010111000101',
		'F' => '101110111000101', 'G' => '101010001110111', 'H' => '111010100011101',
		'I' => '101110100011101', 'J' => '101011100011101', 'K' => '111010101000111',
		'L' => '101110101000111', 'M' => '111011101010001', 'N' => '101011101000111',
		'O' => '111010111010001', 'P' => '101110111010001', 'Q' => '101010111000111',
		'R' => '111010101110001', 'S' => '101110101110001', 'T' => '101011101110001',
		'U' => '111000101010111', 'V' => '100011101010111', 'W' => '111000111010101',
		'X' => '100010111010111', 'Y' => '111000101110101', 'Z' => '100011101110101',
		'-' => '100010101110111', '.' => '111000101011101', ' ' => '100011101011101',
		'*' => '100010111011101', '$' => '100010001000101', '/' => '100010001010001',
		'+' => '100010100010001', '%' => '101000100010001');

	$encoding = $wide ? $wide_encoding : $narrow_encoding;

	//Inter-character spacing
	$gap = ($w > 0.29) ? '00' : '0';

	//Convert to bars
	$encode = '';
	for ($i = 0; $i< strlen($code); $i++)
		$encode .= $encoding[$code[$i]].$gap;

	//Draw bars
	$this->draw_code39($encode, $x, $y, $w, $h);
}

function checksum_code39($code) {

	//Compute the modulo 43 checksum

	$chars = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
							'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K',
							'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V',
							'W', 'X', 'Y', 'Z', '-', '.', ' ', '$', '/', '+', '%');
	$sum = 0;
	for ($i=0 ; $i<strlen($code); $i++) {
		$a = array_keys($chars, $code[$i]);
		$sum += $a[0];
	}
	$r = $sum % 43;
	return $chars[$r];
}

function encode_code39_ext($code) {

	//Encode characters in extended mode

	$encode = array(
		chr(0) => '%U', chr(1) => '$A', chr(2) => '$B', chr(3) => '$C',
		chr(4) => '$D', chr(5) => '$E', chr(6) => '$F', chr(7) => '$G',
		chr(8) => '$H', chr(9) => '$I', chr(10) => '$J', chr(11) => 'ï¿½K',
		chr(12) => '$L', chr(13) => '$M', chr(14) => '$N', chr(15) => '$O',
		chr(16) => '$P', chr(17) => '$Q', chr(18) => '$R', chr(19) => '$S',
		chr(20) => '$T', chr(21) => '$U', chr(22) => '$V', chr(23) => '$W',
		chr(24) => '$X', chr(25) => '$Y', chr(26) => '$Z', chr(27) => '%A',
		chr(28) => '%B', chr(29) => '%C', chr(30) => '%D', chr(31) => '%E',
		chr(32) => ' ', chr(33) => '/A', chr(34) => '/B', chr(35) => '/C',
		chr(36) => '/D', chr(37) => '/E', chr(38) => '/F', chr(39) => '/G',
		chr(40) => '/H', chr(41) => '/I', chr(42) => '/J', chr(43) => '/K',
		chr(44) => '/L', chr(45) => '-', chr(46) => '.', chr(47) => '/O',
		chr(48) => '0', chr(49) => '1', chr(50) => '2', chr(51) => '3',
		chr(52) => '4', chr(53) => '5', chr(54) => '6', chr(55) => '7',
		chr(56) => '8', chr(57) => '9', chr(58) => '/Z', chr(59) => '%F',
		chr(60) => '%G', chr(61) => '%H', chr(62) => '%I', chr(63) => '%J',
		chr(64) => '%V', chr(65) => 'A', chr(66) => 'B', chr(67) => 'C',
		chr(68) => 'D', chr(69) => 'E', chr(70) => 'F', chr(71) => 'G',
		chr(72) => 'H', chr(73) => 'I', chr(74) => 'J', chr(75) => 'K',
		chr(76) => 'L', chr(77) => 'M', chr(78) => 'N', chr(79) => 'O',
		chr(80) => 'P', chr(81) => 'Q', chr(82) => 'R', chr(83) => 'S',
		chr(84) => 'T', chr(85) => 'U', chr(86) => 'V', chr(87) => 'W',
		chr(88) => 'X', chr(89) => 'Y', chr(90) => 'Z', chr(91) => '%K',
		chr(92) => '%L', chr(93) => '%M', chr(94) => '%N', chr(95) => '%O',
		chr(96) => '%W', chr(97) => '+A', chr(98) => '+B', chr(99) => '+C',
		chr(100) => '+D', chr(101) => '+E', chr(102) => '+F', chr(103) => '+G',
		chr(104) => '+H', chr(105) => '+I', chr(106) => '+J', chr(107) => '+K',
		chr(108) => '+L', chr(109) => '+M', chr(110) => '+N', chr(111) => '+O',
		chr(112) => '+P', chr(113) => '+Q', chr(114) => '+R', chr(115) => '+S',
		chr(116) => '+T', chr(117) => '+U', chr(118) => '+V', chr(119) => '+W',
		chr(120) => '+X', chr(121) => '+Y', chr(122) => '+Z', chr(123) => '%P',
		chr(124) => '%Q', chr(125) => '%R', chr(126) => '%S', chr(127) => '%T');

	$code_ext = '';
	for ($i = 0 ; $i<strlen($code); $i++) {
		if (ord($code[$i]) > 127)
			$this->Error('Invalid character: '.$code[$i]);
		$code_ext .= $encode[$code[$i]];
	}
	return $code_ext;
}

function draw_code39($code, $x, $y, $w, $h) {

	//Draw bars

	for($i=0; $i<strlen($code); $i++) {
		if($code[$i] == '1')
			$this->Rect($x+$i*$w, $y, $w, $h, 'F');
	}
}	
	###### FIM DO CÃ“DIGO DE BARRAS ######

   //Mï¿½todo Header que estiliza o cabeï¿½alho da pï¿½gina
   function Header() {
	   $this->Sety(5);
	   if ($this->header1 == 1)
	   	$this->Image('img/logo.jpg',60,5,90,35,'jpg');   
   }
   /*
   //Mï¿½todo Header que estiliza o cabeï¿½alho da pï¿½gina
   function Header() {

      //posicionamos o rodapï¿½ a 1cm do fim da pï¿½gina
    //$this->SetY(-10);
     $this-> SetX(2);
    $this->Image('img/logo.jpg',1,5,207,40,jpg);
	
	
	
		
   } */
   
var  $i=0;

   //Mï¿½todo Footer que estiliza o rodapï¿½ da pï¿½gina
   function Footer() {

	global  $codigo_autentificacao;
    global  $nomeUsuer;
    global  $data_agora;
      //posicionamos o rodapï¿½ a 1cm do fim da pï¿½gina
    //$this->SetY(-10);
     $this-> SetX(2);
	 if ($this->footer == 1)
	$this->Image('img/logo.jpg',1,5,208,40,jpg); 
	
	$dadoscis = dados_consorcio('CIS');
		  
	$this-> SetX(2);
	$this->SetFont('Arial','',8);
	$this ->MultiCell(207, 6, $dadoscis['Titulo'].' - '.$dadoscis['Nome'].' - '.$dadoscis['Cidade'].'/'.$dadoscis['Estado'].'
	          Tel.:'.$dadoscis['Telefone'].'  CNPJ:19.947.645/0001-64'.$dadoscis['CNPJ'], 1, 'C');


	//$this->Sety(-14); 
	//$this-> SetX(156);
	/* $this ->MultiCell(135, 5, ' Sitcon Tecnologia da Informaï¿½ï¿½o  Tel.(31)3822-4656  sistemas@sitcon.com.br ', 0, 'C');
	$this->Sety(-10); 
	$this-> SetX(156);

	$this ->MultiCell(135, 5, ' Sistemas de GestÃ£o em SaÃºde', 0, 'C');*/
	$this->Ln();
	$this-> SetX(10);
	$this->Image('frame.jpg',3,273,20,20,'jpg');
	$this-> SetY(270);
	$this->MultiCell(207,13,'                    Assinado eletronicamente por: '.mb_strtoupper($nomeUsuer).' - '.$data_agora, 0, 'L');
	$this-> SetX(10);
	$this->MultiCell(207, 2,'                    Número do documento: '.$codigo_autentificacao, 0, 'L');
   }

var $widths;
var $aligns;

	function SetWidths($w)
{
    //Set the array of column widths
    $this->widths=$w;
}

	function SetAligns($a)
{
    //Set the array of column alignments
    $this->aligns=$a;
}

function Row($data,$borda)
{
	
	$borda = 0;
	
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    $h=5*$nb;
    //Issue a page break first if needed
    $this->CheckPageBreak($h);
    //Draw the cells of the row
    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border
		
		
		
		
       // $this->Rect($x,$y,$w,$h);
        //Print the text
        $this->MultiCell($w,5,$data[$i],$borda,$a);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
}

function Row2($data,$borda)
{
	
	$borda = 0;
	
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    $h=5*$nb;
    //Issue a page break first if needed
    $this->CheckPageBreak($h);
    //Draw the cells of the row
    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border
		
		
		
		
       // $this->Rect($x,$y,$w,$h);
        //Print the text
        $this->MultiCell($w,1,$data[$i],$borda,$a);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
}

	function CheckPageBreak($h)
{
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage($this->CurOrientation);
}

function NbLines($w,$txt)
{
    //Computes the number of lines a MultiCell of width w will take
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r",'',$txt);
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
        $nb--;
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb)
    {
        $c=$s[$i];
        if($c=="\n")
        {
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
            continue;
        }
        if($c==' ')
            $sep=$i;
        $l+=$cw[$c];
        if($l>$wmax)
        {
            if($sep==-1)
            {

                if($i==$j)
                    $i++;
            }
            else
                $i=$sep+1;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
        }
        else
            $i++;
    }
    return $nl;
}

}
//require('ean13.php');
//Criamos o objeto da classe PDF
$pdf= new PDF();

$pdf->header1 = 1;
//Inserimos a pï¿½gina
$pdf->AddPage('P');
$pdf->header1 = 0;

$pdf->SetLeftMargin('10');


//apontamos a fonte que serï¿½ utilizada no texto
	$pdf->SetFont('Arial','B',15);
	require "conecta.php";
	$pdf-> SetY(47);
	$pdf-> SetX(2);
	$pdf->SetFont('Arial','B',10);
	$pdf->MultiCell(207, 8, 'GUIA DE ENCAMINHAMENTO PARA CONSULTA / EXAME ESPECIALIZADO', 1, 'C');
	if (possuiAlteracao($l['CdSolCons'])) {
		$pdf-> SetX(2);
		$pdf->SetFillColor(244,67,54);
		$pdf->MultiCell(207, 8, 'Atenção! Confira os dados do paciente no sistema antes de iniciar o atendimento, agendamento alterado.', 1, 'C',true);
	}
	$pdf->SetFillColor(0,0,0);
	$pdf->SetX(2);
	$pdf->MultiCell(207, 2, '', 0, 'C');
	$pdf->SetFont('Arial','',10);
	$pdf->Code39(165,70,"$l[CdSolCons]"); ##IMPRIME O CÃ“DIGO DE BARRAS
	$pdf->SetWidths(array(207));
	srand(microtime()*1000000);
	$pdf->ln();
	$pdf->SetX(2);
	$pdf->Row2(array('Código: '.$l['CdSolCons'].'  - Protocolo: '.$l['protocolopac']),1);
	$pdf->ln();
	$pdf-> SetX(2);
	$pdf->Row2(array('Município de Procedência: '.S::create($l['NmCidade'])->titleize(["de", "da", "do"]).' - SP'),1);
	$pdf->ln();
	$pdf->SetX(2);
	
	$data = explode('-',$l['DtNasc']);
	$DtAgCons  = $data[2].'/'.$data[1].'/'.$data[0];
	
	$pdf->Row2(array('Paciente: '.S::create($l['NmPaciente'])->titleize(["de", "da", "do"]).' - CIH: '.$l['CdPaciente']),1);
	$pdf->ln();
	$pdf->SetX(2);
	$pdf->Row2(array('Data de Nascimento: '.$DtAgCons),1);
	$pdf->ln();
	$pdf->SetX(2);	
	$pdf->Row2(array('Cartão SUS: '. $l['csus']),1);
	$pdf->ln();
	$pdf->SetX(2);
	$pdf->Row2(array('Mãe: '.S::create($l['NmMae'])->titleize(["de", "da", "do"])),1);
	$pdf->ln();
	$pdf->SetX(2);

	$pdf->SetX(2);
		
	$sql_pac = mysqli_query($db,"SELECT tbpaciente.CdPaciente, tbpaciente.NmPaciente, tbpaciente.Telefone, tbpaciente.Celular,
	tbpaciente.Logradouro,tbpaciente.Numero,
	tbbairro.NmBairro, tbprefeitura.NmCidade, tbestado.NmEstado
	FROM tbpaciente, tbbairro, tbprefeitura, tbestado
	WHERE tbpaciente.CdBairro = tbbairro.CdBairro
	AND tbbairro.CdPref = tbprefeitura.CdPref
	AND tbprefeitura.CdEstado = tbestado.CdEstado
	AND tbpaciente.CdPaciente = $l[CdPaciente]") or die (mysqli_error());
	$ss = mysqli_fetch_array($sql_pac);
	
	if($ss['Telefone'] != '')
		$tel = $ss['Telefone'];
	else $tel = '  -  ';

	if($ss['Celular'] != '')
		$cel = $ss['Celular'];
	else $cel = '  -  ';

	$pdf->Row2(array('Telefone: '.$tel.'   Celular: '.$cel),1);
	$pdf->ln();
	$pdf-> SetX(2);
	$pdf->Row2(array('Endereço: '.S::create($ss['Logradouro'])->titleize(["de", "da", "do"]).', Nº: '.$ss['Numero'].', Bairro: '.S::create($ss['NmBairro'])->titleize(["de", "da", "do"]).', Cidade: '.S::create($ss['NmCidade'])->titleize(["de", "da", "do"]).'-SP'),1);

	if($l['CdUnidade'] > 0)
		$pdf->Row2(array('Unidade de Saúde Referência: '.S::create($l['NmUnidade'])->titleize(["de", "da", "do"])),1); 

	$pdf->SetFont('Arial','B',15);
	$pdf ->MultiCell(207, 2, '', 0, 'C');
	$pdf->ln();
	$pdf-> SetX(2);
	$pdf ->MultiCell(207, 8, 'LOCAL DO ATENDIMENTO', 1, 'C');
	$pdf-> SetX(2);

	$pdf ->MultiCell(207, 2, '', 0, 'C');
	$pdf->SetFont('Arial','',10);
	$pdf->SetWidths(array(207));
	srand(microtime()*1000000);
	$pdf-> SetX(2);
	$pdf->Row(array('Fornecedor: '.S::create($l['NmForn'])->titleize(["de", "da", "do"])),1);
	$pdf->ln();
	$pdf->SetWidths(array(207));
	srand(microtime()*1000000);
	$pdf-> SetX(2);
	
	$pdf->Row(array('Profissional: '.S::create($l['nmprof'])->titleize(["de", "da", "do"])),1);
	
	
	$sql2 = mysqli_query($db,"SELECT tbprefeitura.NmCidade
	from tbprefeitura, tbfornecedor 
	where tbprefeitura.CdPref = tbfornecedor.CdCidade
	AND tbfornecedor.CdCidade = $l[CdCidade]
	AND tbfornecedor.CdForn = $l[CdForn]");
	$pdf-> SetX(2);
	$l2 = mysqli_fetch_array($sql2);
	$DtAgCons = $l['DtAgCons'];
	$pdf-> SetX(2);
	$DtAgCons = explode('-',$DtAgCons);
	$pdf-> SetX(2);
	$DtAgCons  = $DtAgCons[2].'/'.$DtAgCons[1].'/'.$DtAgCons[0];
	//$HoraAgCons = $l['HoraAgCons'];
	$HoraAgCons = date('H:i',strtotime($l['HoraAgCons']));
	$pdf->ln();
	$pdf-> SetX(2);
	if($l['cdendc'] > 0)
		$pdf->Row(array('Endereço: '.S::create($l['Logradouro'])->titleize(["de", "da", "do"]).', Nº: '.$l['crednumero'].', Bairro: '.S::create($l['bairrocred'])->titleize(["de", "da", "do"]).', Cidade: '.S::create($l['Cidade'])->titleize(["de", "da", "do"]).'-SP'),1);
	else
		$pdf->Row(array('Endereço: '.S::create($l['logradouro'])->titleize(["de", "da", "do"]).', Nº: '.$l['Numero'].', Bairro: '.S::create($l['Bairro'])->titleize(["de", "da", "do"]).', Cidade: '.S::create($l2['NmCidade'])->titleize(["de", "da", "do"]).'-SP'),1);
	$pdf->ln();
	$pdf-> SetX(2);
	if($l['Telefone'] != '')
		$telPrestador = $l['Telefone'];
	else $telPrestador = '  -  ';

	$pdf->Row2(array('Telefone: '.$telPrestador),1);
	$pdf->ln();
	$pdf-> SetX(2);
    $pdf->Row(array('Procedimento: '.$l['cdsus'].'  '.S::create($l['NmProcedimento'])->titleize(["de", "da", "do"]).' '.S::create($l['NmEspecProc'])->titleize(["de", "da", "do"])),1);
	$pdf->ln();
	$pdf-> SetX(2);
	
	$pdf->Row(array('Data do Atendimento: '.$DtAgCons.'   Horário: '.$HoraAgCons),1);
	$pdf->ln();
	$pdf-> SetX(2);


	/* $pdf->Row(array('Complemento: '.$l['Compl'] ),1);
	$pdf->ln();
	$pdf-> SetX(2); */

	$pdf->SetX(2);
	if($lpreparo['preparo'] != '')
		$pdf ->MultiCell(207, 4, $lpreparo['preparo'], 1, 'L');
	if($l['obs'] != '')
		$pdf ->MultiCell(207, 4, $l['obs'], 1, 'L');
	$pdf-> SetX(1);
	$pdf->Ln();
	$pdf ->MultiCell(190, 8, '_____________________________________________', 0, 'C');
	$pdf ->MultiCell(190, 4, 'Assinatura do Agendador', 0, 'C');
	$pdf->Ln();
	$pdf ->MultiCell(190, 8, 'ATENÇÃO: OBRIGATÓRIO APRESENTAÇÃO DO DOCUMENTO DE IDENTIDADE COM FOTO, CARTÃO SUS, PEDIDO DO EXAME, ENCAMINHAMENTO E GUIA DE AUTORIZAÇÃO PARA REALIZAR O ATENDIMENTO.', 0, 'C');
	$pdf->Ln();
$pdf->Output();
mysqli_close();
?>