<h1> Controle &raquo Prontuário </h1>

<script type="text/javascript">
function abrirpop (pagina,largura,altura) {
	//pega a resolução do visitante
	w = screen.width;
	h = screen.height;
	//divide a resolução por 2, obtendo o centro do monitor
	meio_w = w/2;
	meio_h = h/2;
	//diminui o valor da metade da resolução pelo tamanho da janela, fazendo com q ela fique centralizada
	altura2 = altura/2;
	largura2 = largura/2;
	meio1 = meio_h-altura2;
	meio2 = meio_w-largura2;
	//abre a nova janela, já com a sua devida posição
	window.open(pagina,'','height=' + altura + ', width=' + largura + ', top='+meio1+', left='+meio2+''); 
}

</script>



<?php
	require_once("verifica.php");
	
//funcao para tratar erro
	require("admin/function_trata_erro.php");
	
//conecta no banco
    require_once("conecta.php");
    
//variavel do form de busca

        $busca    = mysqli_real_escape_string($_REQUEST["pesq"]);
		$cbopor   = (int)$_REQUEST["cbopesq"];
		
		if ($cbopor == 1){
			$busca = (int)$busca;	
		}
       
//consulta pacientes
        $sql = "SELECT p.CdPaciente,p.NmPaciente,p.NmMae,p.DtNasc,pr.NmCidade
                FROM tbpaciente p INNER JOIN tbbairro b ON p.CdBairro=b.CdBairro
								  INNER JOIN tbprefeitura pr ON b.CdPref=pr.CdPref";
    if ($busca != ""){
		switch ($cbopor){
			case 1: $sql .= " WHERE CdPaciente = $busca";
					break;
			case 2: $sql .= " WHERE NmPaciente LIKE '$busca%'";
					break;
			case 3:
					$busca = explode("/",$busca);
					$dia = $busca[0];
					$mes = $busca[1];
					$ano =  $busca[2];
					 
				 	$sql .= "  WHERE YEAR(p.DtNasc)=$ano AND MONTH(p.DtNasc)=$mes AND DAY(p.DtNasc)=$dia";
					break;
		}
    }
//filtra os pacientes de uma cidade de acordo com o usuario logado
	if ((int)$_SESSION["CdOrigem"]>0)
	{
		$sql .= " AND b.CdPref=".(int)$_SESSION["CdOrigem"];		
	}

    //echo $sql;
//executa a consulta
    $query = mysqli_query($db,$sql)or die (mysqli_errno());

//obtem o numero de linhas da consulta
    $qtdreg = mysqli_num_rows($query);
	//echo "Registros ".$qtdreg;

// Especifique quantos resultados você quer por página
    $lpp = 15;

// Retorna o total de páginas
    $pags = ceil($qtdreg / $lpp);

// Especifica uma valor para variavel pagina caso a mesma não esteja setada
    if(!isset($_GET["pag"])) {
         $pag = 0;
    }else{
         $pag = (int)$_GET["pag"];
	}
// Retorna qual será a primeira linha a ser mostrada no MySQL
    $inicio = $pag * $lpp;

// Executa a query no MySQL com o limite de linhas.
    $limsql = $sql." ORDER BY NmCidade,NmPaciente LIMIT $inicio, $lpp ";
    $query = mysqli_query($db,$limsql)or die(TrataErro(mysqli_errno(),'','index.php?p=inicial','lista_pac:consulta dados'));
	
	
//inicio do form de pesquisa


    $met = $_GET[met];
    if($met=="") {$met = 'l'; }
    
    switch($met)
    {
        
        case 'l':
            include "pgpront.php";
        break;
    
        case 'p':
            include "prontuario_pac/prontuario.php";
        break;
    
    } 
    
    
    
    
    
    
    
?>
