<?php 

function curlExec($url, $post = NULL, array $header = array()){

    //Inicia o cURL
    $ch = curl_init($url);
    
    //Pede o que retorne o resultado como string
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    //Envia cabeçalhos (Caso tenha)
    if(count($header) > 0) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    }
    
    //Envia post (Caso tenha)
    if($post !== null) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    }
    
    //Ignora certificado SSL
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    //Manda executar a requisição
    $data = curl_exec($ch);
    
    //Fecha a conexão para economizar recursos do servidor
    curl_close($ch);
    
    //Retorna o resultado da requisição
    
    return $data;
    }
    function limpa($valor){
        $valor = trim($valor);
        $valor = str_replace(".", "", $valor);
        $valor = str_replace(",", "", $valor);
        $valor = str_replace("-", "", $valor);
        $valor = str_replace("/", "", $valor);
        $valor = str_replace("(", "", $valor);
        $valor = str_replace(")", "", $valor);
        return $valor;
       }

    require_once("conecta.php");
    
    $cnpj = $_POST['cnpj'];
    $cnpj = limpa($cnpj);
    $Email = $_POST['email'];
    //echo 'CNPJ: '.$cnpj; die();
    $teste = curlExec('http://receitaws.com.br/v1/cnpj/'.$cnpj);
    //var_dump(!filter_var($Email, FILTER_VALIDATE_EMAIL)); die();
    $obj = json_decode($teste);
    //var_dump($obj); die();
    //busca a atividade principal
    if($obj->status == 'ERROR' OR $obj->status == null){
        echo '<script type="text/javascript">
                 alert("Esse CNPJ é inválido!");
                 window.location.href="login_credenciando.php";
              </script>';
    }elseif(!filter_var($Email, FILTER_VALIDATE_EMAIL))
    {
        echo '<script type="text/javascript">
                         alert("Esse EMAIL é inválido!");
                         window.location.href="login_credenciando.php";
                      </script>';
    }
    else{
        
        $sql = mysqli_query($GLOBALS['db'],"SELECT f.CdForn from tbfornecedor f WHERE f.CNPJ = $cnpj");
        if(mysqli_num_rows($sql) > 0){
            $dados = mysqli_fetch_array($sql);
            $cdforn = $dados['CdForn'];
    
            echo '<script type="text/javascript">
                         alert("Esse CNPJ já possui acesso!");
                         window.location.href="login_credenciando.php";
                      </script>';
        }else{
         
            //E-MAIL
            define('MAILUSER','chamado@sitcon.com.br');
            define('MAILPASS','Chamado@sitcon2022');
            define('MAILPORT','465');
            define('MAILHOST','email-ssl.com.br');
            //define('MAILHOST','smtpi.iconsorciosaude18.com.br');
            define('MAILHOST','smtp.sitcon.com.br');
            define('MAILNAME','Acesso para credenciamento');
            define('MAILGASSUNTO1','USUÝRIO DE CREDENCIAMENTO');
            define('MAILGASSUNTO2','USERCREDENCIAMENTO');
            define('MAILSHTML','/n/n/n -- /n Atenciosamente, /n Sitcon Tecnologia da Informação/n _____________________________/n Favor não responder esse e-mail.');
            define('MAILHTML','<br/><br/><br/> -- <br/> Atenciosamente, <br/> Sitcon Tecnologia da Informação<br/><br/><br/>_____________________________<br/> Favor não responder esse e-mail.');
            define('MAILGASSUNTOREC','RECUPERAÇÃO DE SENHA');
            
            require ('packs/phpmailer/PHPMailerAutoload.php');
            $mail = new PHPMailer();
    
            $mail->IsSMTP();
            $mail->Host 	= MAILHOST; 
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'ssl';
            $mail->Port 	= MAILPORT; 
            $mail->Username = MAILUSER; 
            
            $mail->Password = MAILPASS; 
    
            $mail->From 	= MAILUSER; 
            $mail->FromName = MAILNAME;
    
            $mail->AddAddress($Email);
            $mail->AddBCC(MAILUSER, MAILNAME);
    
            $mail->IsHTML(true); 
            $mail->CharSet 	= 'UTF-8'; 
    
            $mail->Subject  = ' Credenciamento - Confirmação de E-mail'; 
            $mail->isHTML(true);
            $mail->AddEmbeddedImage('./pg/credenciamento/icons_email/youtube_icon.png', 'youtube_icon.png');          
            $mail->AddEmbeddedImage('./pg/credenciamento/icons_email/whatsapp_icon.png', 'whatsapp_icon.png');        
            $mail->AddEmbeddedImage('./pg/credenciamento/icons_email/facebook_icon.png', 'facebook_icon.png');
            $mail->AddEmbeddedImage('./pg/credenciamento/icons_email/instagram_icon.png', 'instagram_icon.png');
            $mail->AddEmbeddedImage('./pg/credenciamento/icons_email/www_icon.png', 'www_icon.png');
            $mail->AddEmbeddedImage('./pg/credenciamento/icons_email/cabecalho_conf.png', 'cabecalho');
            $mail->Body 	= '
            <!DOCTYPE html>
                <html lang="pt-br">
                <head>
                <meta charset="UTF-8"/>
                <title>Confirmação</title>
                <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
                <style>
                    td#redes{
                    font-size: 10pt;
                    font-family: "Arial", sans-serif;
                    color: #ffffff;
                    }
                    td#confirmacao,	td#link{
                    font-size: 12pt;
                    font-family: "Arial", sans-serif;
                    color: #ffffff;
                    }
                </style>
                </head>
                </html>
                <body style="margin: 0; padding: 0;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                <td>
                    <table style="border: 1px solid #cccccc;" align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border-collapse: collapse;">
                <tr>
                <td align="center" bgcolor="#ffffff" style="padding: 40px 0 30px 0;">
                <img src="cid:cabecalho" width="400" height="220" style="display: block;" />
                </td>
                </tr>
                <tr>
                <td bgcolor="#fe7800" style="padding: 40px 30px 40px 30px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                <td id="confirmacao" align="justify" style="padding: 0px 0 30px 0;">
                    Para finalizar seu cadastro no processo de credenciamento do CISMETRO, clique no link a seguir :
                </td>
                </tr>
                <tr>
                <td id="link">
                    <a href="http://cismetro.sitcon.com.br/cismetro/login_conf_cred.php?c='.$cnpj.'&e='.$Email.'"> Clique aqui para confirmar sua inscrição!</a>
                </td>
                </tr>
                </table>
                </td>
                </tr>
                <tr>
                <td align="right" bgcolor="#2c5f96" style="padding: 30px 30px 30px 30px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                <td id="redes">
                Nos siga nas redes sociais para se manter por dentro das informações: 
                </td>
                <td>
                <a href="http://www.sitconsistemas.com.br/" target="_blank">
                    <img src="cid:www_icon.png" alt="Site" width="38" height="38" style="display: block;" border="0" />
                    </a>
                </td>
                <td style="font-size: 0; line-height: 0;" width="20">&nbsp;</td>
                <td>
                <a href="https://www.instagram.com/sitcon.sistemas/" target="_blank">
                    <img src="cid:instagram_icon.png" alt="Instagram" width="38" height="38" style="display: block;" border="0" />
                </a>
                </td>
                <td style="font-size: 0; line-height: 0;" width="20">&nbsp;</td>
                <td>
                <a href="https://www.facebook.com/sitconsistemas/" target="_blank">
                    <img src="cid:facebook_icon.png" alt="Facebook" width="38" height="38" style="display: block;" border="0" />
                    </a>
                </td>
                <td style="font-size: 0; line-height: 0;" width="20">&nbsp;</td>
                <td>
                <a href="https://api.whatsapp.com/send?phone=5531971028716" target="_blank">
                    <img src="cid:whatsapp_icon.png" alt="WhatsApp" width="38" height="38" style="display: block;" border="0" />
                </a>
                </td>
                <td style="font-size: 0; line-height: 0;" width="20">&nbsp;</td>
                <td>
                <a href="https://www.youtube.com/channel/UCwaftxrfrvBdbxJLiIcLnBg" target="_blank">
                    <img src="cid:youtube_icon.png" alt="YouTube" width="38" height="38" style="display: block;" border="0" />
                </a>
                </td>
                </tr>
                </table>
                </td>
                </tr>
                </table>
                </td>
                </tr>
                </table>
            </body>';
            $mail->AltBody 	= ' Aprovar particiação no credenciamento';
    
            $enviado = $mail->Send();
    
            $mail->ClearAllRecipients();
            $mail->ClearAttachments();
            //var_dump($mail);
            if($enviado){
                echo '<script type="text/javascript">
                         alert("Confirme sua inscrição acessando o link enviado para o seu email!");
                         window.location.href="index.php";
                      </script>';
            }else{
                echo '<script type="text/javascript">
                        alert("Houve uma queda na conexão, tente novamente!");
                      </script>';
            }
        }
    }
    
?>