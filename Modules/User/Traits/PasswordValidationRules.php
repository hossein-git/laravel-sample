<?php

namespace Modules\User\Traits;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     */
    protected function passwordRules(): array
    {
        return ['required', 'string', 'min:8', 'max:20', 'confirmed'];
    }
}
