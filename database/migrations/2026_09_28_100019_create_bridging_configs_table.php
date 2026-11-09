<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bridging_configs', function (Blueprint $table) {
            $table->id();
            $table->string('provider', 50)->unique(); // bpjs_antrol, bpjs_vclaim, satusehat
            $table->string('client_id')->nullable(); // Cons ID / Client ID
            $table->string('client_secret')->nullable(); // Secret Key / Client Secret
            $table->string('user_key')->nullable(); // User Key (BPJS)
            $table->string('base_url')->nullable(); 
            $table->string('auth_url')->nullable(); // Khusus SatuSehat
            $table->string('organization_id')->nullable(); // Khusus SatuSehat
            $table->boolean('is_production')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bridging_configs');
    }
};
