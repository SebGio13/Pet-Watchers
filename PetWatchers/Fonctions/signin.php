<?php
    require_once("loginBdd.php");
    require_once("createToken.php");
    $liaison = loginBDD();

    if(isset($_POST["prenom"])){

        mysqli_begin_transaction($liaison);

        try{
            // Informations utilisateur
            $role = null;
            $prenom = $_POST["prenom"];
            $nom = $_POST["nom"];
            $mail = $_POST["mail"];
            $telephone = $_POST["telephone"];
            $adresse = $_POST["adresse"];
            $ville = $_POST["ville"];
            $codePostal = $_POST["codePostal"];
            $password = hash("sha256", $_POST["password"] . hash("sha256", "&bqG&Y?@D7zbNg7H"));
            
            // Insert du nouvel utilisateur et récupération de son idUtilisateur
            $createUser = mysqli_prepare($liaison, "INSERT INTO utilisateur (nom, prenom, numTelephone, adresse, codePostal, ville, mail, mdphache) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $resCreateUser = mysqli_stmt_execute($createUser, [$nom, $prenom,$telephone, $adresse, $codePostal, $ville, $mail, $password]);
            if(!$resCreateUser) throw new Exception("Erreur lors de la création d'un utilisateur");
            
            // Récupération de l'ID du dernier insert effectué
            $lastId = mysqli_insert_id($liaison);


            // Création du propriétaire et de ses animaux
            if(isset($_POST["listAnimaux"])){
                $role = "Proprietaire";
                $createOwner = "INSERT INTO proprietaire (idUtilisateur) VALUE ($lastId)";
                $resCreateOwner = mysqli_query($liaison, $createOwner);
                if(!$resCreateOwner) throw new Exception("Erreur lors de la création d'un propriétaire");

                $listAnimaux = json_decode($_POST["listAnimaux"], true);
                $createAnimal = mysqli_prepare($liaison, "INSERT INTO animal (nom, numTatouage, sexe, idEspece, idUtilisateur) VALUE (?, ?, ?, ?, ?)");

                foreach($listAnimaux as $unAnimal){
                    $unAnimal["idUtilisateur"] = $lastId;
                    $unAnimalArray = array_values($unAnimal);
                    $resUnAnimal = mysqli_stmt_execute($createAnimal, $unAnimalArray);

                    if(!$resUnAnimal) throw new Exception("Erreur lors de la création d'un animal");
                }
            }

            // Création du pet sitter
            if(isset($_POST["nbMaxAnimaux"]) && isset($_POST["listDispo"])){
                $role = "PetSitter";
                $nbMaxAnimaux = $_POST["nbMaxAnimaux"];
                $createPetSitter = mysqli_prepare($liaison, "INSERT INTO petsitter (idUtilisateur, nbMaxAnimaux) VALUES (?, ?)");
                $resCreatePetSitter = mysqli_stmt_execute($createPetSitter, [$lastId, $nbMaxAnimaux]);
                if(!$resCreatePetSitter) throw new Exception("Erreur lors de la création d'un pet sitter");

                // Remplissage de la table etreDisponible
                $listDispo = json_decode($_POST["listDispo"], true);
                $createDisp = mysqli_prepare($liaison, "INSERT INTO etredisponible (idJour, tarif, idUtilisateur) VALUE (?, ?, ?)");

                foreach($listDispo as $disp){
                    $disp["idUtilisateur"] = $lastId;
                    $dispArray = array_values($disp);
                    $resDisp = mysqli_stmt_execute($createDisp, $dispArray);
                    if(!$resDisp) throw new Exception("Erreur lors de l'insertion des jours disponibles");
                }
            }

            if($role == null){
                throw new Exception("Aucun rôle n'est défini");
            }

            mysqli_commit($liaison);

            // Récupération des informations de l'utilisateur inscrit afin de créer le token
            $getLastInsert = "SELECT * FROM utilisateur WHERE idUtilisateur = $lastId";
            $resGetLastInsert = mysqli_query($liaison, $getLastInsert);
            $utilisateur = mysqli_fetch_assoc($resGetLastInsert);

            // Création d'un token JWT
            createToken($utilisateur, $role);

            echo json_encode([
                "resultat" => true
            ]);

        } catch(Exception $e) {
            mysqli_rollback($liaison);

            echo json_encode([
                "resultat" => false
            ]);
        }
    }

    mysqli_close($liaison);
?>