<?php

class PersonnagesManager
{
    private $_db;

    public function __construct($db)
    {
        $this->setDb($db);
    }

    public function add($perso)
    {
        $q = $this->_db->prepare('INSERT INTO personnages(nom, forcePerso, degats, experience, niveau, classe, atout, timeEndormi, enLigne, peutFrapper) 
        VALUES(:nom, :forcePerso, :degats, :experience, :niveau, :classe, :atout, :timeEndormi, :enLigne, :peutFrapper)');

        $perso->hydrate([
            'forcePerso' => Personnage::FORCE_INIT,
            'degats' => Personnage::DEGATS_INIT,
            'experience' => Personnage::EXP_INIT,
            'niveau' => Personnage::LV_INIT,
            'atout' => Personnage::ATOUT_INIT,
            'timeEndormi' => Personnage::TIME_ENDORMI_INIT,
            'enLigne' => Personnage::CONNECTE,
            'peutFrapper' => Personnage::QTE_FRAPPE,
        ]);

        $q->bindValue(':nom', $perso->getNom());
        $q->bindValue(':forcePerso', $perso->getForcePerso(), PDO::PARAM_INT);
        $q->bindValue(':degats', $perso->getDegats(), PDO::PARAM_INT);
        $q->bindValue(':experience', $perso->getExperience(), PDO::PARAM_INT);
        $q->bindValue(':niveau', $perso->getNiveau(), PDO::PARAM_INT);
        $q->bindValue(':classe', $perso->getClasse());
        $q->bindValue(':atout', $perso->getAtout(), PDO::PARAM_INT);
        $q->bindValue(':timeEndormi', $perso->getTimeEndormi(), PDO::PARAM_INT);
        $q->bindValue(':enLigne', $perso->getEnLigne(), PDO::PARAM_INT);
        $q->bindValue(':peutFrapper', $perso->getPeutFrapper(), PDO::PARAM_INT);
        
        $q->execute();

        
        $perso->setId($this->_db->lastInsertId());
    }

    public function count()
    {
        return $this->_db->query('SELECT COUNT(*) FROM personnages')->fetchColumn();
    }

    public function delete($perso)
    {
        $this->_db->exec('DELETE FROM personnages WHERE id = ' . $perso->getId());

    }

    public function exists($info)
    {
        if (is_int($info)) //si un personnage ayant pour id $info existe
        {
            return (bool) $this->_db->query('SELECT COUNT(*) FROM personnages WHERE id = ' . $info)->fetchColumn();
        }

        //sinon, on verifie si le nom $info existe
        $q = $this->_db->prepare('SELECT COUNT(*) FROM personnages WHERE nom = :nom');
        $q->execute([':nom' => $info]);

        return (bool) $q->fetchColumn();
    }

    public function get($info)
    {
        if (is_int($info))
        {
            $q =$this->_db->query('SELECT * FROM personnages WHERE id = ' . $info);
            $perso = $q->fetch(PDO::FETCH_ASSOC);

        }
        else
        {
            $q = $this->_db->prepare('SELECT * FROM personnages WHERE nom = :nom');
            $q->execute([':nom' => $info]);
            $perso = $q->fetch(PDO::FETCH_ASSOC);
            
        }
        switch ($perso['classe']) {
            case 'guerrier':
                return new Guerrier($perso);
            case 'magicien':
                return new Magicien($perso);
            default:
                return null;
        }
    }

    public function getConnected($nom)
    {
        $persos = [];

        $q = $this->_db->prepare('SELECT * FROM personnages WHERE nom <> :nom AND enLigne = 1 ORDER BY nom');
        $q->execute([':nom' => $nom]);

        while ($donnees = $q->fetch(PDO::FETCH_ASSOC)) 
        {
            switch ($donnees['classe']) {
                case 'guerrier':
                    $persos[] = new Guerrier($donnees);
                    break;
                case 'magicien':
                    $persos[] = new Magicien($donnees);
                    break;
            }
        }

        return $persos;
    }

    public function resetFrappe()
    {
        $q = $this->_db->prepare('UPDATE personnages SET peutFrapper = 3 WHERE enLigne = 1');
        $q->execute();
    }

    public function update($perso)
    {
        $q = $this->_db->prepare('UPDATE personnages SET degats = :degats, forcePerso = :forcePerso, niveau = :niveau, experience = :experience, timeEndormi = :timeEndormi, atout = :atout, enLigne = :enLigne, peutFrapper = :peutFrapper WHERE id = :id');

        $q->bindValue(':degats', $perso->getDegats());
        $q->bindValue(':forcePerso', $perso->getForcePerso());
        $q->bindValue(':niveau', $perso->getNiveau());
        $q->bindValue(':experience', $perso->getExperience());
        $q->bindValue(':timeEndormi', $perso->getTimeEndormi());
        $q->bindValue(':atout', $perso->getAtout());
        $q->bindValue(':enLigne', $perso->getEnLigne());
        $q->bindValue(':peutFrapper', $perso->getPeutFrapper());
        $q->bindValue(':id', $perso->getId());

        $q->execute();
        
    }

    public function setDb(PDO $db)
    {
        $this->_db = $db;
    }


}