<?php
/**
 * Created by PhpStorm.
 * User: Juarez
 * Date: 23/07/14
 * Time: 11:01
 */
define("DIRECT_ACCESS", true);

include("../verifica.php");
require_once("../funcoes.php");
$p = $_POST;

if($_GET['acao'] != "edit")
{
    $sql = mysqli_query($db,"INSERT INTO tbgenfat(dtini,dtfim) VALUES('$p[dtini]','$p[dtfim]')");

        if(mysqli_errno($db) == 1062)
        {
            echo '<script language="JavaScript" type="text/javascript">
					alert("Período já cadastrado!");
					window.location.href="../index.php?i='.$_GET['i'].'";
			  		</script>';
        }else{
            $id = mysqli_insert_id($db);
            mysqli_query($db,"INSERT INTO tbgenfat_audi(acao,usr,dtalt,idgenfat)
                         VALUES('Inserir','$_SESSION[CdUsuario]','".date("Y-m-d H:i:s")."','$id')");
        }

    echo '<script language="JavaScript" type="text/javascript">
					alert("Período cadastrado com sucesso!");
					window.location.href="../index.php?i='.$_GET['i'].'";
			  		</script>';
}else{
    $sql = mysqli_query($db,"SELECT dtini,dtfim,estado FROM tbgenfat WHERE idgenfat = '$_GET[id]' ") or die(mysqli_error($db));
    $l = mysqli_fetch_object($sql);
    $dtini = $l->dtini;
    $dtfim = $l->dtfim;
    $estado = $l->estado;
    mysqli_query($db,"UPDATE tbgenfat SET dtini='$p[dtini]',dtfim='$p[dtfim]',estado='$p[estado]' WHERE idgenfat = '$_GET[id]' ");

    if(mysqli_errno($db) == 1062)
    {
        echo '<script language="JavaScript" type="text/javascript">
					alert("Período já cadastrado!");
					window.location.href="../index.php?i='.$_GET[i].'";
			  		</script>';
    }else{
        mysqli_query($db,"INSERT INTO tbgenfat_audi(acao,usr,dtalt,idgenfat,descr)
                         VALUES('Alterar','$_SESSION[CdUsuario]','".date("Y-m-d H:i:s")."','$_GET[id]','$dtini,$dtfim,$estado => $p[dtini],$p[dtfim],$p[estado]')")
        or die(mysqli_error($db));

    }

    echo '<script language="JavaScript" type="text/javascript">
					alert("Período atualizado com sucesso!");
					window.location.href="../index.php?i='.$_GET['i'].'";
			  		</script>';
}