currentPage = document.location.href

if(currentPage.includes("signin.php")){
    /* ================================================================== */
    /* ===== Affichage du formulaire correspondant au choix du rôle ===== */
    /* ================================================================== */
    let petSitterCard = document.querySelector(".petSitterCard")
    let ownerCard = document.querySelector(".ownerCard")

    // Formulaire pour pet sitter
    petSitterCard.addEventListener("click", function(){
        document.querySelector(".petSitterForm").style.display = "block"
        document.querySelector(".ownerForm").style.display = "none"
    })
    // Formulaire pour propriétaire
    ownerCard.addEventListener("click", function(){
        document.querySelector(".ownerForm").style.display = "block"
        document.querySelector(".petSitterForm").style.display = "none"
    })





    /* ======================================================== */
    /* ===== Affichage du champ tarif selon le jour coché ===== */
    /* ======================================================== */
    document.querySelectorAll(".dispInfo input[type='checkbox']").forEach(function(checkbox) {
        checkbox.addEventListener("change", function(){
            let jour = this.dataset.jour

            let tarifField = document.getElementById("tarif" + jour)
            if(this.checked){
                tarifField.style.display = "block"
            } else {
                tarifField.style.display = "none"
                tarifField.querySelector("input").value = ""
            }
        })
    })





    /* ========================================================================= */
    /* ===== Bouton d'ajout d'un nouvel animal au formulaire d'inscription ===== */
    /* ========================================================================= */
    var listAnimaux = []
    ownerForm = document.querySelector(".ownerForm")
    ownerForm.addEventListener("change", function(){

        /* Informations d'un animal */
        let nomAnimal = document.getElementById("nomAnimal").value
        let tatouage = document.getElementById("numTatouage").value
        let sexeAnimal = document.getElementById("sexeAnimal").value
        let espece = document.getElementById("especeAnimal").value

        if(nomAnimal != "" && tatouage != "" && sexeAnimal != "" && espece != ""){
            let animal = {
                nomAnimal : nomAnimal,
                numTatouage : tatouage,
                sexeAnimal : sexeAnimal,
                especeAnimal : espece
            };

            /* On vide les champs du formulaire pour permettre un nouvel ajout */
            document.getElementById("nomAnimal").value = ""
            document.getElementById("numTatouage").value = ""
            document.getElementById("sexeAnimal").value = ""
            document.getElementById("especeAnimal").value = ""

            /* Affichage sur le formulaire du dernier animal ajouté */
            let animalRow = document.createElement("div")
            animalRow.setAttribute("class", "animalRow")

            let animalLogo = document.createElement("div")
            animalLogo.setAttribute("class", "animalLogo")
            let logo = espece == 1 ? "🐶" : "😺"
            animalLogo.innerHTML = logo

            let animalInfo = document.createElement("div")
            animalInfo.setAttribute("class", "animalInfo")

            let animalName = document.createElement("div")
            animalName.setAttribute("class", "animalNam")
            let sexe = sexeAnimal == 0 ? "♂️" : "♀️"
            animalName.innerHTML = nomAnimal + sexe

            let animalMeta = document.createElement("div")
            animalMeta.setAttribute("class", "animalMeta")
            let meta = "Numéro de tatouage: " + tatouage
            animalMeta.innerHTML = meta

            animalInfo.appendChild(animalName)
            animalInfo.appendChild(animalMeta)
            animalRow.appendChild(animalLogo)
            animalRow.appendChild(animalInfo)
            

            let divAnimaux = document.getElementById("listAnimaux")
            divAnimaux.appendChild(animalRow)

            listAnimaux.push(animal)
        }
    })





    /* =========================================== */
    /* ===== Gestion du bouton d'inscription ===== */
    /* =========================================== */
    let signInButtons = document.querySelectorAll(".signInButton")
    signInButtons.forEach(signInButton => {
        signInButton.addEventListener("click", function(){
            
            /* Informations générales */
            let prenom = document.getElementById("prenomSignIn").value
            let nom = document.getElementById("nomSignIn").value
            let mail = document.getElementById("mailSignIn").value
            let telephone = document.getElementById("telSignIn").value
            let adresse = document.getElementById("adrSignIn").value
            let ville = document.getElementById("villeSignIn").value
            let codePostal = document.getElementById("cpSignIn").value
            let password = document.getElementById("passwordSignIn").value
            let confirmPassword = document.getElementById("confPasswordSignIn").value

            /* Confirmation que tous les champs du formulaire "Informations générales" ont bien été remplis */
            if(prenom != "" && nom != "" && mail != "" && telephone != "" && ville != "" && codePostal != "" && password != "" && confirmPassword != ""){
                /* Confirmation que les champs "Mot de passe" et "Confirmer mot de passe" correspondent */
                if(password == confirmPassword){

                    let signInData = new FormData();
                    signInData.append("prenom", prenom)
                    signInData.append("nom", nom)
                    signInData.append("mail", mail)
                    signInData.append("telephone", telephone)
                    signInData.append("adresse", adresse)
                    signInData.append("ville", ville)
                    signInData.append("codePostal", codePostal)
                    signInData.append("password", password)
                    signInData.append("confirmPassword", confirmPassword)


                    /* Gestion des champs en fonction du choix de l'inscription */
                    ownerFormDisplay = document.querySelector(".ownerForm")
                    ownerFormDisplay = window.getComputedStyle(ownerFormDisplay).display
                    petSitterFormDisplay = document.querySelector(".petSitterForm")
                    petSitterFormDisplay = window.getComputedStyle(petSitterFormDisplay).display

                    // Champs propriétaire
                    if(ownerFormDisplay == "block" && petSitterFormDisplay == "none"){
                        listAnimaux = JSON.stringify(listAnimaux)
                        signInData.append("listAnimaux", listAnimaux)
                    // Champs pet sitter
                    } else if(ownerFormDisplay == "none" && petSitterFormDisplay == "block"){
                        let nbMaxAnimaux = document.getElementById("nbMaxAnimaux").value
                        signInData.append("nbMaxAnimaux", nbMaxAnimaux)

                        // Récupération des jours disponible entré dans le formulaire
                        let listDispo = []
                        document.querySelectorAll(".dispItem").forEach(function(item) {
                            let checkboxJour = item.querySelector("input[type='checkbox']")
                            let tarifField = item.querySelector("input[type='number']")

                            if(checkboxJour.checked){
                                let jour = checkboxJour.value
                                let tarif = tarifField.value

                                if(tarif != ""){
                                    let dispo = {
                                        idJour : jour,
                                        tarif : tarif
                                    }

                                    listDispo.push(dispo)
                                } else {
                                    alert("Il semblerait que vous ayez oublié d'indiquer votre tarif pour un ou plusieurs jours.")
                                }
                            }
                        })

                        if(listDispo.length > 0 ){
                            listDispo = JSON.stringify(listDispo)
                            signInData.append("listDispo", listDispo)
                        } else {
                            alert("N'oubliez pas d'indiquer vos jours de disponibilité, les propriétaires en ont besoin pour vous contacter !")
                        }
                        
                    }

                    fetch("http://localhost/PetWatchers/Fonctions/signin.php", {
                        method: "POST",
                        body: signInData
                    })
                    .then(res => res.json())
                    .then(data => {

                        if(data["resultat"]){
                            alert("Votre compte a bien été créé, nous sommes ravis de vous avoir parmi nous.")
                            window.location.href = "http://localhost/PetWatchers/index.php"
                        } else {
                            alert("Quelque chose s'est mal passé de notre côté... Pas d'inquiétude, réessayez dans quelques instants !")
                        }
                        
                    })
                } else {
                    alert("Vos deux mots de passe ne semblent pas identiques, vérifiez bien la saisie et réessayez.")
                }
            } else {
                alert("Il reste des champs vides ! Prenez le temps de tout remplir, c'est important pour créer votre profil.")
            }
        })
    })
} 




