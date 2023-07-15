<?php

require_once("./classConnexionDb.php");
require_once('./authQuerry.php');

$pdo = Connexion::connectDb();
$isConnect = Auth::authUser();

function getAllTache(array $dataUser)
{
    global $pdo;
    $JsonTache = [];
    $stmtGetTache = $pdo->prepare('SELECT * from  tache where id_user = :id_user ORDER BY finished_tache ASC , date_created DESC');
    $stmtGetTache->bindValue(':id_user', $dataUser['id_user']);
    $stmtGetTache->execute();
    $allTache = $stmtGetTache->fetchAll();
    if (gettype($allTache) === 'array') {
        return json_encode($allTache);
    } else {
        return json_encode($JsonTache);
    }
}

function saveTache(array $tache, array $dataUser)
{
    $finished_tache = 0;
    $date = date(DateTime::ATOM, time());
    global $pdo;

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
    $stmt->bindValue(':id_user', $dataUser['id_user']);
    $stmt->execute();
}

function deleteTache(array $idTache)
{
    global $pdo;
    $stmt = $pdo->prepare('DELETE FROM tache where id_tache = :id_tache');
    $stmt->bindValue(':id_tache', $idTache['id_tache']);
    $stmt->execute();
}

function updateTache($updateTache)
{
    global $pdo;
    $stmt = $pdo->prepare('UPDATE tache SET finished_tache = :finished_tache where id_tache = :id_tache');
    $stmt->bindValue(':finished_tache', $updateTache['value']);
    $stmt->bindValue(':id_tache', $updateTache['id_tache']);
    $stmt->execute();
}

if ($isConnect) {
    header('Content-Type : application/json');

    //------------------------------------------------------GET------------------------
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        echo getAllTache($isConnect);
    }
    //------------------------------------------------------POST------------------------
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $tacheJson = file_get_contents('php://input');
        $tache = json_decode($tacheJson, true);
        saveTache($tache, $isConnect);
        echo getAllTache($isConnect);
    }

    //------------------------------------------------------DELETE------------------------

    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

        $idTache = json_decode(file_get_contents('php://input'), true);
        deleteTache($idTache);
        echo getAllTache($isConnect);
    }

    //------------------------------------------------------PUT------------------------

    if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
        $putData = json_decode(file_get_contents('php://input'), true);
        updateTache($putData);
        echo getAllTache($isConnect);
    }
} else {
    header('Content-Type : application/json');
    http_response_code(401);
    $message = [];
    $message['message'] = "Erreur d'authentification";
    $res = json_encode($message);
    echo $res;
}
