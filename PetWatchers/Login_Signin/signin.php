<?php
    // array_map(function($file) { require $file; }, glob(__DIR__ . '/../Fonctions/*.php'));
    require_once(__DIR__ . "/../Fonctions/navbar.php");

    createNavbar();
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Pet Watchers - Page d'inscription</title>
        <link href="../style.css" rel="stylesheet"> 
    </head>
    <body>
        <div class="formSignInContainer">
            <div class="formSignIn">
                <a href="../index.php" class="backLink">◄ Retour à l'accueil</a>

                <!-- ================================================================ -->
                <!-- ===== Informations génrérales pour la création d'un compte ===== -->
                <!-- ================================================================ -->

                <h1 class="formTitle">Mon profil Pet Watchers</h1>
                <p class="formSubTitle">Renseignez vos coordonnées.</p>
                <div class="signInLayout">
                    <div class="sectionTitle">INFORMATIONS PERSONNELLES</div>
                    <div class="formRow">
                        <div class="field">
                            <label>PRENOM</label>
                            <input type="text" id="prenomSignIn" placeholder="Charles">
                        </div>
                        <div class="field">
                            <label>NOM</label>
                            <input type="text" id="nomSignIn" placeholder="Peguy">
                        </div>
                    </div>
                    <div class="formRow">
                        <div class="field">
                            <label>ADRESSE E-MAIL</label>
                            <input type="text" id="mailSignIn" placeholder="adresse@mail.fr">
                        </div>
                        <div class="field">
                            <label>NUMERO DE TELEPHONE</label>
                            <input type="text" id="telSignIn" placeholder="04 91 15 76 40">
                        </div>
                    </div>
                    <div class="field">
                        <label>ADRESSE COMPLETE</label>
                        <input type="text" id="adrSignIn" placeholder="102 Rue Sylvabelle">
                    </div>
                    <div class="formRow">
                        <div class="field">
                            <label>VILLE</label>
                            <input type="text" id="villeSignIn" placeholder="Marseille">
                        </div>
                        <div class="field">
                            <label>CODE POSTAL</label>
                            <input type="text" id="cpSignIn" placeholder="13006">
                        </div>
                    </div>
                    
                    <div class="formRow">
                        <div class="field">
                            <label>MOT DE PASSE</label>
                            <input type="password" id="passwordSignIn" placeholder="••••••••••••">
                        </div>
                        <div class="field">
                            <label>CONFIRMER LE MOT DE PASSE</label>
                            <input type="password" id="confPasswordSignIn" placeholder="••••••••••••">
                        </div>
                    </div>
                </div>





                <!-- =============================================================================== -->
                <!-- ===== Choix entre une inscription en temps que propriétaire ou pet sitter ===== -->
                <!-- =============================================================================== -->

                <h1 class="formTitle">Comment souhaitez vous utiliser Pet Watchers ?</h1>
                <p class="formSubTitle">Votre choix détermine les fonctionnalités disponibles dans votre espace.</p>
                <div class="choiceSignIn">
                    <button class="choiceCard green ownerCard">
                        <div class="choiceArrow">→</div>
                        <div class="choiceIcon greenIcon">🐶</div>
                        <h3>Je suis propriétaire</h3>
                        <p>J'ai des animaux et je recherche des personnes de confiance pour les garder.</p>
                    </button>
                    <button class="choiceCard petSitterCard">
                        <div class="choiceArrow">→</div>
                        <div class="choiceIcon">🏠</div>
                        <h3>Je suis pet sitter</h3>
                        <p>J'adore les animaux et je souhaite proposer mes services de garde.</p>
                    </button>
                </div>





                <!-- ========================================================================== -->
                <!-- ===== Formulaire supplémentaire selon le choix effectué précédemment ===== -->
                <!-- ========================================================================== -->
                 
                <!-- Formulaire pet sitter -->
                <div class="petSitterForm">
                    <div class="sectionTitle">Capacité d'accueil</div>
                    <div class="capacityRow">
                        <div style="font-size: 24px;">🐾</div>
                        <div class="capacityInfo">
                            <strong>Nombre maximum d'animaux</strong>
                            <p>Combien d'animaux pouvez-vous garder simultanément ?</p>
                        </div>
                        <div class="capacityControl">
                            <input type="text" id="nbMaxAnimaux" placeholder="Nombre d'animaux">
                        </div>
                    </div>
                    <div class="sectionTitle">Vos jours de disponibilité</div>
                    <div class="dispRow">

                        <div class="dispItem">
                            <label class="dispInfo">
                                <input type="checkbox" name="disp[lundi][actif]" value="1" data-jour="Lundi">
                                <span>LUNDI</span>
                            </label>
                            <div class="tarifField" id="tarifLundi">
                                <input type="number" name="disp[lundi][tarif]" placeholder="€ / jour" min="1" step="0.5">
                            </div>
                        </div>
                        
                        <div class="dispItem">
                            <label class="dispInfo">
                                <input type="checkbox" name="disp[mardi][actif]" value="2" data-jour="Mardi">
                                <span>MARDI</span>
                            </label>
                            <div class="tarifField" id="tarifMardi">
                                <input type="number" name="disp[mardi][tarif]" placeholder="€ / jour" min="0" step="0.5">
                            </div>
                        </div>
                        
                        <div class="dispItem">
                            <label class="dispInfo">
                                <input type="checkbox" name="disp[mercredi][actif]" value="3" data-jour="Mercredi">
                                <span>MERCREDI</span>
                            </label>
                            <div class="tarifField" id="tarifMercredi">
                                <input type="number" name="disp[mercredi][tarif]" placeholder="€ / jour" min="0" step="0.5">
                            </div>
                        </div>
                        
                        <div class="dispItem">
                            <label class="dispInfo">
                                <input type="checkbox" name="disp[jeudi][actif]" value="4" data-jour="Jeudi">
                                <span>JEUDI</span>
                            </label>
                            <div class="tarifField" id="tarifJeudi">
                                <input type="number" name="disp[jeudi][tarif]" placeholder="€ / jour" min="0" step="0.5">
                            </div>
                        </div>
                        
                        <div class="dispItem">
                            <label class="dispInfo">
                                <input type="checkbox" name="disp[vendredi][actif]" value="5" data-jour="Vendredi">
                                <span>VENDREDI</span>
                            </label>
                            <div class="tarifField" id="tarifVendredi">
                                <input type="number" name="disp[vendredi][tarif]" placeholder="€ / jour" min="0" step="0.5">
                            </div>
                        </div>
                        
                        <div class="dispItem">
                            <label class="dispInfo">
                                <input type="checkbox" name="disp[samedi][actif]" value="6" data-jour="Samedi">
                                <span>SAMEDI</span>
                            </label>
                            <div class="tarifField" id="tarifSamedi">
                                <input type="number" name="disp[samedi][tarif]" placeholder="€ / jour" min="0" step="0.5">
                            </div>
                        </div>
                        
                        <div class="dispItem">
                            <label class="dispInfo">
                                <input type="checkbox" name="disp[dimanche][actif]" value="7" data-jour="Dimanche">
                                <span>DIMANCHE</span>
                            </label>
                            <div class="tarifField" id="tarifDimanche">
                                <input type="number" name="disp[dimanche][tarif]" placeholder="€ / jour" min="0" step="0.5">
                            </div>
                        </div>

                    </div>
                    <button class="button buttonPrimary buttonMaxWidth signInButton">S'inscrire</button>
                </div>

                <!-- Formulaire propriétaire -->
                <div class="ownerForm">
                    <div class="sectionTitle">VOS ANIMAUX</div>
                    <div id="listAnimaux"></div>
                    <div class="formRow">
                        <div class="field">
                            <label>NOM DE L'ANIMAL</label>
                            <input type="text" id="nomAnimal" placeholder="Noah">
                        </div>
                        <div class="field">
                            <label>NUMERO DE TATOUAGE</label>
                            <input type="text" id="numTatouage" placeholder="250269400153998">
                        </div>
                    </div>
                    <div class="formRow">
                        <div class="field">
                            <label>SEXE</label>
                            <select id="sexeAnimal">
                                <option value="">Choix du sexe</option>
                                <option value="0">♂️ Mâle</option>
                                <option value="1">♀️ Femelle</option>
                            </select>
                        </div>
                        <div class="field">
                            <label>ESPECE</label>
                            <select id="especeAnimal">
                                <option value="">Choix de l'espèce</option>
                                <option value="1">🐶 Chien</option>
                                <option value="2">😺 Chat</option>
                            </select>
                        </div>
                    </div>
                    <button class="button buttonPrimary buttonMaxWidth signInButton">S'inscrire</button>
                </div>
            </div>
        </div>
        <script src="../script.js"></script>
    </body>
</html>