<header class="header bleed">
    <div class="conteneur">
        <div class="header__logo">

        <ul class="header__liste header__liste--ferme">
            <li class="header__liste__item"><a class="header__liste__item__itemMenu fi flaticon-home" href="index.php">Accueil</a></li>
            <li class="header__liste__item"><a class="header__liste__item__itemMenu fi flaticon-add"  href="ajouter-liste.php">Ajouter une liste</a></li>
            <li class="header__liste__item"><a  class="header__liste__item__itemMenu fi flaticon-user" href="">Compte</a></li>
                <?php for($intCtr = 0; $intCtr < count($arrListes); $intCtr++){?>
                    <li class="header__liste__item header__liste__item<?php if($arrListes["id_liste" == $intCtr]){ echo "--active"; }?>">
                        <a  class="header__liste__item__lien" href="consulter-liste.php?id_liste=<?php echo  $arrListes[$intCtr]["id_liste"] ?>"><?php echo $arrListes[$intCtr]["nom_liste"]; ?></a>
                    </li>
                <?php } ?>
        </ul>
    </div>
</header>