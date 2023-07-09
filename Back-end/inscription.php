<?php

require_once("./classConnexionDb.php");
$pdo = Connexion::connectDb();

const ERRORS_NAME = 'Le nom doit comporter entre 2 et 12 caractère';
const ERRORS_EMAIL = "L'email n'est pas valide";
const ERRORS_PASSWORD = "Le mots de passe doit contenir entre 2 et 12 caractère";
const CHAMP = 'Veuillez remplir tout les champs';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $user = [];
    $donnees = null;
    $json = file_get_contents('php://input');
    $donnees = json_decode($json, true);
    $user_name = $donnees['name'];
    $user_email = $donnees['email'];
    $user_password = $donnees['password'];

    $error = match (true) {
        (trim($user_name) && trim($user_email) && trim($user_password)) === false =>  CHAMP,
        mb_strlen($user_name) < 2 ||  mb_strlen($user_name) > 12 => ERRORS_NAME,
        mb_strlen($user_password) < 2 ||  mb_strlen($user_password) > 12 => ERRORS_PASSWORD,
        filter_var($user_email, FILTER_VALIDATE_EMAIL) === false => ERRORS_EMAIL,
        default => ''
    };

    if (!$error) {
        $user = filter_var_array($donnees, [
            'name' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'email' => FILTER_SANITIZE_EMAIL,
        ]);
        $user['password'] = password_hash($donnees['password'], PASSWORD_ARGON2I);

        $stmt = $pdo->prepare('INSERT INTO user VALUES (
            DEFAULT,
            :name_user,
            :email_user,
            :password_user
        )');

        $stmt->bindValue(":name_user", $user['name']);
        $stmt->bindValue(":email_user", $user['email']);
        $stmt->bindValue(":password_user", $user['password']);
        $respQuery = null;
        try {
            $stmt->execute();
        } catch (PDOException $err) {
            $respQuery =  $err;
        }
        if (is_null($respQuery)) {
            $userId = $pdo->lastInsertId();

            $stmt = $pdo->prepare('SELECT * from user Where id_user=:id');
            $stmt->bindValue(':id', $userId);

            $stmt->execute();
            $dataUser = $stmt->fetch();
            $resp = json_encode($dataUser);
            echo $resp;
        } else {
            http_response_code(400);
            $errors = [];
            $errors['erreur'] = 'Vous êtes dejà inscris, veuillez vous connecter';
            $resp = json_encode($errors);
            echo $resp;
        }
    } else {
        http_response_code(400);
        $errors = [];
        $errors['erreur'] = $error;
        $resp = json_encode($errors);
        echo $resp;
    }
}
