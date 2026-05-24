function ecrireCookie(nom, valeur, jours) {
    var date = new Date();
    date.setTime(date.getTime() + (jours * 24 * 60 * 60 * 1000));
    document.cookie = nom + "=" + valeur + "; expires=" + date.toUTCString() + "; path=/";
}

function lireCookie(nom) {
    var cookies = document.cookie.split(";");
    for (var i = 0; i < cookies.length; i++) {
        var c = cookies[i].trim();
        if (c.indexOf(nom + "=") === 0) {
            return c.substring(nom.length + 1);
        }
    }
    return "";
}

var correspondancesImages = [
    { clair: "images/logo.png",      sombre: "images/logo.sombre.jpg" },
    { clair: "images/Glogo.png",     sombre: "images/Glogo.sombre.jpg" },
    { clair: "images/Rcherche.jpeg", sombre: "images/recherche.sombre.jpg" },
    { clair: "images/Panier.jpeg",   sombre: "images/Panier.sombre.jpg" }
];

function mettreAJourImages(sombre) {
    var images = document.querySelectorAll("img");
    for (var i = 0; i < images.length; i++) {
        var img = images[i];
        for (var j = 0; j < correspondancesImages.length; j++) {
            var paire = correspondancesImages[j];
            if (sombre && img.src.indexOf(paire.clair) !== -1) {
                img.src = img.src.replace(paire.clair, paire.sombre);
            } else if (!sombre && img.src.indexOf(paire.sombre) !== -1) {
                img.src = img.src.replace(paire.sombre, paire.clair);
            }
        }
    }
}

function activerThemeSombre() {
    if (!document.getElementById("css-sombre")) {
        var lien = document.createElement("link");
        lien.id = "css-sombre";
        lien.rel = "stylesheet";
        lien.href = "Sombre.css";
        document.head.appendChild(lien);
    }
    mettreAJourImages(true);
}

function activerThemeClair() {
    var lien = document.getElementById("css-sombre");
    if (lien) {
        lien.remove();
    }
    mettreAJourImages(false);
}

function appliquerThemeDepuisCookie() {
    var choix = lireCookie("theme");
    if (choix === "sombre") {
        activerThemeSombre();
    } else {
        activerThemeClair();
    }
}

function basculerTheme() {
    var choix = lireCookie("theme");
    if (choix === "sombre") {
        activerThemeClair();
        ecrireCookie("theme", "clair", 365);
    } else {
        activerThemeSombre();
        ecrireCookie("theme", "sombre", 365);
    }
    majIconeBoutonTheme();
}

function majIconeBoutonTheme() {
    var bouton = document.getElementById("btn-theme");
    if (!bouton) return;
    var choix = lireCookie("theme");
    if (choix === "sombre") {
        bouton.textContent = "Mode clair";
    } else {
        bouton.textContent = "Mode sombre";
    }
}

document.addEventListener("DOMContentLoaded", function () {
    appliquerThemeDepuisCookie();
    majIconeBoutonTheme();
    var bouton = document.getElementById("btn-theme");
    if (bouton) {
        bouton.addEventListener("click", basculerTheme);
    }
});

appliquerThemeDepuisCookie();
