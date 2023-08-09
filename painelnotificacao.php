<?php 
	session_start();
	include('funcoes.php');
	echo'<link rel="stylesheet" type="text/css" href="css/geral.css"/>
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">';

	$sql = "SELECT pac.NmPaciente,ref.cdreferenciaceae,ref.CdPaciente
			FROM tbnotificacaoceae AS notf
			INNER JOIN tbceaereferencia AS ref ON notf.cdReferencia = ref.cdreferenciaceae
			INNER JOIN tbpaciente AS pac ON ref.CdPaciente = pac.CdPaciente
			WHERE notf.cdUsuario = $_SESSION[CdUsuario]";

	$sql = mysqli_query($db,$sql) or die("Erro ao buscar notificações");
	echo'<table id="table">
		<thead>
			<tr>
				<th>Form. de Referência</th>
				<th>Paciente</th>
				<th>Opções</th>
			</tr>
		</thead>';
	while($l = mysqli_fetch_array($sql)){
		echo'<tbody>
				<tr>
					<td align="center">'.$l[cdreferenciaceae].'</td>
					<td align="center">'.$l[NmPaciente].'</td>
					<td align="center"><a href="index.php?i=47&met=p&CdPaciente='.$l[CdPaciente].'&op1=14" title="Abrir" target="_parent"><i class="fa fa-folder-open"></i></a></td>
				</tr>
			</tbody>
		</table>';
	} 

?>
<style>   
	body { background:#FFF;width:99%; margin: 0 auto; }
	#proc { padding:10px;    }
	#proc div { border-bottom:#666 dashed 1px;  padding:3px;   }
	#proc div li { padding:2px;    }
	#proc h1 {font-size:12px;   }
	.form_agendar{
		display: none;
	}
	.form_irregularidade{
		display: none;
	}
 </style>