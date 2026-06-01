<?php
    
    /**
     * verifToken
     * 
     * Fonction de vérification du token présent dans les cookies
     *
     * @return array
     */
    function verifToken(){

        if(isset($_COOKIE["token"])){
            $token = $_COOKIE["token"];

            $tokenExplode = explode(".", $token);
            $headerToken = $tokenExplode[0];
            $paylodToken = $tokenExplode[1];
            $signatureToken = $tokenExplode[2];

            $signatureAttendue = hash_hmac("sha256", "$headerToken.$paylodToken", "nA@JdDMbhE8Agj!x");

            if($signatureToken != $signatureAttendue){
                echo json_encode(["resultat" => false, "message" => "Token invalide"]);
                return null;
            } else {
                $paylodVerif = json_decode(base64_decode($paylodToken), true);
                return $paylodVerif;
            }
        } else {
            echo json_encode(["resultat" => false, "message" => "Non connecté"]);
            return null;
        }
    }
?>