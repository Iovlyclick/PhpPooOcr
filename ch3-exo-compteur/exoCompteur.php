<?php

function chargerClasse($classe)
{
  require $classe . '.php'; //le paramètre correspond au nom de classe après l'opérateur new
}

spl_autoload_register('chargerClasse'); //autoload fonction appellée à l'instanciation d'une classe absente du fichier

for ($i=0; $i < random_int(0, 128); $i++) { 
    new ClasseQuiCompte();
}


echo ClasseQuiCompte::getCompteur();