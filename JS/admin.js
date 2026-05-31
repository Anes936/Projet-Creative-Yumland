// Espace admin : blocage / déblocage d'un compte en asynchrone (le bouton se met à jour sur place).
document.addEventListener("DOMContentLoaded", function () {

    var boutons = document.querySelectorAll(".btn-bloquer");

    for (var i = 0; i < boutons.length; i++) {
        boutons[i].addEventListener("click", function (evt) {
            var bouton = evt.target;
            var idUser = bouton.getAttribute("data-id");
            var bloqueActuellement = bouton.getAttribute("data-bloque") === "1";
            var doitEtreBloque = !bloqueActuellement;

            fetch("Traitements/traitement_BloquerUser.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    id: idUser,
                    bloquer: doitEtreBloque
                })
            })
            .then(function (r) { return r.json(); })
            .then(function (resultat) {
                if (resultat.ok) {
                    bouton.setAttribute("data-bloque", resultat.bloque ? "1" : "0");
                    bouton.textContent = resultat.bloque ? "Débloquer" : "Bloquer";
                    var span = bouton.parentNode.querySelector(".etat-bloque");
                    if (span) {
                        span.textContent = resultat.bloque ? "(bloqué)" : "";
                    }
                } else {
                    alert("Erreur : " + resultat.message);
                }
            })
            .catch(function () {
                alert("Erreur de connexion au serveur.");
            });
        });
    }

});
