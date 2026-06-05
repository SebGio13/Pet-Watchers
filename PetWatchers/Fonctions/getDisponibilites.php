<?php
    require_once(__DIR__ . "/loginBdd.php");

    /**
     * getDisponibilites
     *
     * Fonction d'affichage de la capacité d'accueil et des jours de
     * disponibilité (avec tarif) du pet sitter passé en paramètre
     * 
     * @param  int $idUtilisateur
     * @return void
     */
    function getDisponibilites($idUtilisateur){
        $liaison = loginBDD();
        
        // Récupération de la capacité d'accueil du pet sitter
        $getCapacite = "SELECT nbMaxAnimaux FROM petSitter WHERE idUtilisateur = $idUtilisateur";
        $resGetCapacite = mysqli_query($liaison, $getCapacite);
        $ligneCapacite = mysqli_fetch_assoc($resGetCapacite);
        $nbMaxAnimaux = $ligneCapacite ? $ligneCapacite["nbMaxAnimaux"] : 1;

        // Récupération des jours de disponibilité du pet sitter
        $getDispos = "SELECT idJour, tarif FROM etreDisponible WHERE idUtilisateur = $idUtilisateur";
        $resGetDispos = mysqli_query($liaison, $getDispos);

        $dispos = [];
        while($uneDispo = mysqli_fetch_assoc($resGetDispos)){
            $dispos[$uneDispo["idJour"]] = $uneDispo["tarif"];
        }

        // Liste des jours pour récupérer leur libelle sans passer par la base de données
        $jours = [
            1 => "lundi",
            2 => "mardi",
            3 => "mercredi",
            4 => "jeudi",
            5 => "vendredi",
            6 => "samedi",
            7 => "dimanche"
        ];

        // Gestion de l'affichage des disponibilités dans index.php
        echo"
            <div class='sectionTitle'>CAPACITE D'ACCUEIL</div>
            <div class='capacityRow'>
                <div style='font-size: 24px;'>🐾</div>
                <div class='capacityInfo'>
                    <strong>Nombre maximum d'animaux</strong>
                    <p>Combien d'animaux pouvez vous garder simultanément ?</p>
                </div>
                <div class='capacityControl'>
                    <input type='text' id='editNbMaxAnimaux' value='".$nbMaxAnimaux."'>
                </div>
            </div>

            <div class='sectionTitle'>VOS JOURS DE DISPONIBILITE</div>
            <div class='dispRow'>
        ";

        // Affichage des jours
        foreach($jours as $idJour => $libelle){
            $estDispo = isset($dispos[$idJour]);
            $checked = $estDispo ? "checked" : "";
            $tarif = $estDispo ? $dispos[$idJour] : "";
            $lowLibelle = strtolower($libelle);
            $displayTarif = $estDispo ? "style='display: block;'" : "";

            echo"
                <div class='dispItem'>
                    <label class='dispInfo'>
                        <input type='checkbox' name='disp[".$lowLibelle."][actif]' value='".$idJour."' data-jour='".$libelle."' ".$checked.">
                        <span>".strtoupper($libelle)."</span>
                    </label>
                    <div class='tarifField' id='tarif".$libelle."' ".$displayTarif.">
                        <input type='number' name='disp[".$lowLibelle."][tarif]' placeholder='€ / jour' min='0' step='0.5' value='".$tarif."'>
                    </div>
                </div>
            ";
        }

        echo "</div>";

        mysqli_close($liaison);
    }
?>
