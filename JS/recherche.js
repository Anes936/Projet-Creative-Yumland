// Barre de recherche du header (toutes les pages sauf la carte, qui a son propre script).
document.addEventListener("DOMContentLoaded", function () {

    if (document.getElementById("blocgrille")) return;

    var champRecherche   = document.getElementById("recherche");
    var formRecherche    = document.getElementById("form-recherche");
    var boiteSuggestions = document.getElementById("suggestions");
    if (!champRecherche) return;

    function viderSuggestions() {
        if (boiteSuggestions) {
            boiteSuggestions.innerHTML = "";
        }
    }

    // On renvoie vers la carte en passant le nom du plat dans l'URL.
    function allerVersPlat(nom) {
        window.location.href = "Carte.php?recherche=" + encodeURIComponent(nom);
    }

    function faireClic(nom) {
        return function () {
            allerVersPlat(nom);
        };
    }

    function remplirSuggestions(plats) {
        if (!boiteSuggestions) return;
        boiteSuggestions.innerHTML = "";
        for (var i = 0; i < plats.length; i++) {
            var ligne = document.createElement("li");
            ligne.textContent = plats[i].nom;
            ligne.addEventListener("click", faireClic(plats[i].nom));
            boiteSuggestions.appendChild(ligne);
        }
    }

    champRecherche.addEventListener("input", function () {
        var texte = champRecherche.value.trim();
        if (texte === "") {
            viderSuggestions();
            return;
        }
        fetch("Traitements/traitement_FiltrerPlats.php?recherche=" + encodeURIComponent(texte))
            .then(function (reponse) { return reponse.json(); })
            .then(function (plats) { remplirSuggestions(plats); })
            .catch(function () { viderSuggestions(); });
    });

    if (formRecherche) {
        formRecherche.addEventListener("submit", function (evenement) {
            evenement.preventDefault();
            var texte = champRecherche.value.trim();
            if (texte !== "") {
                allerVersPlat(texte);
            }
        });
    }

    document.addEventListener("click", function (evenement) {
        var zone = document.getElementById("zone-recherche");
        if (zone && !zone.contains(evenement.target)) {
            viderSuggestions();
        }
    });

});