else if(currentPage.includes("login.php")){
    /* ========================================== */
    /* ===== Gestion du bouton de connexion ===== */
    /* ========================================== */
    let loginButton = document.querySelector(".loginButton")
    loginButton.addEventListener("click", function(){
        
        let loginMail = document.getElementById("mailLogin").value
        let loginPassword = document.getElementById("passwordLogin").value

        if(loginMail != "" && loginPassword != ""){
            let loginData = new FormData()
            loginData.append("loginMail", loginMail)
            loginData.append("loginPassword", loginPassword)

            fetch("http://localhost/PetWatchers/Fonctions/login.php", {
                method: "POST",
                body: loginData
            })
            .then(res => res.json())
            .then(data => {
                if(data["resultat"]){
                    alert("Content de vous revoir ! Vous êtes bien connecté.")
                    window.location.href = "http://localhost/PetWatchers/index.php"
                } else {
                    alert("Quelque chose s'est mal passé de notre côté... Pas d'inquiétude, réessayez dans quelques instants !")
                }
                
            })
        } else {
            alert("Il semblerait que certains champs soient vides, prenez le temps de tout remplir pour vous connecter.")
        }
    })
}





/* ============================================ */
/* ===== Gestion du bouton de déconnexion ===== */
/* ============================================ */
let logoutButton = document.querySelector(".logoutButton")
if(logoutButton){
    logoutButton.addEventListener("click", function(){
        fetch("http://localhost/PetWatchers/Fonctions/logout.php", {
            method: "POST"
        })
        .then(res => res.json())
        .then(data => {
            if(data["resultat"]){
                alert("Vous êtes bien deconnecté !")
                window.location.href = "http://localhost/PetWatchers/index.php"
            }
        })
    })
}





