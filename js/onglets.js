/**
 * @file barreOnglets
 * @author Ève Février
 * @author Yves Hélie
 * @author Gabriel Chouinard Létourneau <chouinardletourneaug@gmail.com>
 * @version 1.0 - avec jQuery
 */

//*******************
// Déclaration d'objet
//*******************
console.log("fwiqnf");
var profil = {

    initialiser: function(){
        console.log("hello?");
        this.reset();
        $(".contenuTable__profilLink").on('click', this.afficherCacher.bind(this));
    },
    reset: function(){
        $('.contenuTable__profil').addClass('contenuTable__profil--hidden');
    },
    afficherCacher: function (evenement) {
        evenement.preventDefault();
        console.log('afficherCacher');
        var $segmentCible = $(evenement.currentTarget).closest('.contenuTable__profil');
        if ($segmentCible.hasClass('contenuTable__profil--hidden')) {
            this.reset();
            $segmentCible.removeClass('contenuTable__profil--hidden');
        }
        else {
            this.reset();
        }
    }

};