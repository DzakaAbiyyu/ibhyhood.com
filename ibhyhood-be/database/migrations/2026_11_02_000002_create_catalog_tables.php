<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('merch_items', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('type');
            $t->string('theme')->nullable();
            $t->unsignedInteger('price'); // angka murni, mis. 250000
            $t->string('badge', 30)->nullable();
            $t->string('image')->nullable();
            $t->text('description')->nullable();
            $t->timestamps();
        });

        Schema::create('merch_filters', function (Blueprint $t) {
            $t->id();
            $t->string('group', 20); // type | theme
            $t->string('name');
            $t->unique(['group', 'name']);
            $t->timestamps();
        });

        Schema::create('promos', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('subtitle')->nullable();
            $t->timestamp('ends_at')->nullable();
            $t->string('image')->nullable();
            $t->timestamps();
        });

        Schema::create('vume_products', function (Blueprint $t) {
            $t->id();
            $t->string('slug')->unique();
            $t->string('title');
            $t->string('subtitle')->nullable();
            $t->string('top')->nullable();
            $t->string('middle')->nullable();
            $t->string('base')->nullable();
            $t->string('size', 30)->nullable();
            $t->string('image')->nullable();
            $t->timestamps();
        });

        Schema::create('gigs', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->unsignedInteger('starting_price');
            $t->string('cover_image')->nullable();
            $t->text('description')->nullable();
            $t->json('gallery')->nullable();
            $t->timestamps();
        });

        Schema::create('gig_packages', function (Blueprint $t) {
            $t->id();
            $t->foreignId('gig_id')->constrained()->cascadeOnDelete();
            $t->string('tier', 20); // basic | standart | premium
            $t->unsignedInteger('price');
            $t->string('description');
            $t->unique(['gig_id', 'tier']);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gig_packages');
        Schema::dropIfExists('gigs');
        Schema::dropIfExists('vume_products');
        Schema::dropIfExists('promos');
        Schema::dropIfExists('merch_filters');
        Schema::dropIfExists('merch_items');
    }
};
