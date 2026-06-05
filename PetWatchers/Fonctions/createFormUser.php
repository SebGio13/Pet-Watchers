<?php
    
    /**
     * createFormUser
     * 
     * Fonction qui génère le formulaire de modification des informations de l'utilisateur
     *
     * @param  Array $token
     * @return void
     */
    function createFormUser($token){
        echo"
            <div class='sectionTitle'>INFORMATIONS PERSONNELLES</div>
            <div class='formRow'>
                <div class='field'>
                    <label>PRENOM</label>
                    <input type='text' id='editPrenom' value=".$token['prenom'].">
                </div>
                <div class='field'>
                    <label>NOM</label>
                    <input type='text' id='editNom' value=".$token['nom'].">
                </div>
            </div>

            <div class='formRow'>
                <div class='field'>
                    <label>ADRESSE E-MAIL</label>
                    <input type='text' id='editMail' value=".$token['mail'].">
                </div>
                <div class='field'>
                    <label>NUMERO DE TELEPHONE</label>
                    <input type='text' id='editTelephone' value=".$token['numTelephone'].">
                </div>
            </div>

            <div class='field'>
                <label>ADRESSE COMPLETE</label>
                <input type='text' id='editAdresse' value=".$token['adresse'].">
            </div>

            <div class='formRow'>
                <div class='field'>
                    <label>VILLE</label>
                    <input type='text' id='editVille' value=".$token['ville'].">
                </div>
                <div class='field'>
                    <label>CODE POSTAL</label>
                    <input type='text' id='editCp' value=".$token['codePostal'].">
                </div>
            </div>

            <div class='sectionTitle'>CHANGER LE MOT DE PASSE</div>
            <div class='formRow'>
                <div class='field'>
                    <label>NOUVEAU MOT DE PASSE</label>
                    <input type='password' id='editPassword' placeholder='••••••••••••'>
                </div>
                <div class='field'>
                    <label>CONFIRMER LE MOT DE PASSE</label>
                    <input type='password' id='editConfPassword' placeholder='••••••••••••'>
                </div>
            </div>

            <div class='profileSaveRow'>
                <button class='button buttonPrimary buttonMaxWidth saveProfilButton'>Enregistrer les modifications</button>
            </div>
        ";
    }
?>