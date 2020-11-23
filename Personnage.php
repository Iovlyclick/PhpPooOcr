<?php
class Personnage
{
    private $_force; //la force du personnage (valeur utilisé par l'attaque)
    private $_experience; //l'exp du personnage
    private $_degats; //les degats du personnage

    //déclarations des constantes en rapport avec la force
    const FORCE_PETITE = 20;
    const FORCE_MOYENNE = 50;
    const FORCE_GRANDE = 80;

    //variable statique PRIVEE
    private static $_texteADire = "La haine blesse celui qui la porte";

    public function __construct($forceInitiale, $degats) // Constructeur demandant 2 paramètres
    {
        echo 'Voici le constructeur !<br/>'; // Message s'affichant une fois qu'une instance de l'objet est créé.
        $this->setForce($forceInitiale); // Initialisation de la force.
        $this->setDegats($degats); // Initialisation des dégâts.
        $this->_experience = 1; // Initialisation de l'expérience à 1.
    }

    public function frapper(Personnage $cible)
    {
        //on ajoute la valeur de $_force de l'objet courant à la valeur de l'attribut $_degats de l'objet contenu dans $cible
        $cible->_degats += $this->_force;
    }

    public function gagnerExperience()
    {
        //on ajoute 1 à l'attributs $_experience
        $this->_experience++;
    }

    public function afficherExperience()
    {
        //on affiche la valeur de l'attribut $_experience
        echo $this->_experience; 
    }

    //setter de $_force
    public function setForce($force)
    {
        if (!is_int($force)) //si $force n'est pas un entier
        {
            trigger_error('La force d\'un personnage doit être un nombre entier', E_USER_WARNING);
            return;
        }

        if ($force > 100) //on vérifie qu'on n'assigne pas une valeur supérieure à 100
        {
            trigger_error('La force d\'un personnage ne peut pas dépasser 100', E_USER_WARNING);
            return;
        }

        //on vérifier que $force passé en paramètre est bien une constante de la classe
        if (in_array($force, [self::FORCE_PETITE, self::FORCE_MOYENNE, self::FORCE_GRANDE])) 
        {
            $this->_force = $force;
        }
    }

    //setter de $_experience
    public function setExperience($experience)
    {
        if (!is_int($experience)) //si $experience n'est pas un entier
        {
            trigger_error('L\'éxperience d\'un personnage doit être un nombre entier', E_USER_WARNING);
            return;
        }

        if ($experience > 100) //on vérifie qu'on n'assigne pas une valeur supérieure à 100
        {
            trigger_error('L\'éxperience d\'un personnage ne peut pas dépasser 100', E_USER_WARNING);
            return;
        }

        $this->_experience = $experience;
    }

    //setter de $_degats.
    public function setDegats($degats)
    {
        if (!is_int($degats)) //si $experience n'est pas un entier
        {
        trigger_error('Le niveau de dégâts d\'un personnage doit être un nombre entier', E_USER_WARNING);

        return;
        }

    $this->_degats = $degats;
    }

    //getter de $_force: renvoie le contenu de l'attribut $_force
    public function force()
    {
        return $this->_force;
    }

    //getter de $_experience: renvoie le contenu de l'attribut $_experience
    public function experience()
    {
        return $this->_experience;
    }

    //getter de $_degats: renvoie le contenu de l'attribut $_degats
    public function degats()
    {
        return $this->_degats;
    }

    public static function parler()
    {
        echo self::$_texteADire;
    }
}
