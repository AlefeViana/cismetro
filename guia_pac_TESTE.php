<?php
	require("conecta.php");


	$id = $_GET['id'];
	
	// Atualiza status guia
	//$sql_att_guia = mysqli_query($db,"UPDATE `tbagendacons` SET `impressaoguia`='S' WHERE (`CdSolCons`='$id')") or die (mysqli_error());
	
	
	$sql = mysqli_query($db,"SELECT sc.CdSolCons,DtAgCons,HoraAgCons, p.CdPaciente,p.RG, p.NmMae, f.CdForn,f.Bairro,f.logradouro, f.Numero, f.CdCidade, ep.nmpreparo, p.NmPaciente, ac.Valor, p.DtNasc, ac.CdUsuario, u.Login, ac.CdForn, pr.NmCidade,sc.Protocolo, sc.DtInc,pr.CdPref, ep.CdEspecProc, ac.qts, ac.protocolopac, NmEspecProc, f.NmForn,sc.Status, ac.Status as StatusAg,Urgente,NmReduzido,
	sc.Obs, ac.obs,ep.cdsus, Pa.NmProcedimento, sc.Obs1, p.DtNasc, p.csus
	FROM tbsolcons sc INNER JOIN tbpaciente p ON sc.CdPaciente=p.CdPaciente 
	INNER JOIN tbbairro b ON b.CdBairro=p.CdBairro
	INNER JOIN tbprefeitura pr ON b.CdPref=pr.CdPref
	INNER JOIN tbespecproc ep ON sc.CdEspecProc=ep.CdEspecProc 
	INNER JOIN tbprocedimento Pa ON ep.CdProcedimento = Pa.CdProcedimento
	LEFT JOIN tbagendacons ac ON sc.CdSolCons=ac.CdSolCons
	LEFT JOIN tbfornecedor f ON ac.CdForn=f.CdForn
	LEFT JOIN tbusuario u ON ac.CdUsuario = u.CdUsuario
	WHERE sc.Status='1' AND ac.Status='1'
	AND sc.CdSolCons = '$id' ");

	
//fazemos a inclusão do arquivo com a classe FPDF
require('relatorios/fpdf/fpdf.php');




//criamos uma nova classe, que será uma extensão da classe FPDF
//para que possamos sobrescrever o método Header()
//com a formatação desejada

class PDF extends FPDF

{


   //Método Header que estiliza o cabeçalho da página
   function Header() {
	   $this->Sety(5);
	   if ($this->header1 == 1)
	   	$this->Image('img/logo.jpg',1,5,208,40,jpg);   
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
     $this-> SetX(2);
	 if ($this->footer == 1)
    $this->Image('img/logo.jpg',1,5,208,40,jpg); 
		  
	$this-> SetX(2);
	$this->SetFont('Arial','',8);
	$this ->MultiCell(207, 6, 'CISMIV - VIÇOSA - MG 
	          TEL: (031) 3891-4488  -  CNPJ: 02.326.365/0001-36  ', 1, 'C');


	/* $this->Sety(-14); 
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


//apontamos a fonte que será utilizada no texto
	$pdf->SetFont('Arial','B',15);
	require "conecta.php";
	$pdf-> SetY(47);
	$pdf-> SetX(2);
	$pdf->SetFont('Arial','B',10);
	$pdf->MultiCell(207, 8, 'GUIA DE ENCAMINHAMENTO PARA CONSULTA / EXAME ESPECIALIZADO', 1, 'C');
	$pdf->SetX(2);
	$pdf->SetX(2);
	$pdf->MultiCell(207, 2, '', 0, 'C');
	$pdf->SetFont('Arial','',10);
	$pdf->SetWidths(array(207));
	srand(microtime()*1000000);
	$pdf->ln();
	$pdf->SetX(2);
	$pdf->Row2(array('CÓDIGO: '.$l[CdSolCons].'  - PROTOCOLO: '.$l[protocolopac]),1);
	$pdf->ln();
	$pdf-> SetX(2);
	$pdf->Row2(array('MUNICIPIO DE PROCEDÊNCIA: '.$l[NmCidade].' - MG'),1);
	$pdf->ln();
	$pdf->SetX(2);
	
	$data = explode('-',$l[DtNasc]);
	$DtAgCons  = $data[2].'/'.$data[1].'/'.$data[0];
	
	$pdf->Row2(array('PACIENTE: '.strtoupper($l[NmPaciente])),1);
	$pdf->ln();
	$pdf->SetX(2);
	$pdf->Row2(array('DATA DE NASCIMENTO: '.$DtAgCons),1);
	$pdf->ln();
	$pdf->SetX(2);	
	$pdf->Row2(array('CARTÃO SUS: '. $l[csus]),1);
	$pdf->ln();
	$pdf->SetX(2);
	$pdf->Row2(array('MÃE: '.strtoupper($l[NmMae])),1);
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
	
	if($ss[Telefone] != '')
		$tel = $ss[Telefone];
	else $tel = '  -  ';

	if($ss[Celular] != '')
		$cel = $ss[Celular];
	else $cel = '  -  ';

	$pdf->Row2(array('TELEFONE: '.$tel.'   CELULAR: '.$cel),1);
	$pdf->ln();
	$pdf-> SetX(2);
	$pdf->Row2(array('ENDEREÇO: '.$ss[Logradouro].', Nº: '.$ss[Numero].', BAIRRO: '.$ss[NmBairro].', CIDADE: '.$ss[NmCidade].'-MG'),1);
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
	$pdf->Row(array('FORNECEDOR: '.strtoupper($l[NmForn])),1);
	
	
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
	$HoraAgCons = $l['HoraAgCons'];
	$pdf->ln();
	$pdf-> SetX(2);
	$pdf->Row(array('ENDEREÇO: '.$l[logradouro].', Nº: '.$l[Numero].', BAIRRO: '.$l[Bairro].', CIDADE: '.$l2[NmCidade].'-MG'),1);
	$pdf->ln();
	$pdf-> SetX(2);
    $pdf->Row(array('PROCEDIMENTO: '.$l[cdsus].'  '.$l[NmProcedimento].' '.$l[NmEspecProc]),1);
	$pdf->ln();
	$pdf-> SetX(2);
	
	
	
	
	$pdf-> SetX(2);
	$pdf->Ln();
	$pdf->Row(array(' 
								
																																																_____________________________________________
																																																						 						   				      Assinatura do Agendador
				   																																				
																																								
																																														
																																ATENÇÃO: OBRIGATÓRIO APRESENTAÇAO DO CARTÃO SUS,PEDIDO DO EXAME 
																										 E/OU ENCAMINHAMENTO GUIA DE AUTORIZAÇAO PARA REALIZAR O ATENDIMENTO.
	
	'),1);

$pdf->Output();
mysqli_close();
?>