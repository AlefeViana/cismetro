<?php
	session_start();
	require("conecta.php");

	require "../vendor/autoload.php";
	use Stringy\Stringy as S;


	$id = $_GET['id'];
	
	// Atualiza status guia
	//$sql_att_guia = mysqli_query($db,"UPDATE `tbagendacons` SET `impressaoguia`='S' WHERE (`CdSolCons`='$id')") or die (mysqli_error());
	
	$sql1 = "SELECT sc.CdSolCons,p.NmPaciente,p.NmMae,f.NmForn,fc.DtAgen,fc.HrAgen,sc.Obs,sc.Obs1,fc.Obs,DtNasc,csus,p.Telefone,
						tbprocedimento.NmProcedimento,ep.NmEspecProc,pr.NmCidade,ep.CdEspecProc,p.CdPaciente,pr.CdPref,fc.CdFornfc,p.Celular,
						f.Logradouro,f.Numero, f.Bairro, p.cdunidade, f.Telefone as telforn,etfd.NmEspecProc as NmEspecProc1,
						etfd.cdsus as cdsus1, ptfd.NmProcedimento as NmProcedimento1, u.NmUsuario, idcombo, e.UF
						FROM tbsolcons AS sc
						LEFT JOIN tbagentfd fc ON sc.CdSolCons = fc.CdSolCons
						INNER JOIN tbpaciente AS p ON sc.CdPaciente = p.CdPaciente
						INNER JOIN tbprefeitura as pr ON sc.CdPref = pr.CdPref
						INNER JOIN tbestado AS e ON pr.CdEstado = e.CdEstado
						left JOIN tbfornecedortfd AS f ON fc.CdFornfc = f.CdForn
						left JOIN tbespecproc AS ep ON sc.CdEspecProc = ep.CdEspecProc
						left JOIN tbprocedimento ON ep.CdProcedimento = tbprocedimento.CdProcedimento
						LEFT JOIN tbusuario u ON sc.Userinc = u.CdUsuario
						INNER JOIN tbfornecedor_mun ON sc.CdUnid = tbfornecedor_mun.CdForn
						LEFT JOIN tbespecproctfd etfd ON fc.cdespectfd = etfd.CdEspecProc
						LEFT JOIN tbprocedimento ptfd ON etfd.CdProcedimento = ptfd.CdProcedimento
						WHERE sc.CdSolCons = '$id' ";

	//echo $sql1;
	$sql = mysqli_query($db,$sql1);

	$l = mysqli_fetch_array($sql);
	
	//$sql_preparo = mysqli_query($db,"SELECT	tbespecproc.nmpreparo,	tbespecproc.CdEspecProc FROM tbespecproc WHERE tbespecproc.CdEspecProc = '$l[CdEspecProc]' ");
	/*$sql_preparo = "SELECT tbespecproc.CdEspecProc,tbespecproc.NmEspecProc,tbespecproc.nmpreparo
					FROM tbespecproc
					WHERE CdEspecProc = $l[CdEspecProc]";
	$sql_preparo = mysqli_query($db,$sql_preparo) or die("Erro ao consultar preparo");
		

	$lpreparo = mysqli_fetch_array($sql_preparo);*/
	
		#Atualiza o Campo impresso para 'S'
		//if($_GET["usr"]==3)
			//mysqli_query($db,"UPDATE tbsolcons SET tbsolcons.impresso = 'S' WHERE tbsolcons.CdSolCons = $id") or die(mysqli_error());

//fazemos a inclusÃ£o do arquivo com a classe FPDF
require('relatorios/fpdf/fpdf.php');




