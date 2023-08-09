<?php

// A session is required

require "../vendor/autoload.php";
require "controle/chat/jwt-generate.php";
if (!session_id()) {
    @session_start();
}

$_SESSION['first_access'] = false;

// Instantiate the class
$msg = new \Plasticbrain\FlashMessages\FlashMessages();

// Add a few messages

// Display the messages
//$msg->display();

require_once "conecta.php";
function usuarioMultiGrupos($cdusuario)
{
    include "conecta.php";
    $query = "SELECT tbmultigrupo.cdgrusuario FROM tbmultigrupo WHERE tbmultigrupo.CdUsuario = $cdusuario";
    ($result = mysqli_query($GLOBALS['db'], $query)) or
        die('Erro ao listar: ' . mysqli_error());
    if (mysqli_num_rows($result) > 0) {
        while ($dado = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $dados[] = $dado[cdgrusuario];
        }
        return $dados;
    } else {
        return 0;
    }
}
// Recupera o login
$login = isset($_POST["usuario"]) ? addslashes(trim($_POST["usuario"])) : false;
// Recupera a senha, a criptografando em MD5
$senha = isset($_POST["senha"]) ? md5(trim($_POST["senha"])) : false;

$CdTpUsuario = isset($_POST["tipo"]) ? addslashes(trim($_POST["tipo"])) : false;

$SQL_Usuario = "SELECT *
				FROM tbusuario
				WHERE Login = '$login' AND Senha = '$senha' AND Status=1";
if ($CdTpUsuario) {
    $SQL_Usuario = "SELECT *
				FROM tbusuario
				WHERE CdTpUsuario = $CdTpUsuario AND Status=1 
				LIMIT 1";
}
//echo $SQL_Usuario;
($result = @mysqli_query($GLOBALS['db'], $SQL_Usuario)) or die(mysqli_error());

$Total = @mysqli_num_rows($result);

if ($Total == 1) {
    $dados = mysqli_fetch_array($result);

    $multigrupos = usuarioMultiGrupos((int) $dados['CdUsuario']);
    if ($multigrupos) {
        array_push($multigrupos, $dados["cdgrusuario"]);
    } else {
        $multigrupos = [0 => $dados["cdgrusuario"]];
    }

    $_SESSION["CdUsuario"] = (int) $dados["CdUsuario"];
    $_SESSION["NmUsuario"] = $dados["NmUsuario"];
    $_SESSION["CdTpUsuario"] = (int) $dados["CdTpUsuario"];
    $_SESSION["CdOrigem"] = (int) $dados["CdOrigem"];
    $_SESSION["cdfornecedor"] = (int) $dados["cdfornecedor"];
    $_SESSION["Login"] = $dados["Login"];
    $_SESSION["cdgrusuario"] = $dados["cdgrusuario"];
    $_SESSION["Email"] = $dados["Email"] ? $dados["Email"] : "";
    $_SESSION["Telefone"] = $dados["Telefone"];
    $_SESSION["Celular"] = $dados["Celular"];
    $_SESSION["Responsavel"] = $dados["Responsavel"];
    $_SESSION["Multigrupos"] = $multigrupos;
    $_SESSION["cdprofissional"] = (int) $dados["cdprof"];
    $_SESSION["token"] = jwtCreate(
        [
            'cdCliente' => CLIENTE, 
            'cdUsuario' => (int) $dados["CdUsuario"]
        ],
        'cdfec2c2d16772816f70fa345c1ffa3a8c19b651dce9bb23fd88481531c63c99'
    );
    



    if (!$dados["Email"]) $_SESSION['first_access'] = true;

    header("Location: index.php?p=inicial");
       
    
} else {
    $msg->error('Credenciais incorretas', 'frm_login.php');
}

?>
