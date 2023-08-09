<?php

// A session is required

require "../vendor/autoload.php";

include "conecta.php";
require "./functions.php";

if (!session_id()) {
    @session_start();
}

if(isset($_POST["municipio"]) && $_POST["municipio"] > 0){
    $municipio = $_POST["municipio"];
    mysqli_query($GLOBALS['db'], "UPDATE tbusuario SET CdOrigem = $municipio WHERE CdUsuario = 1603");
    
}else{

    $_SESSION['first_access'] = false;

    // Instantiate the class
    $msg = new \Plasticbrain\FlashMessages\FlashMessages();
    
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
    $result = mysqli_query($GLOBALS['db'], $SQL_Usuario) or die(mysqli_error($db));
    
    $correct_credentials = mysqli_num_rows($result);
    
    if ($correct_credentials) {
        $dados = mysqli_fetch_array($result);
    
        $userId = $dados['CdUsuario'];
       
        $sessions = getUserSessions($db,$userId);
    
        if(count($sessions) >= 1){
            
            //destroyAuth($db, $userId);
            clearDatabaseSession($db, $userId);
        }
     
        initAuth($db, $userId);
        
    
        if (!$dados["Email"]) $_SESSION['first_access'] = true;
    
    
        header("Location: index.php?p=inicial");
           
        
    } else {
        $msg->error('Credenciais incorretas', 'frm_login.php');
    }
        
}
