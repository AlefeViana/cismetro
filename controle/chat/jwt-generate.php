<?php 
function jwtCreate($data, $secret) {
    $date = new DateTime("now");
    $issuedAt = $date->getTimestamp();
    $expiration = $date->modify('+ 8 hour')->getTimestamp();

    // Create token header as a JSON string
    $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);

    // Create token payload as a JSON string
    $payload = json_encode(array_merge(
        [
            'iat' => $issuedAt,
            'exp' => $expiration,
        ],
        $data
    ));

    // Encode Header to Base64Url String
    $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));

    // Encode Payload to Base64Url String
    $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

    // Create Signature Hash
    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);

    // Encode Signature to Base64Url String
    $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

    // Create JWT
    $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    //echo $jwt; die();
    return $jwt;
}
/* 
echo jwtCreate([
    'cdCliente' => 89,
    'cdUsuario' => 1,
], 'cdfec2c2d16772816f70fa345c1ffa3a8c19b651dce9bb23fd88481531c63c99'); */
