/**
 * @file Objet de validations avec jQuery
 * @author Samuel Loranger <samuelloranger@gmail.com>
 */

var menu = {
    /**
     * dans la méthode initialiser, on peut définir les attributs de l'objet et ajouter les écouteurs d'événements
     * @param evenement {Objet Event 'ready'}
     */

    initialiser : function(evenement){
        // pour les champs de saisie, on peut se servir du id #

        $(".header__liste").before('' +
            '<span class="header__btnMenu" id="header__btnMenu">');

        $('#header__btnMenu').on('click', this.ouvrirFermerMenu.bind(this));
    },

    ouvrirFermerMenu : function(evenement){
        $(".header__liste").toggleClass("header__liste--ferme");
    }
};