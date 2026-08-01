<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Système d'avis clients : chaque hôtel collecte des avis (note + commentaire)
 * depuis sa vitrine. L'hôtelier modère (approuve / rejette) et peut répondre.
 * Multi-tenant : chaque avis appartient à un hôtel (hotel_id).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('transaction_id')->nullable()->constrained()->nullOnDelete();
            $table->string('author_name', 120);
            $table->string('author_city', 120)->nullable();
            $table->unsignedTinyInteger('rating'); // 1..5
            $table->text('comment');
            $table->string('status', 20)->default('pending'); // pending | approved | rejected
            $table->text('reply')->nullable();       // réponse de l'hôtelier
            $table->timestamp('replied_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['hotel_id', 'status']);
        });

        Schema::table('hotels', function (Blueprint $table) {
            if (! Schema::hasColumn('hotels', 'show_reviews')) {
                $table->boolean('show_reviews')->default(true)->after('show_contact');
            }
            if (! Schema::hasColumn('hotels', 'reviews_moderation')) {
                // true = les avis passent en "pending" et attendent l'approbation.
                $table->boolean('reviews_moderation')->default(true)->after('show_reviews');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
        Schema::table('hotels', function (Blueprint $table) {
            foreach (['reviews_moderation', 'show_reviews'] as $col) {
                if (Schema::hasColumn('hotels', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
