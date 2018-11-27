<?php

// *************************** Liste des listes **********************
$strRequeteListes = "SELECT id_liste, nom_liste FROM t_liste";

$pdosResultatListes = $pdoConnexion -> query($strRequeteListes);

$arrListesHeader = array();
for($intCtr = 0; $ligne = $pdosResultatListes -> fetch(); $intCtr++){
    $arrListes[$intCtr]["id_liste"] = $ligne["id_liste"];
    $arrListes[$intCtr]["nom_liste"] = $ligne["nom_liste"];
}

$pdosResultatListes -> closeCursor();
?>

<aside class="sidenav">
    <ul class="sidenav__liste">
        <div class="sidenav__listePrimaire">
            <li class="sidenav__listePrimaire__item">
                <a class="sidenav__listePrimaire__item__lien fi flaticon-home" href="index.php">Accueil</a>
            </li>
            <li class="sidenav__listePrimaire__item">
                <a class="sidenav__listePrimaire__item__lien fi flaticon-list" href="ajouter-liste.php">Ajouter une liste</a>
            </li>
        </div>
        <div class="sidenav__listeSecondaire">
            <?php for($intCtr = 0; $intCtr < count($arrListes); $intCtr++){?>
                <li class="sidenav__listeSecondaire__item <?php if(isset($_GET["id_item"]) == true){  if($arrListes[$intCtr]["id_liste"] == $arrInfosItem["id_liste"]) { echo "sidenav__listeSecondaire__item--active"; } } else{ if($arrListes[$intCtr]["id_liste"] == $arrInfosListe["id_liste"]) { echo "sidenav__listeSecondaire__item--active"; } }?>">
                    <a class="sidenav__listeSecondaire__item__lien" href="consulter-liste.php?id_liste=<?php echo  $arrListes[$intCtr]["id_liste"] ?>"><?php echo $arrListes[$intCtr]["nom_liste"]; ?></a>
                </li>
            <?php } ?>
        </div>
    </ul>
</aside>