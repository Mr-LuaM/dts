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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('school_id')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role')->default('dwcc.dts.user');
            $table->string('lname');
            $table->string('fname');
            $table->string('mname')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('sex', 50)->nullable(); // Consider using 'male', 'female', 'other'
            $table->string('contact_number')->nullable();
            $table->text('address')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes(); // Optional, for soft deletes
        });

        Schema::create('user_otps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('otp');
            $table->timestamp('expires_at');
            $table->timestamps();
        });
        
        
        Schema::create('password_resets', function (Blueprint $table) {
            $table->id(); // Add this line for a unique identifier
            $table->string('email')->index(); // Change primary to index
            $table->string('token');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        
        
        
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('ip_address', 45)->nullable();
            $table->string('action');
            $table->text('details')->nullable(); // Can store JSON with additional details
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('user_otps');
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('users');
    }
    
};
