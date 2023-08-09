<?php 
    include "funcoes.php";
	//header("Content-Type: text/html; charset=ISO-8859-1", true);
	$id= $_GET['id'];
?>
 

	<?php 
	require("conecta.php");
	require "../vendor/autoload.php";
	use Stringy\Stringy as S;

	$sql = mysqli_query($db,"SELECT
	tbsolcons.CdSolCons,
	AG.NmUsuario AS UserINC,
	MAR.NmUsuario AS UserM,
	REL.NmUsuario AS USERrel,
	CANC.NmUsuario AS USERcanc,
	tbsolcons.dtinc,
	tbsolcons.hrinc,
	tbsolcons.userm,
	tbsolcons.dtm,
	tbsolcons.hrm,
	tbsolcons.userrel,
	tbsolcons.dtrel,
	tbsolcons.hrrel,
	tbsolcons.usercanc,
	tbsolcons.dtcanc,
	tbsolcons.hrcanc
	FROM 
	tbsolcons
	LEFT JOIN tbusuario as AG ON AG.CdUsuario = tbsolcons.userinc
	LEFT JOIN tbusuario as MAR ON MAR.CdUsuario = tbsolcons.userm
	LEFT JOIN tbusuario as REL ON REL.CdUsuario = tbsolcons.userrel
	LEFT JOIN tbusuario as CANC ON CANC.CdUsuario = tbsolcons.usercanc
	WHERE tbsolcons.CdSolCons = '$id'
	") or die (mysqli_error());

	$lin = mysqli_fetch_array($sql);
	
	$UserINC = (String)S::create($lin['UserINC'])->titleize(["de", "da", "do"]);
	$UserM = (String)S::create($lin['UserM'])->titleize(["de", "da", "do"]);
	$USERrel = (String)S::create($lin['USERrel'])->titleize(["de", "da", "do"]);
	$USERcanc = (String)S::create($lin['USERcanc'])->titleize(["de", "da", "do"]);
	
	
	
	$dtinc =  FormataDataBr($lin['dtinc']);
	$hrinc  =  $lin['hrinc'];

	$dtm =  FormataDataBr($lin['dtm']);
	$hrm  =  $lin['hrm'];

	$dtrel =  FormataDataBr($lin['dtrel']);
	$hrrel  =  $lin['hrrel'];

	$dtcanc =  FormataDataBr($lin['dtcanc']);
	$hrcanc  =  $lin['hrcanc'];



	?>

	<hr>


<div class="table-responsive">
  <table class="table table-bordered table-sm">
      <thead>
	      <tr>
		    
		    <th> Aguardando</th>
		 	<th> Marcado </th>
			<th> Realizado </th>
			<th> Cancelado </th>
		  </tr>
	  </thead>
      <tbody>

	    <tr>
	    
			<td style="text-align:center"> <?php echo $UserINC.' - '.$dtinc.' - '.$hrinc  ?></td>
			<td style="text-align:center"> <?php echo $UserM.' - '.$dtm.' - '.$hrm  ?></td>
			<td style="text-align:center"> <?php echo $USERrel.' - '.$dtrel.' - '.$hrrel  ?></td>
			<td style="text-align:center"> <?php echo $USERcanc.' - '.$dtcanc.' - '.$hrcanc  ?></td>

		</tr>

	  </tbody>
  </table>
</div>


<?php

	$query = mysqli_query($db,"SELECT a.tipo,DATE_FORMAT(a.dtalt,'%d/%m/%Y às %H:%i:%s') AS dthr,a.cdag, 
						  (SELECT u.NmUsuario from tbusralt ua inner join tbusuario u on ua.cdusr = u.CdUsuario 
						   WHERE ua.dtalt >= a.dtalt AND ua.cdag = a.cdag and date(ua.dtalt) = date(a.dtalt) ORDER BY ua.dtalt ASC LIMIT 1) as usuario
						   FROM tblogag a
						   WHERE a.cdag = '$id' ORDER BY a.dtalt DESC");

		$count = mysqli_num_rows($query); 

		
		
		?>
		<div class="table-responsive">
			<table class="table table-bordered table-sm">
				<thead>
					<tr>
						<th> Ação</th>
						<th> Data </th>
						<th> Usuário </th>
					</tr>
				</thead>
				<tbody>

					
						<?php
						//if(!$count) { echo "<tr><td colspan='3'> Nenhuma informação encontrada!</td></tr>"; }

						while($row = mysqli_fetch_assoc($query) ){
							?>

							<tr>
							    <td><?php echo $row["tipo"]; ?></td>
								<td><?php echo $row["dthr"]; ?></td>
								<td><?php echo $row["usuario"] ? $row["usuario"] : "-"; ?></td>
							</tr>

							<?php } ?>

				</tbody>
			</table>
        </div>


		
		
	