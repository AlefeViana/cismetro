<?php

    define("DIRECT_ACCESS", true);

    include("verifica.php");
    
   //verifica se o usuario tem permiss�o para acessar a pagina
   if ((int)$_SESSION["CdTpUsuario"] != 3 && (int)$_SESSION["CdTpUsuario"] != 4)	
   {
		echo '<script language="JavaScript" type="text/javascript"> 
			window.location.href="index.php?p=login";				
		  </script>';	
   }
?>
<div class="nav">
                <ul id="nav">
                 
                    <li>Cadastro
                        <ul>
                            <li><a href="index.php?p=lista_pac">Paciente</a></li>
                            <li><a href="index.php?p=lista_bairro">Bairro</a></li>
                        </ul>
                    </li>
                 
                    <li>Controle
                        <ul>
                            <li><a href="index.php?p=lista_solagd">Requisitar Consulta</a></li>               
                        </ul>
                    </li>
                 
                    <li>Relat&oacute;rio
                        <ul>
                            <li><a href="admin/rel_agd.php">Consultas</a></li>     
                            <li><a href="admin/rel_consulta_bairro.php">Estatisticas bairro</a></li>   
                            <li><a href="admin/rel_fornecedor.php">Fornecedor</a></li>                                                     
                        </ul>
                    </li>
                    <li>Manuten&ccedil;&atilde;o   
                        <ul>
                        	<li><a href="index.php?p=frm_trsenha">Trocar Senha</a></li>
                            <li><a href="login_sai.php">Sair</a></li>
                 		</ul>
                    </li>    
                </ul>			
	</div><!-- end .nav -->