<?php
    require_once(__DIR__ . "/loginBdd.php");

    /**
     * displayAnimal
     *
     * Fonction permettant l'affichage de la liste des animaux de l'utilisateur
     * 
     * @param  int $id
     * @return void
     */
    function displayAnimal($id){
        $liaison = loginBDD();

        // Récupération des informations des animaux de l'utilisateur depuis la BDD
        $getAnimaux = "SELECT * FROM animal WHERE idUtilisateur = $id";
        $resGet = mysqli_query($liaison, $getAnimaux);

        
        // Affichage des animaux
        while($unAnimal = mysqli_fetch_assoc($resGet)){
            $sexe = $unAnimal["sexe"] == 0 ? "♂️" : "♀️";
            $espece = $unAnimal["idEspece"] == 1 ? "🐶" : "😺";

            echo"
                <div class='animalRow'>
                    <div class='animalLogo'>".$espece."</div>
                    <div class='animalInfo'>
                        <div class='animalName'>".$unAnimal['nom']." ".$sexe."</div>
                        <div class='animalMeta'>Tatouage: ".$unAnimal['numTatouage']."</div>
                    </div>
                    <div class='animalRowAction'>
                        <!-- <button class='animalActionBtn editAnimalBtn'>Modifier</button> -->
                        <!-- <button class='animalActionBtn danger deleteAnimalBtn'>Supprimer</button> -->
                    </div>
                </div>
            ";
        }

        mysqli_close($liaison);
    }
?>