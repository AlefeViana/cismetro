<?php

//ini_set('display_errors', 1);

$login = $_POST['login'];
$acao = $_POST['acao'];

function curlExec($url, $post = NULL, array $header = array())
{
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  if (count($header) > 0) {
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
  }
  if ($post !== null) {
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
  }
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  $data = curl_exec($ch);
  curl_close($ch);
  return $data;
}

// Retira a formatação de um valor
function limpa($valor)
{
  $valor = trim($valor);
  $valor = str_replace(".", "", $valor);
  $valor = str_replace(",", "", $valor);
  $valor = str_replace("-", "", $valor);
  $valor = str_replace("/", "", $valor);
  $valor = str_replace("(", "", $valor);
  $valor = str_replace(")", "", $valor);
  return $valor;
}

function gerar_senha($tamanho, $maiusculas, $minusculas, $numeros, $simbolos)
{
  $ma = "ABCDEFGHIJKLMNOPQRSTUVYXWZ";
  $nu = "0123456789";
  $senha = '';

  if ($maiusculas) {
    $senha .= str_shuffle($ma);
  }
  if ($numeros) {
    $senha .= str_shuffle($nu);
  }
  return substr(str_shuffle($senha), 0, $tamanho);
}

require_once("../../conecta.php");
require_once("../../funcoes.php");

$senha = gerar_senha(7, true, true, true, true);

$update_senha = "UPDATE tbusuario SET autenticacao = '$senha', autenticacaoInc = '".date("H:i:s")."' WHERE login = '$login' ";
$sql_exe = mysqli_query($db, $update_senha) or die();

// ===================================== SMS ====================================================== //
if ($acao === 'sms') {

  $sql_sms = "SELECT  u.Celular, u.Responsavel
              FROM    tbusuario u 
              WHERE   u.login = '$login' ";

  $result_sql_sms = mysqli_query($db, $sql_sms);
  $sms = mysqli_fetch_assoc($result_sql_sms);

  $telefone = preg_replace("/[^0-9]/", "", $sms['Celular']);

  // Verifica se o telefone tem o formato correto
  if (preg_match("/^([0-9]{2})([0-9]{5})([0-9]{4})$/", $telefone, $matches)) {
    // Substitui os 5 dígitos entre parênteses por asteriscos
    $telefone_formatado = "({$matches[1]})*****-{$matches[3]}";
  } else {
    $telefone_formatado = $sms['Celular'];
  }

  enviar_sms_2fa($sms['Celular'], $senha);

  echo json_encode(array('Status' => true, 'nome' => $sms['Responsavel'], 'censura' => $telefone_formatado));
}
// ===================================== EMAIL ====================================================== //
if ($acao === 'email') {

  $sql_email = "SELECT  u.Email, u.Responsavel 
                FROM    tbusuario u 
                WHERE   u.login = '$login' ";

  $sql_exe = mysqli_query($db, $sql_email);

  $Email = mysqli_fetch_assoc($sql_exe);

  $email = $Email['Email'];

  // separa o início do email, o @ e o fim do email
  $inicio = substr($email, 0, strpos($email, '@'));
  $fim = substr($email, strpos($email, '@'));

  // determina o tamanho do trecho do meio do email que será censurado com asteriscos
  $tamanho_censura = max(0, min(8, strlen($inicio) - 3));

  // substitui o trecho do meio do email por asteriscos
  $inicio_censurado = substr($inicio, 0, 3) . str_repeat('*', $tamanho_censura);

  // junta o início censurado do email com o final do email
  $censurado = $inicio_censurado . $fim;

  $x_auth_token = 'ea087240fdd11339cd31098d33ec22d8';
  $api_url = 'https://api.smtplw.com.br/v1';

  $from = "chamado@sitcon.com.br";
  $to = array($Email['Email']);
  $subject = "IConsórcio - Autenticação de 2 fatores";
  $body = <<<EOT
        <!DOCTYPE html>
        <html lang="pt-br">
        
        <head>
          <meta charset="UTF-8" />
          <title>Autenticação de 2 fatores</title>
          <meta name="viewport" content="width=device-width, initial-scale=1.0" />
          <style>
            td#redes {
              font-size: 10pt;
              font-family: "Arial", sans-serif;
              color: #ffffff;
            }
        
            td#credenciamento,
            td#usuario,
            td#senha {
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
                      <img src="$url/pg/credenciamento/icons_email/cabecalho.png" width="400" height="220" style="display: block;" />
                    </td>
                  </tr>
                  <tr>
                    <td bgcolor="#fe7800" style="padding: 40px 30px 40px 30px;">
                      <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                          <td id="credenciamento" align="justify" style="padding: 0px 0 30px 0;">
                            Solicitação de código para autenticação de 2 fatores realizada pelo usuário.
                          </td>
                        </tr>
                        <tr>
                          <td id="senha">
                            Código de verificação: $senha
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
                              <img src="$url/pg/credenciamento/icons_email/www_icon.png" alt="Site" width="38" height="38" style="display: block;" border="0" />
                            </a>
                          </td>
                          <td style="font-size: 0; line-height: 0;" width="20">&nbsp;</td>
                          <td>
                            <a href="https://www.instagram.com/sitcon.sistemas/" target="_blank">
                              <img src="$url/pg/credenciamento/icons_email/instagram_icon.png" alt="Instagram" width="38" height="38" style="display: block;" border="0" />
                            </a>
                          </td>
                          <td style="font-size: 0; line-height: 0;" width="20">&nbsp;</td>
                          <td>
                            <a href="https://www.facebook.com/sitconsistemas/" target="_blank">
                              <img src="$url/pg/credenciamento/icons_email/facebook_icon.png" alt="Facebook" width="38" height="38" style="display: block;" border="0" />
                            </a>
                          </td>
                          <td style="font-size: 0; line-height: 0;" width="20">&nbsp;</td>
                          <td>
                            <a href="https://api.whatsapp.com/send?phone=5531971028716" target="_blank">
                              <img src="$url/pg/credenciamento/icons_email/whatsapp_icon.png" alt="WhatsApp" width="38" height="38" style="display: block;" border="0" />
                            </a>
                          </td>
                          <td style="font-size: 0; line-height: 0;" width="20">&nbsp;</td>
                          <td>
                            <a href="https://www.youtube.com/channel/UCwaftxrfrvBdbxJLiIcLnBg" target="_blank">
                              <img src="$url/pg/credenciamento/icons_email/youtube_icon.png" alt="YouTube" width="38" height="38" style="display: block;" border="0" />
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

  switch ($curlInfo['http_code']) {
    case '201':
      $status = true;
      if (preg_match('@^Location: (.*)$@m', $response, $matches)) {
        $location = trim($matches[1]);
      }
      // Add other actions here, if necessary.

      echo json_encode(array('Status' => $status, 'Error' => null, 'Email' => $Email, 'nome' => $Email['Responsavel'], 'censura' => $censurado));

      // echo ' <script type="text/javascript">
      //             alert("Confirme sua inscrição acessando o link enviado para o seu email!");
      //             window.location.href="index.php";
      //        </script>';
      break;
    default:
      $status = false;
      echo json_encode(array('Status' => $status, 'Error' => 'Erro'));
      // echo '<script type="text/javascript">
      //         alert("Houve uma queda na conexão, tente novamente!");
      //       </script>';
      break;
  }

  // echo "\nStatus: {$status}\n";

  // if ($location) {
  // echo "\nLocation: {$location}\n\n";
  // }
}
