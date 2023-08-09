 <!--[if IE 6]>

<style>

body {behavior: url("csshover3.htc");}

#menu li .drop {background:url("img/drop.gif") no-repeat right 8px; 

</style>

<![endif]-->
<style> 

#menu {
	list-style:none;
	width:1085px;
	margin:0px auto 0px auto;
	height:43px;
	padding:0px 0px 0px;
	font-size:10px;
	/* Rounded Corners 
	-moz-border-radius: 10px;
	-webkit-border-radius: 10px;
	border-radius: 10px;*/
	

	/* Background color and gradients
	background: #FFF; */
	margin-bottom:10px;
/*	background: -moz-linear-gradient(top, #0272a7, #013953); */
	/*background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#0272a7), to(#013953));*/
	
	/* Borders 
	
	border: 1px solid #002232;*/

	-moz-box-shadow:inset 0px 0px 1px #edf9ff;
	-webkit-box-shadow:inset 0px 0px 1px #edf9ff;
	  box-shadow:inset 0px 0px 1px #edf9ff; 
}

#menu li {
	float:left;
	display:block;
	text-align:center;
	position:relative;
	padding: 4px 10px 4px 10px;
	margin-right:12px;
	margin-top:1px;
	border:none;

	
}

#menu li:hover {
	border: 1px solid #D9D9D9;
	padding: 4px 9px 4px 9px;
	
	/* Background color and gradients */
	
	background: #F4F4F4;
	 background: -moz-linear-gradient(top, #FFFFFF, #FFFFFF);
	background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#FFFFFF), to(#FFFFFF));
	/* Rounded corners */
	
	-moz-border-radius: 5px 5px 0px 0px;
	-webkit-border-radius: 5px 5px 0px 0px;
	border-radius: 5px 5px 0px 0px;
}

#menu li a {
	font-family:Arial, Helvetica, sans-serif;
	font-size:13px; 
	color: #06C;
	display:block;
	outline:0;
	text-decoration:none;
	
	/* text-shadow: 1px 1px 1px #000; */
}

#menu li:hover a {
	color:#039;
	
	
	/* text-shadow: 1px 1px 1px #ffffff; */
}
#menu li .drop {
	padding-right:21px;
	background:url("img/drop.png") no-repeat right 8px;
}
#menu li:hover .drop {
	background:url("img/drop.png") no-repeat right 7px;
	
}

.dropdown_1column, 
.dropdown_2columns, 
.dropdown_3columns, 
.dropdown_4columns,
.dropdown_5columns {
	margin:4px auto;
	float:left;
	position:absolute;
	left:-999em; /* Hides the drop down */
	text-align:left;
	padding:10px 5px 10px 5px;
	border:1px solid #D9D9D9;
	border-top:none;
	
	/* Gradient background */
	background:#F4F4F4;
	 background: -moz-linear-gradient(top, #FFFFFF, #FFFFFF);
	background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#FFFFFF), to(#FFFFFF));
	
	/* Rounded Corners */
	-moz-border-radius: 0px 5px 5px 5px;
	-webkit-border-radius: 0px 5px 5px 5px;
	border-radius: 0px 5px 5px 5px;
}

.dropdown_1column {width:250px; z-index:999999999}
.dropdown_2columns {width: 280px;z-index:999999999}
.dropdown_3columns {width: 420px; z-index:999999999}
.dropdown_4columns {width: 560px; z-index:999999999}
.dropdown_5columns {width: 700px; z-index:999999999}

#menu li:hover .dropdown_1column, 
#menu li:hover .dropdown_2columns, 
#menu li:hover .dropdown_3columns,
#menu li:hover .dropdown_4columns,
#menu li:hover .dropdown_5columns {
	left:-1px;
	top:auto;
	
}

.col_1,
.col_2,
.col_3,
.col_4,
.col_5 {
	display:inline;
	float: left;
	position: relative;
	margin-left: 5px;
	margin-right: 5px;
}
.col_1 {width:250px;}
.col_2 {width:270px;}
.col_3 {width:410px;}
.col_4 {width:550px;}
.col_5 {width:690px;}

#menu .menu_right {
	float:right;
	margin-right:0px;
}
#menu li .align_right {
	/* Rounded Corners 
	-moz-border-radius: 5px 0px 5px 5px;
    -webkit-border-radius: 5px 0px 5px 5px;
    border-radius: 5px 0px 5px 5px;*/
}

#menu li:hover .align_right {
	left:auto;
	right:-1px;
	top:auto;
}

#menu p, #menu h2, #menu h3, #menu ul li {
	font-family:Arial, Helvetica, sans-serif;
	line-height:21px;
	font-size:12px;
	text-align:left;
	/* text-shadow: 1px 1px 1px #FFFFFF; */
}
#menu h2 {
	font-size:21px;
	font-weight:400;
	letter-spacing:-1px;
	margin:7px 0 14px 0;
	padding-bottom:14px;
	border-bottom:1px solid #666666;
}
#menu h3 {
	font-size:14px;
	margin:7px 0 14px 0;
	padding-bottom:7px;
	border-bottom:1px solid #FFF;
	margin-bottom:10px;
}
#menu p {
	line-height:18px;
	margin:0 0 10px 0;
}

#menu li:hover div a {
	font-size:12px;
	padding-left:3px;
	color:#015b86;
	border-bottom:#D9EAF5 solid 1px;
	
	
}
#menu li:hover div a:hover {
	color:#FFF;
	background:#06F;
	

}


.strong {
	font-weight:bold;
}
.italic {
	font-style:italic;
}

