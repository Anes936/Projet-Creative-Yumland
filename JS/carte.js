// Page produits : filtres, tri et recherche instantanée, le tout sans recharger la page.
document.addEventListener("DOMContentLoaded", function () {

    var grille = document.getElementById("blocgrille");
    if (!grille) return;

    var checkCategories = document.querySelectorAll(".filtre-categorie");
    var checkGouts      = document.querySelectorAll(".filtre-gout");
    var selectTri       = document.getElementById("tri");

    var champRecherche   = document.getElementById("recherche");
    var formRecherche    = document.getElementById("form-recherche");
    var boiteSuggestions = document.getElementById("suggestions");

    function valeursCochees(listeCheck) {
        var valeurs = [];
        for (var i = 0; i < listeCheck.length; i++) {
            if (listeCheck[i].checked) {
                valeurs.push(listeCheck[i].value);
            }
        }
        return valeurs;
    }

    // On envoie les filtres + la recherche au serveur, puis on réaffiche la grille avec sa réponse.
    function appliquerFiltres(montrerSuggestions) {
        var cats = valeursCochees(checkCategories).join(",");
        var gts  = valeursCochees(checkGouts).join(",");
        var texte = champRecherche ? champRecherche.value.trim() : "";

        var url = "Traitements/traitement_FiltrerPlats.php"
                + "?categories=" + encodeURIComponent(cats)
                + "&gouts="      + encodeURIComponent(gts)
                + "&recherche="  + encodeURIComponent(texte);

        fetch(url)
            .then(function (reponse) { return reponse.json(); })
            .then(function (plats)  {
                remplirGrille(plats);
                if (montrerSuggestions && texte !== "") {
                    remplirSuggestions(plats);
                } else {
                    viderSuggestions();
                }
            })
            .catch(function () {
                grille.innerHTML = "<p>Erreur lors du chargement des produits.</p>";
            });
    }

    function remplirGrille(plats) {
        grille.innerHTML = "";
        if (plats.length === 0) {
            grille.innerHTML = "<p>Aucun produit ne correspond aux filtres choisis.</p>";
            return;
        }
        for (var i = 0; i < plats.length; i++) {
            var p = plats[i];
            var article = document.createElement("article");
            article.className = "gateau";
            article.setAttribute("data-id", p.id);
            article.setAttribute("data-prix", p.prix);
            article.setAttribute("data-commandes", p.nb_commandes);

            var img = document.createElement("img");
            img.src = p.image;
            img.alt = p.nom;
            article.appendChild(img);

            var titre = document.createElement("h3");
            titre.textContent = p.nom;
            article.appendChild(titre);

            var prix = document.createElement("p");
            prix.className = "prix";
            prix.textContent = p.prix.toFixed(2).replace(".", ",") + " €";
            article.appendChild(prix);

            var lien = document.createElement("a");
            lien.href = "Traitements/traitement_Panier.php?action=ajouter&id=" + p.id;
            var bouton = document.createElement("button");
            bouton.className = "btn-ajouter";
            bouton.textContent = "Ajouter au panier";
            lien.appendChild(bouton);
            article.appendChild(lien);

            grille.appendChild(article);
        }
        appliquerTri();
    }

    function viderSuggestions() {
        if (boiteSuggestions) {
            boiteSuggestions.innerHTML = "";
        }
    }

    function remplirSuggestions(plats) {
        if (!boiteSuggestions) return;
        boiteSuggestions.innerHTML = "";
        for (var i = 0; i < plats.length; i++) {
            var ligne = document.createElement("li");
            ligne.textContent = plats[i].nom;
            ligne.addEventListener("click", choisirSuggestion(plats[i].nom));
            boiteSuggestions.appendChild(ligne);
        }
    }

    function choisirSuggestion(nom) {
        return function () {
            champRecherche.value = nom;
            appliquerFiltres(false);
            grille.scrollIntoView({ behavior: "smooth" });
        };
    }

    function brancherFiltres(liste) {
        for (var i = 0; i < liste.length; i++) {
            liste[i].addEventListener("change", function () {
                appliquerFiltres(false);
            });
        }
    }
    brancherFiltres(checkCategories);
    brancherFiltres(checkGouts);

    if (champRecherche) {
        champRecherche.addEventListener("input", function () {
            appliquerFiltres(true);
        });
    }

    if (formRecherche) {
        formRecherche.addEventListener("submit", function (evenement) {
            evenement.preventDefault();
        });
    }

    document.addEventListener("click", function (evenement) {
        var zone = document.getElementById("zone-recherche");
        if (zone && !zone.contains(evenement.target)) {
            viderSuggestions();
        }
    });

    function appliquerTri() {
        var critere = selectTri.value;
        var articles = Array.prototype.slice.call(grille.querySelectorAll(".gateau"));

        if (critere === "prix_croissant") {
            articles.sort(function (a, b) {
                return parseFloat(a.dataset.prix) - parseFloat(b.dataset.prix);
            });
        } else if (critere === "prix_decroissant") {
            articles.sort(function (a, b) {
                return parseFloat(b.dataset.prix) - parseFloat(a.dataset.prix);
            });
        } else if (critere === "plus_commandes") {
            articles.sort(function (a, b) {
                return parseInt(b.dataset.commandes, 10) - parseInt(a.dataset.commandes, 10);
            });
        }

        for (var i = 0; i < articles.length; i++) {
            grille.appendChild(articles[i]);
        }
    }

    if (selectTri) {
        selectTri.addEventListener("change", appliquerTri);
    }

    // Si on arrive depuis une autre page (clic sur une suggestion), on filtre direct sur le plat.
    var params = new URLSearchParams(window.location.search);
    var rechercheInitiale = params.get("recherche");
    if (rechercheInitiale && champRecherche) {
        champRecherche.value = rechercheInitiale;
        appliquerFiltres(false);
    }

});
