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
        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id');
            $table->foreignId('label_id');
            $table->foreignId('priority_id')->nullable();
            $table->foreignId('status_id')->nullable()->constrained('statuses');
            $table->foreignId('parent_id')->nullable();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');;
            $table->foreignId('assigned_by')->nullable()->constrained('users');


            $table->string('title');
            $table->text('details')->nullable();
            $table->integer('story_point')->nullable();
            $table->integer('value_point')->nullable();
            $table->integer('time_spent')->nullable();
            $table->integer('completion_perc')->default(0);
            $table->boolean('is_new')->default(0);
            $table->boolean('order')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('issues');
    }
};
