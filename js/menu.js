/**
 * @file Fichier qui gère le menu hamburger
 * @author Samuel Loranger <samuelloranger@gmail.com>
 */

var menu = {
    /**
     * dans la méthode initialiser, on peut définir les attributs de l'objet et ajouter les écouteurs d'événements
     */
    initialiser : function(evenement){

        //Insère un bouton menu hamburger dans le html
        $(".header__liste").before(
            '<div class="btnMenu">' +
                '<span class="ligne"></span>' +
                '<span class="ligne"></span>' +
                '<span class="ligne"></span>' +
                '<span class="ligne"></span>' +
            '</div>');

        //Écouteur d'évènement du menu hamburger
        $('.btnMenu').on('click', this.ouvrirFermerMenu.bind(this));
    },

    ouvrirFermerMenu : function(evenement){

        //Affiche ou cache la liste
        $(".header__liste").toggleClass("header__liste--ferme");

        //Change l'état du menu hamburger
        $(".btnMenu").toggleClass('open');
    }
};