<?php

require_once('./authQuerry.php');
require_once("./classConnexionDb.php");

$pdo = Connexion::connectDb();
$isConnect = Auth::authUser();

if ($isConnect) {
    $idSession =  $_COOKIE['session'] ?? '';
    $stmt = $pdo->prepare('DELETE FROM session where id_session = :id_session');
    $stmt->bindValue(':id_session', $idSession);
    $stmt->execute();
    http_response_code(200);
} else {
    http_response_code(401);
}
