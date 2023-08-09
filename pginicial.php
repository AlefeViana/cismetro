<style> 
h4 { padding: 10px; color:#004080; }
</style>
<?php  ?>
<?php //include 'banners/banners.php';?>
<?php
if (isset($_GET['m1'])) {
	    mysqli_query($db,"UPDATE tbnotificaconf SET status='0' WHERE cdforn = '$_GET[m1]' ");
	    echo '<script language="JavaScript" type="text/javascript"> 
			window.location.href="index.php?i=";				
		  </script>';	
}
	if($_GET['t']!=1)
	{
   		echo '<div id="inicial_painel">
   		<div id="inicial_titulo">Painel de Controle</div>
   		<div id="inicial_imagem"><img src="img/panel_inicial.png" height="134" width="220"></div>';
   		
        ?>
   	<!-- </div> -->
  </div>

  
  <?php  
  	$sql = (" SELECT *
	FROM tbnoticia ");  //Consultas as notícias a serem exibidas
	$limsql = $sql;
	$sql = mysqli_query($db,$sql);
		#############INICIO DA PAGINAÇÃO#############2/10/2014
		//obtem o numero de linhas da consulta
			$qtdreg = mysqli_num_rows($sql);
			//echo "Registros ".$qtdreg;
		
		// Especifique quantos resultados voc? quer por p?gina
			$lpp = 3;
		
		// Retorna o total de p?ginas
			$pags = ceil($qtdreg / $lpp);
		
		// Especifica uma valor para variavel pagina caso a mesma n?o esteja setada
			if(!isset($_GET["pag"])) {
				 $pag = 0;
			}else{
				 $pag = (int)$_GET["pag"];
			}
		// Retorna qual será a primeira linha a ser mostrada no MySQL
			$inicio = $pag * $lpp;

		// Executa a query no MySQL com o limite de linhas.
		$limsql .= "ORDER BY tbnoticia.`data` DESC, tbnoticia.hora DESC LIMIT $inicio, $lpp";
		$query = mysqli_query($GLOBALS['db'],$limsql)or die(mysqli_error());			
				
    echo"<h4>ÚLTIMAS NOTÍCIAS </h4>";
	##############EXIBE AS NOTÍCIAS################
    if(mysqli_num_rows($query)>0)
    {
			 while( $lin = mysqli_fetch_array($query))
		 		{
					$data = FormataDataBr($lin['data']);
       				 echo "<div id='not' style='border-bottom: silver dashed 1px; padding:5px; margin-bottom:10px;'> 
	         	   <li style='font-size: 15PX; font-weight: bold; color: #C4C4C4; '> $data  - $lin[hora], Por: ".($lin['autor'])."  </li>
    	     	   <li style='font-size: 15PX; font-weight: bold; color: blue;'> <a href='?i=&t=1&cd=$lin[cdnoticia]'> ".($lin['titulo'])." </a> </li>
   		  	   </div>";
        				
				}
	
				echo "<div id='paginacao'>";
				 $busca = rawurlencode($busca);		
				// $param = "&i=4&s=l&pesq=$busca&cbopesq=$cbopor#h1";
					$param = "?i=#";
				if ($pags > 1)
				 // echo "<br><br>Ver p&aacute;gina:&nbsp;";
				 if($pag > 0) {
					  $menos = $pag - 1;
					  $url = "$PHP_SELF?pag=$menos".$param;
					  $url2 = "$PHP_SELF?pag=0".$param;
					  echo '<a href='.$url2.'>P&aacute;gina 1</a>&nbsp;<a href='.$url.'>&laquo; Anterior</a>'; // Vai para a página anterior
				 }
				 
								 
				 if ($pags > 1){
					$i = $pag;
	
					if($i > 10){
						$i = $pag-10;
						if($pag < ($pags-19))
							$cont = $pag+19;
						else $cont = $pags;
					}else
					if($pag < ($pags-19)){
						$i=0; $cont = $pag+19;
						if($pag < 10 && $pag < ($pags-30))	$cont = 30;	
					}						 
					else {$i = 0; $cont = $pags;}		 
					 
					 for($i;$i<$cont;$i++) { // Gera um loop com o link para as p?ginas
							$url = "$PHP_SELF?pag=$i".$param;
							$j = $i + 1;
							if ($pag == $i)
								echo " <span>$j</span>";
							else
								echo " <a href=".$url.">$j</a>";
					 }
				 }
			 if($pag < ($pags - 1)) {
					$mais = $pag + 1;
					$url = "$PHP_SELF?pag=$mais".$param;
					echo ' <a href='.$url.'>Pr&oacute;xima &raquo;</a></center>';
			 }#############FIM DA PAGINAÇÃO#############
			echo "</div>";

	} else { echo "Nenhuma notícia publicada!";}
    ?>
    
    
    
<?php 
	}
	else {
		$cd = $_GET['cd'];
		$sql2 = mysqli_query($db," SELECT * FROM tbnoticia WHERE tbnoticia.cdnoticia='$cd' "); 
		$lin2 = mysqli_fetch_array($sql2);
		$data = FormataDataBr($lin2[data]);
		$hora = $lin2[hora];
		
		
		$arquivo1 = $lin2[arquivo1];
		$arquivo2 = $lin2[arquivo2];
		$arquivo3 = $lin2[arquivo3];
		
		
		$arquivo1 = explode('/',$arquivo1);
		$arquivo1 = $arquivo1[1].'/'.$arquivo1[2];
	
		$arquivo2 = explode('/',$arquivo2);
		$arquivo2 = $arquivo2[1].'/'.$arquivo2[2];

		
		$foto =  $lin2[foto];
        $foto = explode('/',$foto);
        $foto = $foto[2];
		
        
		
		?>
		 <style type="text/css"> 
			#gallery  {  text-align:justify; line-height:20px;  font-size:15px; }
			#gallery 	img { float:left; margin-right:10px; margin-bottom:20px; border:#EDEDED solid 2px; }
			#gallery img:hover { border:#990000 solid 2px; }
			#gallery h1 { font-size:20px; border-bottom:none; background: white; margin-bottom: 2px; font-weight: bold;}
			#gallery h3 { margin-left:0px; margin-top:-10px; }
			
			#vtb { clear:both; margin-top:80px;}
			#vtb li { padding:10px;}
			#vtb li:hover { background:#EDEDED;}
        </style> 
    
   <div id="gallery"> 
  <h1> <?php echo $lin2[titulo] ?> <span> <a href='javascript:history.back(1)'> &laquo;  Voltar </a></span></h1>
  <h3> <?php echo $data.'  '.$hora ?> , por <?php echo $lin2[autor] ?> </h3>
  <div style="width:100%">
  <?php 
  if ($lin2["foto"]!= "")
  {
	 
	  echo "<img src=img/$foto />";  
  }
  ?>
  </div>
	<?php echo $lin2[corpo]; ?> 
    <?php
    if (($lin2["arquivo1"] <> "") || ($lin2["arquivo1"] <> "") || ($lin2["arquivo1"] <> ""))
    {
    echo "<h4>Downloads Disponíveis</h4>";
    if ($lin2["arquivo1"] <> "")
    {
        echo "<a href='$arquivo1' target='_blank'> <img src=img/btndownload.png /> </a> ";
    }
    if ($lin2["arquivo2"] <> "")
    {
        echo "<a href='$arquivo2' target='_blank'><img src=img/btndownload.png /></a> ";
    } 
    
    if ($lin2["arquivo3"] <> "")
    {
        echo "<a href='$arquivo3' target='_blank'><img src=img/btndownload.png /></a>";
    } 
        
    }
    ?>    	
    </div>


 <br clear="all">
  </div>       

        
<?php 
	}

?>
