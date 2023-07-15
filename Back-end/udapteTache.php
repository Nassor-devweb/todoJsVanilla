<?php

require_once('./classConnexionDb.php');
require_once('./authQuerry.php');

$pdo = Connexion::connectDb();
$isConnect = Auth::authUser();
header('Content-Type : application/json');

function udapteTache($dataTache)
{
    global $pdo;
    $stmt = $pdo->prepare('UPDATE tache SET nom_tache = :nom_tache where id_tache = :id_tache');
    $stmt->bindValue(':nom_tache', $dataTache['nom_tache']);
    $stmt->bindValue(':id_tache', $dataTache['id_tache']);
    $stmt->execute();
}

function getAllTache(array $dataUser)
{
    global $pdo;
    $JsonTache = [];
    $stmtGetTache = $pdo->prepare('SELECT * from  tache where id_user = :id_user ORDER BY date_created DESC');
    $stmtGetTache->bindValue(':id_user', $dataUser['id_user']);
    $stmtGetTache->execute();
    $allTache = $stmtGetTache->fetchAll();
    if (gettype($allTache) === 'array') {
        return json_encode($allTache);
    } else {
        return json_encode($JsonTache);
    }
}


//---------------------------------------------METHOD-----------------------------------

if ($isConnect) {

    //---------------------------------------------PATCH-----------------------------------

    if ($_SERVER['REQUEST_METHOD']  === 'PATCH') {
        $newDataTache = json_decode(file_get_contents('php://input'), true);
        udapteTache($newDataTache);
        echo getAllTache($isConnect);
    }
} else {
    http_response_code(401);
    $message = [];
    $message['message'] = "Erreur d'authentification";
    $res = json_encode($message);
    echo $res;
}
