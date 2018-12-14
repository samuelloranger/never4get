/**
 * @file Objet de validations avec jQuery
 * @author Samuel Loranger <samuelloranger@gmail.com>
 */

var validationsMandatB = {

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

    /******************************************************************************************
     * Méthodes spécifiques
     * On ajoute une méthode validerQuelqueChose pour chaque élément de formulaire à valider
     */

    configurerValidations : function(){

        // pour les champs de saisie, on peut se servir du id #
        $('#nom_item').on('blur', this.validerTache.bind(this));

        // sur la date d'échéance, on validera seulement au sortir du dernier select : l'année
        // $('#date').on('blur', this.validerAnnee.bind(this));

        //Masque le fieldset à l'ouverture du fichier

        if($("#date").val() == "" && $("#heure").val() == "") {
            console.log("pas de date entrée");
            $(".formulaire__dateEcheanceTitre").append('' +
                '<div class="cacherDateEcheance" id="btnCacherDateEchance"> ' +
                '<label class="cacherDateEcheance__btn"></label> ' +
                '<input type="checkbox" class="visuallyhidden" id="curseurCacherDateEchance"> ' +
                '</div>');

            $(".formulaire__conteneurDate").toggleClass("formulaire__conteneurDate--cacher");
            $(".date__conteneurSelectDate").toggleClass("date__conteneurSelectDate--cacher");
        }
        else{
            $(".formulaire__dateEcheanceTitre").append('' +
                '<div class="cacherDateEcheance" id="btnCacherDateEchance"> ' +
                '<label  class="cacherDateEcheance__btn cacherDateEcheance__btn--active"></label> ' +
                '<input type="checkbox" class="visuallyhidden" id="curseurCacherDateEchance"> ' +
                '</div>');
        }

        //teste le bouton pour cacher la date d'échéance
        $('#btnCacherDateEchance').on('click', this.cacherAfficherDateEcheance.bind(this));
    },

    cacherAfficherDateEcheance : function(){
        $("#date").val(0);
        $("#heure").val(0);

        $(".formulaire__conteneurDate").toggleClass("formulaire__conteneurDate--cacher");
        $(".date__conteneurSelectDate").toggleClass("date__conteneurSelectDate--cacher");
        $(".cacherDateEcheance").toggleClass("cacherDateEcheance--active");
        $(".cacherDateEcheance__btn").toggleClass("cacherDateEcheance__btn--active");
    },

    /**
     * Exemple de méthode pour valider les input dont le type supporte l'attribut pattern
     * text, date, search, url, tel, email, password
     * @param evenement {Objet Event 'blur'}
     */
    validerTache : function(evenement){
        var $objCible = $(evenement.currentTarget);
        this.effacerRetro($objCible);

        if (this.verifierSiRempli($objCible) == false){
            this.afficherErreur($objCible, this.objJSONMessages.nomItem.erreurs.vide);
        }
        else {
            if (this.verifierPattern($objCible) == true){
                this.ajouterEncouragement($objCible);
            }
            else {
                this.afficherErreur($objCible, this.objJSONMessages.nomItem.erreurs.motif);
            }
        }

    },

    /**
     * Exemple de méthode pour valider une date à partir de trois select:
     * #jour - #mois - #annee
     * @param evenement {Objet Event 'blur'}
     */
    validerAnnee : function(evenement){
        var $objCible = $(evenement.currentTarget);
        this.effacerRetro($objCible);

        // L'échéance est facultative mais si l'utilisateur entre une date incomplète, il faut afficher une erreur. Donc on vérifie si l'un des select n'est pas null.
        if ($objCible.val() !== 'null' || $('#date').val() !== 'null'){
            // si oui on vérifie que TOUS les select de date sont complétés
            if ($objCible.val() !== 'null' && $('#date').val() !== 'null'){
                // Ici, on pourrait ajouter d'autres validations comme de vérifier s'il s'agit d'une date valide : pas un 30 février par exemple!
                this.ajouterEncouragement($objCible);
            }
            else{
                this.afficherErreur($objCible, this.objJSONMessages.echeance.erreurs.vide);
            }

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
        $objJQueryDOM.closest('.formulaire__conteneurChamp').find('.erreur').text('⚠ ' + message);
        $parent = $objJQueryDOM.closest('.formulaire__conteneurChamp');
        $legende = $parent.find('legend');

        if ($legende.length) {
            // On vérifie si le parent a une balise legend
            $parent.addClass('erreurElement');
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
        $legende = $objJQueryDOM.closest('.formulaire__conteneurChamp').find('label');

        if($legende.length){
            // On vérifie si le parent a une balise legend
            $legende.append('<span class="ok"> ✓ </span>');
        }
        else {
            // Sinon on travaille directement sur l'élément de formulaire
            $objJQueryDOM.append('<span class="ok"> ✓ </span>');
        }

    },

    /**
     * effacerRetro reçoit un élément de formulaire
     * @param $objJQueryDOM
     */
    effacerRetro : function ($objJQueryDOM){
        $parent = $objJQueryDOM.closest('.formulaire__conteneurChamp');
        $legende = $parent.find('legend');

        if($legende.length){
            $parent.removeClass('erreurElement');
        }else{
            $objJQueryDOM.removeClass('erreurElement');
        }

        $objJQueryDOM.closest('.formulaire__conteneurChamp').find('.erreur').text('');
        $objJQueryDOM.closest('.formulaire__conteneurChamp').find('.ok').remove();
    },

};