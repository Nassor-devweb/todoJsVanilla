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
            $res = $stmt->execute();
            $dataUser = $stmt->fetch();
            if (gettype($dataUser) !== 'array') {
                $errorQuerry = new PDOException("Vous n'êtes pas inscris");
                throw $errorQuerry;
            }
        } catch (PDOException $err) {
            $respQuery = $err;
        }
        if (is_null($respQuery)) {
            if (password_verify($password_user, $dataUser['password_user'])) {
                $resp = json_encode($dataUser);
                $stmtSession = $pdo->prepare('INSERT INTO session VALUES (DEFAULT,:id_user)');
                $stmtSession->bindValue(':id_user', $dataUser['id_user']);
                $stmtSession->execute();
                $id_session = $pdo->lastInsertId();
                setcookie('session', $id_session, time() + 60 * 60 * 24, '', '', false, true);
                echo $resp;
            } else {
                http_response_code(401);
                $error = [];
                $errors['erreur'] = "Le mots de passe est incorrect ! !";
                $resp = json_encode($errors);
                echo $resp;
            }
        } else {
            http_response_code(401);
            $error = [];
            $errors['erreur'] = "Vous n'êtes pas inscris veuillez vous inscrire ! !";
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
