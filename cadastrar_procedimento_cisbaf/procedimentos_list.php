<?php

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
		e.valorm,
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

($query = mysqli_query($db, $sql)) or die('Error counting results...');

$count = mysqli_num_rows($query);

$pages = new Paginator(15, 'pag');

$pages->set_total($count);

$sql .= " ORDER BY `Status` DESC, NmEspecProc " . $pages->get_limit();

$query = mysqli_query($db, $sql);

// var_dump($sql);
// die();

if (!$query) {
	die("Data could not be loaded");
}
?>

<form method="get" name="frm_pesq">
	<input type="hidden" name="i" value="<?= $_GET['i'] ?>">
	<input type="hidden" name="s" value="<?= $_GET['s'] ?>">
	<div class="row">
		<div class="col-12 col-md-6">
			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text" id="inputGroup-sizing-sm">Pesquisar</span>
				</div>
				<input type="text" name="search_value" id="search_value" value="<?= $searchValue ?>" class="form-control" aria-label="Search input" aria-describedby="inputGroup-sizing-sm" />
			</div>
		</div>
		<div class="col-12 col-md-6">
			<div class="input-group mb-3">

				<select name="search_filter" class="custom-select" id="search_filter">
					<option value="specialty_name" <?php if ($searchFilter == "specialty_name") echo 'selected="selected"'; ?>>Especificação </option>
					<option value="id" <?php if ($searchFilter == "id") echo 'selected="selected"'; ?>>Código </option>
					<option value="csus" <?php if ($searchFilter == "csus") echo 'selected="selected"'; ?>>Código SUS </option>
					<option value="procedure_type" <?php if ($searchFilter == "procedure_type") echo 'selected="selected"'; ?>> Grupo </option>
				</select>

				<div class="input-group-append">
					<input type="hidden" name="acao" value="buscar">
					<input type="submit" class="input-group-text gogo bg-success text-white" for="select_filtros" name="gogo" value="Buscar">
				</div>

			</div>
		</div>
	</div>
</form>

<?php if ($searchValue) { ?>
	<div class="card my-4" id=res_pesq>
		<div class="card-body">
			<h5>Informações de pesquisa </h5>
			<ul class="list-unstyled">

				<li> Resultado(s) encontrado(s): <strong><?php echo $count; ?> </strong> </li>
			</ul>
		</div>
	</div>
<?php }	?>

<div class="table-responsive">
	<table class="table table-bordered table-striped " style="width: 100%;">
		<caption>Lista de procedimentos</caption>
		<thead class="thead-light">
			<tr>
				<th></th>
				<th> Código </th>
				<th style="width:10%"> Código SUS </th>
				<th style="width:34%"> Especificação </th>
				<th> Grupo </th>
				<th> PPI </th>
				<th> BPA </th>
				<th> Valor Regional (Valor Médico): </th>
				<th> Valor SUS </th>
				<th> Valor Total (Valor): </th>
				<th> Ações </th>
			</tr>
		</thead>
		<tbody>
			<?php

			if (!$count) {
				echo "<tr class='bg-light  text-center'> <td colspan='11'>Nenhum registro encontrado...</td> </tr>";
			}

			while ($l = mysqli_fetch_array($query)) {
				$link = 'index.php?i=4&s=e&id=' . $l['CdEspecProc'] . '&acao=edit';
				$link_del = 'index.php?i=4&s=d&id=' . $l['CdEspecProc'] . '&acao=del';

				$icon = ($l["Status"] == 1) ? '<i class="fa-solid fa-circle text-success" title="Ativo"></i>' : '<i class="fa-solid fa-circle text-danger" title="Inativo"></i>';

				$valorm = number_format((float)$l['valorm'], 2, ',', '.');
				$valorsus = number_format((float)$l['valorsus'], 2, ',', '.');
				$valor = number_format((float)$l['valor'], 2, ',', '.');

				// $dif = (float)$l['valor'] - (float)$l['valorsus'];
				// $dif = ($dif == 0) ? "0,00" : number_format($dif, 2, ',', '.');
			?>
				<tr>
					<td> <?= $icon ?> </td>
					<td align="left"><?= $l['CdEspecProc'] ?></td>
					<td><?= $l['cdsus'] ?></td>
					<td align="left"><?= (string)S::create($l['NmEspecProc'])->titleize(["de", "da", "do"]) ?></td>
					<td align="center"><?= (string)S::create($l['NmProcedimento'])->titleize(["de", "da", "do"]) ?></td>


					<td align="center"> <?= $l['ppi'] ?></td>
					<td align="center"> <?= $l['bpa'] ?></td>

					<td align="center"> <?= $valorm ?></td>
					<td align="center"> <?= $valorsus ?></td>

					<td align="center"> <?= $valor ?></td>

					<td style="text-aling:center"><a href="<?= $link ?>"><i class="fas fa-edit" aria-hidden="true"></i></a>
						<!-- <a href='$link_del'><i class='fas fa-times-circle' style='color:red;'' alt='Desativar Registro' aria-hidden='true'  onclick=\" return confirm('Tem certeza que deseja excluir o registro selecionado?')\"></i></a>"; -->
					</td>
				</tr>

			<?php } ?>
		</tbody>
	</table>
</div>

<?php

$queryString = '?' . $_SERVER['QUERY_STRING'] . '&';
echo $pages->page_links($queryString);
