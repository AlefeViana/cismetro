<?php

// Importa os arquivos de conexão com o banco de dados, funções e a biblioteca de autoload do Composer
require("../../conecta.php");
require "../../../vendor/autoload.php";
require "../../functions.php";

function gerar_token($tamanho, $maiusculas, $numeros)
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

// Armazena a ação a ser executada
$acao = $_POST['acao'];

// Adiciona um novo local de acesso confiável para o usuário
if ($acao == 'novo') {

    // Obtém os dados enviados por POST
    $Login  = $_POST['login'];
    $codigo = $_POST['codigo'];

    // Realiza uma consulta SQL para validar o login do usuário e obter o código de autenticação
    $sql_valida = " SELECT u.autenticacao, u.autenticacaoInc, u.Email, u.CdUsuario 
                    FROM tbusuario u 
                    WHERE u.Login LIKE '$Login' ";

    $result_sql_valida = mysqli_query($db, $sql_valida);
    $codigo_usuario = mysqli_fetch_array($result_sql_valida);

    // Verifica se o código de autenticação do usuário é válido
    if (!empty($codigo_usuario['autenticacao'])) {
        $time = strtotime($codigo_usuario['autenticacaoInc']);
        $time += 120;
        // var_dump($time);
        // var_dump(time());
        // die();
        if ($codigo_usuario['autenticacao'] == $codigo) {
            if($time >= time()){
                // Obtém informações do navegador do usuário
                $browser = $_SERVER['HTTP_USER_AGENT'];

                if (preg_match('/\b(\w+)\/[\d\.]+\b/', $browser, $matches)) {
                    // o nome do navegador está na primeira palavra capturada
                    $nome_do_navegador = $matches[1];
                } else {
                    // não foi possível identificar o navegador
                    $nome_do_navegador = 'Não Definido';
                }

                // Armazena o nome do navegador
                $browser = $nome_do_navegador;

                // Armazena o código do usuário e informações do IP
                $CdUsuario  = $codigo_usuario['CdUsuario'];

                $token = gerar_token(20, true, true);
                $validade = time() + (3 * 30 * 24 * 60 * 60); 

                $chave = gerar_token(20, true, true);
              
                setcookie('auth_two_factor_token', openssl_encrypt($token, "AES-256-CBC", $chave), $validade, '/cismetro' ,'develop.nuvemsitcon.com.br', true);

                // Armazena a data e hora atuais
                $data_time  = date('Y-m-d h:i:s');

                // Insere um novo registro na tabela de máquinas confiáveis
                $sql = "INSERT INTO tbmaquinas_confiaveis (CdUsuario, token, chave, DtInc, Agente, status)
                        VALUES ($CdUsuario, '$token', '$chave', '$data_time', '$browser', 1)";

                $verifica = mysqli_query($db, $sql);

                // Obtém as sessões ativas do usuário e, caso exista alguma, as limpa do banco de dados
                $sessions = getUserSessions($db, $CdUsuario);

                if (count($sessions) >= 1) {
                    clearDatabaseSession($db, $CdUsuario);
                }

                // Inicializa a autenticação do usuário
                initAuth($db, $CdUsuario);

                // Armazena a data e hora atuais novamente
                $dataAtual = date('Y-m-d H:i:s');

                // Remove o código de autenticação do usuário
                $update_senha = "   UPDATE tbusuario 
                                    SET autenticacao = NULL,autenticacaoInc = NULL
                                    WHERE `Login` LIKE '$Login' ";

                $sql_exe = mysqli_query($db, $update_senha) or die();

                echo json_encode(array('erro' => false, 'msg' => 'Verificação realizada com sucesso'));
            }else{
                echo json_encode(array('erro' => true, 'msg' => 'Código de verificação expirou'));
            }
        } else {
            echo json_encode(array('erro' => true, 'msg' => 'Código inserido é inválido'));
        }
    } else {
        echo json_encode(array('erro' => true, 'msg' => 'Solicite um novo código primeiro'));
    }
// Exclui um local de acesso confiavel para o usuario
} elseif ($acao == 'excluir') {

    $CdMaquina = $_POST['CdMaquina'];

    $sql = "UPDATE   tbmaquinas_confiaveis
            SET     `status` = 0
            WHERE    CdMaquina = '$CdMaquina'";

    $verifica = mysqli_query($db, $sql);

    if ($verifica) {
        echo json_encode(array('erro' => false, 'msg' => 'Acesso desconectado com sucesso'));
    } else {
        echo json_encode(array('erro' => true, 'msg' => 'Falha na operação'));
    }
}