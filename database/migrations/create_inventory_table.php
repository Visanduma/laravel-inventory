<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create(config('inventory.table_name_prefix') . '_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('category_id');
            $table->integer('metric_id');
            $table->integer('parent_id')->nullable();
            $table->timestamps();

            $table->foreign('parent_id')
                ->references('id')
                ->on(config('inventory.table_name_prefix') . '_products')
                ->cascadeOnDelete();
        });


        Schema::create(config('inventory.table_name_prefix') . '_product_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('parent_id')->nullable();
            $table->timestamps();

            $table->foreign('parent_id')
                ->references('id')
                ->on(config('inventory.table_name_prefix') . '_product_categories')
                ->restrictOnDelete();

        });


        Schema::create(config('inventory.table_name_prefix') . '_product_sku', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->integer('product_id');
            $table->timestamps();

            $table->foreign('product_id')
                ->references('id')
                ->on(config('inventory.table_name_prefix') . '_products')
                ->cascadeOnDelete();
        });

        Schema::create(config('inventory.table_name_prefix') . '_metrics', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('symbol');
            $table->timestamps();
        });

        Schema::create(config('inventory.table_name_prefix') . '_stock', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->double('qty');
            $table->double('cost')->default(0);
            $table->double('price')->default(0);
            $table->timestamps();

            $table->foreign('product_id')
                ->references('id')
                ->on(config('inventory.table_name_prefix') . '_products')
                ->cascadeOnDelete();
        });


        Schema::create(config('inventory.table_name_prefix') . '_stock_movements', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->double('before')->default(0);
            $table->double('after')->default(0);
            $table->double('qty')->default(0);
            $table->double('price')->default(0);
            $table->string('reason')->nullable();
            $table->nullableMorphs('reference');
            $table->timestamps();
        });

        Schema::create(config('inventory.table_name_prefix') . '_suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('address_id');
            $table->string('contact_title')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->timestamps();

            $table->foreign('address_id')
                ->references('id')
                ->on(config('inventory.table_name_prefix') . '_address')
                ->cascadeOnDelete();
        });


        Schema::create(config('inventory.table_name_prefix') . '_address', function (Blueprint $table) {
            $table->id();
            $table->string('building')->nullable();
            $table->string('street')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('country')->nullable();
            $table->timestamps();
        });
    }
};
