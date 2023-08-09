
<?php 
	if(!defined('DIRECT_ACCESS')) {
		die('Direct access not permitted');
	}

	use Stringy\Stringy as S;

?>
<form action="index.php?i=<?php echo $_GET['i'];?>" method="POST">
    <input type="submit" style="display:none;" >
	<div class="row">
		
		<div class="col-sm-12 col-md-6">
			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text" id="inputGroup-sizing-sm">Pesquisar</span>
				</div>
				<input name="pesq" value="<?php echo $busca ?>" type="text" class="form-control" aria-label="Search input" aria-describedby="inputGroup-sizing-sm">
			</div>
		</div>
		<div class="col-sm-12 col-md-6">
			<div class="input-group mb-3">
				<select name="cbopesq" class="custom-select" id="select_filtros">
					<option value="1" <?php if ($cbopor == 1) echo 'selected="selected"'; ?> >C&oacute;digo</option>
					<option value="2" <?php if ($cbopor == 2 || $cbopor == "") echo 'selected="selected"';?> >Nome do Paciente</option>
					<option value="3" <?php if ($cbopor == 3 || $cbopor == "") ?> >Data de Nascimento</option>
				</select>
				<div class="input-group-append">
					<input type="hidden" name="acao" value="buscar">
        			<input type="submit" class="gogo btn btn-success" for="select_filtros" name="gogo" value="Buscar">
				</div>
			</div>
		</div>
	
	</div>
</form> 


	    <?php

         if($busca ) { ?>
			
			<div class="card my-4">
				<div class="card-body">
					<h5>Informações de pesquisa </h5>
					<ul class="list-unstyled">
						<li> Filtro:  <strong><?php echo $busca  ?> </strong></li>
						<li> Resultado(s) encontrado(s): <strong><?php echo $count ?>  </strong>  </li>
					</ul>
					
				</div>
			</div>

		<?php } ?>


	<div class="table-responsive">
		<table class="table table-bordered table-striped ">
			<caption>Lista de pacientes</caption>
			<thead class="thead-light">
				<tr>
				    <th>CIH</th>
				    <th>Paciente</th>
				    <th>Nome da Mãe</th>
				    <th>Data de Nascimento</th>
				    <th>Cidade</th>
					<th>Ações</th>
				</tr>
			</thead>
			<tbody>
				<?php
				   if(!$count)
				       echo "<tr class='bg-light  text-center'> <td colspan='10'>Nenhum registro encontrado...</td> </tr>";
					while($l = mysqli_fetch_array($query)){
						
						$link = 'index.php?i=$cdsubitem&s=n&id='.$l['CdPaciente'].'&acao=edit&first=1';
						$link_del = 'index.php?i=$cdsubitem&s=n&id='.$l['CdPaciente'].'&acao=del&first=1';
						echo "
						    <tr>
						        <td>".$l['CdPaciente']."</td>
								<td>".S::create($l['NmPaciente'])->titleize(["de", "da", "do"])."</td>
								<td>".S::create($l['NmMae'])->titleize(["de", "da", "do"])."</td>
								<td>".\Carbon\Carbon::parse($l['DtNasc'])->format("d/m/Y")."</td>
								<td>".S::create($l['NmCidade'])->titleize(["de", "da", "do"])."</td>
								<td>
									<a href='?i=47&met=p&CdPaciente=$l[CdPaciente]' >  
									<i class='fas fa-user'></i>
									</a>
					        </tr>";
					}
				?>
			</tbody>
		</table>
	</div>



				<?php
					
         

				
				$busca = rawurlencode($busca);	
				$params = "?i=".$_GET['i']."&s=l&pesq=$busca&cbopesq=$cbopor&";

				echo $pages->page_links($params);

			
		
		@mysqli_free_result($query);
		@mysqli_close();
	
?>