<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NoHtmlValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (preg_match('/<[^>]+>/', $value)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
