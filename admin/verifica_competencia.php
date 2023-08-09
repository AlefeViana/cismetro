<?php
	require "../../vendor/autoload.php";
	use Stringy\Stringy as S;

    session_start();
    require_once("../funcoes.php");
    $data = $_POST['data'];
    $status = $_POST['status'];
    if($status != 'espera'){
        $sql_verifica = " SELECT estado from tbgenfat WHERE '$data' BETWEEN dtini and dtfim limit 0,1";

        $query = mysqli_query($db,$sql_verifica);
        if(mysqli_num_rows($query) > 0){
            $estado = mysqli_fetch_array($query);
            if($estado["estado"] == 'A')
                $est = 'true';
            else
                $est = 'false';
        
            
        }
    }else{
        $est = 'true';
    }
    
    echo json_encode(array('estado' => $est));
    
    
?>