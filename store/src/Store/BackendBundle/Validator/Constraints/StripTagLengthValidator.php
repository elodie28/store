<?php

namespace Store\BackendBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class StripTagLengthValidator extends ConstraintValidator {

    public function validate($value, Constraint $constraint) {

        // Si la longueur de ma chaîne avec suppression des tags html est > à 500 caractères
        if (500 < strlen(strip_tags($value))) {
            // ajouter une violation au niveau des erreurs de mon formulaire
            $this->context->addViolation(
                $constraint->message, array('%string%' => $value));
        }
    }

}