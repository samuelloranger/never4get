/**
 * @file Validations du mandat A
 * @author Gabriel Chouinard Létourneau <chouinardletourneaug@gmail.com>
 */

var validationsMandatA = {

    objJSONMessages : null,

    /******************************************************************************************
     * Constructeur
     */

    /**
     * dans la méthode initialiser, on peut définir les attributs de l'objet et ajouter les écouteurs d'événements
     * @param evenement {Objet Event 'ready'}
     */

    initialiser : function(evenement){
        // On charge le fichier JSON via une requête AJAX
        $.ajax({
            context: this,
            url: niveau + "js/objJSONMessages.json",
            type: 'GET',
            dataType: "json"
        }).done(function(data){
            this.objJSONMessages = data;
            this.configurerValidations();
        });
    },

    configurerValidations : function(){
        // pour les champs de saisie, on peut se servir du id #
        $('#nomListe').on('blur', this.validerTache.bind(this));

        // sur les boutons radio on se sert du name qui est commun
        $('[name=couleur]').on('blur', this.validerCours.bind(this));
    },

    /******************************************************************************************
     * Méthodes spécifiques
     * On ajoute une méthode validerQuelqueChose pour chaque élément de formulaire à valider
     */

    /**
     * Exemple de méthode pour valider les input dont le type supporte l'attribut pattern
     * text, date, search, url, tel, email, password
     * @param evenement {Objet Event 'blur'}
     */
    validerTache : function(evenement){
        var $objCible = $(evenement.currentTarget);
        this.effacerRetro($objCible);

        if (this.verifierSiRempli($objCible) == false){
            this.afficherErreur($objCible, this.objJSONMessages.nom_liste.erreurs.vide);
        }
        else {
            if (this.verifierPattern($objCible) == true){
                this.ajouterEncouragement($objCible);
            }
            else {
                this.afficherErreur($objCible, this.objJSONMessages.nom_liste.erreurs.motif);
            }
        }

    },

    /**
     * Exemple de méthode pour valider des boutons radio
     * @param evenement {Objet Event 'blur'}
     */
    validerCours : function(evenement){
        var $objCible = $(evenement.currentTarget);
        this.effacerRetro($objCible);

        if ($objCible.prop('checked') == false){
            this.afficherErreur($objCible, this.objJSONMessages.couleurs.erreurs.vide);
        }
        else{
            this.ajouterEncouragement($objCible);
        }

    },

    /******************************************************************************************
     * Méthodes utilitaires
     */

    /**
     * verifierSiRempli reçoit un élément de formulaire et retourne true ou false
     * @param $objJQueryDOM
     * @return {boolean}
     */
    verifierSiRempli : function($objJQueryDOM){
        var rempli = true;
        if($objJQueryDOM.val() == ""){
            rempli = false;
        }
        return rempli;
    },

    /**
     * verifierPattern reçoit un élément de formulaire et retourne true ou false
     * @param $objJQueryDOM
     * @return {boolean}
     */
    verifierPattern : function($objJQueryDOM){
        var strMotif = "^" + $objJQueryDOM.attr('pattern') + "$";
        var regex = new RegExp(strMotif);
        return regex.test($objJQueryDOM.val());
    },

    /**
     * afficherErreur reçoit un élément de formulaire et un texte de message d'erreur
     * @todo remplacer le caractère utf8 ⚠ par un balisage accessible d'une icône provenant d'une police d'icône
     * @todo pour réaliser le todo précédent il faudra remplacer la méthode .text() par la méthode .html()
     * @param $objJQueryDOM
     * @param message
     */
    afficherErreur : function($objJQueryDOM, message){

        // On remonte au conteneur parent puis et on cherche à l'intérieur le conteneur pour l'erreur
        $parent = $objJQueryDOM.closest('.conteneurChamp');

        $parent.find('.contenantRetro').html('<span class="fi flaticon-error erreur">'+message+'</span>');
        $legende = $parent.find('legend');

        if ($legende.length) {
            // On vérifie si le parent a une balise legend
            $parent.addClass('erreur erreurElement');
        }
        else {
            // Sinon on travaille directement sur l'élément de formulaire
            $objJQueryDOM.addClass('erreurElement');
        }

    },

    /**
     * ajouterEncouragement reçoit un élément de formulaire
     * @todo adapter le html de l'encouragement
     * @param $objJQueryDOM
     */
    ajouterEncouragement : function ($objJQueryDOM){

        $legende = $objJQueryDOM.closest('.conteneurChamp').find('legend');

        if($legende.length){
            // On vérifie si le parent a une balise legend
            $legende.append('<span class="fi flaticon-success ok"> </span>');
        }
        else {
            // Sinon on travaille directement sur l'élément de formulaire
            $objJQueryDOM.after('<span class="fi flaticon-success ok"></span>');
        }

    },

    /**
     * effacerRetro reçoit un élément de formulaire
     * @param $objJQueryDOM
     */
    effacerRetro : function ($objJQueryDOM){
        $parent = $objJQueryDOM.closest('.conteneurChamp');
        $legende = $parent.find('legend');

        if($legende.length){
            $parent.removeClass('erreurElement');
        }else{
            $objJQueryDOM.removeClass('erreurElement');
        }

        $objJQueryDOM.closest('.conteneurChamp').find('.erreur').text('');
        $objJQueryDOM.closest('.conteneurChamp').find('.ok').remove();
    },

};