<?php

function chargerClasse($classe)
{
    require $classe . '.php'; //le paramètre correspond au nom de classe après l'opérateur new
};

spl_autoload_register('chargerClasse'); //autoload fonction appellée à l'instanciation d'une classe absente du fichier

session_start();


$db = new PDO('mysql:host=localhost;dbname=ocr_poo', 'root', 'toto');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); //alert à chaque requête échouée

$manager = new PersonnagesManager($db);


if (isset($_SESSION['perso'])) 
{
    $perso = $_SESSION['perso'];
}

if (isset($_GET['deconnexion'])) 
{
    $perso->setEnLigne(Personnage::DECONNECTE);
    $perso->getEnLigne();
    $manager->update($perso);
    session_destroy();
    header('Location: .');
    exit();
}

if (isset($_POST['creer']) && isset($_POST['nom'])) 
{
    switch ($_POST['classe']) {
        case 'magicien':
            $perso = new Magicien(['nom' => $_POST['nom']]);
            break;

        case 'guerrier':
            $perso = new Guerrier(['nom' => $_POST['nom']]);
            break;

        default:
            $message = 'La classe du personnage est invalide.';
            break;
    }

    if (isset($perso)) {
        if (!$perso->nomValide()) 
        {
            $message = 'Le nom choisi est invalide.';
            unset($perso);
        }
        elseif ($manager->exists($perso->getNom())) 
        {
            $message = 'Le nom du personnage est déjà pris !';
            unset($perso);
        }
        else
        {
            $manager->add($perso);
        }
    }
    
}
elseif (isset($_POST['utiliser']) && isset($_POST['nom'])) 
{
    if ($manager->exists($_POST['nom'])) 
    {
        $perso = $manager->get($_POST['nom']);
        $perso->setEnLigne(Personnage::CONNECTE);
        $perso->getEnLigne();
        $manager->update($perso);
    }
    else 
    {
        $message = 'Ce personnage n\'existe pas !';
    }
}
elseif (isset($_GET['frapper'])) 
{
    if (!isset($perso)) 
    {
        $message = 'Merci de créer un personnage ou de vous identifier.';
    }
    else 
    {
        if (!$manager->exists((int) $_GET['frapper'])) 
        {
            $message = 'Le personnage que vous voulez frapper n\'existe pas !';
        }
        else 
        {
            $persoAFrapper = $manager->get((int) $_GET['frapper']);

            $retour = $perso->frapper($persoAFrapper);

            switch ($retour) 
            {
                case Personnage::CEST_MOI :
                    $message = 'Mais... pourquoi voulez-vous vous frapper ???';

                    break;

                case Personnage::PERSONNAGE_FRAPPE :
                    $message = 'Le personnage a bien été frappé !';

                    $perso->reduirePeutFrapper();
                    $manager->update($perso);
                    $manager->update($persoAFrapper);

                    break;

                case Personnage::PERSONNAGE_TUE :
                    $message = 'Vous avez tué ce personnage !';

                    $perso->gagnerExperience();
                    $perso->reduirePeutFrapper();
                    $manager->update($perso);
                    $manager->delete($persoAFrapper);

                    break;
                
                case Personnage::PERSONNAGE_ENDORMI :
                    $message = 'Vous êtes endormi, vous ne pouvez pas frapper de personnage !';

                    break;
                
                case Personnage::PAS_MON_TOUR :
                    $message = 'Votre tour est fini, vous ne pouvez pas frapper de personnage !';
                    
                    header('Location: index.php?message=' . $message);
            }
        }
    }
}
elseif (isset($_GET['sort'])) 
{
    if (!isset($perso)) 
    {
        $message = 'Merci de créer un personnage ou de vous identifier.';
    }
    else 
    {
        $persoAEnsorceler = $manager->get((int) $_GET['sort']);

        $retour = $perso->lancerUnSort($persoAEnsorceler);

        switch ($retour) {
            case Personnage::CEST_MOI:
                $message = 'Mais... pourquoi voulez-vous vous ensorceler ???';
                break;

            case Personnage::PERSONNAGE_ENSORCELE:
                $message = 'Le personnage a bien été ensorcelé !';

                $manager->update($perso);
                $manager->update($persoAEnsorceler);

                break;

            case Personnage::PAS_DE_MAGIE:
                $message = 'Vous n\'avez pas de magie !';
                break;

            case Personnage::PERSONNAGE_ENDORMI:
                $message = 'Vous êtes endormi, vous ne pouvez pas lancer de sort !';
                break;
        }
    }

}
?>

