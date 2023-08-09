<?php
if($_GET['e']==ok)
	{
		
		
	function upload($arquivo,$caminho){
	if(!(empty($arquivo))){
		$arquivo1 = $arquivo;
		$arquivo_minusculo = strtolower($arquivo1['name']);
		$caracteres = array("ç","~","^","]","[","{","}",";",":","´",",",">","<","-","/","|","@","$","%","ã","â","á","à","é","è","ó","ò","+","=","*","&","(",")","!","#","?","`","ã"," ","©");
		$arquivo_tratado = str_replace($caracteres,"",$arquivo_minusculo);
		$numero = rand(0,100);
		$destino = $caminho."/".$numero.$arquivo_tratado;
		
		if(move_uploaded_file($arquivo1['tmp_name'],$destino)){
			$sql = mysqli_query($db,"UPDATE `tbpaciente` SET `foto`='$destino' WHERE (`CdPaciente`='$_GET[CdPaciente]')");
			echo '<script language="JavaScript" type="text/javascript"> 
					alert("Foto Alterada com sucesso!");
				  </script>';
				  echo "<meta HTTP-EQUIV='refresh' CONTENT='2'>";
		}else{
			
		}
	}
}
	
	$foto = $_FILES['foto'];
	$caminho = "img/fotos";
	upload($foto,$caminho);

}
   
  	
?>

<h1> Upload Foto  </span> </h1>

<form action="?i=<?php echo $cdsubitem ?>&met=p&CdPaciente=<?php echo $CdPaciente ?>&op1=foto&e=ok" method="post"  enctype="multipart/form-data">
<label>  <input type="file" name="foto"> </label>
<div id="btns"> <input type="submit" value="Salvar "></div>

</form>