//criamos uma nova classe, que serÃ¡ uma extensÃ£o da classe FPDF
//para que possamos sobrescrever o mÃ©todo Header()
//com a formataÃ§Ã£o desejada

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
		chr(8) => '$H', chr(9) => '$I', chr(10) => '$J', chr(11) => 'Â£K',
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

   //MÃ©todo Header que estiliza o cabeÃ§alho da pÃ¡gina
   function Header() {
	    $this->Sety(5);
	    if ($this->header1 == 1)
	    {
	   		//$this->Image('img/logo.jpg',-3,5,208,40,jpg); 
	   		//$this->Image('img/logo_new.jpg',12,14,43,24,jpg);    
	   		/*$sql = mysqli_query($db,"SELECT tbprefend.CdPref,tbprefend.NmSecretaria,tbprefend.Logradouro,tbprefend.Numero,tbprefend.Compl,tbprefend.Bairro,
	   							tbprefend.CEP,tbprefend.Email,tbprefend.Telefone
								FROM tbprefend
								WHERE CdPref = ".$_SESSION[CdOrigem]);
	   		$end = mysqli_fetch_array($sql);

	   		$sec = strtoupper($end[NmSecretaria]);
	   		$this->Cell(30,10,'Title',1,0,'C');*/
	   		//$this ->MultiCell(135, 5, $sec, 0, 'C');
	   		//$this->MultiCell(207, 8, $sec, 0, 'C');
	    }
   }
   /*
   //MÃ©todo Header que estiliza o cabeÃ§alho da pÃ¡gina
   function Header() {

      //posicionamos o rodapÃ© a 1cm do fim da pÃ¡gina
    //$this->SetY(-10);
     $this-> SetX(2);
    $this->Image('img/logo.jpg',1,5,207,40,jpg);
	
	
	
		
   } */
   
var  $i=0;

   //MÃ©todo Footer que estiliza o rodapÃ© da pÃ¡gina
   /*  function Footer() {

      //posicionamos o rodapÃ© a 1cm do fim da pÃ¡gina
    //$this->SetY(-10);
   $this-> SetX(2);
	 if ($this->footer == 1)
    $this->Image('img/logo.jpg',1,5,208,40,jpg); 
		  
	$this-> SetX(2);
	$this->SetFont('Arial','',8);
	$query_con = mysqli_query($db,"SELECT nmconsorcio, enderecoconsorcio, dadosconsorcio from tbconsorcio");
	$qry_con = mysqli_fetch_array($query_con);

	$this ->MultiCell(207, 6, "".strtoupper($qry_con["nmconsorcio"])."
	          ".strtoupper($qry_con["enderecoconsorcio"])."
".strtoupper($qry_con["dadosconsorcio"]), 1, 'C');*/


	/* $this->Sety(-14); 
	$this-> SetX(156);
	$this ->MultiCell(135, 5, ' Sitcon Tecnologia da InformaÃ§Ã£o  Tel.(31)3822-4656  sistemas@sitcon.com.br ', 0, 'C');
	$this->Sety(-10); 
	$this-> SetX(156);

	$this ->MultiCell(135, 5, ' Sistemas de GestÃ£o em SaÃºde', 0, 'C');*/
 /*  }
*/
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
function ultimoDiaMes($data=""){
    if (!$data) {
       $dia = date("d");
       $mes = date("m");
       $ano = date("Y");
    } else {
       $dia = date("d",$data);
       $mes = date("m",$data);
       $ano = date("Y",$data);
    }
    $data = mktime(0, 0, 0, $mes, 1, $ano);
    return date("d",$data-1);
}
function CalcularIdade($nascimento,$formato,$separador)
{
	//Data Nascimento
	$nascimento = explode($separador, $nascimento);

	if ($data1>$data2)
	{
       return " ";
    }

	if ($formato=="dma")
	{
		$ano = $nascimento[2];
		$mes = $nascimento[1];
		$dia = $nascimento[0];
	}
	elseif ($formato=="amd")
	{
		$ano = $nascimento[0];
		$mes = $nascimento[1];
		$dia = $nascimento[2];
	}

	$dia1 = $dia;
	$mes1 = $mes;
	$ano1 = $ano;

    $dia2 = date("d");
    $mes2 = date("m");
    $ano2 = date("Y");

    $dif_ano = $ano2 - $ano1;
    $dif_mes = $mes2 - $mes1;
    $dif_dia = $dia2 - $dia1;

    if ( ($dif_mes == 0) and ($dia2 < $dia1) ) {
       $dif_dia = (ultimoDiaMes($data1) - $dia1) + $dia2;
       $dif_mes = 11;
       $dif_ano--;
    } elseif ($dif_mes < 0) {
       $dif_mes = (12 - $mes1) + $mes2;
       $dif_ano--;
       if ($dif_dia<0){
          $dif_dia = (ultimoDiaMes($data1) - $dia1) + $dia2;
          $dif_mes--;
       }
    } elseif ($dif_dia < 0) {
       $dif_dia = (ultimoDiaMes($data1) - $dia1) + $dia2;
       if ($dif_mes>0) {
          $dif_mes--;
       }
    }
    if ($dif_ano>0) {
       $dif_ano = $dif_ano . " ano" . (($dif_ano>1) ? "s ": " ") ;
    } else { $dif_ano = ""; }
    if ($dif_mes>0) {
       $dif_mes = $dif_mes . " mes" . (($dif_mes>1) ? "es ": " ") ;
    } else { $dif_mes = ""; }
    if ($dif_dia>0) {
       $dif_dia = $dif_dia . " dia" . (($dif_dia>1) ? "s ": " ") ;
    } else { $dif_dia = ""; }

    return $dif_ano . $dif_mes . $dif_dia;

}
//require('ean13.php');
//Criamos o objeto da classe PDF
$pdf= new PDF();

