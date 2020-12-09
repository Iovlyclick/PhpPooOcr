<?php

abstract class Personnage
{
    protected $id;
    protected $nom;
    protected $forcePerso;
    protected $degats;
    protected $experience;
    protected $niveau;
    protected $classe;
    protected $atout;
    protected $timeEndormi;
    protected $enLigne;
    protected $peutFrapper;

    //constantes liées à la valeur de retour des actions de combats
    const CEST_MOI = 1;
    const PERSONNAGE_TUE = 2;
    const PERSONNAGE_FRAPPE = 3;
    const PERSONNAGE_ENSORCELE = 4;
    const PAS_DE_MAGIE = 5;
    const PERSONNAGE_ENDORMI = 6;
    const PAS_MON_TOUR = 7;

    //constantes liées aux statistiques du personnage
    const FORCE_INIT = 5;
    const DEGATS_INIT = 0;
    const EXP_INIT = 0;
    const LV_INIT = 1;
    const ATOUT_INIT = 4;
    const TIME_ENDORMI_INIT = 0;

    const GAIN_EXP = 99;
    const QTE_DEGATS = 0;
    const QTE_FRAPPE = 3;
    const CONNECTE = 1;
    const DECONNECTE = 0;

    public function __construct($donnees)
    {
        $this->hydrate($donnees);
        $this->classe = strtolower(static::class);
    }

    public function estEndormi()
    {
        return $this->timeEndormi > time();
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

    public function frapper(Personnage $perso)
    {
    if ($perso->id == $this->id)
    {
        return self::CEST_MOI;
    }
    
    if ($this->estEndormi())
    {
        return self::PERSONNAGE_ENDORMI;
    }
    if ($this->peutFrapper === 0) 
    {
        return self::PAS_MON_TOUR;
    }
    
        return $perso->recevoirDegats();
    }

    public function reduirePeutFrapper()
    {
        $this->setPeutFrapper($this->getPeutFrapper() - 1);
    }

    public function getId() 
    {
        return $this->id;
    }

    public function getNom() 
    {
        return $this->nom;
    }

    public function getForcePerso() 
    {
        return $this->forcePerso;
    }

    public function getDegats() 
    {
        return $this->degats;
    }

    public function getExperience() 
    {
        return $this->experience;
    }

    public function getNiveau() 
    {
        return $this->niveau;
    }

    public function getClasse() 
    {
        return $this->classe;
    }

    public function getAtout() 
    {
        return $this->atout;
    }

    public function getTimeEndormi() 
    {
        return $this->timeEndormi;
    }

    public function getEnLigne()
    {
        return $this->enLigne;
    }

    public function getPeutFrapper()
    {
        return $this->peutFrapper;
    }

    public function setId($id) 
    {
        $this->id = (int) $id;
    }

    public function nomValide()
    {
        return !empty($this->nom);
    }

    public function setNom($nom) 
    {
        if (is_string($nom) && strlen($nom) <=30)
        {
            $this->nom = $nom;
        }
    }

    public function setForcePerso($forcePerso) 
    {
        $forcePerso = (int) $forcePerso;

        if ($forcePerso >= 0 && $forcePerso <= 100)
        {
            $this->forcePerso = $forcePerso;
        }
    }

    public function gagnerForcePerso()
    {
        $this->forcePerso += 5;
    }

    public function setDegats($degats) 
    {
        $degats = (int) $degats;

        if ($degats >= 0 && $degats <= 100)
        {
            $this->degats = $degats;
        }
    }

    public function recevoirDegats()
    {
        $this->degats += self::QTE_DEGATS; 

        if ($this->degats >= 100)
        {
            return self::PERSONNAGE_TUE;
        }

        return self::PERSONNAGE_FRAPPE;
    }

    public function setExperience($exp) 
    {
        $exp = (int) $exp;
        
        $this->experience = $exp;
    }

    public function gagnerExperience()
    {
        $this->experience += self::GAIN_EXP;
        
        if ($this->experience > 99) 
        {
            $this->experience -= 100;
            $this->gagnerNiveau();
        }
    }

    public function setNiveau($niveau) 
    {
        $niveau = (int) $niveau;

        if ($niveau >= 0 && $niveau <= 100)
        {
            $this->niveau = $niveau;
        }
    }

    public function gagnerNiveau()
    {
        $this->niveau += 1;
        
        $this->gagnerForcePerso();
    }

    public function setAtout($atout)
    {
        $atout = (int) $atout;
    
        if ($atout >= 0 && $atout <= 100)
        {
            $this->atout = $atout;
        }
    }

    public function setTimeEndormi($time)
    {
        $this->timeEndormi = (int) $time;
    }

    public function setEnLigne($statut)
    {
        $statut = (int) $statut;
        $this->enLigne = $statut;
    }

    public function setPeutFrapper($qteFrappe)
    {
        $qteFrappe = (int) $qteFrappe;
        $this->peutFrapper = $qteFrappe;
    }

    public function recevoirSort()
    {
        $this->timeEndormi += 5; 

        return self::PERSONNAGE_ENDORMI;
    }

    public function reveil()
    {
        $secondes = $this->timeEndormi;
        $secondes -= time();
        
        $heures = floor($secondes / 3600);
        $secondes -= $heures * 3600;
        $minutes = floor($secondes / 60);
        $secondes -= $minutes * 60;
        
        $heures .= $heures <= 1 ? ' heure' : ' heures';
        $minutes .= $minutes <= 1 ? ' minute' : ' minutes';
        $secondes .= $secondes <= 1 ? ' seconde' : ' secondes';
        
        return $heures . ', ' . $minutes . ' et ' . $secondes;
    }

}
