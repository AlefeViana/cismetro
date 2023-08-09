<?php
define("DIRECT_ACCESS",  true);

include("../verifica.php");
require("../funcoes.php");
include("../../vendor/autoload.php");
use Stringy\Stringy as S;

$condunidade="";
//print_r($_REQUEST['cdfornecedor']);die();
$condunidade = (isset($_REQUEST['cdunidade'])) ? " AND forne.CdCidade =".$_REQUEST['cdunidade'] : "";

    $Query = "SELECT 
        forne.CdForn as 'codigo', 
        UPPER(forne.NmForn) AS 'nmfornecedor',
        UPPER(forne.NmReduzido) AS 'nmreduzido'
        FROM tbfornecedor_mun AS forne
        INNER JOIN tbprefeitura as pref
        ON forne.CdCidade = pref.CdPref
        WHERE pref.`Status` = 1
        AND pref.consorciado = 'S'
        AND forne.Status = 1
        $condunidade
        ORDER BY forne.NmForn";
    // print_r($Query);die();
    $sql = mysqli_query($db,$Query) or die (mysqli_error());

    $returnArray=array();

    if(mysqli_num_rows($sql) > 0){
        while($n = mysqlI_fetch_array($sql)){

            $returnArray[] = array(
                "codigo" => $n["codigo"],
                "nmfornecedor" => ($n["nmfornecedor"]),
                "nmreduzido" => ($n["nmreduzido"]),
            );
            
        }
    }
          
    if(false==empty($returnArray)){
        echo json_encode(array('dados' => $returnArray, 'meta' => 200,'erro'=>''));
    }else{
        echo json_encode(array('dados' =>null,
                                'meta' => 404,
                                'erro'=>strtoupper("Nenhum profissional disponi­vel!")));
    }