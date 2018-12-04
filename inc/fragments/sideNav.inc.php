<?php

// *************************** Liste des listes **********************
$strRequeteListesSideNav = "SELECT id_liste, nom_liste FROM t_liste";

$pdosResultatListesSideNav = $pdoConnexion -> query($strRequeteListesSideNav);

$arrListesSideNav = array();
for($intCtr = 0; $ligne = $pdosResultatListesSideNav -> fetch(); $intCtr++){
    $arrListesSideNav[$intCtr]["id_liste"] = $ligne["id_liste"];
    $arrListesSideNav[$intCtr]["nom_liste"] = $ligne["nom_liste"];
}

$pdosResultatListesSideNav -> closeCursor();
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
            <?php for($intCtr = 0; $intCtr < count($arrListesSideNav); $intCtr++){?>
                <li class="sidenav__listeSecondaire__item <?php if(isset($_GET["id_item"]) == true){  if($arrListesSideNav[$intCtr]["id_liste"] == $arrInfosItem["id_liste"]) { echo "sidenav__listeSecondaire__item--active"; } } else{ if($arrListesSideNav[$intCtr]["id_liste"] == $_GET["id_liste"]) { echo "sidenav__listeSecondaire__item--active"; } }?>">
                    <a class="sidenav__listeSecondaire__item__lien" href="consulter-liste.php?id_liste=<?php echo  $arrListesSideNav[$intCtr]["id_liste"];?>"><?php echo $arrListesSideNav[$intCtr]["nom_liste"]; ?></a>
                </li>
            <?php } ?>
        </div>
    </ul>
</aside>