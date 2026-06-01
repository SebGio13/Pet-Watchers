<?php
    require_once("verifToken.php");

    /**
     * createNavbar
     * 
     * Fonction de génération de la navbar sur les différentes pages
     *
     * @return void
     */
    function createNavbar(){

        // Récupération de l'URL présent dans la barre d'adresse
        $etatNavbar = '';
        $url = $_SERVER['PHP_SELF'];
        $url = substr($url, 1);

        // Création des différents URL présents dans les href des bouttons
        $urlIndex = './index.php';
        $urlLoginButton = './Login_Signin/login.php';
        $urlSigninButton = './Login_Signin/signin.php';

        if(str_contains($url, 'login.php')){
            $etatNavbar = 'login';
            $urlIndex = '../index.php';
            $urlSigninButton = './signin.php';
        } else if(str_contains($url, 'signin.php')){
            $etatNavbar = 'signin';
            $urlIndex = '../index.php';
            $urlLoginButton = "./login.php";
        }

        // Vérification de la présence d'un token et contrôle de ce dernier
        if(isset($_COOKIE["token"])){
            $tokenVerif = verifToken();

            if(!empty($tokenVerif)){
                $etatNavbar = "connecte";
            }
        }

        // Création des boutons de connexion et d'inscription
        $loginButton = '<a href="'.$urlLoginButton.'" class="button">Se connecter</a>';
        $signinButton = '<a href="'.$urlSigninButton.'" class="button buttonPrimary">S\'inscrire</a>';

        echo '<nav>';
            echo '<a href="'.$urlIndex.'"><img class="logoPetWatchers" src="./logoPetWatchers.png" onerror="this.src=\'../logoPetWatchers.png\'"/>Pet Watchers</a>';
            echo '<div>';
                switch($etatNavbar){
                    case "connecte":
                        echo '<button class="button buttonPrimary logoutButton">Se deconnecter</button>';
                        break;
                    
                    case "login":
                        echo '<span class="spanNavbar">Pas encore de compte ?</span>';
                        echo $signinButton;
                        break;
                    
                    case "signin":
                        echo '<span class="spanNavbar">Déjà inscrit ?</span>';
                        echo $loginButton;
                        break;

                    default:
                        echo $loginButton;
                        echo $signinButton;
                }
            echo '</div>';
        echo '</nav>';
    }
?>