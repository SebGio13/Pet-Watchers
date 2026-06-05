currentPage = document.location.href

if(currentPage.includes("signin.php")){
    /* ================================================================== */
    /* ===== Affichage du formulaire correspondant au choix du rôle ===== */
    /* ================================================================== */
    let petSitterCard = document.querySelector(".petSitterChoice")
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





    /* ================================================================ */
    /* ===== Ajout d'un nouvel animal au formulaire d'inscription ===== */
    /* ================================================================ */
    var listAnimaux = []
    ownerForm = document.querySelector(".ownerForm")
    ownerForm.addEventListener("change", function(){

        /* Informations d'un animal */
        let nomAnimal = document.getElementById("nomAnimal").value
        let tatouage = document.getElementById("numTatouage").value
        let sexeAnimal = document.getElementById("sexeAnimal").value
        let espece = document.getElementById("especeAnimal").value

        if(nomAnimal != "" && tatouage != "" && sexeAnimal != "" && espece != ""){
            let divAnimaux = document.getElementById("listAnimaux")
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
            fillListAnimaux(nomAnimal, tatouage, sexeAnimal, espece, divAnimaux)

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

                    fetch("/Fonctions/signin.php", {
                        method: "POST",
                        body: signInData
                    })
                    .then(res => res.json())
                    .then(data => {

                        if(data["resultat"]){
                            alert("Votre compte a bien été créé, nous sommes ravis de vous avoir parmi nous.")
                            window.location.href = "/index.php"
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

            fetch("/Fonctions/login.php", {
                method: "POST",
                body: loginData
            })
            .then(res => res.json())
            .then(data => {
                if(data["resultat"]){
                    alert("Content de vous revoir ! Vous êtes bien connecté.")
                    window.location.href = "/index.php"
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
        fetch("/Fonctions/logout.php", {
            method: "POST"
        })
        .then(res => res.json())
        .then(data => {
            if(data["resultat"]){
                alert("Vous êtes bien deconnecté !")
                window.location.href = "/index.php"
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
let updateButton = document.querySelector(".saveProfilButton")
if(updateButton){
    updateButton.addEventListener("click", function(){

        let newPrenom = document.getElementById("editPrenom").value
        let newNom = document.getElementById("editNom").value
        let newMail = document.getElementById("editMail").value
        let newTelephone = document.getElementById("editTelephone").value
        let newAdresse = document.getElementById("editAdresse").value
        let newVille = document.getElementById("editVille").value
        let newCodePostal = document.getElementById("editCp").value
        let newPassword = document.getElementById("editPassword").value
        let newConfirmPassword = document.getElementById("editConfPassword").value

        if(newPrenom != "" && newNom != "" && newMail != "" && newTelephone != "" && newVille != "" && newCodePostal != "" && newPassword != "" && newConfirmPassword != ""){
            /* Confirmation que les champs "Nouveau Mot de passe" et "Confirmer le mot de passe" correspondent */
            if(newPassword == newConfirmPassword){

                let newSignInData = new FormData();
                newSignInData.append("newPrenom", newPrenom)
                newSignInData.append("newNom", newNom)
                newSignInData.append("newMail", newMail)
                newSignInData.append("newTelephone", newTelephone)
                newSignInData.append("newAdresse", newAdresse)
                newSignInData.append("newVille", newVille)
                newSignInData.append("newCodePostal", newCodePostal)
                newSignInData.append("newPassword", newPassword)
                newSignInData.append("newConfirmPassword", newConfirmPassword)

                fetch("/Fonctions/updateUserData.php", {
                    method: "POST",
                    body: newSignInData
                })
                .then(res => res.json())
                .then(data => {

                    if(data["resultat"]){
                        alert("Vos informations ont bien été modifiées !")
                        window.location.href = "/index.php"
                    } else {
                        alert("Quelque chose s'est mal passé de notre côté... Pas d'inquiétude, réessayez dans quelques instants !")
                    }
                    
                })
            } else {
                alert("Vos deux mots de passe ne semblent pas identiques, vérifiez bien la saisie et réessayez.")
            }
        } else {
            alert("Il reste des champs vides ! Prenez le temps de tout remplir, c'est important pour modifier votre profil.")
        }
    })
}





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
        let joursActifs = []

        if(petSitterSearchBar != ""){
            document.querySelectorAll(".filterChip.active").forEach(function(e){
                joursActifs.push(e.dataset.jour)
            })

            let searchData = new FormData()
            searchData.append("petSitterSearchBar", petSitterSearchBar)
            if(joursActifs.length > 0){
                searchData.append("joursActifs", JSON.stringify(joursActifs))
            } else {
                searchData.append("joursActifs", null)
            }

            fetch("/Fonctions/searchPetSitters.php", {
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
                        let tags = ps.dispos.map(d => `<span class="dispTag">${d.jour} · ${d.tarif}€</span>`).join("")
                        let card = document.createElement("div")
                        card.setAttribute("class", "petSitterCard")
                        card.innerHTML = `
                            <div class="petSitterInfo">
                                <div class="petSitterName">${ps.prenom} ${ps.nom}</div>
                                <div class="petSitterMeta">
                                    <span>${ps.ville} ${ps.codePostal}</span>
                                    <span>🐾 Maximum ${ps.nbMaxAnimaux} animaux</span>
                                </div>
                                <div class="petSitterDispoTags">${tags}</div>
                            </div>
                            <div class="petSitterActions">
                                <button class="button buttonPrimary contactPetSitterButton" data-idpetsitter="${ps.idUtilisateur}">Contacter</button>
                            </div>
                        `
                        list.appendChild(card)

                        /* Gestion du clic sur "Contacter" de cette carte */
                        let contactButton = card.querySelector(".contactPetSitterButton")
                        contactButton.addEventListener("click", function(){
                            let idPetSitter = this.dataset.idpetsitter

                            let demandeData = new FormData()
                            demandeData.append("idPetSitter", idPetSitter)

                            fetch("/Fonctions/insertDemande.php", {
                                method: "POST",
                                body: demandeData
                            })
                            .then(res => res.json())
                            .then(data => {
                                if(data["resultat"]){
                                    alert("Votre demande a bien été envoyée au pet sitter !")
                                    this.disabled = true
                                    this.textContent = "Demande envoyée"
                                } else {
                                    alert("Quelque chose s'est mal passé de notre côté... Pas d'inquiétude, réessayez dans quelques instants !")
                                }
                            })
                        })
                    })
                })
        } else {
            alert("Merci de remplir la barre de recherche") // ########## A rendre plus corpo
        }
    })
}





/* ======================================================================================= */
/* ===== Index - Propriétaire - Ajout d'un nouvel animal dans l'onglet "Mes animaux" ===== */
/* ======================================================================================= */
var newListAnimaux = []
formAddNewAnimaux = document.querySelector(".addAnimalBlock")
if(formAddNewAnimaux){
    formAddNewAnimaux.addEventListener("change", function(){
        let nomNewAnimal = document.getElementById("newNomAnimal").value
        let tatouageNewAnimal = document.getElementById("newNumTatouage").value
        let sexeNewAnimal = document.getElementById("newSexeAnimal").value
        let especeNewAnimal = document.getElementById("newEspeceAnimal").value

        if(nomNewAnimal != "" && tatouageNewAnimal != "" && sexeNewAnimal != "" && especeNewAnimal != ""){
            let divAnimaux = document.getElementById("listeAnimaux")
            let animal = {
                nomNewAnimal : nomNewAnimal,
                tatouageNewAnimal : tatouageNewAnimal,
                sexeNewAnimal : sexeNewAnimal,
                especeNewAnimal : especeNewAnimal
            };

            /* On vide les champs du formulaire pour permettre un nouvel ajout */
            document.getElementById("newNomAnimal").value = ""
            document.getElementById("newNumTatouage").value = ""
            document.getElementById("newSexeAnimal").value = ""
            document.getElementById("newEspeceAnimal").value = ""

            /* Affichage sur le formulaire du dernier animal ajouté */
            fillListAnimaux(nomNewAnimal, tatouageNewAnimal, sexeNewAnimal, especeNewAnimal, divAnimaux)

            newListAnimaux.push(animal)
        }
    })
}

// Ajout de nouveaux animaux dans la base de données
let addAnimalButton = document.querySelector(".addAnimalButton")
if(addAnimalButton){
    addAnimalButton.addEventListener("click", function(){
        let newAnimalData = new FormData()
        newListAnimaux = JSON.stringify(newListAnimaux)
        newAnimalData.append("newAnimalData", newListAnimaux)

        fetch("/Fonctions/insertNewAnimal.php", {
            method: "POST",
            body: newAnimalData
        })
        .then(res => res.json())
        .then(data => {

            if(data["resultat"]){
                alert("Nouveaux animaux ajoutés") // ##### Plus corpo !!!
                window.location.href = "/index.php"
            } else {
                alert("Quelque chose s'est mal passé de notre côté... Pas d'inquiétude, réessayez dans quelques instants !")
            }
            
        })
    })
}


/* ============================================ */
/* ===== Index - Pet Sitter - Accepter / Refuser une demande ===== */
/* ============================================ */
let demandesList = document.getElementById("demandesList")
if(demandesList){
    // Délégation d'événement : on écoute les clics sur le conteneur,
    // car les boutons sont générés par PHP et peuvent être nombreux
    demandesList.addEventListener("click", function(e){

        let card = e.target.closest(".demandeCard")
        if(!card) return

        let statusBadge = card.querySelector(".demandeStatus")
        let actionsDiv = card.querySelector(".demandeActions")

        // On ne réagit qu'aux clics sur Accepter ou Refuser
        let action = ""
        if(e.target.classList.contains("acceptDemandeBtn")){
            action = "accepter"
        } else if(e.target.classList.contains("refuseDemandeBtn")){
            action = "refuser"
        } else {
            return
        }

        let demandeData = new FormData()
        demandeData.append("idDemande", card.dataset.idDemande)
        demandeData.append("action", action)

        fetch("/Fonctions/updateDemande.php", {
            method: "POST",
            body: demandeData
        })
        .then(res => res.json())
        .then(data => {
            if(data["resultat"]){
                // Mise à jour de l'affichage de la carte sans recharger la page
                if(action == "accepter"){
                    statusBadge.className = "demandeStatus accepted"
                    statusBadge.textContent = "Acceptée"
                } else {
                    statusBadge.className = "demandeStatus refused"
                    statusBadge.textContent = "Refusée"
                }
                // Une fois traitée, on retire les boutons
                actionsDiv.innerHTML = ""
            } else {
                alert("Quelque chose s'est mal passé de notre côté... Pas d'inquiétude, réessayez dans quelques instants !")
            }
        })
    })
}


/* ====================================== */
/* ===== Fonctions de factorisation ===== */
/* ====================================== */
function fillListAnimaux(nom, tatouage, sexe, espece, div){
    let animalRow = document.createElement("div")
    animalRow.setAttribute("class", "animalRow")

    let animalLogo = document.createElement("div")
    animalLogo.setAttribute("class", "animalLogo")
    let logo = espece == 1 ? "🐶" : "😺"
    animalLogo.innerHTML = logo

    let animalInfo = document.createElement("div")
    animalInfo.setAttribute("class", "animalInfo")

    let animalName = document.createElement("div")
    animalName.setAttribute("class", "animalName")
    let symboleSexe = sexe == 0 ? "♂️" : "♀️"
    animalName.innerHTML = nom + symboleSexe

    let animalMeta = document.createElement("div")
    animalMeta.setAttribute("class", "animalMeta")
    let meta = "Numéro de tatouage: " + tatouage
    animalMeta.innerHTML = meta

    animalInfo.appendChild(animalName)
    animalInfo.appendChild(animalMeta)
    animalRow.appendChild(animalLogo)
    animalRow.appendChild(animalInfo)
    
    div.appendChild(animalRow)
}