<?php
/**
 * @author Wellington Ribeiro - IdealMind.com.br
 * @since 31/10/2009
 */
 

 
$hostname = 'mysql09.sitcon.com.br';
$username = 'sitcon8';
$password = 'dat*03*ICOMEP*';
$dbname = 'sitcon8';
	
mysqli_connect( $hostname, $username, $password ) or die ( 'Erro ao tentar conectar ao banco de dados.' );
mysqli_select_db( $dbname );

$q = mysqli_real_escape_string( $_GET['q'] );

$sql = "SELECT * FROM tbprofissional where locate('$q',nmprof) > 0 order by locate('$q',nmprof) limit 10";

$res = mysqli_query($db, $sql );

while( $campo = mysqli_fetch_array( $res ) )
{
	//echo "Id: {$campo['id']}\t{$campo['sigla']}\t{$campo['estado']}<br />";
	$nome = $campo['nmprofissional'];
	$cns = $campo['cnsprof'];
	$html = preg_replace("/(" . $q . ")/i", "<span style=\"font-weight:bold\">\$1</span>", $nome);
	echo "<li onselect=\"this.setText('$nome').setValue('$id','$nome','$cns');\">$html ($cns)</li>\n";
}

?>