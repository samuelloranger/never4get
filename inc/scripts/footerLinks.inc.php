<script src="https://code.jquery.com/jquery-3.2.1.min.js"
integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
crossorigin="anonymous"></script>

<script>window.jQuery || document.write('<script src="node_modules/jquery/dist/jquery.min.js">\x3C/script>')</script>

<script src="js/menu.js"></script>

<script>
var niveau = "<?php echo $strNiveau; ?>"    
$('body').addClass('js');
$(document).ready(function()
{
    /**
     *Initialiser les modules JavaScript ici: menu, accordéon...
        */
    menu.initialiser();
});
</script>