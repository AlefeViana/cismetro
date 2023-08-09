<?php

// É necessário ter uma sessão ativa
require "../vendor/autoload.php";

// Inclui o arquivo de conexão com o banco de dados
include "conecta.php";

// Inclui o arquivo com funções úteis
include "funcoes.php";

// Inclui o arquivo com outras funções úteis
require "./functions.php";

// Função para obter o endereço IP do usuário
function getUserIP()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if (filter_var($client, FILTER_VALIDATE_IP)) {
        $ip = $client;
    } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
        $ip = $forward;
    } else {
        $ip = $remote;
    }

    return $ip;
}

// Inicia a sessão, caso ainda não esteja iniciada
if (!session_id()) {
    @session_start();
}

// Verifica se foi submetido um formulário de mudança de município e atualiza o município do usuário no banco de dados
if (isset($_POST["municipio"]) && $_POST["municipio"] > 0 && isset($_POST["user"]) && $_POST["user"] != '') {
    $municipio = $_POST["municipio"];
    $user = $_POST["user"];
    mysqli_query($GLOBALS['db'], "UPDATE tbusuario SET CdOrigem = $municipio WHERE LOGIN LIKE '$user'");
}
// Verifica se foi submetido um formulário de mudança de fornecedor e atualiza o fornecedor do usuário no banco de dados
elseif (isset($_POST["cdforn"]) && $_POST["cdforn"] > 0 && isset($_POST["user"]) && $_POST["user"] != '') {
    $cdforn = $_POST["cdforn"];
    $user = $_POST["user"];
    mysqli_query($GLOBALS['db'], "UPDATE tbusuario SET cdfornecedor = $cdforn WHERE LOGIN LIKE '$user'");
}
// Se não houver submissão de formulário, o processo de autenticação é realizado
else {

    // Define a variável de sessão que indica se é o primeiro acesso do usuário
    $_SESSION['first_access'] = false;

    // Instancia a classe FlashMessages para exibir mensagens de alerta ou erro
    $msg = new \Plasticbrain\FlashMessages\FlashMessages();

    // Recupera o nome de usuário
    $login = isset($_POST["usuario"]) ? addslashes(trim($_POST["usuario"])) : false;

    // Recupera a senha criptografada em MD5
    $senha = isset($_POST["senha"]) ? md5(trim($_POST["senha"])) : false;

    // Recupera o tipo de usuário
    $CdTpUsuario = isset($_POST["tipo"]) ? addslashes(trim($_POST["tipo"])) : false;

    // Cria a consulta SQL para buscar o usuário no banco de dados
    $SQL_Usuario = "SELECT  CdUsuario, Email, Celular, Responsavel, Telefone
                    FROM    tbusuario
                    WHERE   Login   = '$login' 
                    AND     Senha   = '$senha' 
                    AND     Status  = 1
                    LIMIT   1";

    // Se o tipo de usuário for especificado, altera a consulta SQL
    if ($CdTpUsuario) {
        $SQL_Usuario = "SELECT  CdUsuario, Email, Celular, Responsavel, Telefone
                        FROM    tbusuario
                        WHERE   CdTpUsuario = '$CdTpUsuario' 
                        AND     Status = 1 
                        LIMIT   1";
    }

    //echo $SQL_Usuario;
    $result = mysqli_query($GLOBALS['db'], $SQL_Usuario) or die(mysqli_error($db));

    // Verifica se as credenciais de acesso foram informadas corretamente
    $correct_credentials = mysqli_num_rows($result);

    if ($correct_credentials) {

        // Recupera os dados do usuário
        $dados = mysqli_fetch_array($result);

        // Armazena o ID do usuário e o endereço IP
        $userId = $dados['CdUsuario'];

        // Verifica se o período de autenticação está ativo
        $periodo = getConfiguracao(39);

        if ($periodo['estado'] == 'A') {
            // Subtrai o valor do período do mês atual
            if (((int)$mes - (int)$periodo['valor']) < 1) {
                $mes = 12 + ((int)$mes - (int)$periodo['valor']);
                $ano = (int)$ano - 1;
            } else {
                $mes = (int)$mes - (int)$periodo['valor'];
            }
        }

        // Verifica se o usuário está autenticado em outra máquina confiável
        $sql_atuenticacao = "   SELECT  token, chave
                                FROM    tbmaquinas_confiaveis
                                WHERE   CdUsuario = '$userId' 
                                AND     `status`  = 1";

        $autenticacao = false;

        $autenticacao_config = getConfiguracao(41);

        if ($autenticacao_config['estado'] == 'A') {

            $resultado_autenticacao = mysqli_query($GLOBALS['db'], $sql_atuenticacao);

            // Verifica se o token do cookie consta na lista de máquinas confiáveis
            if (isset($_COOKIE['auth_two_factor_token'])) {
                while ($row = mysqli_fetch_array($resultado_autenticacao)) {
                    if (openssl_decrypt($_COOKIE['auth_two_factor_token'], "AES-256-CBC", $row['chave']) == $row['token']) {
                        $autenticacao = true;
                    }
                }
            }
        } else {
            $autenticacao = true;
        }

        // Verifica se a autenticação foi bem-sucedida ou se é o primeiro acesso do usuário
        if ($autenticacao || (empty($dados["Email"]) || empty($dados["Celular"]) || empty($dados["Telefone"]) || empty($dados["Responsavel"]))) {
            // Remove as sessões antigas do usuário e cria uma nova
            $sessions = getUserSessions($db, $userId);

            if (count($sessions) >= 1) {

                //destroyAuth($db, $userId);
                clearDatabaseSession($db, $userId);
            }

            initAuth($db, $userId);

            // Define a flag de primeiro acesso
            if (empty($dados["Email"]) || empty($dados["Celular"]) || empty($dados["Telefone"]) || empty($dados["Responsavel"])) {
                $_SESSION['first_access'] = true;
                $autenticacao = true;
            }
        }

        // Retorna o resultado da autenticação
        echo json_encode(array('status' => true, 'autenticacao' => $autenticacao));
    } else {
        // Retorna a mensagem de erro para credenciais inválidas
        echo json_encode(array('status' => false, 'msg' => 'Credenciais estão incorretas'));
    }
}