/* ================================================================= */
/* ===== Index - Gestion des onglets de l'utilisateur connecté ===== */
/* ================================================================= */
document.querySelectorAll(".tabButton").forEach(function(btn) {
    btn.addEventListener("click", function() {
        document.querySelectorAll(".tabButton").forEach(b => b.classList.remove("active"))
        document.querySelectorAll(".tabPanel").forEach(p => p.classList.remove("active"))
        this.classList.add("active")
        document.getElementById("tab-" + this.dataset.tab).classList.add("active")
    })
})





/* ========================================================= */
/* ===== Index - Sauvegarde des informations du profil ===== */
/* ========================================================= */





/* ============================================================= */
/* ===== Index - Propriétaire - Gestion du filtre par jour ===== */
/* ============================================================= */
document.querySelectorAll(".filterChip").forEach(function(e) {
    e.addEventListener("click", function() {
        this.classList.toggle("active")
    })
})





/* ===================================================== */
/* ===== Index - Propriétaire - Barre de recherche ===== */
/* ===================================================== */
let searchPetSitterButton = document.querySelector(".searchPetSitterButton")
if(searchPetSitterButton){
    searchPetSitterButton.addEventListener("click", function(){
        let petSitterSearchBar = document.getElementById("searchPetSitter").value
        let joursActif = []

        if(petSitterSearchBar != ""){
            document.querySelectorAll(".filterChip.active").forEach(function(e){
                joursActifs.push(chip.dataset.jour)
            })

            let searchData = new FormData()
            searchData.append("petSitterSearchBar", petSitterSearchBar)
            if(typeof joursActifs != null && joursActifs.length > 0){
                searchData.append("joursActifs", joursActif)
            } else {
                searchData.append("joursActifs", null)
            }

            fetch("http://localhost/PetWatchers/Fonctions/searchPetSitters.php", {
                    method: "POST",
                    body: searchData
                })
                .then(res => res.json())
                .then(data => {
                    let list = document.getElementById("petSitterList")
                    list.innerHTML = ""

                    if(data.length == 0) {
                        list.innerHTML = "<p class='noResults'>Aucun pet sitter trouvé pour cette recherche.</p>"
                        return
                    }

                    data.forEach(function(ps) {
                        let tags = ps.dispos.map(j => `<span class="dispTag">${j}</span>`).join("")
                        let card = document.createElement("div")
                        card.setAttribute("class", "petSitterCard")
                        card.innerHTML = `
                            <div class="petSitterAvatar">🐾</div>
                            <div class="petSitterInfo">
                                <div class="petSitterName">${ps.prenom} ${ps.nom.charAt(0)}.</div>
                                <div class="petSitterMeta">
                                    <span>📍 ${ps.ville} ${ps.cp}</span>
                                    <span>🐾 Max. ${ps.nbMax} animaux</span>
                                </div>
                                <div class="petSitterDispoTags">${tags}</div>
                            </div>
                            <div class="petSitterActions">
                                <button class="button buttonPrimary contactPetSitterButton">Contacter</button>
                            </div>
                        `
                        list.appendChild(card)
                    })
                })
        } else {
            alert("Merci de remplir la barre de recherche") // ########## A rendre plus corpo
        }
    })
}