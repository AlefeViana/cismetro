<?php
   $db = mysqli_connect('mysql09.sitcon.com.br', 'sitcon8', 'SIT1981*st') or die ("Nao foi possivel conectar ao banco de dados");
    mysqli_select_db('sitcon8',$db) or die (mysqli_error()); 
?>
<?php
    /* $db = mysqli_connect('localhost', 'root', '') or die ("Nao foi possivel conectar ao banco de dados");
    mysqli_select_db('iconscorio_treinamento_zerado',$db) or die (mysqli_error());*/
?>
