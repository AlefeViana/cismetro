<div id="table" class="table-responsive">
		<table class="table table-bordered table-sm ">
			<caption>Pacote <?= $idcombo ?></caption>
			<thead class="thead-light">
				<tr>
					<th> Código </th>
					<th> Status</th>
					<th> Data/Hora </th>
					<th> Paciente </th>
					<th> Data Nascimento </th>
					<th> Cidade </th>
					<th> Fornecedor </th>
					<th> Especificação </th>
					<th> Ações</th>
				</tr>
			</thead>

			<tbody>

				<?php

				if (!$count)
					echo "<tr class='bg-light  text-center'> <td colspan='9'>Nenhum registro encontrado...</td> </tr>";


				while ($n = mysqli_fetch_assoc($query)) {
					$img = ($n['StatusT'] == 'T') ? "<img src='../imagens/recepcionado.gif' width='13' height='13' title='recepcionado' />" : "";
					echo "<tr data-row='{ \"data\": " . json_encode($n) . "  }'>";

					echo    "<td>$n[CdSolCons]</td>";

					//status

					echo "<td>";



					if ($n['Status'] == 1 && $n['StatusAg'] == 1) echo '<i style="color: #60CD1B;" class="fas fa-circle" title="marcado" ></i>';
					elseif ($n['Status'] == 1 && $n['StatusAg'] == null) echo '<i style="color: #DF4023;" title="auditoria" class="fas fa-exclamation-circle"></i>';
					elseif ($n['Status'] == 1 && $n['StatusAg'] == 2) echo '<i style="color: #067FFB;" class="fas fa-circle" title="realizado" ></i>';
					elseif ($n['Status'] == 'F') echo '<i style="color: gray;" class="fas fa-circle" title="cancelar" ></i>';
					elseif ($n['Status'] == 2)  echo '<i class="fas fa-circle" style="color: #F44728;" title="cancelado" ></i>';
					elseif ($n['Status'] == 'E') echo '<i style="color: #FCEF33" class="fas fa-circle" title="aguardando" ></i>';
					else echo "";

					echo "</td>";


					echo    "<td>" . FormataDataBR($n['DtAgCons']) . " " . $n['HoraAgCons'] . "</td>
									<td>$n[NmPaciente]</td>
									<td>" . FormataDataBR($n['DtNasc']) . "</td>
									<td>$n[NmCidade]</td>
									<td>$n[NmForn]</td>
									<td>$n[NmEspecProc]</td>";

					//actions
					echo "<td>";

					foreach ($_SESSION['procedure_queue']['options'] as $key => $option) {

						if ($option === "Estorno") {

							echo "<a href='#' data-action='voltar'
										   class='manage_items_btn' >
										   <i title='Voltar agenda' class='text-secondary fas fa-undo-alt'></i></a>";
						} elseif ($option === "Cancelar") {

							echo "<a href='#' data-action='cancelar'
										   class='manage_items_btn' >
										   <i title='Cancelar procedimento' style='color: #F44728' class='far fa-calendar-times'></i> </a>";
						} elseif ($option === "Confirmar") {

							echo "<a href='#' data-action='confirmar'
										   class='manage_items_btn' >
										   <i style='color: #60CD1B' class='far fa-calendar-check'></i></a>";
						} elseif ($option === "Falta") {

							echo "<a href='#' data-action='falta'
										   class='manage_items_btn' >
										   <i title='Falta' style='color: #999999' class='far fa-calendar'></i></a>";
						}
					}

					if ($status == 2) {

						if ($n['impresso'] == 'S')
							echo "";

						if ($_SESSION['CdOrigem'] > 0 && $n['aceite'] == 1)
							echo "<a href='../guia_pac.php?id=$n[CdSolCons]&usr=3' target='__blank'  
											id='exibe'>" . '<i class="fas fa-print text-primary" title="Imprimir"></i>' . "</a>";
						elseif ($_SESSION["CdTpUsuario"] != 5 && $n['aceite'] == 1)
							echo "<a href='../guia_pac.php?id=$n[CdSolCons]' target='__blank' 
										id='exibe'>" . '<i class="fas fa-print text-primary" title="Imprimir"></i>' . "</a>";
						else
							echo "";
					}

					if ($status == 3 && $_SESSION["CdTpUsuario"] == 1)
						//echo '<a href="#" class="impDeclaration" data-iddecla="' . base64_encode($n['CdSolCons']) . '"><i class="fas fa-print text-secondary" title="Imprimir declaração"></i></a>';

					echo "</td>";
					echo "</tr>";
				}




				?>
			</tbody>
		</table>
	</div>
<?php } else
	echo "Something went wrong...";
?>