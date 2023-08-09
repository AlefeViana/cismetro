<?php

    if(!defined('DIRECT_ACCESS')) {
		die('Direct access not permitted');
	}

	$current_page = $_SESSION["current_page"]; 

    use Stringy\Stringy as S;	
	$query_per = mysqli_query($db,"SELECT * FROM tbmultigrupo Where CdUsuario = $_SESSION[CdUsuario]"); 
    
    $multigrupo = "";    
    if(mysqli_num_rows($query_per) > 0)
       	while ($n = mysqli_fetch_array($query_per, MYSQLI_ASSOC)) 
           	$multigrupo .= " OR tbitemus.cdgrusuario = '$n[cdgrusuario]' ";

	$sql = mysqli_query($db,"SELECT * from tbitem, tbitemus 
						WHERE tbitemus.cditem = tbitem.cditem 
						AND ( tbitemus.cdgrusuario = '$_SESSION[cdgrusuario]' $multigrupo )
						group by tbitemus.cditem
					    ") or die (mysqli_error($db));
		//echo"<li class='nav-item'> <a class='nav-link' href='?i='> Inicio </a> </li>";

	while($lin = mysqli_fetch_array($sql, MYSQLI_ASSOC))
	{
		$cditem = $lin['cditem'];
		$nmitem = $lin['nmitem'];

		$subitems_sql = "SELECT * ,tbsubitem.icons AS icon FROM tbitem, tbsubitem, tbitemus
		WHERE tbitem.cditem = tbsubitem.cditem AND tbsubitem.cditem = 
		'$lin[cditem]' 
		AND tbitemus.cdsubitem = tbsubitem.cdsubitem
		AND ( tbitemus.cdgrusuario = '$_SESSION[cdgrusuario]' $multigrupo )
		group by tbsubitem.cdsubitem
		order by nmsubitem";

		$query = mysqli_query($db,$subitems_sql) or die (mysqli_error($db));

		$count = mysqli_num_rows($query);
		
			if($count > 0){
			
				echo "<li  class='nav-item dropdown' >
				    <a  href='#' class='nav-link  dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'> ".($lin['nmitem'])."</a>"; 
				echo '<div style="overflow-y: scroll; height: '.($count >=  4 ? "200" :  ($count == 1 ? (string)(($count*50)/$count) : (string)($count*45)) ).'px;" class="dropdown-menu">';
				while ($item = mysqli_fetch_array($query, MYSQLI_ASSOC)){
					$url = "index.php?i=".$item['cdsubitem'];
					$desc = $item['nmsubitem'];
						 
					if($item['cdsubitem']==77) continue;
					/**
					 * Bloquear menu para o municipio
					 */
					// Sem validação de bloqueio de menus
				
					$menusBlock = array('0' => 0);
					
					if (in_array($item['cdsubitem'], $menusBlock)) {
						echo "<a href='#' title='Bloqueado' class='dropdown-item disabled'>  ".($item['nmsubitem'])."</a>";
					}else{
						echo "<a 
						class='dropdown-item " .($_GET['i'] == $item['cdsubitem'] ? 'disabled' : '' ). "' 
						href='".$url."'> 
							<i 
							style='font-size: 21px' 
							class='".($item['icon'])."'>
							</i>".S::create($desc)."</a>";
					}
				}

				echo '</div>';
				echo '</li>';
			}

			else
			    echo "<li  class='nav-item' ><a  href='#' class='nav-link'> ".($lin['nmitem'])."</a> </li>";
		
	}

?>

