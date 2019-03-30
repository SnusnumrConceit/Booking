<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuditTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit', function (Blueprint $table) {
            $table->increments('id');
            $table->json('subject')
                ->comment('субъект, над которым производились изменения');
            $table->unsignedInteger('user_id')
                ->nullable()
                ->comment('пользователь');
            $table->json('status')
                ->comment('статус');
            $table->unsignedInteger('type')
                ->nullable()
                ->comment('тип события');
            $table->timestamps();
        });

        Schema::table('audit', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->foreign('type')
                ->references('id')
                ->on('audit_events')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('audit', 'type'))
        {
            Schema::table('audit', function (Blueprint $table) {
                $table->dropForeign(['type']);
            });
        }

        if (Schema::hasColumn('audit', 'user_id'))
        {
            Schema::table('audit', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
            });
        }

        Schema::dropIfExists('audit');
    }
}
