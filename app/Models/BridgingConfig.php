<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BridgingConfig extends Model
{
    protected $table = 'bridging_configs';

    protected $fillable = [
        'provider', 'client_id', 'client_secret', 'user_key', 
        'base_url', 'auth_url', 'consent_url', 'organization_id',
        'developer_email', 'access_name', 'token', 'is_production'
    ];

    protected $casts = [
        'is_production' => 'boolean',
    ];
}
