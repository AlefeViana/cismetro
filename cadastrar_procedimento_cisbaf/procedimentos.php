<?php require_once("verifica.php");  ?>

<?php $cd = $_GET['cd']; ?>
<div id="menu_aba">
	<ul class="nav nav-pills">
		<li class="nav-item">
			<a class="nav-link <?php echo $_GET['s'] == "n" ? '' : 'active' ?>" href="?i=<?php echo $cdsubitem ?>&s=l" class="mm2" style="font-size:13px;">Listar</a>
		</li>
		<li>
			<a class="nav-link <?php echo $_GET['s'] == "n" ? 'active'  : '' ?>" href="?i=<?php echo $cdsubitem ?>&s=n" class="mm1" style="font-size:13px;">Cadastrar</a>
		</li>
	</ul>
</div>


<?php
$s = $_GET['s'];
switch ($s) {

	default:
		echo "<style> .mm2 { background:#EFF4FA; }</style>";
		include "cadastrar_procedimento/procedimentos_list.php";
		break;

	case "n":
		echo "<style> .mm1 { background:#EFF4FA;}</style>";
		include "cadastrar_procedimento/procedimentos_frm.php";
		break;

	case "e":
		echo "<style> .mm1 { background:#EFF4FA;}</style>";
		include "cadastrar_procedimento/procedimentos_frm.php";
		break;

	case  "l":
		echo "<style> .mm2 {background:#EFF4FA; }</style>";
		include "cadastrar_procedimento/procedimentos_list.php";
		break;
}
?>