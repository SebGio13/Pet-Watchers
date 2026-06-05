<?php
    require_once("./Fonctions/navbar.php");
    require_once("./Fonctions/createFormUser.php");
    require_once("./Fonctions/displayAnimal.php");
    require_once("./Fonctions/getDisponibilites.php");
    require_once("./Fonctions/getDemandes.php");

    // Détermination de l'état de la page en fonction de l'utilisateur
    $etatPage = "Visiteur";

    if(isset($_COOKIE["token"])){
        $token = verifToken();
        if(!empty($token)){
            if($token["roleUtilisateur"] == "Proprietaire"){
                $etatPage = "Proprietaire";
            } else if($token["roleUtilisateur"] == "PetSitter"){
                $etatPage = "PetSitter";
            }
        }
    }
?>

<html>
    <head>
        <meta charset="utf-8">
        <title>Pet Watchers - Index</title>
        <link href="style.css" rel="stylesheet">
    </head>
    <body>
        <?php createNavbar() ?>

        <!-- ==================================== -->
        <!-- ===== Utilisateur non connecté ===== -->
        <!-- ==================================== -->
        <?php if($etatPage == "Visiteur"): ?>
        <div class="visiteur">
            <div class="visiteurBadge">🐾 La garde d'animaux réinventée 🐾</div>
            <h1 class="visiteurTitle">Trouvez le <span>pet sitter</span><br>idéal pour votre animal</h1>
            <p class="visiteurSubTitle">Pet Watchers met en relation des propriétaires d'animaux avec des passionnés prêts à les garder en toute sécurité, près de chez vous.</p>
            <div class="visiteurButton">
                <a href="./Login_Signin/signin.php" class="button buttonPrimary">Créer un compte gratuitement</a>
                <a href="./Login_Signin/login.php" class="button">Se connecter</a>
            </div>
        </div>





        <!-- ========================= -->
        <!--  ===== Propriétaire ===== -->
        <!-- ========================= -->
        <?php elseif($etatPage == "Proprietaire"): ?>
        <div class="indexContainer">

            <div class="indexHeader">
                <p class="indexGreeting">Bonjour <?php echo $token["prenom"]; ?> 👋</p>
                <h1 class="indexTitle">Mon espace propriétaire</h1>
            </div>

            <div class="tabs">
                <button class="tabButton active" data-tab="profil">Mon profil</button>
                <button class="tabButton" data-tab="animaux">Mes animaux</button>
                <button class="tabButton" data-tab="petsitters">Trouver un Pet Sitter</button>
            </div>

            <!-- Mon Profil -->
            <div class="tabPanel active" id="tab-profil">
                <?php createFormUser($token) ?>
            </div>

            <!-- Mes animaux -->
            <div class="tabPanel" id="tab-animaux">
                <div class="animauxHeader">
                    <h2 class="animauxTilte">Mes animaux</h2>
                </div>

                <div id="listeAnimaux">
                    <?php displayAnimal($token["idUtilisateur"]) ?>
                </div>

                <div class="addAnimalBlock">
                    <div class="addAnimalBlockTitle">AJOUTER UN ANIMAL</div>
                    <div class="formRow">
                        <div class="field">
                            <label>NOM DE L'ANIMAL</label>
                            <input type="text" id="newNomAnimal" placeholder="Noah">
                        </div>
                        <div class="field">
                            <label>NUMERO DE TATOUAGE</label>
                            <input type="text" id="newNumTatouage" placeholder="250269400153998">
                        </div>
                    </div>
                    <div class="formRow">
                        <div class="field">
                            <label>SEXE</label>
                            <select id="newSexeAnimal">
                                <option value="">Choix du sexe</option>
                                <option value="0">♂️ Mâle</option>
                                <option value="1">♀️ Femelle</option>
                            </select>
                        </div>
                        <div class="field">
                            <label>ESPECE</label>
                            <select id="newEspeceAnimal">
                                <option value="">Choix de l'espèce</option>
                                <option value="1">🐶 Chien</option>
                                <option value="2">😺 Chat</option>
                            </select>
                        </div>
                    </div>
                    <div class="profileSaveRow">
                        <button class="button buttonPrimary buttonMaxWidth addAnimalButton">Enregistrer de nouveaux animaux</button>
                    </div>
                </div>
            </div>

            <!-- Trouver un Pet Sitter -->
            <div class="tabPanel" id="tab-petsitters">
                <div class="searchBar">
                    <input type="text" id="searchPetSitter" placeholder="🔍  Rechercher par ville, prénom…">
                    <button class="button buttonPrimary searchPetSitterButton">Rechercher</button>
                </div>

                <div class="filterRow">
                    <span class="filterLabel">Disponible le :</span>
                    <button class="filterChip" data-jour="1">Lundi</button>
                    <button class="filterChip" data-jour="2">Mardi</button>
                    <button class="filterChip" data-jour="3">Mercredi</button>
                    <button class="filterChip" data-jour="4">Jeudi</button>
                    <button class="filterChip" data-jour="5">Vendredi</button>
                    <button class="filterChip" data-jour="6">Samedi</button>
                    <button class="filterChip" data-jour="7">Dimanche</button>
                </div>

                <div class="petSitterList" id="petSitterList"></div> <!-- A remplir via PHP -->
            </div>
        </div>





        <!-- ========== -->
        <!-- Pet Sitter -->
        <!-- ========== -->
        <?php elseif($etatPage == "PetSitter"):  ?>
        <div class="indexContainer">
            <div class="indexHeader">
                <p class="indexGreeting">Bonjour <?php echo $token["prenom"]; ?> 👋</p>
                <h1 class="indexTitle">Mon espace propriétaire</h1>
            </div>

            <div class="tabs">
                <button class="tabButton active" data-tab="profil">Mon profil</button>
                <button class="tabButton" data-tab="disponibilites">Mes disponibilités</button>
                <button class="tabButton" data-tab="demandes">Mes demandes</button>
            </div>

            <!-- Mon Profil -->
            <div class="tabPanel active" id="tab-profil">
                <?php createFormUser($token) ?>
            </div>

            <!-- Mes disponibilités -->
            <div class="tabPanel" id="tab-disponibilites">
                <?php getDisponibilites($token["idUtilisateur"]); ?>
            </div>

            <!-- Mes demandes -->
            <div class="tabPanel" id="tab-demandes">
                <?php getDemandes($token["idUtilisateur"]); ?>
            </div>
        </div>

        
        <?php endif; ?>

    </body>
    <script src="./script.js"></script>
</html>