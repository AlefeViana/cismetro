<?php

require "../../../../vendor/autoload.php";

use OpenTok\OpenTok;

$apiKey = "46690492";
$apiSecret = "T1==cGFydG5lcl9pZD00NjY5MDQ5MiZzaWc9MDUyMmVjMThiMzY5MTM2NjNjOTQ3ZTA4YTU5YjMzNmQ2NTZiZTIwYzpzZXNzaW9uX2lkPTJfTVg0ME5qWTVNRFE1TW41LU1UVTROelE0T0RZeE1EZzBOMzVVYm1GV2FrUnBPRWN2ZUhsU1RGZDNORTVXVEdGNE9GTi1RWDQmY3JlYXRlX3RpbWU9MTU4NzQ4ODYxMCZyb2xlPW1vZGVyYXRvciZub25jZT0xNTg3NDg4NjEwLjk1NjM0MDAyMTk1OCZleHBpcmVfdGltZT0xNTg4MDkzNDEwJmNvbm5lY3Rpb25fZGF0YT1uYW1lJTNESm9obm55JmluaXRpYWxfbGF5b3V0X2NsYXNzX2xpc3Q9Zm9jdXM=";


$opentok = new OpenTok($apiKey, $apiSecret);

// Create a session that attempts to use peer-to-peer streaming:
$session = $opentok->createSession();

// A session that uses the OpenTok Media Router, which is required for archiving:
$session = $opentok->createSession(array( 'mediaMode' => MediaMode::ROUTED ));

// A session with a location hint:
$session = $opentok->createSession(array( 'location' => '12.34.56.78' ));

// An automatically archived session:
$sessionOptions = array(
    'archiveMode' => ArchiveMode::ALWAYS,
    'mediaMode' => MediaMode::ROUTED
);
$session = $opentok->createSession($sessionOptions);


// Store this sessionId in the database for later use
$sessionId = $session->getSessionId();