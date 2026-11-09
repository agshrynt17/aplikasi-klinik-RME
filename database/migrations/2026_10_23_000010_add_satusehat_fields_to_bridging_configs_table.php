<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bridging_configs', function (Blueprint $table) {
            if (!Schema::hasColumn('bridging_configs', 'consent_url')) {
                $table->string('consent_url')->nullable()->after('auth_url');
            }
            if (!Schema::hasColumn('bridging_configs', 'developer_email')) {
                $table->string('developer_email')->nullable()->after('organization_id');
            }
            if (!Schema::hasColumn('bridging_configs', 'access_name')) {
                $table->string('access_name')->nullable()->after('developer_email');
            }
            if (!Schema::hasColumn('bridging_configs', 'token')) {
                $table->text('token')->nullable()->after('access_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bridging_configs', function (Blueprint $table) {
            $table->dropColumn(['consent_url', 'developer_email', 'access_name', 'token']);
        });
    }
};
