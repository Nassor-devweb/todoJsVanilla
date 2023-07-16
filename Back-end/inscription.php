<?php

require_once("./classConnexionDb.php");
$pdo = Connexion::connectDb();

const ERRORS_NAME = 'Le nom doit comporter entre 2 et 12 caractère';
const ERRORS_EMAIL = "L'email n'est pas valide";
const ERRORS_PASSWORD = "Le mots de passe doit contenir entre 2 et 12 caractère";
const CHAMP = 'Veuillez remplir tout les champs';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    //echo json_encode($_FILES);
    $user_name = $_POST['name'];
    $user_email = $_POST['email'];
    $user_password = $_POST['password'];


    $error = match (true) {
        (trim($user_name) && trim($user_email) && trim($user_password)) === false =>  CHAMP,
        mb_strlen($user_name) < 2 ||  mb_strlen($user_name) > 12 => ERRORS_NAME,
        mb_strlen($user_password) < 2 ||  mb_strlen($user_password) > 12 => ERRORS_PASSWORD,
        filter_var($user_email, FILTER_VALIDATE_EMAIL) === false => ERRORS_EMAIL,
        default => ''
    };

    if (!$error) {
        $user = filter_var_array($_POST, [
            'name' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'email' => FILTER_SANITIZE_EMAIL,
        ]);
        $user['password'] = password_hash($_POST['password'], PASSWORD_ARGON2I);

        $path_photo = '';
        $finalyLink = '';

        if (isset($_FILES['path_photo'])) {
            $tmpFilePath = $_FILES['path_photo']['tmp_name'];
            $path_photo = 'img/' . time() . '-' . $_FILES['path_photo']['name'];
            move_uploaded_file($tmpFilePath, $path_photo);
        }
        if ($path_photo) {
            $finalyLink = 'http://localhost:3000/' . $path_photo;
        } else {
            $finalyLink = '';
        }


        $user['link'] = $finalyLink;

        $stmt = $pdo->prepare('INSERT INTO user VALUES (
                DEFAULT,
                :name_user,
                :email_user,
                :password_user,
                :photo_user
            )');

        $stmt->bindValue(":name_user", $user['name']);
        $stmt->bindValue(":email_user", $user['email']);
        $stmt->bindValue(":password_user", $user['password']);
        $stmt->bindValue(":photo_user", $user['link']);

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
