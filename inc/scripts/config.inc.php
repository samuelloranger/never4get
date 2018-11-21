<?php

// Verifier si l'exécution se fait sur le serveur de développement (local) ou celui de la production:
if (stristr($_SERVER['HTTP_HOST'], 'local') || (substr($_SERVER['HTTP_HOST'], 0, 7) == '192.168')) {
    $blnLocal = TRUE;
} else {
    $blnLocal = FALSE;
}

//var_dump($_SERVER['HTTP_HOST']);

// Selon l'environnement d'exécution (développement ou en ligne)
if ($blnLocal) {
    $strHost = 'localhost';
    $strBD="18_pni1_tofu";
    $strUser = "never4get";
    $strPassword= "badrequest";
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    $strHost = 'timunix2.cegep-ste-foy.qc.ca';
    $strBD='18_pni1_tofu_err';
    $strUser = '18_pni1_tofu_err';
    $strPassword = 'oups';
    // error_reporting(E_ALL & ~E_NOTICE);
    // error_reporting(0);
}

//FTP: erreur400, mdp: badrequest

//Data Source Name pour l'objet PDO
$strDsn = 'mysql:dbname='.$strBD.';host='.$strHost;
//Tentative de connexion
$pdoConnexion = new PDO($strDsn, $strUser, $strPassword);
//Changement d'encodage de l'ensemble des caractères pour UTF-8
$pdoConnexion->exec("SET CHARACTER SET utf8");
//Pour obtenir des rapports d'erreurs et d'exception avec errorInfo() du pilote PDO
$pdoConnexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//$pdoConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);


date_default_timezone_set("America/Montreal");
?>