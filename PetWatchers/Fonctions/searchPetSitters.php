<?php
    require_once(__DIR__ . "/loginBdd.php");
    $liaison = loginBDD();

    if(isset($_POST["petSitterSearchBar"])){
        $petSitterData = "%".$_POST["petSitterSearchBar"]."%";
        $joursActifs = json_decode($_POST["joursActifs"], true);

        $selectJour = $joursActifs != null ? ", etreDisponible as E " : "";
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

        $listDispos = mysqli_prepare($liaison, "SELECT J.libelle, E.tarif FROM etreDisponible as E, jour as J WHERE E.idJour = J.idJour AND E.idUtilisateur = ?");

        while($unPetSitter = mysqli_fetch_assoc($res)){
            $id = $unPetSitter["idUtilisateur"];
            if(isset($petSitterList[$id])){
                continue;
            }

            mysqli_stmt_execute($listDispos, [$unPetSitter["idUtilisateur"]]);
            $resListDispos = mysqli_stmt_get_result($listDispos);

            $dispos = [];
            while($uneDispo = mysqli_fetch_assoc($resListDispos)){
                $dispos[] = [
                    "jour" => $uneDispo["libelle"],
                    "tarif" => $uneDispo["tarif"]
                ];
            }

            $unPetSitter["dispos"] = $dispos;
            $petSitterList[$id] = $unPetSitter;
        }

        echo json_encode(array_values($petSitterList));
        mysqli_close($liaison);
    }
?>