<?php
	session_start();
	if (!isset($_SESSION["CdUsuario"],$_SESSION["NmUsuario"],$_SESSION["CdTpUsuario"]))
	{
		echo '<script language="JavaScript" type="text/javascript"> 
				window.location.href="../index.php?p=frm_login";				
			  </script>';
	}
?>