.imgshadow { /*  Better style on light background */
	background:#FFFFFF;
	padding:4px;
	border:1px solid #777777;
	margin-top:5px;
	/*-moz-box-shadow:0px 0px 5px #666666;
	-webkit-box-shadow:0px 0px 5px #666666;
	box-shadow:0px 0px 5px #666666; */
}
.img_left { /* Image sticks to the left */
	width:auto;
	float:left;
	margin:5px 15px 5px 5px;
}

#menu li .black_box {
	background-color:#030 ;
	color: #eeeeee;
	/* text-shadow: 1px 1px 1px #000;*/
	padding:4px 6px 4px 6px;

	/* Rounded Corners */
	-moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    border-radius: 5px;

	/* Shadow
	-webkit-box-shadow:inset 0 0 3px #000000;
	-moz-box-shadow:inset 0 0 3px #000000;
	box-shadow:inset 0 0 3px #000000; */
}

#menu li ul {
	list-style:none;
	padding:0;
	margin:0 0 0px 0; /* Alterado  */
}
#menu li ul li {
	font-size:12px;
	line-height:24px;
	position:relative;
	/*  text-shadow: 1px 1px 1px #ffffff;*/
	padding:0;
	margin:0;
	float:none;
	text-align:left;
	width:240px;
}
#menu li ul li:hover {
	background:none;
	border:none;
	padding:0;
	margin:0;
}

#menu li .greybox li {
	background:#F60;
	border:1px solid #bbbbbb;
	margin:0px 0px 4px 0px;
	padding:4px 6px 4px 6px;
	width:116px;

	Rounded Corners 
	-moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    -khtml-border-radius: 5px;
    border-radius: 5px;
}
#menu li .greybox li:hover {
	background:#ffffff;
	border:1px solid #aaaaaa;
	padding:4px 6px 4px 6px;
	margin:0px 0px 4px 0px;
}
#menu #sair{
	background: #09f;
	border-radius: 4px;
	height: 20px;
	width: 30px;
	border: 1px solid #6E80A5;
	padding: 4px 9px 4px 9px;
}
#menu #sair:hover{
	background: #5DBDFD;
	height: 20px;
	width: 30px;
	border: 1px solid #6E80A5;
}

.menubloqueado{
    background-color: #dcdcdc;
    border-radius: 3px;
    border-bottom:  1px solid;
    cursor: help;
    color: #ffffff;
}
</style> 
<ul id="menu">
    <?php 
	require("conecta.php");
	$query_per = mysqli_query($db,"SELECT * FROM tbmultigrupo Where CdUsuario = $_SESSION[CdUsuario]"); 
    
    $multigrupo = "";    
    if(mysqli_num_rows($query_per) > 0)
       	while ($n = mysqli_fetch_array($query_per, MYSQLI_ASSOC)) 
           	$multigrupo .= " OR tbitemus.cdgrusuario = '$n[cdgrusuario]' ";

	$sql = mysqli_query($db,"SELECT * from tbitem, tbitemus 
						WHERE tbitemus.cditem = tbitem.cditem 
						AND ( tbitemus.cdgrusuario = '$_SESSION[cdgrusuario]' $multigrupo )
						group by tbitemus.cditem
					    ") or die (mysqli_error());
		echo"<li> <a href='?i='> Inicio </a> </li>";

	while($lin = mysqli_fetch_array($sql, MYSQLI_ASSOC))
	{
		$cditem = $lin['cditem'];
		$nmitem = $lin['nmitem'];
		if($nmitem2 != $nmitem){
			echo"<li><a href='#' class='drop'> ".utf8_encode($lin['nmitem'])."</a> "; $nmitem2 = $nmitem;
		}

		$sql2 = mysqli_query($db,"SELECT * FROM tbitem, tbsubitem, tbitemus
							WHERE tbitem.cditem = tbsubitem.cditem AND tbsubitem.cditem = 
							'$lin[cditem]' 
							AND tbitemus.cdsubitem = tbsubitem.cdsubitem
							AND ( tbitemus.cdgrusuario = '$_SESSION[cdgrusuario]' $multigrupo )
							group by tbsubitem.cdsubitem
							order by nmsubitem") or die (mysqli_error());
		if(mysqli_num_rows($sql2)>0)
		{
			echo "<div class='dropdown_1column'>";
			// echo "<h3>   </h3>";
			echo "<div class='col_1'>";
			while ($lin2 = mysqli_fetch_array($sql2, MYSQLI_ASSOC))
			{
			 	$linkk = "index.php?i=$lin2[cdsubitem]";
			 	
				if($lin2['cdsubitem']==77) continue;
				/**
				 * Bloquear menu para o municipio
				 */
				// Sem validação de bloqueio de menus
			
					$menusBlock = array('0' => 0);
				
				if (in_array($lin2['cdsubitem'], $menusBlock)) {
					echo "<ul>
						<li><a href='#' title='Bloqueado' class='menubloqueado' style='color:#ffffff;background-color: #dcdcdc;'>  ".utf8_encode($lin2['nmsubitem'])."</a></li>
					</ul>";
				}else{
					echo "<ul>
							<li><a href='$linkk' $target >  ".utf8_encode($lin2['nmsubitem'])."</a></li>
						</ul>";
				}
			}
			echo "</div>";
			echo "</div>";
		} 
	}
	echo "</li>";
	echo '<li id="sair">';
	echo '<a href="login_sai.php?sair=1" style="color: white; text-transform: uppercase; font-weight: bold;"> Sair </a> </li>';
	?>

</ul>
