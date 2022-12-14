<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->text('answer');
            $table->bigInteger('views')->unsigned()->default(0)->index();
            $table->foreignUuid('question_uuid')->references('uuid')->on('questions')->nullOnDelete();

            $table->foreignIdFor(User::class)->references('id')->on('users')->nullOnDelete();
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
        Schema::dropIfExists('answers');
    }
};
