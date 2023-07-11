<?php

require_once("./classConnexionDb.php");
require_once('./authQuerry.php');

$pdo = Connexion::connectDb();
$isConnect = Auth::authUser();

if ($isConnect) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $finished_tache = 0;
        $tacheJson = file_get_contents('php://input');
        $tache = json_decode($tacheJson, true);
        $date = date(DateTime::ATOM, time());
        $stmt = $pdo->prepare('INSERT INTO tache VALUES (
            DEFAULT,
            :nom_tache,
            :finished_tache,
            :date_created,
            :id_user
        )');

        $stmt->bindValue(':nom_tache', $tache['tache']);
        $stmt->bindValue(':finished_tache', $finished_tache);
        $stmt->bindValue(':date_created', $date);
        $stmt->bindValue(':id_user', $isConnect['id_user']);

        $stmt->execute();
    }
} else {
    header('Content-Type : application/json');
    http_response_code(401);
    $message = [];
    $message['message'] = "Erreur d'authentification";
    $res = json_encode($message);
    echo $res;
}
