<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require "../../vendor/autoload.php";

use OpenTok\OpenTok;
use OpenTok\Session;
use OpenTok\Role;

$apiKey = "46690492";
$apiSecret = "282fa54a1120b405e5ea7e2c40981357e95ad3d7";

$opentok = new OpenTok($apiKey, $apiSecret);

// Create a session that attempts to use peer-to-peer streaming:
$session = $opentok->createSession();

// // A session that uses the OpenTok Media Router, which is required for archiving:
// $session = $opentok->createSession(array( 'mediaMode' => MediaMode::ROUTED ));

// // A session with a location hint:
// $session = $opentok->createSession(array( 'location' => '12.34.56.78' ));

// // An automatically archived session:
// $sessionOptions = array(
//     'archiveMode' => ArchiveMode::ALWAYS,
//     'mediaMode' => MediaMode::ROUTED
// );
// $session = $opentok->createSession($sessionOptions);

// // Store this sessionId in the database for later use

echo json_encode($session->getSessionId());