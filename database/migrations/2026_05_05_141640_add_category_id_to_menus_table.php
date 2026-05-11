<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null')->after('id');
        });

        // Transférer les données existantes
        $categories = DB::table('categories')->get();
        foreach ($categories as $category) {
            DB::table('menus')
                ->where('category', $category->slug)
                ->update(['category_id' => $category->id]);
        }

        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->string('category')->nullable()->after('name');
        });

        // Re-transférer les données
        $categories = DB::table('categories')->get();
        foreach ($categories as $category) {
            DB::table('menus')
                ->where('category_id', $category->id)
                ->update(['category' => $category->slug]);
        }

        Schema::table('menus', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
};
