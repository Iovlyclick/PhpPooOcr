<?php

function chargerClasse($classe)
{
  require $classe . '.php'; //le paramètre correspond au nom de classe après l'opérateur new
}

spl_autoload_register('chargerClasse'); //autoload fonction appellée à l'instanciation d'une classe absente du fichier

$perso1 = new Personnage(Personnage::FORCE_MOYENNE, 0);
$perso2 = new Personnage(Personnage::FORCE_PETITE, 10);

Personnage::parler();
// $perso1->setForce(10);
// $perso1->setExperience(2);

// $perso2->setForce(90);
// $perso2->setExperience(58);

// $perso1->frapper($perso2);
// $perso1->gagnerExperience();

// $perso2->frapper($perso1);
// $perso2->gagnerExperience();

// echo 'La force du personnage 1 est de : ' . $perso1->force() . '. il a ' . $perso1->experience(). ' points d\'éxperience et son niveau de dégat et de ' . $perso1->degats() . '<br/>';

// echo 'La force du personnage 2 est de : ' . $perso2->force() . '. il a ' . $perso2->experience(). ' points d\'éxperience et son niveau de dégat et de ' . $perso2->degats() . '<br/>';


