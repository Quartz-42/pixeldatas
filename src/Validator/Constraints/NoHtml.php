<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NoHtml extends Constraint
{
    public $message = 'No HTML allowed';
}
