<?php 
require("verifica.php");
require("../conecta.php");
include("../funcoes.php");

$p = $_POST;

$cid = ""; $ag = "";
if($p[cd_pref] != 0)
	$cid = "AND pr.CdPref = ".(int)$p[cd_pref];
if($p[cd_forn] != 0){
	$forn = "AND f.CdForn = ".(int)$p[cd_forn];
	$sqlf = "SELECT tbfornecedor.CdForn,tbfornecedor.NmForn FROM tbfornecedor WHERE tbfornecedor.CdForn = $p[cd_forn]";
	$sqlf = mysqli_query($db,$sqlf) or die("Erro ao buscar fornecedor");
	$lf = mysqli_fetch_array($sqlf);
	mysqli_free_result($sqlf);
	$fornecedor = "<br />Fornecedor: $lf[NmForn]";
} 
if($p[cd_proc] != 0){
	$proc = "AND Pa.CdProcedimento = ".(int)$p[cd_proc];
	$sqlp = "SELECT tbprocedimento.CdProcedimento,tbprocedimento.NmProcedimento FROM tbprocedimento WHERE tbprocedimento.CdProcedimento = $p[cd_proc]";
	$sqlp = mysqli_query($db,$sqlp) or die("Erro ao buscar procedimento");
	$lp = mysqli_fetch_array($sqlp);
	mysqli_free_result($sqlp);
	$proced = "<br />Procedimento: $lp[NmProcedimento]";
}
if($p[cd_especificacao] != 0){
	$espec = "AND ep.CdEspecProc = ".(int)$p[cd_especificacao];
	$sqle = "SELECT tbespecproc.CdEspecProc,tbespecproc.NmEspecProc FROM tbespecproc WHERE tbespecproc.CdEspecProc = $p[cd_especificacao]";
	$sqle = mysqli_query($db,$sqle) or die("Erro ao buscar especificação");
	$le = mysqli_fetch_array($sqle);
	mysqli_free_result($sqle);
	$especif = "<br />".utf8_decode(Especificação).": $le[NmEspecProc]";
}

$datai = FormataDataBD($p[dtinicio]);
$dataf = FormataDataBD($p[dttermino]);

$sql1 = "SELECT
		pr.NmCidade,pr.CdPref
		FROM tbsolcons sc 
		INNER JOIN tbpaciente p ON sc.CdPaciente=p.CdPaciente 
		INNER JOIN tbbairro b ON b.CdBairro=p.CdBairro 
		INNER JOIN tbprefeitura pr ON b.CdPref=pr.CdPref 
		INNER JOIN tbespecproc ep ON sc.CdEspecProc=ep.CdEspecProc
		INNER JOIN tbprocedimento Pa ON ep.CdProcedimento = Pa.CdProcedimento 
		LEFT JOIN tbagendacons ac ON sc.CdSolCons=ac.CdSolCons 
		LEFT JOIN tbfornecedor f ON ac.CdForn=f.CdForn 
		LEFT JOIN tbusuario u ON ac.CdUsuario = u.CdUsuario
		WHERE (sc.Status='1' OR sc.Status='2' OR ac.Status='1' OR ac.Status='2') $cid $forn
		AND (sc.dtrel BETWEEN  '$datai' AND '$dataf' or sc.dtcanc BETWEEN  '$datai' AND '$dataf' or ac.DtAgCons BETWEEN  '$datai' AND '$dataf'
		or sc.dtinc BETWEEN  '$datai' AND '$dataf')
		group by pr.NmCidade";
$sql = mysqli_query($db,$sql1) or die("Erro ao buscar municipios");

$cont = mysqli_num_rows($sql); //echo "cont ".$cont;
$tam = 400;
$tam += $cont * 20;

?>

<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Gráfico de Totais de procedimentos por período</title>

		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<script type="text/javascript">
