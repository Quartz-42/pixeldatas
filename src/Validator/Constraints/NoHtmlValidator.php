<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NoHtmlValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        /** @var NoHtml $constraint */
        if (preg_match('/<[^>]+>/', (string) $value)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
