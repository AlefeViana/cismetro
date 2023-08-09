<?php

    define("DIRECT_ACCESS", true);

    include("verifica.php");

   //verifica se o usuario tem permiss�o para acessar a pagina
   if ((int)$_SESSION["CdTpUsuario"] != 7)	
   {
		echo '<script language="JavaScript" type="text/javascript"> 
			window.location.href="index.php?p=login";				
		  </script>';	
   }
?>
<div class="nav">
                <ul id="nav">
                 
                    <li>Controle
                        <ul>
                            <li><a href="index.php?p=lista_pac">Laudos Oftalmo</a></li>                   
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