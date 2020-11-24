<?php

class Personnage
{
    private $_id;
    private $_nom;
    private $_forcePerso;
    private $_degats;
    private $_experience;
    private $_niveau;

    public function __construct($donnees)
    {
        $this->hydrate($donnees);
    }

    public function hydrate(array $donnees)
    {
        foreach ($donnees as $cle => $valeur) {
            $method = 'set'.ucfirst($cle);
            if (method_exists($this, $method)) {
                $this->$method($valeur);
            }
        }
    }

    public function getId() 
    {
        return $this->_id;
    }

    public function getNom() 
    {
        return $this->_nom;
    }

    public function getForcePerso() 
    {
        return $this->_forcePerso;
    }

    public function getDegats() 
    {
        return $this->_degats;
    }

    public function getExperience() 
    {
        return $this->_experience;
    }

    public function getNiveau() 
    {
        return $this->_niveau;
    }

    public function setId($id) 
    {
        $this->_id = (int) $id;
    }

    public function setNom($nom) 
    {
        if (is_string($nom) && strlen($nom) <=30)
        {
            $this->_nom = $nom;
        }
    }

    public function setForcePerso($forcePerso) 
    {
        $forcePerso = (int) $forcePerso;

        if ($forcePerso >= 0 && $forcePerso <= 100)
        {
            $this->_forcePerso = $forcePerso;
        }
    }

    public function setDegats($degats) 
    {
        $degats = (int) $degats;

        if ($degats >= 0 && $degats <= 100)
        {
            $this->_degats = $degats;
        }
    }

    public function setNiveau($niveau) 
    {
        $niveau = (int) $niveau;

        if ($niveau >= 0 && $niveau <= 100)
        {
            $this->_niveau = $niveau;
        }
    }

    public function setExperience($exp) 
    {
        $exp = (int) $exp;

        if ($exp >= 0 && $exp <= 100)
        {
            $this->_experience = $exp;
        }
    }
  
  
}
