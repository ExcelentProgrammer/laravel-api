<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sms_confirms', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("code");
            $table->integer("try_count")->default(0);
            $table->integer("resend_count")->default(0);
            $table->string("phone");
            $table->string("expired_at")->nullable();
            $table->string("unblocked_at")->nullable();
            $table->string("resend_unblock_at")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_confirms');
    }
};
