<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Usuwanie wszystkie zmienne sesji

    $_SESSION = array();

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    // Na koniec usuń sesję
    session_destroy();
    $sessionsDeleted = 1;
    echo $sessionsDeleted;
}
