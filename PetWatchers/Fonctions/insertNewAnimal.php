<?php
    require_once(__DIR__ . "/loginBdd.php");
    require_once(__DIR__ . "/verifToken.php");
    $liaison = loginBDD();

    if(isset($_COOKIE["token"]) && isset($_POST["newAnimalData"])){
        $tokenVerif = verifToken();

        $id = $tokenVerif["idUtilisateur"];
        $listAnimaux = json_decode($_POST["newAnimalData"], true);
        $createAnimal = mysqli_prepare($liaison, "INSERT INTO animal (nom, numTatouage, sexe, idEspece, idUtilisateur) VALUE (?, ?, ?, ?, ?)");

        foreach($listAnimaux as $unAnimal){
            $unAnimal["idUtilisateur"] = $id;
            $unAnimalArray = array_values($unAnimal);
            $resUnAnimal = mysqli_stmt_execute($createAnimal, $unAnimalArray);
        }

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