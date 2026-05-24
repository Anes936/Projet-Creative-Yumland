document.addEventListener("DOMContentLoaded", function () {

    var grille = document.getElementById("blocgrille");
    if (!grille) return;

    var checkCategories = document.querySelectorAll(".filtre-categorie");
    var checkRegimes    = document.querySelectorAll(".filtre-regime");
    var checkSaveurs    = document.querySelectorAll(".filtre-saveur");
    var selectTri       = document.getElementById("tri");

    function valeursCochees(listeCheck) {
        var valeurs = [];
        for (var i = 0; i < listeCheck.length; i++) {
            if (listeCheck[i].checked) {
                valeurs.push(listeCheck[i].value);
            }
        }
        return valeurs;
    }

    function appliquerFiltres() {
        var cats = valeursCochees(checkCategories).join(",");
        var regs = valeursCochees(checkRegimes).join(",");
        var savs = valeursCochees(checkSaveurs).join(",");

        var url = "Traitements/traitement_FiltrerPlats.php"
                + "?categories=" + encodeURIComponent(cats)
                + "&regimes="    + encodeURIComponent(regs)
                + "&saveurs="    + encodeURIComponent(savs);

        fetch(url)
            .then(function (reponse) { return reponse.json(); })
            .then(function (plats)  { remplirGrille(plats); })
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

    function brancherFiltres(liste) {
        for (var i = 0; i < liste.length; i++) {
            liste[i].addEventListener("change", appliquerFiltres);
        }
    }
    brancherFiltres(checkCategories);
    brancherFiltres(checkRegimes);
    brancherFiltres(checkSaveurs);

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

});
