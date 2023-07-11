<?php
require_once('./classConnexionDb.php');

class Auth
{
    static function authUser()
    {
        $userConnected = null;
        $pdo = Connexion::connectDb();
        $idSession = $_COOKIE['session'] ?? '';
        if ($idSession) {
            $stmt = $pdo->prepare('SELECT * FROM session where id_session = :id_session');
            $stmt->bindValue(':id_session', $idSession);
            $stmt->execute();
            $dataSession = $stmt->fetch();
            if ($dataSession) {
                $idUser = $dataSession['id_user'];
                $stmtUserInfo = $pdo->prepare('SELECT * FROM user where id_user = :id_user');
                $stmtUserInfo->bindValue(':id_user', $idUser);
                $stmtUserInfo->execute();
                $dataUser = $stmtUserInfo->fetch();
                $userConnected = $dataUser;
            }
        }
        return $userConnected;
    }
}
