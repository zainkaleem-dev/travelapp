<?php

namespace App\Models\Concerns;

trait ScopedToSelectedCompany
{
    use ScopedToCompany;

    protected static function bootScopedToSelectedCompany(): void
    {
        // Backward compatible alias: older models used this trait name.
        static::bootScopedToCompany();
    }
}
