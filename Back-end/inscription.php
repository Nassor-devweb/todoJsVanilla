<?php

require_once("./classConnexionDb.php");

$pdo = Connexion::connectDb();
$user = [];
$donnees = null;

$user['name'] = null;
$user['email'] = null;
$user['password'] = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');

    // Décoder le contenu JSON en tableau associatif
    $$_POST = json_decode($json, true);
    filter_input_array()
    $user['name'] = $donnees['name'];
    $user['email'] = $donnees['email'];
    $user['password'] = $donnees['password'];

    $stmt = $pdo->prepare('INSERT INTO user VALUES (
        DEFAULT,
        :name_user,
        :email_user,
        :password_user
    )');

    $stmt->bindValue(":name_user", $user['name']);
    $stmt->bindValue(":email_user", $user['email']);
    $stmt->bindValue(":password_user", $user['password']);

    $stmt->execute();
    $userId = $pdo->lastInsertId();

    $stmt = $pdo->prepare('SELECT * from user Where id_user=:id');
    $stmt->bindValue(':id', $userId);

    $stmt->execute();
    $dataUser = $stmt->fetch();

    $resp = json_encode($dataUser);
    header('Content-Type: application/json');
    echo $resp;
}
