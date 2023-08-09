<?php

require('conecta.php');
require('funcoes.php');

use Stringy\Stringy as S;

$CdLogEspec = $_REQUEST['cdlogespec'];

$sql = mysqli_query($db, "  SELECT  us.NmUsuario,       logg.Valor_Antigo, 
                                    logg.Valor_Novo,    logg.DtInc, 
                                    logg.HrInc,         esp.NmEspecProc, 
                                    logg.DtAgIni,       logg.DtAgFim,
                                    logg.Situacao

                            FROM       tblogespec  AS   logg 
                            INNER JOIN tbespecproc AS   esp     ON logg.CdEspecProc = esp.CdEspecProc  
                            INNER JOIN tbusuario        us      ON us.CdUsuario     = logg.CdUsuario 

                            WHERE logg.CdLogEspec =" . $CdLogEspec);

$query = mysqli_fetch_array($sql);

$sql_consorcio = mysqli_query($db, "SELECT * FROM tbconsorcio") or die(mysqli_error($db));

$lin = mysqli_fetch_array($sql_consorcio);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Relatório Faturamento por <?php echo $grupo ?></title>
    <link rel="stylesheet" type="text/css" href="relatorios/table/financeiro3/estilo.css">


    <style>
        #watermark {
            position: relative;


            opacity: 0.2;
            z-index: 99;


            transform: rotate(60deg);
        }

        #folha {
            width: 750px;
            border: 1px solid #f2f2f2;
            padding: 25px;
            background-color: #fff;
            box-shadow: 3px 2px 9px #AEAEAE;
            margin-top: 5px;
            min-height: 900px;
            margin: 10px auto;
        }

        @media print {
            .imprimir {
                display: none;
            }

            body {
                padding: 0;
                margin: 0;
            }

            #folha {
                padding: 0;
                margin: 0;
                width: 105%;
                border: none;
                box-shadow: none;
                margin-top: 0px;
                min-height: none;
                margin-left: 0px;
                margin-bottom: 0px;
                top: 0;
                left: 0;
                bottom: 0;
            }

            .sub-titulo {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
        }
    </style>
</head>

<body>

    <div id="folha">
        <div id="watermark"><img src="img/logo_cismetro.png" width="500px" height="500px" style="position: fixed; top:100%; left:60%">
        </div>
        <?php require "./cabecalho.php" ?>

        <?php
        if ($query['DtAgFim'] == '0000-00-00')
            $msg = "Acarretando modificações nos valores dos agendamentos correspondentes, a partir do dia " . FormataDataBR($query['DtAgIni']);
        else
            $msg = "Acarretando modificações nos valores dos agendamentos correspondentes ao período de  " . FormataDataBR($query['DtAgIni']) . " á " . FormataDataBR($query['DtAgFim']);


        if ($query['Situacao'] == 'Criado') {
            $texto = "  O usuário " . (string)S::create($query['NmUsuario'])->titleize(["de", "da", "do"]) . ", na data " . FormataDataBR($query['DtInc']) . ",
                criou no valor de R$ " . number_format($query['Valor_Novo'], 2, ',', '.') . " o procedimento " . (string)S::create($query['NmEspecProc'])->titleize(["de", "da", "do"]) . ". ";
        } else {
            $texto = "  O usuário " . (string)S::create($query['NmUsuario'])->titleize(["de", "da", "do"]) . ", na data " . FormataDataBR($query['DtInc']) . ",
                alterou o valor de R$ " . number_format($query['Valor_Antigo'], 2, ',', '.') . " para R$ " . number_format($query['Valor_Novo'], 2, ',', '.') . " referente 
                ao procedimento " . (string)S::create($query['NmEspecProc'])->titleize(["de", "da", "do"]) . " , alterando os valores dos contratos vinculados a este procedimento.";
        }

        ?>
        <div style="padding-top: 20px;">
            <p class="titulo" style="font-size: 14px; text-align: center; font-weight: bold;">Ficha de Autorização
                <?php echo mb_strtoupper($grupo, $encoding) ?>
            </p>
            <p style="font-size: 14px; text-align: center; font-weight: bold;">
                <?= $texto ?>
                <?= $msg ?>
                <br>

            </p>
        </div>

        <div style="padding-top: 100px">
            <p style="font-size: 14px; text-align: center; font-weight: bold;">
                ___________________________________
            </p>
            <p style="font-size: 13px; text-align: center;">
                Assinatura
            </p>
        </div>

    </div>
</body>

</html>