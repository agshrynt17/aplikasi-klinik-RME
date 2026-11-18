<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BridgingConfig;

class SatuSehatKonhakesSeeder extends Seeder
{
    public function run(): void
    {
        $cfg = BridgingConfig::firstOrNew(['provider' => 'satusehat']);
        $cfg->auth_url = 'https://api-satusehat.kemkes.go.id/oauth2/v1';
        $cfg->base_url = 'https://api-satusehat.kemkes.go.id/fhir-r4/v1';
        $cfg->client_id = 'feCScRaGMjGzSSv9iGpcWG1WJqaIXxuor5enEbb68bzrFq8N';
        $cfg->client_secret = 'QsXHMc1AEKcqv6zerECNgqROnPiqcZ8W6jB6rOcovK2dGXClRaNRIc2owigVTfcz';
        $cfg->consent_url = 'https://api-satusehat.dto.kemkes.go.id/consent/v1';
        $cfg->organization_id = '100064741';
        $cfg->developer_email = 'uphi.ulfi@gmail.com';
        $cfg->access_name = 'ihs-prod-1';
        $cfg->token = 'dWQ7tuTorRJHR4TtkdgiImNtkcySI';
        $cfg->is_production = true;
        $cfg->save();
    }
}
