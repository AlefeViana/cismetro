<?php
function curlExec($url, $post = NULL, array $header = array()){
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  if(count($header) > 0) {
      curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
  }
  if($post !== null) {
      curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
  }
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  $data = curl_exec($ch);
  curl_close($ch);
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

  function gerar_senha($tamanho, $maiusculas, $minusculas, $numeros, $simbolos){
    $ma = "ABCDEFGHIJKLMNOPQRSTUVYXWZ";
    $mi = "abcdefghijklmnopqrstuvyxwz"; 
    $nu = "0123456789"; 
    $si = "@*"; 
      $senha='';
      if ($maiusculas) {  $senha .= str_shuffle($ma);   }
      if ($minusculas) {  $senha .= str_shuffle($mi);   }
      if ($numeros)    {  $senha .= str_shuffle($nu);   }
      if ($simbolos)   {  $senha .= str_shuffle($si);   }
      return substr(str_shuffle($senha),0,$tamanho);
  }

require_once("conecta.php");

$cnpj  = $_GET['c'];
$Email = $_GET['e'];

$sql = mysqli_query($GLOBALS['db'],"SELECT f.CdForn from tbfornecedor f WHERE f.CNPJ = $cnpj");
if(mysqli_num_rows($sql) > 0){
    $dados = mysqli_fetch_array($sql);
    $cdforn = $dados['CdForn'];

    echo '<script type="text/javascript">
                 alert("Esse CNPJ já possui acesso!");
                 window.location.href="login_credenciando.php";
              </script>';
}else{

    $busca_receita = curlExec('http://receitaws.com.br/v1/cnpj/'.$cnpj);
    $obj = json_decode($busca_receita);
    $nmResp = $obj->qsa[0]->nome;
    $dtinc = date('Y-m-d h:m:s');
    $tel = limpa($obj->telefone[0]);
    $cep = limpa($obj->cep);
    
    $sql_forn = "INSERT INTO tbfornecedor (NmForn,NmReduzido,CNPJ,Telefone,NmResp,Email,Logradouro,Numero,Bairro,CEP,DtInc,`Status`,tpforn,sit) 
    VALUES (\"$obj->nome\", \"$obj->fantasia\", $cnpj, '$tel', '$nmRes', '$obj->email', \"$obj->logradouro\", '$obj->numero', \"$obj->bairro\", '$cep', '$dtinc', 0, 'PJ', 'E')";
    //echo $sql_forn; die();
    $insert_forn = mysqli_query($GLOBALS['db'],$sql_forn);
    $cdforn = mysqli_insert_id($GLOBALS['db']);
    $sql_cred = " INSERT INTO tbcredfornecedor(
                    tbcredfornecedor.CdForn,
                    tbcredfornecedor.CNPJ,
                    tbcredfornecedor.Nome,
                    tbcredfornecedor.CNPJBanco,
                    tbcredfornecedor.NomeFornBanco,
                    tbcredfornecedor.NomeFantasia,
                    tbcredfornecedor.CEP,
                    tbcredfornecedor.Endereco,
                    tbcredfornecedor.Bairro,
                    tbcredfornecedor.Cidade,
                    tbcredfornecedor.Estado,
                    tbcredfornecedor.Telefone,
                    tbcredfornecedor.Email,
                    tbcredfornecedor.`Status`,
                    tbcredfornecedor.DtInc)
                  VALUES (
                    '$cdforn',
                    '$cnpj',
                    \"$obj->nome\",
                    '$cnpj',
                    \"$obj->nome\",
                    \"$obj->nome\",
                    '$cep',
                    \"$obj->logradouro\",
                    \"$obj->bairro\",
                    \"$obj->municipio\",
                    '$obj->uf',
                    '$obj->telefone',
                    '$obj->email',
                    '1',
                    '$dtinc')";
    //echo $sql_cred;
    $insert_cred = mysqli_query($GLOBALS['db'],$sql_cred);

    $cCdTpUsuario =  5;	
    $cNmUsuario = $obj->nome;	
    $cLogin = 'F.'.$cdforn;	
    $cCdOrigem = 0;	
    $ccdfornecedor = $cdforn;	
    $ccdgrusuario = 22;	
    $ccdprof = 0;

    $senha = gerar_senha(7, true, true, true, true);
    $db_senha = md5($senha);
    //echo 'Senha: '.$senha.' Hash: '.$db_senha; die();
    $qry = "INSERT INTO `tbusuario` (`CdTpUsuario`, `NmUsuario`, `Login`, `Senha`, `CdOrigem`, `cdfornecedor`, `cdgrusuario`,`cdprof`,`Email`,`Responsavel`) 
    VALUES ('$cCdTpUsuario', '$cNmUsuario', '$cLogin', '$db_senha' , '$cCdOrigem', '$ccdfornecedor', '$ccdgrusuario', '$ccdprof','$obj->email','$cNmUsuario')	";
    //print_r($qry);
    $sql = mysqli_query($db,$qry) or die (mysqli_error($db));
 
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
    
    $x_auth_token = 'ea087240fdd11339cd31098d33ec22d8';
    $api_url = 'https://api.smtplw.com.br/v1';

    $from = "chamado@sitcon.com.br";
    $to = array($Email);
    $subject = "Credenciamento - Confirmação de E-mail";
    $body = <<<EOT
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
      <meta charset="UTF-8"/>
      <title>Usuário e Senha</title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
      <style>
        td#redes{
         font-size: 10pt;
         font-family: "Arial", sans-serif;
         color: #ffffff;
      }
      td#credenciamento,	td#usuario, td#senha{
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
       <img src="https://develop.nuvemsitcon.com.br/cismetro/pg/credenciamento/icons_email/cabecalho.png" width="400" height="220" style="display: block;" />
      </td>
     </tr>
     <tr>
      <td bgcolor="#fe7800" style="padding: 40px 30px 40px 30px;">
       <table border="0" cellpadding="0" cellspacing="0" width="100%">
      <tr>
       <td id="credenciamento" align="justify" style="padding: 0px 0 30px 0;">
        Foi criado seu login de acesso ao sistema para realizar o credenciamento. Para acessar, utilize as credenciais informadas abaixo:
       </td>
      </tr>
      <tr>
       <td id="usuario">
        Usuário:   $cLogin
       </td>
      </tr>
      <tr>
       <td id="senha"> 
        Senha:   $senha
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
         <img src="https://develop.nuvemsitcon.com.br/cismetro/pg/credenciamento/icons_email/www_icon.png" alt="Site" width="38" height="38" style="display: block;" border="0" />
        </a>
      </td>
      <td style="font-size: 0; line-height: 0;" width="20">&nbsp;</td>
      <td>
       <a href="https://www.instagram.com/sitcon.sistemas/" target="_blank">
         <img src="https://develop.nuvemsitcon.com.br/cismetro/pg/credenciamento/icons_email/instagram_icon.png" alt="Instagram" width="38" height="38" style="display: block;" border="0" />
       </a>
      </td>
      <td style="font-size: 0; line-height: 0;" width="20">&nbsp;</td>
      <td>
       <a href="https://www.facebook.com/sitconsistemas/" target="_blank">
         <img src="https://develop.nuvemsitcon.com.br/cismetro/pg/credenciamento/icons_email/facebook_icon.png" alt="Facebook" width="38" height="38" style="display: block;" border="0" />
        </a>
      </td>
      <td style="font-size: 0; line-height: 0;" width="20">&nbsp;</td>
      <td>
       <a href="https://api.whatsapp.com/send?phone=5531971028716" target="_blank">
         <img src="https://develop.nuvemsitcon.com.br/cismetro/pg/credenciamento/icons_email/whatsapp_icon.png" alt="WhatsApp" width="38" height="38" style="display: block;" border="0" />
       </a>
      </td>
      <td style="font-size: 0; line-height: 0;" width="20">&nbsp;</td>
      <td>
       <a href="https://www.youtube.com/channel/UCwaftxrfrvBdbxJLiIcLnBg" target="_blank">
         <img src="https://develop.nuvemsitcon.com.br/cismetro/pg/credenciamento/icons_email/youtube_icon.png" alt="YouTube" width="38" height="38" style="display: block;" border="0" />
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
    </body>    
    EOT;

        $headers = array(
        "x-auth-token: $x_auth_token",
        "Content-type: application/json"
        );

        $data_string = array(
        'from'    => $from,
        'to'      => $to,
        'subject' => $subject,
        'body'    => $body,
        'headers' => array('Content-type' => 'text/html')
        );

        $ch = curl_init("$api_url/messages");

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data_string));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);
        $curlInfo = curl_getinfo($ch);
        curl_close($ch);

        switch($curlInfo['http_code']) {
        case '201':
            $status = 'OK';
            if (preg_match('@^Location: (.*)$@m', $response, $matches)) {
            $location = trim($matches[1]);
            }
            // Add other actions here, if necessary.
            echo ' <script type="text/javascript">
                        alert("Confirme sua inscrição acessando o link enviado para o seu email!");
                        window.location.href="index.php";
                   </script>';
            break;
        default:
            $status = "Error: $curlInfo[http_code]";
            echo '<script type="text/javascript">
                    alert("Houve uma queda na conexão, tente novamente!");
                  </script>';
            break;
        }

        echo "\nStatus: {$status}\n";

        if ($location) {
        echo "\nLocation: {$location}\n\n";
        }
}
?>