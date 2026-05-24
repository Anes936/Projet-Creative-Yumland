function estVide(valeur) {
    return valeur.trim() === "";
}

function estEmailValide(valeur) {
    var motif = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return motif.test(valeur);
}

function estTelephoneValide(valeur) {
    var motif = /^0[0-9]{9}$/;
    return motif.test(valeur);
}

function estCodePostalValide(valeur) {
    var motif = /^[0-9]{5}$/;
    return motif.test(valeur);
}

function estNombrePositif(valeur) {
    var n = Number(valeur);
    return !isNaN(n) && n > 0;
}

function estDateValide(valeur) {
    if (valeur === "") return false;
    var d = new Date(valeur);
    if (isNaN(d.getTime())) return false;
    var aujourdhui = new Date();
    return d <= aujourdhui;
}

function estMotDePasseValide(valeur) {
    if (valeur.length < 6) return false;
    if (!/[0-9]/.test(valeur)) return false;
    return true;
}

function afficherErreur(input, message) {
    var suivant = input.nextElementSibling;
    var span;
    if (suivant && suivant.classList && suivant.classList.contains("erreur")) {
        span = suivant;
    } else {
        span = document.createElement("span");
        span.className = "erreur";
        input.parentNode.insertBefore(span, input.nextSibling);
    }
    span.textContent = message;
    input.classList.add("champ-invalide");
}

function effacerErreur(input) {
    var suivant = input.nextElementSibling;
    if (suivant && suivant.classList && suivant.classList.contains("erreur")) {
        suivant.textContent = "";
    }
    input.classList.remove("champ-invalide");
}

function validerUnChamp(input) {
    var valeur = input.value;
    var type = input.type;
    var nom  = input.name;

    if (input.hasAttribute("required") && estVide(valeur)) {
        return "Ce champ est obligatoire.";
    }

    if (estVide(valeur)) {
        return "";
    }

    if (type === "email") {
        if (!estEmailValide(valeur)) {
            return "Adresse email invalide (exemple : nom@domaine.fr).";
        }
    }

    if (type === "tel") {
        if (!estTelephoneValide(valeur)) {
            return "Numero invalide (10 chiffres commencant par 0).";
        }
    }

    if (type === "date") {
        if (!estDateValide(valeur)) {
            return "Date invalide ou dans le futur.";
        }
    }

    if (type === "number") {
        if (!estNombrePositif(valeur)) {
            return "Doit etre un nombre positif.";
        }
    }

    if (type === "password") {
        if (!estMotDePasseValide(valeur)) {
            return "Le mot de passe doit faire au moins 6 caracteres et contenir au moins 1 chiffre.";
        }
    }

    if (nom === "postal") {
        if (!estCodePostalValide(valeur)) {
            return "Code postal invalide (5 chiffres).";
        }
    }

    if (nom === "nrue") {
        if (isNaN(Number(valeur))) {
            return "Le numero de rue doit etre un nombre.";
        }
    }

    return "";
}

function validerFormulaire(formulaire) {
    var tousLesChamps = formulaire.querySelectorAll("input, select, textarea");
    var toutEstOk = true;

    for (var i = 0; i < tousLesChamps.length; i++) {
        var champ = tousLesChamps[i];
        if (champ.type === "submit" || champ.type === "hidden" || champ.type === "button") {
            continue;
        }
        var erreur = validerUnChamp(champ);
        if (erreur === "") {
            effacerErreur(champ);
        } else {
            afficherErreur(champ, erreur);
            toutEstOk = false;
        }
    }

    var mdp1 = formulaire.querySelector("input[name='emdp']");
    var mdp2 = formulaire.querySelector("input[name='cmdp']");
    if (mdp1 && mdp2 && mdp1.value !== mdp2.value) {
        afficherErreur(mdp2, "Les deux mots de passe ne correspondent pas.");
        toutEstOk = false;
    }

    return toutEstOk;
}

document.addEventListener("DOMContentLoaded", function () {

    var formulaires = document.querySelectorAll("form");
    for (var i = 0; i < formulaires.length; i++) {
        if (formulaires[i].querySelector("#recherche")) continue;

        formulaires[i].addEventListener("submit", function (evenement) {
            if (!validerFormulaire(evenement.target)) {
                evenement.preventDefault();
            }
        });

        var champs = formulaires[i].querySelectorAll("input, select, textarea");
        for (var j = 0; j < champs.length; j++) {
            champs[j].addEventListener("blur", function (evt) {
                var erreur = validerUnChamp(evt.target);
                if (erreur === "") {
                    effacerErreur(evt.target);
                } else {
                    afficherErreur(evt.target, erreur);
                }
            });
        }
    }

    var champsMdp = document.querySelectorAll("input[type='password']");
    for (var k = 0; k < champsMdp.length; k++) {
        ajouterBoutonOeil(champsMdp[k]);
    }

});

function ajouterBoutonOeil(input) {
    var bouton = document.createElement("button");
    bouton.type = "button";
    bouton.className = "btn-oeil";
    bouton.textContent = "Afficher";

    bouton.addEventListener("click", function () {
        if (input.type === "password") {
            input.type = "text";
            bouton.textContent = "Cacher";
        } else {
            input.type = "password";
            bouton.textContent = "Afficher";
        }
    });

    input.parentNode.insertBefore(bouton, input.nextSibling);
}
