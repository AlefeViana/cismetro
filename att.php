<?php 

	require ('conecta.php');
	
	
	
	$matriz = array(
		array(0, 1, 2, 3),
		array(4, 5, 6, 7)
	);

	
	for($i=0;$i<=count($vet_fornecedor);$i++)
	{
		echo $vet_fornecedor[$i];	
		
	}
	
		
	$sql = mysqli_query($db,"SELECT tbusuario.*, tbtpusuario.TpUsuario
							   FROM tbusuario, tbtpusuario
							   WHERE tbusuario.CdTpUsuario = tbtpusuario.CdTpUsuario
							   AND tbtpusuario.TpUsuario = 'admfornecedor'
							   ") or die (mysqli_error());
							   
							   
	
	echo "<style> .m1 { background:#F1F6FA; color:#0033CC} </style>";
			
			if(mysqli_num_rows($sql)>0)
			{
			
			
			echo "<table id='table'>
			  <tr>
			  	<th> Usuário  </th>
			  	<th> Código  </th>
				<th> Nome  </th>
				<th> Tipo Usuário  </th>
				<th> Login  </th>
				<th> Resetar Senha  </th>
				<th> Excluir  </th>
			  </tr>";
			  
	
				while($lin = mysqli_fetch_array($sql))
				{
					
					
					
					INSERT INTO `tbitemus` (`cditem`, `cdsubitem`, `cdpessoa`) VALUES ('2', '52', '13')
					
					
					
					
					if($lin['Status']=="2") {$style = "style='color:#C5C5C5'";   }
					if($lin['Status']=="1") {$style = "style='color:#000000'";   }
				
					echo "<tr>
						<td $style>
						<a href='?i=$cdsubitem&cdusuario=$lin[CdUsuario]&u=p' $style title='Configurações e Permissões' > 
						
						<img src='img/icon_pessoa_user.png' /> 
						</a> 
						</td>
						<td $style> $lin[CdUsuario] </td>

						<td $style> $lin[NmUsuario] </td>
						<td $style> $lin[TpUsuario] </td>
						<td $style> $lin[Login]  </td>";
						
						
						if($lin['Status']=="1") { $status = "Ativo"; $t= "Inativar"; }  
						if($lin['Status']=="2") { $status = "Inativo"; $t = "Ativar"; }  
						
						echo "<td > <a href='?i=$cdsubitem&u=l&ac=reset&CdUsuario=$lin[CdUsuario]' $style title='resetar senha sitcon'   > 	Resetar Senha </a>  </td>";
						
						$link_del = "?i=15&ac=del&CdUsuario=$lin[CdUsuario]";			
						
					//	echo "<td > <a href='?i=$cdsubitem&u=l&ac=s&s=$status&CdUsuario=$lin[CdUsuario]' $style title='$t'  > $status </a> </td>";
						
						echo "<td><a href='$link_del'> <img src='img/icon_excluir.png' title='excluir' onclick=\" return confirm('Tem certeza que deseja excluir o registro selecionado?')\"/></a></td>";
				 		echo "</tr>";
				}
				echo "</table>";
			}



?>