<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Table racine du multi-tenant : chaque hôtel de la plateforme SaaS.
     * Les données opérationnelles seront rattachées via hotel_id.
     */
    public function up()
    {
        if (Schema::hasTable('hotels')) {
            return;
        }

        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('currency', 10)->default('CFA');
            $table->string('timezone')->default('Africa/Lagos');
            $table->string('logo')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone', 50)->nullable();
            $table->string('address')->nullable();

            // Gestion de l'abonnement / accès plateforme
            $table->boolean('is_active')->default(true);
            $table->timestamp('subscription_ends_at')->nullable();

            // Propriétaire (admin de l'hôtel) — FK ajoutée après hotel_id sur users
            $table->unsignedBigInteger('owner_user_id')->nullable();

            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('hotels');
    }
};
