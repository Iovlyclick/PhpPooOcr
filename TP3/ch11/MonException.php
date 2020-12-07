<?php
class MonException extends ErrorException
{
    public function __toString()
    {
        switch ($this->severity) {
            case E_USER_ERROR: //si l'utilisateur émet une erreur fatale
                $type = 'Erreur fatale';
                break;

            case E_WARNING: //si PHP émet une alerte
            case E_USER_WARNING: //si l'utilisateur émet une alerte
                $type = 'Attention';
                break;

            case E_NOTICE: //si PHP émet une notice
            case E_USER_NOTICE: //si l'utilisateur émet une notice
                $type = 'Note';
                break;

            default: //erreur inconnue
                $type = 'Erreur inconnue';
                break;
        }

        return '<strong>' . $type . '</strong> : [' . $this->code . '] ' . $this->message . '<br /><strong>' . $this->file . '</strong> à la ligne <strong>' . $this->line . '</strong>';
    }
}

function error2exception($code, $message, $fichier, $ligne)
{
    //le code fait office de sévérité
    //se reporter aux constantes prédéfinies pour en savoir plus
    throw new MonException($message, 0, $code, $fichier, $ligne);
}

function customException($e)
{
    echo 'Ligne ', $e->getLine(), ' dans ', $e->getFile(), '<br /><strong>Exception lancée</strong> : ', $e->getMessage();
}

set_error_handler('error2exception');
set_exception_handler('customException');
