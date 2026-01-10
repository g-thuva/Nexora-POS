<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations - Complete unified database schema
     */
    public function up(): void
    {
        // USERS (create without shop foreign constraint to avoid circular FK issues)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('photo')->nullable();
            $table->enum('role', ['admin','shop_owner','manager','employee'])->default('employee');
            $table->unsignedBigInteger('shop_id')->nullable();
            $table->timestamps();
        });

        // SHOPS
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address');
            $table->string('phone');
            $table->string('email');
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->boolean('is_active')->default(true);
            $table->enum('subscription_status', ['active','suspended','expired','trial'])->default('trial');
            $table->date('subscription_start_date')->nullable();
            $table->date('subscription_end_date')->nullable();
            $table->date('last_payment_date')->nullable();
            $table->decimal('monthly_fee', 10, 2)->default(0.00);
            $table->integer('grace_period_days')->default(7);
            $table->text('suspension_reason')->nullable();
            $table->timestamp('suspended_at')->nullable();
            $table->foreignId('suspended_by')->nullable()->constrained('users');
            $table->json('payment_history')->nullable();
            $table->json('job_letterhead_config')->nullable();
            $table->timestamps();
        });

        // Add foreign key from users.shop_id to shops.id
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('shop_id')->references('id')->on('shops')->cascadeOnDelete();
        });

        // WARRANTIES
        Schema::create('warranties', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('duration');
            $table->string('years');
            $table->timestamps();
        });

        // UNITS
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->string('short_code')->nullable();
            $table->foreignId('shop_id')->nullable()->constrained('shops')->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->unique(['name','shop_id']);
            $table->unique(['slug','shop_id']);
        });

        // CATEGORIES
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->foreignId('shop_id')->nullable()->constrained('shops')->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->unique(['name','shop_id']);
            $table->unique(['slug','shop_id']);
        });

        // CUSTOMERS
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('photo')->nullable();
            $table->string('account_holder')->nullable();
            $table->string('account_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->foreignId('shop_id')->nullable()->constrained('shops')->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->unique(['email','shop_id']);
        });

        // PRODUCTS
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->string('code')->nullable();
            $table->bigInteger('quantity')->default(1);
            $table->decimal('buying_price', 13, 2)->default(0);
            $table->decimal('selling_price', 13, 2)->default(0);
            $table->bigInteger('quantity_alert')->default(1);
            $table->text('notes')->nullable();
            $table->string('product_image')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('unit_id')->constrained('units')->cascadeOnDelete();
            $table->foreignId('shop_id')->nullable()->constrained('shops')->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('warranty_id')->nullable()->constrained('warranties')->nullOnDelete();
            $table->timestamps();
            $table->unique(['code','shop_id']);
        });

        // ORDERS
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers');
            $table->string('order_date');
            $table->tinyInteger('order_status')->comment('0 - Pending / 1 - Complete');
            $table->bigInteger('total_products');
            $table->decimal('sub_total', 13, 2)->default(0);
            $table->decimal('discount_amount', 13, 2)->default(0);
            $table->decimal('service_charges', 13, 2)->default(0);
            $table->decimal('total', 13, 2)->default(0);
            $table->string('invoice_no');
            $table->string('payment_type');
            $table->decimal('pay', 13, 2)->default(0);
            $table->decimal('due', 13, 2)->default(0);
            $table->foreignId('shop_id')->nullable()->constrained('shops')->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // ORDER DETAILS
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('serial_number')->nullable();
            $table->tinyInteger('warranty_years')->nullable();
            $table->unsignedBigInteger('warranty_id')->nullable();
            $table->string('warranty_name')->nullable();
            $table->string('warranty_duration')->nullable();
            $table->bigInteger('quantity')->default(1);
            $table->decimal('unitcost', 13, 2)->default(0);
            $table->decimal('total', 13, 2)->default(0);
            $table->timestamps();
        });

        // CREDIT SALES
        Schema::create('credit_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->decimal('total_amount', 13, 2)->default(0);
            $table->decimal('paid_amount', 13, 2)->default(0);
            $table->decimal('due_amount', 13, 2)->default(0);
            $table->date('due_date');
            $table->date('sale_date');
            $table->enum('status', ['pending','partial','paid'])->default('pending');
            $table->integer('credit_days')->default(30);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // CREDIT PAYMENTS
        Schema::create('credit_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('credit_sale_id')->constrained('credit_sales')->cascadeOnDelete();
            $table->decimal('payment_amount', 13, 2)->default(0);
            $table->date('payment_date');
            $table->string('payment_method');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // RETURN SALES
        Schema::create('return_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->date('return_date')->nullable();
            $table->decimal('sub_total', 13, 2)->default(0);
            $table->decimal('total', 13, 2)->default(0);
            $table->text('notes')->nullable();
            $table->foreignId('shop_id')->nullable()->constrained('shops')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // RETURN SALE ITEMS
        Schema::create('return_sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('return_sale_id')->constrained('return_sales')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->integer('quantity')->default(1);
            $table->decimal('unitcost', 13, 2)->default(0);
            $table->decimal('total', 13, 2)->default(0);
            $table->string('serial_number')->nullable();
            $table->timestamps();
        });

        // EXPENSES
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('amount', 13, 2)->default(0);
            $table->date('expense_date')->nullable();
            $table->foreignId('shop_id')->nullable()->constrained('shops')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // JOB TYPES
        Schema::create('job_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->integer('default_days')->nullable();
            $table->timestamps();
        });

        // JOBS
        Schema::create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('reference_number')->unique();
            $table->string('type')->nullable();
            $table->unsignedBigInteger('job_type_id')->nullable()->index();
            $table->text('description')->nullable();
            $table->integer('estimated_duration')->nullable()->comment('Estimated duration in minutes');
            $table->string('status')->default('pending');
            $table->unsignedBigInteger('shop_id')->nullable()->index();
            $table->unsignedBigInteger('customer_id')->nullable()->index();
            $table->timestamps();

            $table->foreign('job_type_id')->references('id')->on('job_types')->onDelete('set null');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
        });

        // SUBSCRIPTION PLANS
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->decimal('price', 10, 2)->default(0.00);
            $table->integer('duration_months')->default(12);
            $table->json('features')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('max_products')->nullable();
            $table->integer('max_users')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // SHOP SUBSCRIPTIONS
        Schema::create('shop_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained('shops')->cascadeOnDelete();
            $table->foreignId('subscription_plan_id')->constrained('subscription_plans')->cascadeOnDelete();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('amount_paid', 10, 2)->default(0.00);
            $table->enum('payment_status', ['pending','completed','failed','refunded'])->default('pending');
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('cancelled_at')->nullable();
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();
        });

        // FAILED JOBS
        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        // NOTIFICATIONS
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->string('notifiable_type');
            $table->unsignedBigInteger('notifiable_id');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['notifiable_type', 'notifiable_id']);
        });

        // PERSONAL ACCESS TOKENS
        if (!Schema::hasTable('personal_access_tokens')) {
            Schema::create('personal_access_tokens', function (Blueprint $table) {
                $table->id();
                $table->string('tokenable_type');
                $table->unsignedBigInteger('tokenable_id');
                $table->string('name');
                $table->string('token', 64)->unique();
                $table->text('abilities')->nullable();
                $table->timestamp('last_used_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamps();

                $table->index(['tokenable_type', 'tokenable_id']);
            });
        }

        // SHOPPING CART
        Schema::create('shoppingcart', function (Blueprint $table) {
            $table->string('identifier');
            $table->string('instance');
            $table->longText('content');
            $table->timestamps();
            $table->primary(['identifier','instance']);
        });

        // PAYMENTS
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('payment_method');
            $table->enum('status', ['pending','completed','failed','refunded'])->default('pending');
            $table->string('transaction_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->foreignId('shop_id')->nullable()->constrained('shops')->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop in reverse order of creation
        Schema::dropIfExists('payments');
        Schema::dropIfExists('shoppingcart');
        Schema::dropIfExists('personal_access_tokens');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('shop_subscriptions');
        Schema::dropIfExists('subscription_plans');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('job_types');
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('return_sale_items');
        Schema::dropIfExists('return_sales');
        Schema::dropIfExists('credit_payments');
        Schema::dropIfExists('credit_sales');
        Schema::dropIfExists('order_details');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('products');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('units');
        Schema::dropIfExists('warranties');

        // Drop users.shop_id foreign key before dropping shops table
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['shop_id']);
        });

        Schema::dropIfExists('shops');
        Schema::dropIfExists('users');
    }
};
