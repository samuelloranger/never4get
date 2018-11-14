/**
 * @file Objet de validations avec jQuery
 * @author Nom étudiant.e <courriel etudiant.e>
 */

var validationsMandatX = {

    objJSONMessages : {
        "nomItem": {
            "erreurs": {
                "vide": "Veuillez entrer une tâche.",
                "motif": "Minuscules, majuscules, caractères accentués, espaces, guillemets simples, traits d'union et #. Maximum 55 caractères."
            }
        },
        "echeance": {
            "erreurs": {
                "vide": "Veuillez enter une date d'échéance complète.",
                "motif": "Entrez une date d'échéance valide!"
            }
        },
        "cours": {
            "erreurs": {
                "vide": "Veuillez associer un cours à cette tâche!"
            }
        }
    },

    /******************************************************************************************
     * Constructeur
     */

    /**
     * dans la méthode initialiser, on peut définir les attributs de l'objet et ajouter les écouteurs d'événements
     * @param evenement {Objet Event 'ready'}
     */

    initialiser : function(evenement){
        //console.log('dans initialiser');

        // pour les champs de saisie, on peut se servir du id #
        $('#tache').on('blur', this.validerTache.bind(this));

        // sur les boutons radio on se sert du name qui est commun
        $('[name=cours]').on('blur', this.validerCours.bind(this));

        // sur la date d'échéance, on validera seulement au sortir du dernier select : l'année
        $('#annee').on('blur', this.validerAnnee.bind(this));
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
     * Exemple de méthode pour valider des boutons radio
     * @param evenement {Objet Event 'blur'}
     */
    validerCours : function(evenement){
        var $objCible = $(evenement.currentTarget);
        this.effacerRetro($objCible);

        if ($objCible.prop('checked') == false){
            this.afficherErreur($objCible, this.objJSONMessages.cours.erreurs.vide);
        }
        else{
            this.ajouterEncouragement($objCible);
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
        if ($objCible.val() !== 'null' || $('#mois').val() !== 'null' || $('#jour').val() !== 'null'){

            // si oui on vérifie que TOUS les select de date sont complétés
            if ($objCible.val() !== 'null' && $('#mois').val() !== 'null' && $('#jour').val() !== 'null'){
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
        $objJQueryDOM.closest('.conteneurChamp').find('.erreur').text('⚠ ' + message);
        $parent = $objJQueryDOM.closest('.conteneurChamp');
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

        $legende = $objJQueryDOM.closest('.conteneurChamp').find('legend');

        if($legende.length){
            // On vérifie si le parent a une balise legend
            $legende.append('<span class="ok"> ✓ </span>');
        }
        else {
            // Sinon on travaille directement sur l'élément de formulaire
            $objJQueryDOM.after('<span class="ok"> ✓ </span>');
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
        $objJQueryDOM.closest('.conteneurChamp').find('.OK').remove();
    },

};