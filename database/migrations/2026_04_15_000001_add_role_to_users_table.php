<?php

use Illuminate\Database\Migrations\Migration;

// La colonne 'role' existe déjà (ajoutée dans 2026_01_27_171522_add_roles_to_users_enum.php).
// Cette migration est un no-op conservé pour la cohérence du journal de migrations.
return new class extends Migration
{
    public function up(): void
    {
        // Rien à faire : colonne role déjà présente
    }

    public function down(): void
    {
        // Rien à faire
    }
};
