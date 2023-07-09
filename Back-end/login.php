<?php

require_once("./classConnexionDb.php");

const ERRORS_EMAIL = "L'email n'est pas valide";
const ERRORS_PASSWORD = "Le mot de passe est incorrect";
const CHAMP = 'Veuillez remplir tout les champs';

$pdo = Connexion::connectDb();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type : application/json');
    $dataJson = file_get_contents('php://input');
    $user = json_decode($dataJson, true);

    $error = match (true) {
        (trim($user['email']) && trim($user['password'])) === false => CHAMP,
        filter_var($user['email'], FILTER_VALIDATE_EMAIL) === false => ERRORS_EMAIL,
        default => ''
    };

    if (!$error) {
        $email_user = filter_var($user['email'], FILTER_SANITIZE_EMAIL);
        $password_user = $user['password'];

        $stmt = $pdo->prepare('SELECT * FROM user where email_user=:email_user');

        $stmt->bindValue(':email_user', $email_user);
        $respQuery = null;
        $dataUser = null;
        try {
            $stmt->execute();
        } catch (PDOException $err) {
            $respQuery = $err->getMessage();
        }

        if (is_null($respQuery)) {
            $dataUser = $stmt->fetch();
            if (password_verify($password_user, $dataUser['password_user'])) {
                $resp = json_encode($dataUser);
                echo $resp;
            } else {
                http_response_code(401);
                $error = [];
                $errors['erreur'] = "Le mots de passe est incorrect";
                $resp = json_encode($errors);
                echo $resp;
            }
        } else {
            http_response_code(401);
            $error = [];
            $errors['erreur'] = "Vous n'êtes pas inscris veuillez vous inscrire";
            $resp = json_encode($errors);
            echo $resp;
        }
    } else {
        http_response_code(401);
        $error = [];
        $errors['erreur'] = $error;
        $resp = json_encode($errors);
        echo $resp;
    }
}
