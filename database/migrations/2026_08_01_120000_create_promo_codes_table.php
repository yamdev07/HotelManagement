<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Codes promo / réductions appliqués aux réservations en ligne.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->cascadeOnDelete();
            $table->string('code', 40);
            $table->enum('type', ['percent', 'fixed'])->default('percent');
            $table->decimal('value', 12, 2);           // % (0-100) ou montant fixe
            $table->unsignedSmallInteger('min_nights')->default(1);
            $table->date('starts_at')->nullable();
            $table->date('ends_at')->nullable();
            $table->unsignedInteger('max_uses')->nullable(); // null = illimité
            $table->unsignedInteger('used_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['hotel_id', 'code']);       // code unique par hôtel
        });

        Schema::table('transactions', function (Blueprint $table) {
            if (! Schema::hasColumn('transactions', 'promo_code')) {
                $table->string('promo_code', 40)->nullable()->after('total_price');
            }
            if (! Schema::hasColumn('transactions', 'discount_amount')) {
                $table->decimal('discount_amount', 12, 2)->default(0)->after('promo_code');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            foreach (['promo_code', 'discount_amount'] as $col) {
                if (Schema::hasColumn('transactions', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
        Schema::dropIfExists('promo_codes');
    }
};
