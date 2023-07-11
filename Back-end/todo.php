<?php

require_once('./authQuerry.php');

$isConnect = Auth::authUser();

if ($isConnect) {
    $message = [];
    $message['message'] = "Authentification réussi";
    $res = json_encode($message);
    echo $res;
} else {
    header('Content-Type : application/json');
    http_response_code(401);
    $message = [];
    $message['message'] = "Erreur d'authentification";
    $res = json_encode($message);
    echo $res;
}
