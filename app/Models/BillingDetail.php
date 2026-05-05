<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillingDetail extends Model
{
    protected $fillable = [
        'company_id',
        'entity_name',
        'display_name',
        'registration_number',
        'tax_number',
        'currency',
        'currency_code',
        'country',
        'city',
        'state',
        'postal_code',
        'address_line_1',
        'address_line_2',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'phone',
        'fax',
        'bank_name',
        'bank_account_number',
        'bank_iban',
        'bank_swift',
        'notes',
    ];

    /** @return BelongsTo<Company, BillingDetail> */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
