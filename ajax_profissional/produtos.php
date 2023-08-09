<?php 
$con = mysqli_connect("mysql09.sitcon.com.br","sitcon8","dat*03*ICOMEP*");
if(!$con){die("Problema na conexão com o banco de dados"); }

// STEP 1: INCLUDING AJAX AGENT FRAMEWORK/LIBRARY
include_once("agent.php");
?>

<html>
<head>
<title>@PRDOTUOS@</title>
<?php
	$agent->init(); 
?>
</head>
<body>
<script language="JavaScript">
	/**
	*	Função que é executada sempre que uma tecla é digitada dentro do campo do formulário.
	*	Está função também é executada assim que a página é carregada, para listagem completa dos produtos.
	* 	os parametros passados sao: funcado do php chamada,funcao javascript que é executada, e valor passado para a funcao php.
	*/
	function call_listaProdutos(){
		var letra = document.getElementById('nome').value;
		agent.call('','listaProdutos','callback_listaProdutos',letra);
		document.getElementById('listaDeProdutos').innerHTML = "pesquisando....";
	}
	
	/**
	* Funcao que recebe o return da funcao php. valores podem ser tratados
	*/
	function callback_listaProdutos(str) {
		document.getElementById('listaDeProdutos').innerHTML = str;
	}
 </script>


<form name= formulario method="post">
<?php
	$sql = "SELECT * FROM tbprofissional";
  	$query = mysqli_db_query('sitcon8',$sql,$con);
?>
Digite um nome:
<input type="text" name="nome" id="nome" onkeyup="call_listaProdutos();">
</form>
<div id="carregando"></div>
<div id=listaDeProdutos></div>


</body>
</html>

<?php 
  // Funcao php que recebe a letra.
  // a forma mais correta seria retornar um array para a funcao callback_listaProdutos, mas fiz de uma forma simplificada
  // o unico caso em que a funcao callback_listaProdutos recebe um valor, nesse caso, eh quando não é encontrado produtos.
function listaProdutos($letra){

	//a conexao precisa ser criada novamente pois a forma com que o java script chama essa funcao eh como se ela fosse uma página externa, 
	$con = mysqli_connect("mysql09.sitcon.com.br","sitcon8","dat*03*ICOMEP*");
	if(!$con){die("Problema na conexão com o banco de dados"); }
	
	$sql = "SELECT cdprof,nmprof FROM tbprofissional where nome like  '%$letra%'";
	$query = mysqli_db_query('sitcon8',$sql,$con);

	

	if(mysqli_num_rows($query)>0){
		if(empty($letra)){
			$letra = "<b>TODOS</b>";
		}
		echo "Pesquisa realizada: ".$letra."<br>";
		echo "Total de registros <b>".mysqli_num_rows($query)."</b>";
		
		echo "<table border =1>";
		echo "<tr><td>Id</td><td>Nome</td></tr>";
		$i=0;
		
		while ($row = mysqli_fetch_array($query) ){
			echo "<tr><td>" . $row['id']."</td><td>".$row['nome'] ."</td></tr>";
		}
		echo "</table>";
		echo "<br>Query = ".$sql;
	}else{
		return "Nenhum registro Localizado";
	}
}
?>
<script>
call_listaProdutos();
</script>