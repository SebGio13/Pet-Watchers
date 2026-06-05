<?php
    require_once(__DIR__ . "/verifToken.php");

    $token = verifToken();

    setcookie("token", "", [
        "expires" => time() - 3600,
        "httponly" => true,
        "samesite" => "Strict",
        "path" => "/"
    ]);

    echo json_encode(["resultat" => true]);
?>