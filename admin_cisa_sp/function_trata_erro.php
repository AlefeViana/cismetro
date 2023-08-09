<?php

function TrataErro($num_erro,$campo,$link,$msg){
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
	echo '<script language="JavaScript" type="text/javascript"> ';
	switch($num_erro){
		case 1062: 	echo "alert('Você está tentando entrar com um campo já existente nessa tabela. Nome do Campo: $campo');";
					echo "window.location.href='$link'";	 	
					echo '</script>';	 
					break;	 
		default: echo "alert('Ocorreu um erro de numero: $num_erro, $msg. Tente executar novamente a tarefa e se o erro persistir, contate o administrador do sistema informando essa mensagem. Copie essa mensagem!');
						 window.location.href='$link'";
					echo '</script>';	 
					break;	 
	}
}

?>