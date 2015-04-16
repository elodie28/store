<?php

namespace Store\BackendBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;


/**
 * Classe qui représente ma contrainte
 * Class StripTagLength
 *
* @Annotation
*/
class StripTagLength extends Constraint {

    // Message qui apparaît dans ma contrainte de validation
    public $message = 'Votre texte est trop long';

}