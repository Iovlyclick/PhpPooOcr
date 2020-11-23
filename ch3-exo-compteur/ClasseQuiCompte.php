<?php

class ClasseQuiCompte
{
    private static $_compteur = 0; //on declare l'attribut static privée $_compteur

    public function __construct()
    {
        self::$_compteur++; //on incrémente la valeur à l'instanciation
    }

    public static function getCompteur() // M&thode static qui renvoie la valeur du compteur
    {
        return self::$_compteur;
    }
}