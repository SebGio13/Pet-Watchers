<?php
    require_once("loginBdd.php");
    $liaison = loginBDD();

    if(isset($_POST["petSitterSearchBar"])){
        $petSitterData = "%".$_POST["petSitterSearchBar"]."%";
        $joursActifs = json_decode($_POST["joursActifs"], true);

        $selectJour = $joursActifs != null ? ", etredisponible as E " : "";
        $whereJour = $joursActifs != null ? " AND U.idUtilisateur = E.idUtilisateur " : "";

        /* ##### A faire contrôler à Giroud pour requête préparée ##### */
        $searchPetSitter = "SELECT * FROM utilisateur as U, petSitter as P".$selectJour." WHERE U.idUtilisateur = P.idUtilisateur" . $whereJour;
        $searchPetSitter .= " AND(prenom LIKE ? OR nom LIKE ? OR ville LIKE ?)";

        if($joursActifs != null){
            $listJours = implode(",", array_fill(0, count($joursActifs), "?"));
            $searchPetSitter .= " AND E.idJour IN ($listJours)";
        }

        $params = [$petSitterData, $petSitterData, $petSitterData];
        if($joursActifs != null){
            $params = array_merge($params, $joursActifs);
        }
        $resSearchPetSitter = mysqli_prepare($liaison, $searchPetSitter);
        mysqli_stmt_execute($resSearchPetSitter, $params);
        $res = mysqli_stmt_get_result($resSearchPetSitter);

        $petSitterList = [];
        while($unPetSitter = mysqli_fetch_assoc($res)){
            $petSitterList[] = $unPetSitter;
        }

        echo json_encode($petSitterList);
        mysqli_close($liaison);
    }
?>