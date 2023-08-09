<?php
	require_once("verifica.php");
	require("funcoes.php");

	$mcd = $_REQUEST['a'];
	$lista = implode(',', $mcd);
	
	$sql = mysqli_query($db,"SELECT agvv.cdagvivavida,agvv.protocolo,pr.NmCidade,p.NmPaciente,p.CdPaciente,p.DtNasc,p.csus,p.NmMae,p.Celular,p.Logradouro,
						p.Numero,agvv_i.cdespec,agvv.cdforn,agvv.cdpref,f.NmReduzido,f.NmForn,f.Logradouro,f.Numero,f.Bairro,pro.NmProcedimento,
						ep.NmEspecProc,prof.nmprof,
						agvv.dtag,
						date_format(agvv.dtag,'%d/%m/%Y') AS `dtagbr`,
						agvv.hrag,
						ep.nmpreparo,agvv.tipo,agvv.usr_alt,f.Telefone,est.NmEstado,est.UF,ep.cdsus
						FROM
						tbagvivavida AS agvv
						INNER JOIN tbagvivavida_itens AS agvv_i ON agvv_i.cdagvv = agvv.cdagvivavida
						INNER JOIN tbprefeitura AS pr ON agvv.cdpref = pr.CdPref
						INNER JOIN tbespecproc AS ep ON agvv_i.cdespec = ep.CdEspecProc
						INNER JOIN tbfornecedor AS f ON agvv.cdforn = f.CdForn
						INNER JOIN tbprocedimento AS pro ON pro.CdProcedimento = ep.CdProcedimento
						INNER JOIN tbpaciente AS p ON agvv.cdpac = p.CdPaciente
						INNER JOIN tbestado AS est ON pr.CdEstado = est.CdEstado
						INNER JOIN tbprofissional AS prof ON agvv.cdmed = prof.cdprof
						WHERE (agvv.estado = 'M' OR agvv.estado = 'R') 
						AND	agvv.cdagvivavida IN ($lista)
						ORDER BY agvv.cdforn ASC,agvv.dtag DESC,agvv.hrag ASC
						");
	$l = mysqli_fetch_array($sql);
	if($_GET["usr"]==3)
		mysqli_query($db,"UPDATE tbagvivavida SET tbagvivavida.impresso = 'S' WHERE tbagvivavida.cdagvivavida IN ($lista)") or die(mysqli_error());

//fazemos a inclusão do arquivo com a classe FPDF
require('relatorios/fpdf/fpdf.php');

//criamos uma nova classe, que será uma extensão da classe FPDF
//para que possamos sobrescrever o método Header()
//com a formatação desejada

class PDF extends FPDF
{

###### CÓDIGO DE BARRAS ######
function Code39($x, $y, $code, $ext = true, $cks = false, $w = 0.4, $h = 7, $wide = false) {

	//Display code
	$this->SetFont('Arial', '', 10);
	//$this->Text($x, $y+$h+4, $code);

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
		chr(8) => '$H', chr(9) => '$I', chr(10) => '$J', chr(11) => '£K',
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
###### FIM DO CÓDIGO DE BARRAS ######

   //Método Header que estiliza o cabeçalho da página
   function Header() {

   }
   /*
   //Método Header que estiliza o cabeçalho da página
   function Header() {

      //posicionamos o rodapé a 1cm do fim da página
    //$this->SetY(-10);
     $this-> SetX(2);
    $this->Image('img/logo.jpg',1,5,207,40,jpg);
	
	
	
		
   } */
   
var  $i=0;

   //Método Footer que estiliza o rodapé da página
   function Footer() {

      //posicionamos o rodapé a 1cm do fim da página
    //$this->SetY(-10);
     /*$this-> SetX(2);
	 if ($this->footer == 1)
    $this->Image('img/logo.jpg',1,5,208,40,jpg); 
		  
	$this-> SetX(2);
	$this->SetFont('Arial','',8);
	$this ->MultiCell(207, 6, 'CEAE - BECO FELISBERTO, Nº101, RIO GRANDE, DIAMANTINA-MG
	         TEL: (38)3531-3006', 1, 'C');
*/
	/*$this->Sety(-14); 
	$this-> SetX(156);
	$this ->MultiCell(135, 5, ' Sitcon Tecnologia da Informação  Tel.(31)3822-4656  sistemas@sitcon.com.br ', 0, 'C');
	$this->Sety(-10); 
	$this-> SetX(156);

	$this ->MultiCell(135, 5, ' Sistemas de Gestão em Saúde', 0, 'C');*/
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
//Inserimos a página
$pdf->AddPage('P');
$pdf->header1 = 0;

$pdf->SetLeftMargin('10');
	$sql2 = mysqli_query($db,"SELECT * FROM tbconsorcio") or die (mysqli_error());
			
	$lin = mysqli_fetch_array($sql2);	   
			
	$pdf-> SetLeftMargin(1);
	$pdf->SetY(1);
	$pdf->Image('imgceae/logo_viva.jpg',-7,1,70,25);
	$pdf->SetFont('Arial','B',8);
	$pdf->SetX(1); 
	$pdf->SetFont('Arial','B',8);	
	$pdf->Sety(6); 
	$pdf->SetX(52);  $pdf->SetFont('Arial','B',8);
	$pdf->MultiCell(108,3, 'CEAE - '.utf8_decode($lin['nmconsorcio']),0, 'C');
	$pdf->Sety(11); 
	$pdf->SetX(52);  $pdf->SetFont('Arial','B',8);
	$pdf->MultiCell(115,6, utf8_decode($lin['enderecoconsorcio']),0, 'C');
	$pdf->SetFont('Arial','B',7);
	$pdf->Sety(14); 
	$pdf->SetX(52);  
	//$pdf->MultiCell (108,6, $lin[dadosconsorcio],0, 'C');
	$pdf->SetY(5);
	$pdf->SetFont('Arial','B',8);
	$pdf->SetX(52); 
	$pdf->SetFont('Arial','B',10);
	$pdf->Sety(6); 
	$pdf->SetX(1); 
	$pdf->SetY(25);
	$pdf-> SetLeftMargin(1);
	$pdf->SetY(20);
	$pdf-> SetLeftMargin(1);   
			
	$data = date('d/m/Y');
	$horario = date("H:i:s");
	$pdf->SetFont('Arial','B',8);	

	$pdf-> SetY(5);	
	$pdf-> SetX(169);	
	$pdf->	MultiCell(40, 6,utf8_decode('Data Emissão: ').$data.utf8_decode(' Hora Emissão: ').$horario.'',1);    
	$pdf->SetY(30);

//apontamos a fonte que será utilizada no texto
$dadosPaciente = descobrePaciente($l['CdPaciente']);
	$pdf->SetFont('Arial','B',15);
	$pdf-> SetY(26);
	$pdf-> SetX(2);
	$pdf->SetFont('Arial','B',10);
	$pdf->MultiCell(207, 8, 'GUIA DE ENCAMINHAMENTO PARA CONSULTA / EXAME ESPECIALIZADO', 1, 'C');
	$pdf->SetFont('Arial','',10);
	$pdf->ln(3);
	$pdf->SetX(2);
	$pdf->Row2(array('PACIENTE: '.utf8_decode($dadosPaciente['NmPaciente'])."   CIH: ".$dadosPaciente['CdPaciente'].utf8_decode("   CARTÃO SUS: "). $dadosPaciente['csus']),1);
	$pdf->ln(0.2);
	$pdf->SetX(2);
	$pdf->Row2(array('DATA DE NASCIMENTO: '.$dadosPaciente['DtNascbr'].'   IDADE:  '.CalcularIdade($dadosPaciente['DtNascbr'],"dma","/")),1);
	$pdf->ln(0.2);
	$pdf->SetX(2);	
	$pdf->ln(0.2);
	$pdf->SetX(2);
	$pdf->Row2(array(utf8_decode('MÃE: '.$dadosPaciente['NmMae'])),1);
	$pdf->ln(0.2);
	$pdf->SetX(2);
	$pdf->Row2(array(utf8_decode('ENDEREÇO: ').utf8_decode($dadosPaciente['Logradouro']).utf8_decode(', Nº: ').$dadosPaciente['Numero'].', BAIRRO: '.utf8_decode($dadosPaciente['NmBairro']).', CIDADE: '.utf8_decode($dadosPaciente['NmCidade']).'-'.$dadosPaciente['UF']),1);
	$pdf-> SetX(2);
	$pdf->SetFont('Arial','B',10);
	$pdf->MultiCell(207, 8, 'Procedimentos', 1, 'C');
	$pdf->ln(2);
	$pdf->MultiCell(207, 2, '', 0, 'C');
	mysqli_data_seek($sql,0);
	$x=1;
	$preconsultalist = '1';
	while($aresult = mysqli_fetch_array($sql))
	{
		if ($preconsultalist) {
			$pdf-> SetX(2);
			$pdf->SetFont('Arial','B',8);
		    $pdf->Row(array('PROCEDIMENTO '.$x++.utf8_decode(': PRÉ-CONSULTA')),1);
		    $pdf->SetFont('');
		    $pdf->SetX(3);
			$pdf->ln(0.2);
		}
		$pdf-> SetX(2);
		$pdf->SetFont('Arial','B',8);
	    $pdf->Row(array('PROCEDIMENTO '.$x++.': '.$aresult['cdsus'].'  '.$aresult['NmProcedimento'].' '.$aresult['NmEspecProc']),1);
	    $pdf->SetFont('');
	    $pdf->SetX(3);
		$pdf->ln(0.2);
	}
	$pdf-> SetX(2);
	$pdf->Ln();
	$pdf ->MultiCell(190, 8, '_____________________________________________', 0, 'C');
	$pdf ->MultiCell(190, 4, 'Assinatura do Agendador', 0, 'C');
	$pdf->Ln();
	$pdf ->MultiCell(190, 8, utf8_decode('ATENÇÃO: OBRIGATÓRIO APRESENTAÇÃO DO DOCUMENTO DE IDENTIDADE COM FOTO, CARTÃO SUS, PEDIDO DO EXAME, ENCAMINHAMENTO E GUIA DE AUTORIZAÇAO PARA REALIZAR O ATENDIMENTO.'), 0, 'C');
	$pdf->SetFont('Arial','B',10);
	$pdf->MultiCell(207, 2, '', 0, 'C');
	$pdf->ln();
	$pdf->SetX(2);
	$pdf->MultiCell(207, 8, 'LOCAIS DE ATENDIMENTO', 1, 'C');
	$pdf->SetX(2);
	mysqli_data_seek($sql,0);
	$cdfornInicial = 0;
	$cdespecInicial = 0;
	$preconsulta = 1;
	while($fornproc = mysqli_fetch_array($sql))
	{
		/*Pré-Consulta*/
		if ($preconsulta) {
			$datapre = $fornproc['dtagbr'];
			//$horapre = strtotime($fornproc[hrag] - '30 minutes' );
			$time = strtotime($fornproc['hrag']);
			$horapre = date("H:i:s", strtotime('-30 minutes', $time));

			$pdf->SetFont('Arial','B',10);
			$pdf->SetWidths(array(207));
			$pdf->Row(array("------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"),1);
			$pdf->SetFont('Arial','',8);
			$pdf-> SetX(2);
			$pdf->Row(array(utf8_decode('ENDEREÇO: BECO DO FELISBERTO, Nº: 101, BAIRRO: RIO GRANDE, CIDADE: DIAMANTINA-MG')),1);
			$pdf-> SetX(2);
			$pdf->SetFont('Arial','B',8);
	    	$pdf->Row(array(utf8_decode('PROCEDIMENTO: PRÉ-CONSULTA')),1);
	    	$pdf-> SetX(2);
	    	$pdf->SetFont('Arial','',8);
			$pdf->Row(array('DATA: '.$datapre.utf8_decode('   HORÁRIO ').$horapre),1);
			$preconsulta--;
		}
		/*Pré-Consulta*/

		if($fornproc['cdforn']!= $cdfornInicial){
			$pdf->SetFont('Arial','B',10);
			$pdf->SetWidths(array(207));
			$pdf->Row(array("------------------------------------------------------------------------------------------------------------------------------------------------------------------------------"),1);
			$pdf->SetFont('Arial','',8);
			$pdf-> SetX(2);
			$pdf->Row(array('PACIENTE: '.utf8_decode($dadosPaciente['NmPaciente'])."   CIH: ".$dadosPaciente['CdPaciente'].utf8_decode("   CARTÃO SUS: "). $dadosPaciente['csus']),1);
			$pdf->ln(0.2);
			$pdf-> SetX(2);

			if($fornproc['Telefone'] != '')
				$tel = $fornproc['Telefone'];
			else $tel = '  -  ';

			$pdf->Row(array('FORNECEDOR: '.strtoupper(utf8_decode($fornproc['NmForn'])).'  TELEFONE: '.$tel),1);
			$cdfornInicial = $fornproc['cdforn'];
			$cdespecInicial = 0;
		}
			$pdf->Row(array(""),1);
		if ($cdespecInicial != $fornproc['cdespec']) {
			$pdf-> SetX(2);
			$fornend = getEnderecoFornCerto($fornproc['cdforn'],$fornproc['cdespec']);

			$pdf->Row(array(utf8_decode('ENDEREÇO: '.$fornend['Logradouro'].', Nº: '.$fornend['Numero'].', BAIRRO: '.$fornend['Bairro'].', CIDADE: '.$fornend['NmCidade'].'-'.$fornend['UF'])),1);

			$cdespecInicial = $fornproc['cdespec'];
		}
			$pdf-> SetX(2);
			$pdf->SetFont('Arial','B',8);
			$pdf->SetWidths(array(150));
	    	$pdf->Row(array('PROCEDIMENTO '.$s.': '.$fornproc['cdsus'].'  '.$fornproc['NmProcedimento'].' '.$fornproc['NmEspecProc'].' - '.utf8_decode($fornproc['nmprof']).utf8_decode(' CÓDIGO: ').$fornproc['cdagvivavida'].'   PROTOCOLO: '.$fornproc['protocolo']),1);
	    	$pdf->Code39(165,($pdf->GetY()-9),"$fornproc[cdagvivavida]"); 
			$pdf->SetFont('Arial','',8);
			$pdf->ln(0.2);
			$pdf-> SetX(2);
			$pdf->Row(array('DATA DA CONSULTA / EXAME: '.$fornproc['dtagbr'].utf8_decode('   HORÁRIO ').$fornproc['hrag']),1);
			$pdf->ln(0.2);
			$pdf-> SetX(2);
			$pdf->SetFont('Arial','B',8);
			if ($fornproc['nmpreparo'] != '') {
				$pdf->MultiCell(180, 4,'PREPARO: '.rtrim(ltrim($fornproc['nmpreparo'])), 0, 'L');
			}
	}
$pdf->Output();
mysqli_close();
?>