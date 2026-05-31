// Profil : on bascule les infos en champs modifiables, puis on les envoie au serveur en asynchrone.
document.addEventListener("DOMContentLoaded", function () {

    var boutonModifier = document.getElementById("btn-modifier-infos");
    if (!boutonModifier) return;

    var message = document.getElementById("message-modif");
    var enEdition = false;

    boutonModifier.addEventListener("click", function () {
        if (!enEdition) {
            passerEnEdition();
            boutonModifier.textContent = "Valider les modifications";
            enEdition = true;
        } else {
            envoyerModifications();
        }
    });

    function passerEnEdition() {
        var spans = document.querySelectorAll(".valeur-info");
        for (var i = 0; i < spans.length; i++) {
            var span = spans[i];
            var champ = span.getAttribute("data-champ");
            var input = document.createElement("input");
            input.type = "text";
            input.className = "valeur-info input-edition";
            input.setAttribute("data-champ", champ);
            input.value = span.textContent.trim();
            span.parentNode.replaceChild(input, span);
        }
    }

    // Envoi des nouvelles infos au serveur, sans recharger la page.
    function envoyerModifications() {
        var inputs = document.querySelectorAll(".input-edition");
        var donnees = {};
        var adresseTexte = "";
        for (var i = 0; i < inputs.length; i++) {
            var champ = inputs[i].getAttribute("data-champ");
            if (champ === "adresse") {
                adresseTexte = inputs[i].value;
            } else {
                donnees[champ] = inputs[i].value;
            }
        }

        if (adresseTexte !== "") {
            var partie1 = adresseTexte;
            var ville = "";
            var postal = "";
            var virgule = adresseTexte.indexOf(",");
            if (virgule !== -1) {
                partie1 = adresseTexte.substring(0, virgule).trim();
                var apres = adresseTexte.substring(virgule + 1).trim();
                var espace = apres.indexOf(" ");
                if (espace !== -1) {
                    postal = apres.substring(0, espace);
                    ville  = apres.substring(espace + 1);
                } else {
                    postal = apres;
                }
            }
            var morceaux = partie1.split(" ");
            var numero = morceaux.shift();
            var rue = morceaux.join(" ");
            donnees.adresse = {
                numero: numero,
                rue:    rue,
                postal: postal,
                ville:  ville
            };
        }

        if (donnees.mail && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(donnees.mail)) {
            message.style.color = "#c0392b";
            message.textContent = "Email invalide.";
            return;
        }
        if (donnees.tel && !/^0[0-9]{9}$/.test(donnees.tel)) {
            message.style.color = "#c0392b";
            message.textContent = "Telephone invalide.";
            return;
        }

        fetch("Traitements/traitement_ModifierProfil.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(donnees)
        })
        .then(function (reponse) { return reponse.json(); })
        .then(function (resultat) {
            if (resultat.ok) {
                message.style.color = "#2d7a2d";
                message.textContent = "Modifications enregistrees.";
                revenirEnLecture();
                boutonModifier.textContent = "Modifier mes informations";
                enEdition = false;
            } else {
                message.style.color = "#c0392b";
                message.textContent = "Erreur : " + resultat.message;
            }
        })
        .catch(function () {
            message.style.color = "#c0392b";
            message.textContent = "Erreur de connexion au serveur.";
        });
    }

    function revenirEnLecture() {
        var inputs = document.querySelectorAll(".input-edition");
        for (var i = 0; i < inputs.length; i++) {
            var input = inputs[i];
            var span = document.createElement("span");
            span.className = "valeur-info";
            span.setAttribute("data-champ", input.getAttribute("data-champ"));
            span.textContent = input.value;
            input.parentNode.replaceChild(span, input);
        }
    }

});
