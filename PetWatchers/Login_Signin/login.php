<?php
    // array_map(function($file) { require $file; }, glob(__DIR__ . '/../Fonctions/*.php'));
    require_once(__DIR__ . "/../Fonctions/navbar.php");

    createNavbar();
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Pet Watchers - Page de connexion</title>
        <link href=../style.css rel="stylesheet"> 
    </head>
    <body>
        <div class="formLoginContainer">
            <div class="formLogin">
                <a href="../index.php" class="backLink">◄ Retour à l'accueil</a>
                <h1 class="formTitle">Bon retour !</h1>
                <p class="formSubTitle">Connectez-vous pour accéder à votre espace.</p>
                <div class="field">
                    <label>ADRESSE E-MAIL</label>
                    <input type="text" id="mailLogin" placeholder="adresse@mail.fr">
                </div>
                <div class="field">
                    <label>MOT DE PASSE</label>
                    <input type="password" id="passwordLogin" placeholder="••••••••••••">
                </div>
                <button class="button buttonPrimary buttonMaxWidth loginButton">Se connecter</button>
            </div>
        </div>
        <script src="../script.js"></script>
    </body>
</html>