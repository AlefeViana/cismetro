<?php

	require('../../fpdf/fpdf.php');
	require "../../conecta.php";
	
	
	function FormataDataBR($data){
	if ($data == '')
		return '';
	$data_f = explode('-',$data);
	return $data_f[2].'/'.$data_f[1].'/'.$data_f[0];
}

function FormataDataBD($data){
	if ($data == '')
		return '';
	$data_f = explode('/',$data);
	return $data_f[2].'-'.$data_f[1].'-'.$data_f[0];
}

function Seleciona_Item($valor, $campo) {
	return preg_replace("#<option value=\"$valor\">#is", 
	"<option value=\"$valor\" selected=\"selected\">", $campo);
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

	class PDF extends FPDF
	{
	  //Método Header que estiliza o cabeçalho da página
		function Header() {
			$sql = mysqli_query($db,"SELECT * FROM tbconsorcio") or die (mysqli_error());
			$lin = mysqli_fetch_array($sql);	   
			$this-> SetLeftMargin(1);
			$this->SetY(1);
			$this->Image('logo.jpg',-1,3,0);
			$this->SetFont('Arial','B',8);
			$this->SetX(1); 
			$this->SetFont('Arial','B',8);	
			$this->Sety(6); 
			$this->SetX(52);  $this->SetFont('Arial','B',10);
			$this->MultiCell(130,3, $lin[nmconsorcio],0, 'C');
			$this->Sety(10); 
			$this->SetX(52);  $this->SetFont('Arial','B',8);
			$this->MultiCell(135,3, $lin[enderecoconsorcio],0, 'C');
			$this->SetFont('Arial','B',8);
			$this->Sety(14); 
			$this->SetX(52); 
			$this->MultiCell(135,6, $lin[dadosconsorcio],0, 'C');   
			$this->SetY(5);
			$this->SetFont('Arial','B',8);
			$this->SetX(52); 
			$this->SetFont('Arial','B',10);
			$this->Sety(6); 
			$this->SetX(1); 
			$this->SetY(25);
			$this-> SetLeftMargin(1);
	  
			$data = date('d/m/Y');
			$horario = date("H:i:s");
			$this->SetFont('Arial','B',8);	
			
			$this-> SetY(5);	
			$this-> SetX(179);	
			$this->	MultiCell(30, 6,'EMISSÃO: '.$data.' - '.$horario.'',1);    
			$this->SetY(24);
			
	
		}
	   
		var  $i=0;
		function Footer() {
			$this->SetY(-10);
			$this->SetFont('Arial','I',8);
			$this->Cell(0,10,'Sitcon - Tecnologia da Informação - Tel. (31) 3822 4656 | email: sistemas@sitcon.com.br | www.sitcon.com.br                   Página '.$this->PageNo().$i++,0,0,'C');
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
		//Calculate the height of the row
		$nb=0;
		for($i=0;$i<count($data);$i++)
			$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
		$h=4*$nb;
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
			
			if($borda)
			// x começa a borda
			// y começa vertical 
			// w comprimento 
			// h altura
			
			$this->Rect($x,$y,$w,$h);
		   // $this->Rect($x,$y-$h,$w,0);
			//Print the text
			
			
			//$this->SetFillColor(255,255,255);
			$this->MultiCell($w,4,$data[$i],10,$a);
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
	
	
	
	
	
	
	
	
	
	
	
	
	// SELECIONA O PACIENTE 
	
	
	
	
	$CdPaciente = $_GET['cd'];
	
	
	
	$sql = mysqli_query($db,"SELECT
	p.CdPaciente,
	p.CdBairro,
	p.NmPaciente,
	p.RG,
	p.CPF,
	p.csus,
	p.foto,
	b.NmBairro,
	p.OutrosDocs,
	p.Sexo,
	p.DtNasc,
	p.Naturalidade,
	p.Nacionalidade,
	p.NmMae,
	p.NmPai,
	p.Telefone,
	p.Celular,
	p.Email,
	p.Profissao,
	p.Logradouro,
	p.Numero,
	p.Compl,
	p.CEP,
	b.CdPref,
	p.Referencia,
	tbprefeitura.NmCidade
	FROM
	tbpaciente AS p
	INNER JOIN tbbairro AS b ON p.CdBairro = b.CdBairro
	INNER JOIN tbprefeitura ON tbprefeitura.CdPref = b.CdPref
	WHERE CdPaciente='$CdPaciente' ");



	$lin = mysqli_fetch_array($sql);
	$foto = $lin[foto];
	
	
	if($foto=="")
	{
		
		$foto ="img/fotos/foto_pc.jpg";
	}
	
	
	
	
	
	$foto = explode('/',$foto);
	
	$NmPaciente = strtoupper($lin[NmPaciente]);
	$csus = strtoupper($lin[csus]);
	$RG = strtoupper($lin[RG]);
	$DtNasc = $lin[DtNasc];
	$Idade = CalcularIdade($DtNasc,"amd","-");	;
	$Profissao = strtoupper($lin[Profissao]);

	$pdf=new PDF();
	$pdf->AddPage();
	

		$pdf->SetFont('Arial','B',12);	
	$pdf->	ln(); 
	$pdf->	MultiCell(208, 6,'Paciente:  '.$NmPaciente,0,'C'); 
	
	 $pdf->Image($foto[2],5,33,25);

	$pdf->	SetY(35);    
	$pdf->SetFont('Arial','',8);	  
	$pdf->	ln();  

	$pdf->SetX(35);
	$pdf->	MultiCell(175, 6,' SEXO: '.$lin[Sexo].'	     CARTÃO SUS:'.$csus.'								RG:'.$RG .'					DATA NASCIMENTO: '.FormataDataBr($DtNasc).'  ('.$Idade.')' ,0,'l'); 

	$pdf->	MultiCell(208, 6,'																																												MÃE: '.$lin[NmMae].'																		PAI: '.$lin[NmPai] ,0,'l'); 	
	
	$pdf->	MultiCell(208, 6,'																																												ENDEREÇO: '.$lin[Logradouro].' , '.$lin[Numero].' , '.$lin[Compl].'  '.$lin[NmBairro].' , '.$lin[NmCidade].' - MG',0,'l'); 
	
	$pdf->SetFont('Arial','B',8);	  
	$pdf->ln();
	$pdf->	MultiCell(208, 6,'PRONTUÁRIO' ,1,'C');    
	$pdf->	MultiCell(208, 6,'		' ,1,'L');    
	$pdf->	MultiCell(208, 6,'' ,1,'C');    
	$pdf->	MultiCell(208, 6,'' ,1,'C');    
	$pdf->	MultiCell(208, 6,'' ,1,'C');    
	$pdf->	MultiCell(208, 6,'' ,1,'C');    
	$pdf->	MultiCell(208, 6,'' ,1,'C');    
	$pdf->	MultiCell(208, 6,'' ,1,'C');    
	$pdf->	MultiCell(208, 6,'' ,1,'C');    
	$pdf->	MultiCell(208, 6,'' ,1,'C');    
	$pdf->	MultiCell(208, 6,'' ,1,'C');    
	$pdf->	MultiCell(208, 6,'' ,1,'C');    
	$pdf->	MultiCell(208, 6,'' ,1,'C');    
	$pdf->	MultiCell(208, 6,'' ,1,'C');    
	$pdf->	MultiCell(208, 6,'' ,1,'C');    
	$pdf->	MultiCell(208, 6,'' ,1,'C');    
	$pdf->	MultiCell(208, 6,'' ,1,'C');    
	$pdf->	MultiCell(208, 6,'' ,1,'C');    
	$pdf->	MultiCell(208, 6,'' ,1,'C');    
	$pdf->	MultiCell(208, 6,'' ,1,'C');    
	$pdf->	MultiCell(208, 6,'' ,1,'C');    
	$pdf->	MultiCell(208, 6,'' ,1,'C');    
	$pdf->	MultiCell(208, 6,'' ,1,'C');    
	$pdf->	MultiCell(208, 6,'' ,1,'C');    
	$pdf->	MultiCell(208, 6,'' ,1,'C');    
	$pdf->	MultiCell(208, 6,'' ,1,'C');    
	$pdf->	MultiCell(208, 6,'' ,1,'C');    
	$pdf->	MultiCell(208, 6,'' ,1,'C');    
	$pdf->	MultiCell(208, 6,'' ,1,'C');    
	$pdf->	MultiCell(208, 6,'' ,1,'C');    
	$pdf->	MultiCell(208, 6,'' ,1,'C');    
	$pdf->	MultiCell(208, 6,'' ,1,'C');    
	$pdf->	MultiCell(208, 6,'' ,1,'C');    
	$pdf->	MultiCell(208, 6,'' ,1,'C');    
   
	$pdf->SetY(30);
$pdf->Output();

mysqli_close();
?>