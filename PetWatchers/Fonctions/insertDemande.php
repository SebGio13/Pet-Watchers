<?php
    require_once(__DIR__ . "/loginBdd.php");
    require_once(__DIR__ . "/verifToken.php");
    $liaison = loginBDD();

    if(isset($_COOKIE["token"]) && isset($_POST["idPetSitter"])){
        $tokenVerif = verifToken();

        $idProprietaire = $tokenVerif["idUtilisateur"];
        $idPetSitter = $_POST["idPetSitter"];

        // Insertion de la demande dans la base de données
        $createDemande = mysqli_prepare($liaison, "INSERT INTO demande (statut, dateDemande, idProprietaire, idPetSitter) VALUE ('En attente', CURDATE(), ?, ?)");
        $resCreateDemande = mysqli_stmt_execute($createDemande, [$idProprietaire, $idPetSitter]);

        echo json_encode([
            "resultat" => $resCreateDemande
        ]);
    } else {
        echo json_encode([
            "resultat" => false
        ]);
    }

    mysqli_close($liaison);
?>
