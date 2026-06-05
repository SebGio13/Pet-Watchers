<?php
    require_once(__DIR__ . "/loginBdd.php");

    /**
     * getDemandes
     *
     * Fonction d'affichage de la liste des demandes reçues par le pet sitter
     *
     * @param  int $idPetSitter
     * @return void
     */
    function getDemandes($idPetSitter){
        $liaison = loginBDD();

        // Récupération des demandes reçues par le pet sitter, avec les infos du propriétaire
        $getDemandes = "SELECT D.idDemande, D.statut, D.dateDemande, U.idUtilisateur, U.nom, U.prenom, U.ville, U.codePostal FROM demande as D, utilisateur as U WHERE D.idProprietaire = U.idUtilisateur AND D.idPetSitter = $idPetSitter";
        $resGetDemandes = mysqli_query($liaison, $getDemandes);

        // Si aucune demande, on affiche un message et on s'arrête
        if(mysqli_num_rows($resGetDemandes) == 0){
            echo "<p class='noResults'>Vous n'avez aucune demande pour le moment.</p>";
            mysqli_close($liaison);
            return;
        }

        // Préparation de la requête de récupération des animaux d'un propriétaire
        $getAnimaux = mysqli_prepare($liaison, "SELECT nom, idEspece FROM animal WHERE idUtilisateur = ?");

        echo "<div class='demandesList' id='demandesList'>";

        while($uneDemande = mysqli_fetch_assoc($resGetDemandes)){

            // Récupération des animaux du propriétaire de cette demande
            mysqli_stmt_execute($getAnimaux, [$uneDemande["idUtilisateur"]]);
            $resAnimaux = mysqli_stmt_get_result($getAnimaux);

            $tagsAnimaux = "";
            $premierEmoji = "🐾";
            $premier = true;
            while($unAnimal = mysqli_fetch_assoc($resAnimaux)){
                $emoji = $unAnimal["idEspece"] == 1 ? "🐶" : "😺";
                if($premier){
                    $premierEmoji = $emoji;
                    $premier = false;
                }
                $tagsAnimaux .= "<span class='animalTag'>".$emoji." ".$unAnimal["nom"]."</span>";
            }

            // Détermination de la classe CSS et du libellé du statut
            switch($uneDemande["statut"]){
                case "Acceptée":
                    $classeStatut = "accepted";
                    break;
                case "Refusée":
                    $classeStatut = "refused";
                    break;
                default:
                    $classeStatut = "pending";
            }

            // Les boutons Accepter/Refuser ne s'affichent que si la demande est en attente
            $actions = "";
            if($uneDemande["statut"] == "En attente"){
                $actions = "
                    <button class='demandeActionBtn accept acceptDemandeBtn' data-iddemande='".$uneDemande["idDemande"]."'>Accepter</button>
                    <button class='demandeActionBtn refuse refuseDemandeBtn' data-iddemande='".$uneDemande["idDemande"]."'>Refuser</button>
                ";
            }

            echo "
                <div class='demandeCard' data-id-demande='".$uneDemande["idDemande"]."'>
                    <div class='demandeAvatar'>".$premierEmoji."</div>
                    <div class='demandeInfo'>
                        <div class='demandeOwnerName'>".$uneDemande["prenom"]." ".$uneDemande["nom"]."</div>
                        <div class='demandeMeta'>
                            <span>📍 ".$uneDemande["ville"]." ".$uneDemande["codePostal"]."</span>
                            <span>📅 ".$uneDemande["dateDemande"]."</span>
                        </div>
                        <div class='demandeAnimaux'>".$tagsAnimaux."</div>
                    </div>
                    <span class='demandeStatus ".$classeStatut."'>".$uneDemande["statut"]."</span>
                    <div class='demandeActions'>".$actions."</div>
                </div>
            ";
        }

        echo "</div>";

        mysqli_close($liaison);
    }
?>
