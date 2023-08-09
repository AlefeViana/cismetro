<?php
	/*$db = mysqli_connect('mysql.iconsorciosaude18.com.br', 'iconsorciosau139', 'newcondev20') or die ("Nao foi possivel conectar ao banco de dados");
	mysqli_select_db($db,'iconsorciosau139') or die (mysqli_error()); */
	/* $db = mysqli_connect('mysql.iconsorciosaude19.com.br', 'iconsorciosau144', 'Pd3rpivk') or die ("Nao foi possivel conectar ao banco de dados");
	mysqli_select_db($db,'iconsorciosau144') or die (mysqli_error());
	mysqli_set_charset($db, "utf8");

	$db->query('SET SQL_BIG_SELECTS=1');

	define('CLIENTE','90');
	define('RAIZ',$_SERVER['DOCUMENT_ROOT'].'/'.'cismetro'.'/');
	define('FOLDER_NAME','cismetro');
	mysqli_query($db,'SET lc_time_names = "pt_BR"'); */
	setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
	date_default_timezone_set('America/Sao_Paulo');
	//echo strftime('%A, %d de %B de %Y', strtotime('today'));
	
	// ini_set('display_errors', 'On');
	require $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";

	use App\Config\Connection;
	use App\Objects\Auth;	

	$userId = Auth::id();

	
	$db_attributes = [ 
		'db_host' => 'db-cismetro-demo.cp2misfrgznr.us-east-1.rds.amazonaws.com' ,
		'db_user' => 'admin', 
		'db_name' => 'cismetro',
		'db_psw'  => 'XY$OAPzpAiKs'
	];
	

	// $db_attributes = [ 
	// 	'db_host' => 'mysql.iconsorciosaude18.com.br' ,
	// 	'db_user' => 'iconsorciosau140', 
	// 	'db_name' => 'iconsorciosau140',
	// 	'db_psw'  => 'dev2022sitcon'
	// ];

	$connection = Connection::connect($db_attributes);

	if (!defined('CONNECTION_NAME')) define('CONNECTION_NAME', 'cismetro');
	if (!defined('CONNECTION_ID')) define('CONNECTION_ID', 120);
	if (!defined('FOLDER_NAME')) define('FOLDER_NAME', 'cismetro');	

	if (!defined('CLIENTE')) define('CLIENTE', '120');
	if (!defined('RAIZ')) define('RAIZ', $_SERVER['DOCUMENT_ROOT'].'/'.'cismetro'.'/');
	if (!defined('URL')) define('URL', 'http://ec2-100-24-26-89.compute-1.amazonaws.com/cismetro');
	
	//external apps location; eg: telemedicine...
	$externalAppsUrl = "https://iconsorciosaude18.com.br/";

	//set this value to configure the max life time of the user session
	$MAX_LIFE_TIME = 4000;//default 600
	//set this value to configure the max life time of the user session
	if (!defined('MAX_LIFE_TIME')) define('MAX_LIFE_TIME', 600); //default 600
	
	//end- caio -2020-08-14

	$db = mysqli_connect($db_attributes['db_host'], $db_attributes['db_user'], $db_attributes['db_psw']) or die ("Nao foi possivel conectar ao banco de dados");
	mysqli_select_db($db,$db_attributes['db_name']) or die (mysqli_error());
	mysqli_set_charset($db, "utf8");
	
	$db->query('SET SQL_BIG_SELECTS=1');

	$db_read = mysqli_connect($db_attributes['db_host'], $db_attributes['db_user'], $db_attributes['db_psw']) or die ("Nao foi possivel conectar ao banco de dados");
	mysqli_select_db($db_read,$db_attributes['db_name']) or die (mysqli_error());
	mysqli_set_charset($db_read, "utf8");
	
	$db_read->query('SET SQL_BIG_SELECTS=1');

	
	mysqli_query($db,'SET lc_time_names = "pt_BR"');
	setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
	date_default_timezone_set('America/Sao_Paulo');


	$db_attributes_api_bd = [ 
		'db_host' => 'mysql.iconsorciosaude5.com.br' ,
		'db_user' => 'iconsorciosaud38', 
		'db_name' => 'iconsorciosaud38',
		'db_psw'  => 'api2022sitcon'
	];

	$db_api = mysqli_connect($db_attributes_api_bd['db_host'], $db_attributes_api_bd['db_user'], $db_attributes_api_bd['db_psw']) or die ("Nao foi possivel conectar ao banco de dados");
	mysqli_select_db($db_api,$db_attributes_api_bd['db_name']) or die (mysqli_error());
	mysqli_set_charset($db_api, "utf8");

	$db_api->query('SET SQL_BIG_SELECTS=1');

	mysqli_query($db_api,'SET lc_time_names = "pt_BR"');