$(function () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'container',
                type: 'bar'
            },
			exporting: {      
				width:2000    // or scale down to 100
			}, 			
			colors: ['#4572A7','#AA4643','#89A54E','#F0E400'],
            title: {
                text: 'Totais de procedimentos por período'
            },
            xAxis: {
                //categories: ['Apples', 'Oranges', 'Pears', 'Grapes', 'Bananas']
				categories: [<?php while($lcid = mysqli_fetch_array($sql)) {$cidade[] = $lcid[CdPref];echo "'".utf8_encode($lcid[NmCidade])."',";} ?>]
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Período: <?php echo $p[dtinicio]." à ".$p[dttermino].utf8_encode("$fornecedor $proced $especif"); ?>'
                }
            },
            legend: {
                backgroundColor: '#FFFFFF',
                reversed: true
            },
            tooltip: {
                formatter: function() {
                    return ''+
                        this.series.name +': '+ this.y +'';
                }
            },
            plotOptions: {
                series: {
                    stacking: 'normal',
					dataLabels: {
                        enabled: true,
						 align: 'center',
                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
                    }					
                }
            },
                series: [{
                name: 'Realizados',
                data: [<?php $i=0; while($i < $cont){
										$sql_ag = "SELECT Count(*) AS relizado,pr.NmCidade,pr.CdPref
													FROM tbsolcons sc INNER JOIN tbpaciente p ON sc.CdPaciente=p.CdPaciente INNER JOIN tbbairro b ON b.CdBairro=p.CdBairro 
													INNER JOIN tbprefeitura pr ON b.CdPref=pr.CdPref INNER JOIN tbespecproc ep ON sc.CdEspecProc=ep.CdEspecProc 
													INNER JOIN tbprocedimento Pa ON ep.CdProcedimento = Pa.CdProcedimento LEFT JOIN tbagendacons ac ON sc.CdSolCons=ac.CdSolCons 
													LEFT JOIN tbfornecedor f ON ac.CdForn=f.CdForn LEFT JOIN tbusuario u ON ac.CdUsuario = u.CdUsuario
													WHERE sc.Status='1' AND ac.Status='2' AND pr.CdPref = $cidade[$i] $forn $proc $espec
													AND LEFT (DtAgCons,10) BETWEEN '$datai' AND '$dataf'
													
													group by pr.CdPref
													";						
										//echo $sql_ag."\n";
										$ag = mysqli_query($db,$sql_ag) or die("Erro ao buscar agendas aguardando");
										$lag = mysqli_fetch_array($ag);
										mysqli_free_result($ag); 
										if(!empty($lag[relizado])&&($lag[CdPref] == $cidade[$i]))
											echo $lag[relizado].",";
										else
											echo "null,";
										$i++;
								   } 
					  ?>]
            }, {
                name: 'Cancelados',
                data: [<?php $i=0; while($i < $cont){
							$sql_ag = "SELECT Count(*) AS cancelado,pr.NmCidade,pr.CdPref
								FROM tbsolcons sc INNER JOIN tbpaciente p ON sc.CdPaciente=p.CdPaciente INNER JOIN tbbairro b ON b.CdBairro=p.CdBairro 
								INNER JOIN tbprefeitura pr ON b.CdPref=pr.CdPref INNER JOIN tbespecproc ep ON sc.CdEspecProc=ep.CdEspecProc 
								INNER JOIN tbprocedimento Pa ON ep.CdProcedimento = Pa.CdProcedimento LEFT JOIN tbagendacons ac ON sc.CdSolCons=ac.CdSolCons 
								LEFT JOIN tbfornecedor f ON ac.CdForn=f.CdForn LEFT JOIN tbusuario u ON ac.CdUsuario = u.CdUsuario
								WHERE ((sc.Status='2') or (sc.Status='2' and ac.Status=1))
								AND pr.CdPref = $cidade[$i]
								$forn
								$proc
								$espec
								AND LEFT (DtAgCons,10) BETWEEN '$datai' AND '$dataf'
								GROUP BY pr.CdPref";	
								
										//echo $sql_ag."\n";
										$ag = mysqli_query($db,$sql_ag) or die("Erro ao buscar agendas aguardando");
										$lag = mysqli_fetch_array($ag);
										mysqli_free_result($ag); 
										if(!empty($lag[cancelado])&&($lag[CdPref] == $cidade[$i]))
											echo $lag[cancelado].",";
										else
											echo "null,";
										$i++;
								   } 
					  ?>]
            }, {
                name: 'Marcados',
                data: [<?php $i=0; while($i < $cont){
							$sql_ag = "SELECT Count(*) AS marcado,pr.NmCidade,pr.CdPref
								FROM tbsolcons sc INNER JOIN tbpaciente p ON sc.CdPaciente = p.CdPaciente INNER JOIN tbbairro b ON b.CdBairro = p.CdBairro 								INNER JOIN tbprefeitura pr ON b.CdPref=pr.CdPref INNER JOIN tbespecproc ep ON sc.CdEspecProc=ep.CdEspecProc 
								INNER JOIN tbprocedimento Pa ON ep.CdProcedimento = Pa.CdProcedimento LEFT JOIN tbagendacons ac ON sc.CdSolCons=ac.CdSolCons 
								LEFT JOIN tbfornecedor f ON ac.CdForn=f.CdForn LEFT JOIN tbusuario u ON ac.CdUsuario = u.CdUsuario
								WHERE  sc.Status='1' AND ac.Status='1' AND pr.CdPref = $cidade[$i]
								$forn
								$proc
								$espec
								AND ac.DtAgCons BETWEEN  '$datai' AND '$dataf'
								
								group by pr.CdPref
													";						
										//echo $sql_ag."\n";
										$ag = mysqli_query($db,$sql_ag) or die("Erro ao buscar agendas aguardando");
										$lag = mysqli_fetch_array($ag);
										mysqli_free_result($ag);  
										if(!empty($lag[marcado])&&($lag[CdPref] == $cidade[$i]))
											echo $lag[marcado].",";
										else
											echo "null,";
										$i++;
								   } 
					  ?>]
		    }, {
                name: 'Aguardando',
                data: [<?php $i=0; while($i < $cont){
										$sql_ag = "SELECT Count(*) AS aguardando,pr.NmCidade,pr.CdPref
													FROM tbsolcons sc INNER JOIN tbpaciente p ON sc.CdPaciente=p.CdPaciente INNER JOIN tbbairro b ON b.CdBairro=p.CdBairro 
													INNER JOIN tbprefeitura pr ON b.CdPref=pr.CdPref INNER JOIN tbespecproc ep ON sc.CdEspecProc=ep.CdEspecProc 
													INNER JOIN tbprocedimento Pa ON ep.CdProcedimento = Pa.CdProcedimento LEFT JOIN tbagendacons ac ON sc.CdSolCons=ac.CdSolCons 
													LEFT JOIN tbfornecedor f ON ac.CdForn=f.CdForn LEFT JOIN tbusuario u ON ac.CdUsuario = u.CdUsuario
													WHERE sc.Status='1' AND ac.Status is NULL AND pr.CdPref = $cidade[$i] $forn $proc $espec
													AND LEFT (DtAgCons,10) BETWEEN '$datai' AND '$dataf'
													group by pr.CdPref
													";						
										//echo $sql_ag."\n";
										$ag = mysqli_query($db,$sql_ag) or die("Erro ao buscar agendas aguardando");
										$lag = mysqli_fetch_array($ag);
										mysqli_free_result($ag);  
										if(!empty($lag[aguardando])&&($lag[CdPref] == $cidade[$i]))
											echo $lag[aguardando].",";
										else
											echo "null,";
										$i++;
								   } 
					  ?>]
					  				
            }]
        });
    });
    
});
		</script>
	</head>
	<body>
<script src="js/highcharts.js"></script>
<script src="js/modules/exporting.js"></script>

<div id="container" style="min-width: 400px; height: <?php echo $tam; ?>px; margin: 0 auto"></div>

	</body>
</html>
<?php 
	mysqli_close();
?>