<!DOCTYPE html>
<html>

<head>
    <title>TP : Mini jeu de combat</title>

    <meta charset="utf-8" />
</head>

<body>
    <p>Nombre de personnages créees : <?= $manager->count() ?></p>
<?php 
    if (isset($message)) 
    {
        echo '<p>', $message, '</p>';
    }
    if (isset($_GET)) 
    {

        echo '<p>', $_GET['message'], '</p>';

    }

    if (isset($perso)) 
    {
?>
        <p><a href="?deconnexion=1">Déconnexion</a></p>

        <fieldset>
            <legend>Mes Informations</legend>
            <p>
                Nom : <?= htmlspecialchars($perso->getNom()) ?><br/>
                Classe : <?= htmlspecialchars($perso->getClasse()) ?><br/>
                Dégats : <?= $perso->getDegats() ?><br/>
                Force : <?= $perso->getForcePerso() ?><br/>
                Niveau : <?= $perso->getNiveau() ?><br/>
                Expérience : <?= $perso->getExperience() ?><br/>
                Nombre de coups pour ce tour : <?= $perso->getPeutFrapper() ?><br/>
<?php
        
        // On affiche l'atout du personnage suivant son type.
        switch ($perso->getClasse())
        {
        case 'magicien' :
            echo 'Magie : ';
            break;
        
        case 'guerrier' :
            echo 'Protection : ';
            break;
        }

        echo $perso->getAtout();

?>
            </p>
        </fieldset>

        <fieldset>
            <legend>Qui frapper ?</legend>
            <p>
<?php
        // On récupère tous les personnages par ordre alphabétique, dont le nom est différent de celui de notre personnage (on va pas se frapper nous-même :p).
        $retourPersos = $manager->getConnected($perso->getNom());
        $perso = $manager->get($perso->getId());


        if (empty($retourPersos))
        {
            echo 'Personne à frapper !';
        }

        else
        {
            if ($perso->estEndormi())
            {
                echo 'Un magicien vous a endormi ! Vous allez vous réveiller dans ', $perso->reveil(), '.';
            }
        
            else
            {
                $peutFrapper = [];
                foreach ($retourPersos as $unPerso)
                {
                    echo '<a href="?frapper=', $unPerso->getId(), '">', htmlspecialchars($unPerso->getNom()), '</a> (dégâts : ', $unPerso->getDegats(), ' | classe : ', $unPerso->getClasse(), ')';
                    
                    // On ajoute un lien pour lancer un sort si le personnage est un magicien.
                    if ($perso->getClasse() == 'magicien')
                    {
                        echo ' | <a href="?sort=', $unPerso->getId(), '">Lancer un sort</a>';
                    }
                    if ($unPerso->getPeutFrapper() > 0) {
                        array_push($peutFrapper, TRUE);
                    }
                    else {
                        array_push($peutFrapper, FALSE);
                    }
                    
                    echo '<br />';
                }
                if (!in_array(TRUE, $peutFrapper) && $perso->getPeutFrapper() < 1) 
                {
                    echo 'toto';
                    $manager->resetFrappe();
                }
            }
        }
?>
            </p>
        </fieldset>
<?php
    }
    else 
    {
?>
    <form action="" method="post">
        <p>
            <label for="nom">Nom:</label>
            <input type="text" name="nom" maxlength="50" />
            <input type="submit" value="Utiliser ce personnage" name="utiliser" />
            Classe :
            <select name="classe">
                <option value="magicien">Magicien</option>
                <option value="guerrier">Guerrier</option>
            </select>
            <input type="submit" value="Créer ce personnage" name="creer" />
        </p>
    </form>
<?php
    }
?>
</body>

</html>
<?php
if (isset($perso)) 
{
    $_SESSION['perso'] = $perso;
}