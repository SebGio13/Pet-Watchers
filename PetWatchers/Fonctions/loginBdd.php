<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: *");
    header("Access-Control-Allow-Headers: *");
    
    /**
     * loginBDD
     * 
     * Fonction de connection à la base de donnée
     *
     * @return resource
     */
    function loginBDD(){
        // $liaison = mysqli_connect("localhost","root", "", "pet_watchers") or exit(mysqli_error());
        $liaison = mysqli_connect("mysql-petwatchers.alwaysdata.net", "petwatchers", "2d7Z75vNJyMJkuflBT1WO8j9", "petwatchers_database") or exit(mysqli_error());
        
        return $liaison;
    }
?>