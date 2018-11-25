<aside class="sidenav">
    <ul class="sidenav__liste">
        <div class="sidenav__listePrimaire">
            <li class="sidenav__listePrimaire__item">
                <a class="sidenav__liste__item__lien fi flaticon-home" href="index.php">Accueil</a>
            </li>
            <li class="sidenav__listePrimaire__item">
                <a class="sidenav__liste__item__lien fi flaticon-list" href="ajouter-liste.php">Ajouter une liste</a>
            </li>
        </div>
        <div class="sidenav__listeSecondaire">
            <?php for($intCtr = 0; $intCtr < count($arrListes); $intCtr++){?>
                <li class="sidenav__listeSecondaire__item<?php if($arrInfosItem["id_liste"] == $arrListes[$intCtr]["id_liste"]) { echo "__active"; } ?>">
                    <a  class="sidenav__listeSecondaire__item__lien" href="consulter-liste.php?id_liste=<?php echo  $arrListes[$intCtr]["id_liste"] ?>"><?php echo $arrListes[$intCtr]["nom_liste"]; ?></a>
                </li>
            <?php } ?>
        </div>
    </ul>
</aside>