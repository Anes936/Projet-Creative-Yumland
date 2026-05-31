// Modification d'une commande déjà payée : on ajoute / retire des produits et on recalcule le total.
document.addEventListener("DOMContentLoaded", function () {

    var liste = document.getElementById("liste-articles");
    if (!liste) return;

    var ancienTotal = parseFloat(document.getElementById("ancien-total").dataset.valeur);
    var nouveauTotalSpan = document.getElementById("nouveau-total");
    var infoDiff = document.getElementById("info-difference");
    var message = document.getElementById("message-modif-cmd");

    // Recalcule le total et indique s'il faudra payer un supplément.
    function recalculerTotal() {
        var lignes = liste.querySelectorAll(".ligne-article");
        var total = 0;
        for (var i = 0; i < lignes.length; i++) {
            var prix = parseFloat(lignes[i].dataset.prix);
            var qte  = parseInt(lignes[i].querySelector(".qte").textContent, 10);
            var sousTotal = prix * qte;
            lignes[i].querySelector(".sous-total").textContent = sousTotal.toFixed(2).replace(".", ",");
            total += sousTotal;
        }
        nouveauTotalSpan.textContent = total.toFixed(2).replace(".", ",");

        var diff = total - ancienTotal;
        if (diff > 0.001) {
            infoDiff.textContent = "Vous devrez payer un supplement de " + diff.toFixed(2).replace(".", ",") + " €.";
            infoDiff.style.color = "#c0392b";
        } else if (diff < -0.001) {
            infoDiff.textContent = "Nouveau montant inferieur (aucun remboursement n'est effectue).";
            infoDiff.style.color = "#856404";
        } else {
            infoDiff.textContent = "Le montant total est inchange.";
            infoDiff.style.color = "#666";
        }
    }

    function brancherLigne(ligne) {
        var btnPlus    = ligne.querySelector(".btn-plus");
        var btnMoins   = ligne.querySelector(".btn-moins");
        var btnRetirer = ligne.querySelector(".btn-retirer");
        var spanQte    = ligne.querySelector(".qte");

        btnPlus.addEventListener("click", function () {
            spanQte.textContent = parseInt(spanQte.textContent, 10) + 1;
            recalculerTotal();
        });

        btnMoins.addEventListener("click", function () {
            var q = parseInt(spanQte.textContent, 10);
            if (q > 1) {
                spanQte.textContent = q - 1;
                recalculerTotal();
            }
        });

        btnRetirer.addEventListener("click", function () {
            ligne.remove();
            recalculerTotal();
        });
    }

    var lignesExistantes = liste.querySelectorAll(".ligne-article");
    for (var i = 0; i < lignesExistantes.length; i++) {
        brancherLigne(lignesExistantes[i]);
    }

    var select = document.getElementById("select-ajout");
    var btnAjout = document.getElementById("btn-ajout");

    btnAjout.addEventListener("click", function () {
        if (select.value === "") return;
        var option = select.options[select.selectedIndex];
        var id   = option.value;
        var nom  = option.dataset.nom;
        var prix = parseFloat(option.dataset.prix);

        var lignes = liste.querySelectorAll(".ligne-article");
        for (var i = 0; i < lignes.length; i++) {
            if (lignes[i].dataset.id === id) {
                var spanQte = lignes[i].querySelector(".qte");
                spanQte.textContent = parseInt(spanQte.textContent, 10) + 1;
                recalculerTotal();
                return;
            }
        }

        var nouvelle = document.createElement("div");
        nouvelle.className = "article ligne-article";
        nouvelle.setAttribute("data-id", id);
        nouvelle.setAttribute("data-prix", prix);
        nouvelle.innerHTML = ''
            + '<div class="article-infos">'
            +   '<h3>' + nom + '</h3>'
            +   '<p class="prix-unit">' + prix.toFixed(2).replace(".", ",") + ' €</p>'
            + '</div>'
            + '<div class="article-actions">'
            +   '<button type="button" class="btn-moins">-</button>'
            +   '<span class="qte">1</span>'
            +   '<button type="button" class="btn-plus">+</button>'
            + '</div>'
            + '<p class="prix-total"><span class="sous-total">' + prix.toFixed(2).replace(".", ",") + '</span> €</p>'
            + '<button type="button" class="supprimer btn-retirer">&#10005;</button>';
        liste.appendChild(nouvelle);
        brancherLigne(nouvelle);
        recalculerTotal();
    });

    var btnValider = document.getElementById("btn-valider-modif");

    btnValider.addEventListener("click", function () {
        var articles = [];
        var lignes = liste.querySelectorAll(".ligne-article");
        for (var i = 0; i < lignes.length; i++) {
            articles.push({
                id: lignes[i].dataset.id,
                qte: parseInt(lignes[i].querySelector(".qte").textContent, 10)
            });
        }

        if (articles.length === 0) {
            message.textContent = "La commande ne peut pas etre vide.";
            message.style.color = "#c0392b";
            return;
        }

        var params = new URLSearchParams(window.location.search);
        var idCommande = params.get("id");

        fetch("Traitements/traitement_ModifierCommande.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                id_commande: idCommande,
                articles: articles
            })
        })
        .then(function (r) { return r.json(); })
        .then(function (resultat) {
            if (resultat.ok) {
                if (resultat.paiement_supplementaire) {
                    message.style.color = "#856404";
                    message.textContent = "Redirection vers le paiement du supplement...";
                    window.location.href = "Paiement.php";
                } else {
                    message.style.color = "#2d7a2d";
                    message.textContent = "Commande modifiee avec succes.";
                }
            } else {
                message.style.color = "#c0392b";
                message.textContent = "Erreur : " + resultat.message;
            }
        })
        .catch(function () {
            message.style.color = "#c0392b";
            message.textContent = "Erreur de connexion au serveur.";
        });
    });

});
