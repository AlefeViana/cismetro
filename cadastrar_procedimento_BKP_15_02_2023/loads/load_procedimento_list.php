<?php
 //caio 2020-08-27 - fixed issue where search filter parameters were not being passed with pagination component

 use voku\helper\Paginator;
 use Stringy\Stringy as S;

 $acao = $_GET['acao'];

 if ($acao == "del") {
     //excluir

     $CdEspecProc = $_GET['id'];
     //verifica se existe algum especificacao vinculado ao fornecedor
     ($qry = mysqli_query(
         $db,
         "SELECT CdEspec FROM tbfornespec WHERE CdEspec=$CdEspecProc"
     )) or die('');

     if (mysqli_num_rows($qry) == 0) {
         $sql = "DELETE FROM tbespecproc WHERE CdEspecProc=$CdEspecProc";
         ($qry = mysqli_query($db, $sql)) or die(mysqli_error($db));

         echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
						<script language=\"JavaScript\" type=\"text/javascript\"> 
							alert(\"Especificação excluída com sucesso!\");
							window.location.href=\"index.php?i=4&s=l\";				
			 			 </script>";
     } else {
         echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						 <script language="JavaScript" type="text/javascript"> 
							alert("Especificação pode ser excluída, devido estar associada a um ou mais fornecedores!");
							window.location.href="index.php?i=4&s=l";				
			 			 </script>';
     }
 }

 $sql = "SELECT
		CdEspecProc,
		NmEspecProc,
		NmProcedimento,
		e.`Status`,
		e.valor,
		e.valorsus,
		e.ppi,
		e.bpa,
		e.cdsus,
		CASE
	WHEN (CHAR_LENGTH(e.cdsus) > 10) THEN
		CONCAT(
			SUBSTRING(e.cdsus, 1, 2),
			SUBSTRING(e.cdsus, 4, 2),
			SUBSTRING(e.cdsus, 7, 2),
			SUBSTRING(e.cdsus, 10, 3),
			SUBSTRING(e.cdsus, 14, 1)
		)
	ELSE
		e.cdsus
	END AS 'SUS'
	FROM
		tbespecproc e
	INNER JOIN tbprocedimento p ON e.CdProcedimento = p.CdProcedimento
	WHERE
		e.grupoceae = 0";

 //variavel do form de busca
 $searchValue = $_GET["search_value"];
 $searchFilter = $_GET["search_filter"];

 if ($searchValue) {
     $searchValue = htmlspecialchars(strip_tags($searchValue));
     switch ($searchFilter) {
         case "specialty_name":
             $sql .= " AND NmEspecProc LIKE '%$searchValue%'";
             break;
         case "csus":
             $sql .= " AND (CASE WHEN (CHAR_LENGTH(e.cdsus) > 10) THEN
						CONCAT(
							SUBSTRING(e.cdsus, 1, 2),
							SUBSTRING(e.cdsus, 4, 2),
							SUBSTRING(e.cdsus, 7, 2),
							SUBSTRING(e.cdsus, 10, 3),
							SUBSTRING(e.cdsus, 14, 1)
						) ELSE e.cdsus
						END LIKE '%$searchValue%' OR e.cdsus LIKE '%$searchValue%')";
             break;
         case "id":
             $sql .= " AND CdEspecProc = $searchValue";
             break;
         case 4:
             $sql .= " AND NmProcedimento LIKE '%$searchValue%'";
             break;
     }
 }

 //  ????
 if ($_SESSION['cdgrusuario'] == 18) {
     $sql .= " AND e.CdProcedimento = 8";
 }

 ($query = mysqli_query($db, $sql)) or die('Error counting results...');

 $count = mysqli_num_rows($query);

 $pages = new Paginator(15, 'pag');

 $pages->set_total($count);

 $sql .= " ORDER BY NmEspecProc " . $pages->get_limit();

 $query = mysqli_query($db, $sql);

 if (!$query) {
     die("Data could not be loaded");
 }

?>  