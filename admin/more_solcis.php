<?php
	define("DIRECT_ACCESS", true);
	require_once("verifica.php");
	
	require("../conecta.php");
	
	require "../../vendor/autoload.php";
	use Stringy\Stringy as S;
	
	

    $count = $_REQUEST["count"];  

echo "<div class='solicitacoes_grupocis'>
		<div class='selcis'>
		<div class='row'>
			<div class='col'>
				<label>Procedimento</label>
				<select name='cd_proc[]' style='width: 100%;' required id='cd_proc$count' class='form-control procs'>
		           <option value=''>Selecione um procedimento</option>   ";                      
		                      
		                $sql = "SELECT p.CdProcedimento, p.NmProcedimento
								FROM tbprocedimento AS p
								INNER JOIN tbespecproc ON p.CdProcedimento = tbespecproc.CdProcedimento
								WHERE p.`Status` = '1'
								GROUP BY Nmprocedimento
								ORDER BY p.NmProcedimento ASC";
																
		                        //require("conecta.php");
		                        $qry = mysqli_query($db,$sql) or die (TrataErro(mysqli_errno(),'','index.php?p=lista_solagd','frm_solagd:select dados procedimento'));
		                        if (mysqli_num_rows($qry) > 0){
		                            while ($dados = mysqli_fetch_array($qry)){	

										$NmProcedimento = (String)S::create($dados['NmProcedimento'])->titleize(["de", "da", "do"]);

		                                echo '<option value="'.$dados["CdProcedimento"].'">'.$NmProcedimento.'</option>';
		                            }
		                        } 
		                        @mysqli_close();
		                        @mysqli_free_result($qry); 
								@mysqli_free_result($sql_foto); 
	echo"   	</select>";              
		 echo"	</div>
		 		<div class='col'>
					<label> Especificação do Procedimento </label>   
		            <select name='cd_especcis[]' style='width: 100%;' id='cd_especificacao$count' class='form-control especs' required >
                        <option value=''>Selecione</option>
                    </select> 	
                </div>
            </div>";
		echo '<div class="row">
				<div class="col">
					<label> Observações  (255 Caracteres) </label>
					<textarea name="obscis[]" id="obs$count" class="form-control" style="width:100%; height:80px"  onkeyup="blocTexto(this.value)"></textarea>
				</div>
           		<div class="col">
           			<br>
	            	<label class="form-control" style="width: 100%"> Urgente ? 
	            		<input type="checkbox" name="urgentecis[]" id="urgente$count" value="1" />
	            	</label>
	            </div>
			</div>
				<div class="row justify-content-end">
					<button class="removerSelect btn btn-lg btn-danger" type="button"> <i class="fas fa-times"></i> </button>
				</div>
			
				<div class="msg" style="margin-top: 2px; color: red; margin-left: 2px; font-size: 15px; font-weight: bold;"></div>
		';
    	echo "</div><br/></div></div>  ";
?>
