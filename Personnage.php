<?php
class Personnage
{
    private $_degats = 0; //les degats du personnage
    private $_experience = 0; //l'exp du personnage
    private $_force = 20; //la force du personnage (valeur utilisé par l'attaque)

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

        $this->_force = $force;

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

    //getter de $_degats: renvoie le contenu de l'attribut $_degats
    public function degats()
    {
        return $this->_degats;
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
}

$perso1 = new Personnage;
$perso2 = new Personnage;

$perso1->setForce(10);
$perso1->setExperience(2);

$perso2->setForce(90);
$perso2->setExperience(58);

$perso1->frapper($perso2);
$perso1->gagnerExperience();

$perso2->frapper($perso1);
$perso2->gagnerExperience();

echo 'La force du personnage 1 est de : ' . $perso1->force() . '. il a ' . $perso1->experience(). ' points d\'éxperience et son niveau de dégat et de ' . $perso1->degats() . '<br/>';

echo 'La force du personnage 2 est de : ' . $perso2->force() . '. il a ' . $perso2->experience(). ' points d\'éxperience et son niveau de dégat et de ' . $perso2->degats() . '<br/>';


