<?php
    require_once(__DIR__ . "/loginBdd.php");
    require_once(__DIR__ . "/createToken.php");
    $liaison = loginBDD();

    if(isset($_POST["loginMail"]) && isset($_POST["loginPassword"])){
        $mail = mysqli_real_escape_string($liaison, $_POST["loginMail"]);
        $password = mysqli_real_escape_string($liaison, $_POST["loginPassword"]);
        $password = hash("sha256", $password . hash("sha256", "&bqG&Y?@D7zbNg7H"));

        // Recherche de l'utilisateur dans la BDD en fonction du mail et mdp entrés
        $selectUser = mysqli_prepare($liaison, "SELECT * FROM utilisateur WHERE mail = ? AND mdphache = ?");
        mysqli_stmt_bind_param($selectUser, "ss", $mail, $password);

        $executeSelectUser = mysqli_stmt_execute($selectUser);
        $resSelectUser = mysqli_stmt_get_result($selectUser);
        $utilisateur = mysqli_fetch_assoc($resSelectUser);

        if($utilisateur){
            // Vérification s'il s'agit d'un propriétaire ou d'un pet sitter
            $checkRole = "SELECT * FROM proprietaire WHERE idUtilisateur = {$utilisateur['idUtilisateur']}";
            $resCheck = mysqli_query($liaison, $checkRole);
            if(mysqli_num_rows($resCheck) > 0){
                $role = "Proprietaire";
            } else {
                $role = "PetSitter";
            }

            createToken($utilisateur, $role);
            echo json_encode(["resultat" => true]);
        } else {
            echo json_encode(["resultat" => false]);
        }

        mysqli_close($liaison);
    }
?>