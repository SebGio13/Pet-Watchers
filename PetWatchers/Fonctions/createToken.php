<?php
    /**
     * createToken
     * 
     * Fonction de création du token JWT lors de l'inscription ou la connexion d'un utilisateur
     *
     * @param  String $id
     * @return String
     */
    function createToken($utilisateur, $role){
        $headerToken = [
            "alg" => "HS256",
            "typ" => "JWT"
        ];
        $headerToken = base64_encode(json_encode($headerToken));
        $headerToken = str_replace("=", "", $headerToken);
        $headerToken = str_replace("/", "_", $headerToken);
        $headerToken = str_replace("+", "-", $headerToken);

        $paylodToken = [
            "idUtilisateur" => $utilisateur["idUtilisateur"],
            "nom" => $utilisateur["nom"],
            "prenom" => $utilisateur["prenom"],
            "numTelephone" => $utilisateur["numTelephone"],
            "adresse" => $utilisateur["adresse"],
            "codePostal" => $utilisateur["codePostal"],
            "ville" => $utilisateur["ville"],
            "mail" => $utilisateur["mail"],
            "roleUtilisateur" => $role,
            "exp" => time() + 900
        ];
        $paylodToken = base64_encode(json_encode($paylodToken));
        $paylodToken = str_replace("=", "", $paylodToken);
        $paylodToken = str_replace("/", "_", $paylodToken);
        $paylodToken = str_replace("+", "-", $paylodToken);

        $signatureToken = hash_hmac("sha256", "$headerToken.$paylodToken", "nA@JdDMbhE8Agj!x");

        $tokenJWT = "$headerToken.$paylodToken.$signatureToken";

        setcookie("token", $tokenJWT, [
            "expires" => time() + 900,
            "httponly" => true,
            "samesite" => "Strict",
            "path" => "/"
        ]);
    }
?>