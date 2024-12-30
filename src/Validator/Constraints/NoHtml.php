<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NoHtml extends Constraint
{
    public string $message = 'No HTML allowed';
}
