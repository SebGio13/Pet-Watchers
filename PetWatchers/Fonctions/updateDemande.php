<?php
    require_once(__DIR__ . "/loginBdd.php");
    require_once(__DIR__ . "/verifToken.php");
    $liaison = loginBDD();

    if(isset($_COOKIE["token"]) && isset($_POST["idDemande"]) && isset($_POST["action"])){
        $tokenVerif = verifToken();

        $idPetSitter = $tokenVerif["idUtilisateur"];
        $idDemande = $_POST["idDemande"];

        $statut = null;
        if($_POST["action"] == "accepter"){
            $statut = "Acceptée";
        } else if($_POST["action"] == "refuser"){
            $statut = "Refusée";
        }

        // Si l'action est inconnue, on s'arrête proprement
        if($statut === null){
            echo json_encode([
                "resultat" => false,
                "message" => "Action inconnue"
            ]);
            mysqli_close($liaison);
            exit;
        }

        // Mise à jour du statut
        $updateDemande = mysqli_prepare($liaison, "UPDATE demande SET statut = ? WHERE idDemande = ? AND idPetSitter = ?");
        $resUpdateDemande = mysqli_stmt_execute($updateDemande, [$statut, $idDemande, $idPetSitter]);

        // Si la demande est acceptée, on crée un contrat
        if($resUpdateDemande && $statut == "Acceptée"){

            // Récupération du propriétaire de la demande
            $getProprio = mysqli_prepare($liaison, "SELECT idProprietaire FROM demande WHERE idDemande = ? AND idPetSitter = ?");
            mysqli_stmt_execute($getProprio, [$idDemande, $idPetSitter]);
            $resGetProprio = mysqli_stmt_get_result($getProprio);
            $demande = mysqli_fetch_assoc($resGetProprio);

            if($demande){
                $idProprietaire = $demande["idProprietaire"];

                // On récupère un animal du propriétaire
                $getAnimal = mysqli_prepare($liaison, "SELECT numTatouage FROM animal WHERE idUtilisateur = ? LIMIT 1");
                mysqli_stmt_execute($getAnimal, [$idProprietaire]);
                $resGetAnimal = mysqli_stmt_get_result($getAnimal);
                $animal = mysqli_fetch_assoc($resGetAnimal);

                // On ne crée le contrat que si le propriétaire a au moins un animal
                if($animal){
                    // Dates arbitraires : du jour même à 7 jours plus tard
                    $dateDebut = date("Y-m-d");
                    $dateFin = date("Y-m-d", strtotime("+7 days"));

                    $createContrat = mysqli_prepare($liaison, "INSERT INTO contrat (dateDebut, dateFin, idUtilisateur, numTatouage) VALUE (?, ?, ?, ?)");
                    mysqli_stmt_execute($createContrat, [$dateDebut, $dateFin, $idPetSitter, $animal["numTatouage"]]);
                }
            }
        }

        echo json_encode([
            "resultat" => $resUpdateDemande
        ]);
    } else {
        echo json_encode([
            "resultat" => false
        ]);
    }

    mysqli_close($liaison);
?>
