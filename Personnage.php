<?php
class Personnage
{
    private $_degats = 0; //les degats du personnage
    private $_experience = 0; //l'exp du personnage
    private $_force = 20; //la force du personnage (valeur utilisé par l'attaque)

    public function frapper($cible)
    {
        //on ajoute la valeur de $_force de l'objet courant à la valeur de l'attribut $_degats de l'objet contenu dans $cible
        $cible->_degats += $this->_force;
    }
    
    public function gagnerExperience()
    {
        //on ajoute 1 à l'attributs $_experience
        $this->_experience = $this->_experience + 1;
    }
    
    public function afficherExperience()
    {
        //on affiche la valeur de l'attribut $_experience
        echo $this->_experience; 
    }

}

$perso1 = new Personnage;
$perso2 = new Personnage;

$perso1->frapper($perso2);
$perso1->gagnerExperience();

$perso2->frapper($perso1);
$perso2->gagnerExperience();