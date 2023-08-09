<?php

if(!defined("DIRECT_ACCESS")){
    die('Not authorized');
}

use Carbon\Carbon;

include_once __DIR__ . "/../api/config/central.php";
include_once __DIR__ . "/../api/objects/banner.php";

$central_db = new Central();
$bannerInstance = new Banner($central_db->getConnection());
$bannerInstance->user_id = $user_id;
$bannerInstance->user_type = $user_type;

$banners = $bannerInstance->all("WHERE ba.`status` = 1 
AND ctrl.`status` = 1 
AND ctrl.cdTpUsuario = {$user_type} 
AND ctrl.dataIni = CURRENT_DATE()
AND ctrl.dataExp = CURRENT_DATE() 
AND ba.cdBanner NOT IN 
( SELECT tbbannersalc.cdBanner FROM tbbannersalc WHERE tbbannersalc.cdUsuario = {$user_id} )");








?>


