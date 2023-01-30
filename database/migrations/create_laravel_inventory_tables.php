<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up()
    {
        $prefix = config('inventory.table_name_prefix') . "_";


        Schema::create($prefix . 'products', function (Blueprint $table) use ($prefix) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('category_id');
            $table->integer('metric_id');
            // $table->unsignedBigInteger('parent_id')->nullable();
            $table->timestamps();

            // $table->foreign('parent_id')
            //     ->references('id')
            //     ->on($prefix . 'products')
            //     ->cascadeOnDelete();
        });


        Schema::create($prefix . 'product_categories', function (Blueprint $table) use ($prefix) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->timestamps();

            $table->foreign('parent_id')
                ->references('id')
                ->on($prefix . 'product_categories')
                ->restrictOnDelete();
        });


        Schema::create($prefix . 'product_sku', function (Blueprint $table) use ($prefix) {
            $table->id();
            $table->string('code')->unique();
            $table->unsignedBigInteger('product_variant_id');
            $table->timestamps();

            $table->foreign('product_variant_id')
                ->references('id')
                ->on($prefix . 'product_variants')
                ->cascadeOnDelete();
        });

        Schema::create($prefix . 'metrics', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('symbol');
            $table->timestamps();
        });

        Schema::create($prefix . 'stocks', function (Blueprint $table) use ($prefix) {
            $table->id();
            $table->unsignedBigInteger('product_variant_id');
            $table->string('batch')->nullable();
            $table->double('qty');
            $table->double('cost')->default(0);
            $table->double('price')->default(0);
            $table->date('expire_at')->nullable();
            $table->unsignedBigInteger('supplier_id');
            $table->timestamps();

            $table->foreign('product_variant_id')
                ->references('id')
                ->on($prefix . 'product_variants')
                ->cascadeOnDelete();
        });


        Schema::create($prefix . 'stock_movements', function (Blueprint $table) {
            $table->id();
            $table->integer('stock_id');
            $table->integer('user_id');
            $table->double('before')->default(0);
            $table->double('after')->default(0);
            $table->double('qty')->default(0);
            $table->double('price')->default(0);
            $table->string('reason')->nullable();
            $table->nullableMorphs('reference');
            $table->timestamps();
        });

        Schema::create($prefix . 'address', function (Blueprint $table) {
            $table->id();
            $table->string('building')->nullable();
            $table->string('street')->nullable();
            $table->string('city');
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('country');
            $table->timestamps();
        });

        Schema::create($prefix . 'suppliers', function (Blueprint $table) use ($prefix) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('address_id');
            $table->string('contact_title')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->timestamps();

            $table->foreign('address_id')
                ->references('id')
                ->on($prefix . 'address')
                ->cascadeOnDelete();
        });


        Schema::create($prefix . 'attributes', function (Blueprint $table) use ($prefix) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('name');
            $table->string('value');

            $table->foreign('product_id')
            ->references('id')
            ->on($prefix.'products')
            ->cascadeOnDelete();
        });

        Schema::create($prefix . 'product_variants', function (Blueprint $table) use ($prefix) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_default');
            $table->integer('minimum_stock')->default(0);
            $table->integer('total_stock')->default(0);
            $table->timestamps();

            $table->foreign('product_id')
            ->references('id')
            ->on($prefix.'products')
            ->cascadeOnDelete();
        });

        Schema::create($prefix . 'variants', function (Blueprint $table) use ($prefix) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('name');

            $table->foreign('product_id')
                        ->references('id')
                        ->on($prefix.'products')
                        ->cascadeOnDelete();
        });

        Schema::create($prefix . 'variant_values', function (Blueprint $table) use ($prefix) {
            $table->unsignedBigInteger('variant_id');
            $table->string('value');

            $table->foreign('variant_id')
                    ->references('id')
                    ->on($prefix.'variants')
                    ->cascadeOnDelete();
        });
    }


    public function down()
    {
        $prefix = config('inventory.table_name_prefix') . "_";

        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists($prefix . 'products');
        Schema::dropIfExists($prefix . 'product_categories');
        Schema::dropIfExists($prefix . 'product_sku');
        Schema::dropIfExists($prefix . 'metrics');
        Schema::dropIfExists($prefix . 'stocks');
        Schema::dropIfExists($prefix . 'stock_movements');
        Schema::dropIfExists($prefix . 'suppliers');
        Schema::dropIfExists($prefix . 'address');
        Schema::dropIfExists($prefix . 'attributes');
        Schema::dropIfExists($prefix . 'product_variants');
        Schema::dropIfExists($prefix . 'variants');
        Schema::dropIfExists($prefix . 'variant_values');
    }
};
