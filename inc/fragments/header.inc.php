<header class="header bleed">
    <div class="conteneur">
        <img class="header__logo" src="./images/logo/icon_never4get_x425.png" alt="logo never4get">
        <ul class="header__liste header__liste--ferme">
            <li class="header__liste__item"><a href="index.php">Accueil</a></li>
            <li class="header__liste__item"><a href="ajouter-liste.php">Ajouter une liste</a></li>
            <li class="header__liste__item"><a href="">Compte</a></li>
                <?php for($intCtr = 0; $intCtr < count($arrListes); $intCtr++){?>
                    <li class="header__liste__item">
                        <a  class="header__liste__item__lien" href="consulter-liste.php?id_liste=<?php echo  $arrListes[$intCtr]["id_liste"] ?>"><?php echo $arrListes[$intCtr]["nom_liste"]; ?></a>
                    </li>
                <?php } ?>
        </ul>
    </div>
</header>