$pdf->header1 = 1;
//Inserimos a pÃ¡gina
$pdf->AddPage('P');
$pdf->header1 = 0;

$pdf->SetLeftMargin('10');


//apontamos a fonte que serÃ¡ utilizada no texto
	$pdf->SetFont('Arial','B',15);

	$pdf-> SetY(20);
	$pdf-> SetX(2);
	$pdf->SetFont('Arial','B',10);
	$sql1 = 'SELECT tbprefend.CdPref,tbprefend.NmSecretaria,tbprefend.Logradouro,tbprefend.Numero,tbprefend.Compl,tbprefend.Bairro,
						tbprefend.CEP,tbprefend.Email,tbprefend.Telefone,p.NmCidade,e.UF
						FROM tbprefend
						INNER JOIN tbprefeitura AS p ON tbprefend.CdPref = p.CdPref
						INNER JOIN tbestado AS e ON p.CdEstado = e.CdEstado
						WHERE
						p.CdPref = '.$_SESSION['CdOrigem'].'';
		//echo $sql1;
	$sql = mysqli_query($db,$sql1);
	$end = mysqli_fetch_array($sql);

	$telefone = $end['Telefone'];
	if($telefone =="0000000000"){
		$telefoneN  = "";
	}else {
		$pattern = '/(\d{2})(\d{4})(\d*)/';
		$telefoneN = preg_replace($pattern, '($1) $2-$3', $telefone);
	}

	function FormataDataBR($data){
		if ($data == '')
			return '';
		$data_f = explode('-',$data);
		return $data_f[2].'/'.$data_f[1].'/'.$data_f[0];
	}	


	$sec = S::create($end['NmSecretaria'])->titleize(["de", "da", "do"]);
	$pdf->MultiCell(207, 8, $sec, 0, 'C');
	/*$pdf->ln();
	$pdf-> SetX(2);*/
	$pdf->SetFont('Arial','',10);
	$pdf->MultiCell(207, 8, 'Rua: '.S::create($end['Logradouro'])->titleize(["de", "da", "do"]). ', Nº: '.$end['Numero'].', Bairro: '.S::create($end['Bairro'])->titleize(["de", "da", "do"]).', Cidade: '.S::create($end['NmCidade'])->titleize(["de", "da", "do"]).' - '.$end['UF'], 0, 'C');
	$pdf->MultiCell(185, 8,'Telefone: '.$telefoneN, 0, 'C');

	$pdf-> SetY(47);
	$pdf-> SetX(2);
	$pdf->SetFont('Arial','B',10);
	$pdf->MultiCell(207, 8, 'PROTOCOLO FILA DE ESPERA', 1, 'C');
	$pdf->SetX(2);
	$pdf->SetX(2);
	$pdf->MultiCell(207, 2, '', 0, 'C');
	$pdf->SetFont('Arial','',10);
	//$pdf->Code39(165,60,"$l[CdSolCons]"); ##IMPRIME O CÃ“DIGO DE BARRAS
	$pdf->SetWidths(array(207));
	srand(microtime()*1000000);
	$pdf->ln();
	$pdf->SetX(2);
	//$pdf->Row2(array('CÃ“DIGO: '.$l[Protocolo]),1);
	//$pdf->ln();
	$pdf-> SetX(2);
	$pdf->Row2(array('Município de Procedência: '.S::create($l['NmCidade'])->titleize(["de", "da", "do"]).' - '.$l['UF']),1);
	$pdf->ln();
	$pdf->SetX(2);
	
	$data = explode('-',$l['DtNasc']) ;
	$DtAgCons  = $data[2].'/'.$data[1].'/'.$data[0];
	
	$pdf->Row2(array('PACIENTE: '.strtoupper($l['NmPaciente'])." CIH: ".$l['CdPaciente']),1);
	$pdf->ln();
	$pdf->SetX(2);
	$pdf->Row2(array('DATA DE NASCIMENTO: '.$DtAgCons.'   IDADE:  '.CalcularIdade($DtAgCons,"dma","/")),1);
	$pdf->ln();
	$pdf->SetX(2);	
	$pdf->Row2(array('CARTÃO SUS: '. $l['csus']),1);
	$pdf->ln();
	$pdf->SetX(2);
	$pdf->Row2(array('MÃE: '.strtoupper($l['NmMae'])),1);
	$pdf->ln();

	$pdf->SetFont('Arial','B',10);
	$pdf ->MultiCell(207, 2, '', 0, 'C');
	$pdf->ln();
	$pdf-> SetX(2);
	$pdf ->MultiCell(207, 8, 'DADOS DO ATENDIMENTO', 1, 'C');
	$pdf-> SetX(2);

	$dt = date('d/m/Y');

	$pdf ->MultiCell(207, 2, '', 0, 'C');
	$pdf->SetFont('Arial','',10);
	$pdf->SetWidths(array(207));
	srand(microtime()*1000000);

	$pdf->ln();
	$pdf->SetX(2);
	$pdf->Row2(array('RESPONSÁVEL: '.strtoupper($l['NmUsuario'])),1);
	$pdf-> SetX(2);
	$pdf->Row(array('DATA: '.$dt),1);

	if ($l['idcombo'] > 0) {
		$qrcombo = mysqli_query($db,"SELECT sc.CdEspecProc, sc.dtmedsol, ep.NmEspecProc, tbagentfd.cdespectfd, 
								tbespecproctfd.NmEspecProc as NmEspecProctfd, sc.CdSolCons
								FROM tbsolcons AS sc
								LEFT JOIN tbespecproc AS ep ON sc.CdEspecProc = ep.CdEspecProc
								LEFT JOIN tbagentfd ON sc.CdSolCons = tbagentfd.CdSolCons
								LEFT JOIN tbespecproctfd ON tbagentfd.cdespectfd = tbespecproctfd.CdEspecProc
								WHERE
								sc.idcombo = $l[idcombo]");

		while ($cmb = mysqli_fetch_array($qrcombo)) {
			
			$Espec = ($cmb['CdEspecProc'] > 0)?$cmb['NmEspecProc'] : $cmb['NmEspecProctfd'];

			$pdf->ln();
			$pdf->SetX(2);
			$pdf->Row2(array('Nº PEDIDO: '.strtoupper($cmb['CdSolCons'])),1);
			$pdf->SetX(2);
			$pdf->Row2(array('PROCEDIMENTO: '.strtoupper($Espec)),1);
			$pdf-> SetX(2);
			$pdf->Row(array('DATA DA SOLICITAÇÃO MÉDICA : '.FormataDataBR($cmb['dtmedsol'])),1);
		}
	}
	

	
	


	$pdf->SetX(2);

		$pdf->Ln();
		$pdf->Ln();
		$pdf-> SetX(2);
		$pdf->Row(array('                                                               _____________________________________________'),1); 
		$pdf-> SetX(2);
		$pdf->Row(array('                                                                                        Assinatura do Responsável'),1); 

	$pdf->Output();
	mysqli_close();
?>