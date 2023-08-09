<?php

define("DIRECT_ACCESS", true);

//verifica se o usuario esta logado no sistema
require_once("verifica.php");
require_once("../conecta.php");

//funcao para tratar erro
require("function_trata_erro.php");
//funcao para formatar data para formato americano
function FData($data){
	$val = explode("/",$data);
	return $val[2]."-".$val[1]."-".$val[0];	
}
//recebe a variavel caso chamado da solicitacao
$PagDestino = (int)$_GET["pg"];
//recebe o tipo de acao
$acao       = "Publicar";
function upload($arquivo,$caminho){
	if(!(empty($arquivo))){
		$arquivo1 = $arquivo;
		$arquivo_minusculo = strtolower($arquivo1['name']);
		$caracteres = array("ç","~","^","]","[","{","}",";",":","´",",",">","<","-","/","|","@","$","%","ã","â","á","à","é","è","ó","ò","+","=","*","&","(",")","!","#","?","`","ã"," ","©");
		$arquivo_tratado = str_replace($caracteres,"",$arquivo_minusculo);
		$numero = rand(0,100);
		$destino = $caminho."/".$numero.$arquivo_tratado;
		}
		
		if(move_uploaded_file($arquivo1['tmp_name'],$destino)){
			return $destino;
		}
	}
    
    function upload_imagem($input,$caminho,$largura,$altura,$tamanho)
	{
	   
		$erro = $config = array();
	
		// Prepara a variável do arquivo
		$arquivo = $input;
			// Tamanho máximo do arquivo (em bytes)
		$config["tamanho"] = $tamanho;
		// Largura máxima (pixels)
		$config["largura"] = $largura;
		// Altura máxima (pixels)
		$config["altura"]  = $altura;
        

		// Formulário postado... executa as ações
		if($arquivo)
		{  
		  
			if ($arquivo["name"]=="")
			{
				return "";
			}
			else
			{
				// Verifica se o mime-type do arquivo é de imagem
				// $acceptedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
			    // if (!in_array($arquivo["type"],$acceptedExtensions))
			    // {
			    //     $erro[] = "Arquivo em formato inválido!<br>Formatos válidos .jpeg, .gif, .png, .jpg, .bmp - ";
				// }
				if(1==2){

				}
			    else
			    {
			        // Verifica tamanho do arquivo
			        if($arquivo["size"] > $config["tamanho"])
			        {
			            $erro[] = "Tamanho";
			        }
			        
			        // Para verificar as dimensões da imagem
			        $tamanhos = getimagesize($arquivo["tmp_name"]);
			        
			        // Verifica largura
			        if($tamanhos[0] > $config["largura"])
			        {
			            $erro[] = "Largura";
			        }
			
			        // Verifica altura
			        if($tamanhos[0] > $config["altura"])
			        {
			            $erro[] = "Altura";
			        }
			    }
			    
			    // Imprime as mensagens de erro
			    if(sizeof($erro))
			    {
			    	$erro_texto="";
			        foreach($erro as $err)
			        {
			            $erro_texto=$erro_texto." - ".$err."<BR>";
			        }
					return "ERRO $erro_texto";
			    }	
			    // Verificação de dados OK, nenhum erro ocorrido, executa então o upload...
			    else
			    {
			        // Pega extensão do arquivo
			        preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $arquivo["name"], $ext);
			
			        // Gera um nome único para a imagem
			        $imagem_nome = md5(uniqid(time())) . "." . $ext[1];
			
			        // Caminho de onde a imagem ficará
			        $imagem_dir = "$caminho/" . $imagem_nome;
			
			        // Faz o upload da imagem
			        move_uploaded_file($arquivo["tmp_name"], $imagem_dir);
					return $imagem_dir;
			        
			    }
			}
		}
		else
		{
			return "";
		}
	
	}


		require("../conecta.php");
     // FAZ O CADASTRO   
     if($acao == "Publicar"){
            
            
            // CARREGA VALORES DO FORMULARIO
            
            $cdnoticia = $_POST["cdnoticia"];
            $titulo = $_POST["titulo"];
            $autor = $_POST["autor"];
            $corpo = $_POST["corpo"];
            $arquivo1=$_FILES["arquivo1"];
            $arquivo2=$_FILES["arquivo2"];
            $arquivo3=$_FILES["arquivo3"];
            $data = date('Y-m-d');
            $hora= date("H:i:s");
            
            // FAZ O UPALOAD DOS ARQUIVOS
            
            if(!(empty($arquivo1)))
            {
                $foto = $_FILES['foto'];
                $caminho = "../Arquivos";
    	        $destino1 = upload($arquivo1,$caminho);
            }
            if(!(empty($arquivo2))){
                $foto = $_FILES['foto'];
                $caminho = "../Arquivos";
                $destino2 = upload($arquivo2,$caminho);
            }
            if(!(empty($arquivo3)))
            {
            	$caminho = "../Arquivos";
            	$destino3 = upload($arquivo3,$caminho);
            }
            
            
            $foto = $_FILES['foto'];
            $caminho = "../img";
            $destino = upload_imagem($foto,$caminho,"900","900","10000000");
            
            if (($destino=="ERRO  - Altura") || ($destino=="ERRO  - Largura") || ($destino=="ERRO  - Tamanho"))
            {
                    if ($destino=="ERRO  - Altura")
                    {
                        echo '<script language="JavaScript" type="text/javascript"> 
        				alert("A Altura da Imagem exece o Máximo de 900 Pixels");
        				window.location.href="../index.php?i=48";
        			  </script>';
                    }
                    if ($destino=="ERRO  - Largura")
                    {
                        echo '<script language="JavaScript" type="text/javascript"> 
        				alert("A Largura da Imagem exece o Máximo de 900 Pixels");
        				window.location.href="../index.php?i=48";
        			  </script>';
                    }
                    if ($destino=="ERRO  - Tamanho")
                    {
                        echo '<script language="JavaScript" type="text/javascript"> 
        				alert("Tamanho da Imagem Excede o Tamanho Máximo de 1MB");
        				window.location.href="../index.php?i=48";
        			  </script>';
                    }
             }
        else
        {
        //gera um novo codigo
		/* $qry = mysqli_query($db,"SELECT MAX(cdnoticia) FROM tbnoticia") 
				or die(TrataErro(mysqli_errno(),'','../index.php?p=frm_cadpac','regn_pac:gerar novo codigo'));
		$CdPaciente = mysqli_result($qry,0) + 1; */
		$sql = "INSERT INTO `tbnoticia` (`titulo`, `corpo`, `data`, `hora`, `autor`, `foto`,`arquivo1`, `arquivo2`, `arquivo3`) VALUES ('$titulo', '$corpo', '$data', '$hora', '$autor', '$destino', '$destino1', '$destino2', '$destino3')";	
		$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','../index.php?p=frm_cadpac','regn_pac:insert pac'));
        //verifica a pagina de destino
    		if ($PagDestino == 1)
            {
    		 	echo '<script language="JavaScript" type="text/javascript"> 
    				alert("Cadastro realizado com sucesso!");
    				window.location.href="../index.php?i=48";
    			  </script>';
    		}
    		else
    		{
    			 echo '<script language="JavaScript" type="text/javascript"> 
    					alert("Noticia Publicada com sucesso!");
    					window.location.href="../index.php?i=48";
    				  </script>'; 
    			
    		}
		}
  }
        
        //FAZ A EDIÇÃO
        
	 else
	{
		if($acao == "edit")
		{
			//alterar
			$sql = "UPDATE tbnoticia
						SET titulo = $titulo,
                        corpo = $corpo,
                        autor = $autor
                        WHERE cdnoticia=$cdnoticia";
			
			require("../conecta.php");
			$qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','../index.php?p=lista_pac','regn_pac:update pac'));
			echo '<script language="JavaScript" type="text/javascript"> 
				alert("Dados alterados com sucesso!");
				window.location.href="../index.php?i=1";				
			  </script>';
		}
        
        // DELETA O REGISTRO
		else
		{
			if($acao == "del")
			{
				//excluir	
				require("../conecta.php");
				$qry = mysqli_query($db,"SELECT cdnoticia FROM tbnoticia WHERE cdnoticia=$cdnoticia")
						or die ("");
						//or die (TrataErro(mysqli_errno(),'','../index.php?p=lista_pac','regn_pac:vinculo pac solicitacao'));
				if (mysqli_num_rows($qry) == 0){
					
					$sql = "DELETE FROM tbnoticia WHERE cdnoticia=$cdnoticia";	
					$qry = mysqli_query($db,$sql) or die(TrataErro(mysqli_errno(),'','../index.php?i=1',''));
					echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						 <script language="JavaScript" type="text/javascript"> 
							alert("Notícia excluída com sucesso!");
							window.location.href="../index.php?i=1";				
			 			 </script>';
				}
				else
				{
					echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						<script language="JavaScript" type="text/javascript"> 
							alert("Notícia não pode ser excluída, entre em contato com Administrador!");
							window.location.href="../index.php?i=1";				
			 			 </script>';
				}
			}
			
		}
	}
	
	@mysqli_close();
	@mysqli_free_result($qry);
    
 ?>   				