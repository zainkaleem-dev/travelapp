<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyIntegration extends Model
{
    protected $fillable = [
        'company_id',
        'amadeus_url',
        'amadeus_client_id',
        'amadeus_client_secret',
        'amadeus_grant_type',
        'mail_mailer',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_address',
        'mail_from_name',
        'filesystem_disk',
        'aws_access_key_id',
        'aws_secret_access_key',
        'aws_default_region',
        'aws_bucket',
        'aws_use_path_style_endpoint',
    ];

    /** @return BelongsTo<Company, CompanyIntegration> */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
