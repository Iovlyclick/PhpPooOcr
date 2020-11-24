<?php

function chargerClasse($classe)
{
  require $classe . '.php'; //le paramètre correspond au nom de classe après l'opérateur new
}

spl_autoload_register('chargerClasse'); //autoload fonction appellée à l'instanciation d'une classe absente du fichier

$perso = new Personnage([
    'nom' => 'Victor',
    'forcePerso' => 5, 
    'degats' => 0,
    "niveau" => 1,
    'experience' => 0,
]);

$db = new PDO('mysql:host=localhost;dbname=ocr_poo', 'root', 'toto');

$manager = new PersonnagesManager($db);

$manager->add($perso);