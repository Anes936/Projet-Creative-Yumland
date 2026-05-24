var LIMITES_PAR_DEFAUT = {
    "emdp":        20,
    "cmdp":        20,
    "mdp":         20,
    "nom":         30,
    "prenom":      30,
    "ville":       40,
    "rue":         60,
    "telephone":   10,
    "tel":         10,
    "postal":      5,
    "commentaire": 200,
    "avis":        200
};

function ajouterCompteur(input) {
    var limite = parseInt(input.getAttribute("maxlength"), 10);
    if (isNaN(limite)) {
        limite = LIMITES_PAR_DEFAUT[input.name];
    }
    if (!limite) {
        return;
    }

    input.setAttribute("maxlength", limite);

    var span = document.createElement("span");
    span.className = "compteur";
    span.textContent = input.value.length + " / " + limite;

    input.parentNode.insertBefore(span, input.nextSibling);

    input.addEventListener("input", function () {
        span.textContent = input.value.length + " / " + limite;
        if (input.value.length >= limite) {
            span.style.color = "#c0392b";
        } else {
            span.style.color = "#888";
        }
    });
}

document.addEventListener("DOMContentLoaded", function () {
    var champs = document.querySelectorAll("input[type='text'], input[type='email'], input[type='password'], input[type='tel'], textarea");
    for (var i = 0; i < champs.length; i++) {
        if (champs[i].id === "recherche") continue;
        ajouterCompteur(champs[i]);
    }
});
