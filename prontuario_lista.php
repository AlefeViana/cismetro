
<?php
	if(!defined('DIRECT_ACCESS')) {
		die('Direct access not permitted');
	}

	use voku\helper\Paginator;

	$busca    = mysqli_real_escape_string($db,$_REQUEST["pesq"]);
	$cbopor   = (int)$_REQUEST["cbopesq"];
	
	if ($cbopor == 1)
		$busca = (int)$busca;	
	

	$valida_prontuario_forn= "";
	if($_SESSION["CdTpUsuario"] == 5){
	$dataini = date('Y-m-d');
	//$datafim = date('Y-m-d', strtotime($dataini. ' - 5 days'));
	$sql_forn_mult = mysqli_query($db,"SELECT CdForn FROM tbusuarioforn WHERE CdUsuario =".$_SESSION['CdUsuario']);
	$qry_forn = mysqli_fetch_array($sql_forn_mult);
	if(mysqli_num_rows($sql_forn_mult)>0)
	$multforn = " OR ac.CdForn in (".implode(',', $qry_forn).")";
	$forn = $_SESSION["cdfornecedor"];
	// $valida_prontuario_forn = " INNER JOIN tbsolcons sc on sc.CdPaciente = p.CdPaciente
	// 							INNER JOIN tbagendacons ac on ac.CdSolCons = sc.CdSolCons
	// 							WHERE (ac.CdForn = $forn $multforn )";
	}

//consulta pacientes
	$sql = "SELECT p.CdPaciente,p.NmPaciente,p.NmMae,p.DtNasc,pr.NmCidade
			FROM tbpaciente p INNER JOIN tbbairro b ON p.CdBairro=b.CdBairro
								INNER JOIN tbprefeitura pr ON b.CdPref=pr.CdPref
								$valida_prontuario_forn";

	if($_SESSION["CdTpUsuario"] == 5){
		if ($busca != ""){
			switch ($cbopor){
				case 1: $sql .= " AND p.CdPaciente = $busca";
						break;
				case 2: $sql .= " AND NmPaciente LIKE '$busca%'";
						break;
				case 3:
					$valorBuscar = explode("/",$busca);
					$dia = $valorBuscar[0];
					$mes = $valorBuscar[1];
					$ano =  $valorBuscar[2];
							
						$sql .= " AND YEAR(p.DtNasc)=$ano AND MONTH(p.DtNasc)=$mes AND DAY(p.DtNasc)=$dia";
						break;
			}
		}
	}else{
		if ($busca != ""){
			switch ($cbopor){
				case 1: $sql .= " WHERE p.CdPaciente = $busca";
						break;
				case 2: $sql .= " WHERE NmPaciente LIKE '$busca%'";
						break;
				case 3:
						$valorBuscar = explode("/",$busca); 
						$dia = $valorBuscar[0];
						$mes = $valorBuscar[1];
						$ano =  $valorBuscar[2];
							
						$sql .= "  WHERE YEAR(p.DtNasc)=$ano AND MONTH(p.DtNasc)=$mes AND DAY(p.DtNasc)=$dia";
						break;
			}
		}
	}
//filtra os pacientes de uma cidade de acordo com o usuario logado
	if ((int)$_SESSION["CdOrigem"]>0)
	{
		$sql .= " AND b.CdPref=".(int)$_SESSION["CdOrigem"];		
	}

	$sql .= " AND p.`Status` = 1 ";

    //echo $sql;
//executa a consulta
    $query = mysqli_query($db,$sql)or die (mysqli_errno());

//obtem o numero de linhas da consulta
    $total = mysqli_num_rows($query);
	$pages = new Paginator(15, 'pag');
	$pages->set_total($total); 

// Executa a query no MySQL com o limite de linhas.
    $limsql = $sql." ORDER BY NmCidade,NmPaciente ".$pages->get_limit();
    //echo $limsql;
    $query = mysqli_query($db,$limsql)or die(TrataErro(mysqli_errno(),'','index.php?p=inicial','lista_pac:consulta dados'));
	$count = mysqli_num_rows($query);

    $met = $_GET['met'];
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