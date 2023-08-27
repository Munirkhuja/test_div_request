<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_models', function (Blueprint $table) {
            $table->id();
            $table->string('name',250);
            $table->string('email',250);
            $table->enum('status',['Active','Resolved']);
            $table->text('message');
            $table->text('comment');
            $table->foreignId('comment_user_id')->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('request_models');
    }
};
