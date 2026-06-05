<?php
    require_once(__DIR__ . "/loginBdd.php");
    require_once(__DIR__ . "/verifToken.php");
    require_once(__DIR__ . "/createToken.php");

    if(isset($_COOKIE["token"]) && isset($_POST["newPrenom"])){
        $tokenVerif = verifToken();
        $liaison = loginBDD();

        $id = $tokenVerif["idUtilisateur"];
        $prenom = $_POST["newPrenom"];
        $nom = $_POST["newNom"];
        $mail = $_POST["newMail"];
        $telephone = $_POST["newTelephone"];
        $adresse = $_POST["newAdresse"];
        $ville = $_POST["newVille"];
        $codePostal = $_POST["newCodePostal"];
        $password = hash("sha256", $_POST["newPassword"] . hash("sha256", "&bqG&Y?@D7zbNg7H"));

        // Update de la table utilisateur
        $updateUser = mysqli_prepare($liaison, "UPDATE utilisateur SET nom = ?, prenom = ?, numTelephone = ?, adresse = ?, codePostal = ?, ville = ?, mail = ?, mdpHache = ? WHERE idUtilisateur = $id");
        $resUpdateUser = mysqli_stmt_execute($updateUser, [$nom, $prenom, $telephone, $adresse, $codePostal, $ville, $mail, $password]);

        // Mise à jour du token JWT
        $newData = "SELECT * FROM utilisateur WHERE idUtilisateur = $id";
        $resNewData = mysqli_query($liaison, $newData);
        $utilisateur = mysqli_fetch_assoc($resNewData);

        createToken($utilisateur, $tokenVerif["roleUtilisateur"]);

        echo json_encode([
            "resultat" => true
        ]);
    } else {
        echo json_encode([
            "resultat" => false
        ]);
    }

    mysqli_close($liaison);
?>