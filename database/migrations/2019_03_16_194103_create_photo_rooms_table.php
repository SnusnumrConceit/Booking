<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhotoRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photo_rooms', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('photo_id')->comment('фотография');
            $table->unsignedInteger('room_id')->comment('комната');
        });

        Schema::table('photo_rooms', function (Blueprint $table) {
            $table->foreign('room_id')
                ->references('id')
                ->on('rooms')
                ->onDelete('cascade');
        });

        Schema::table('photo_rooms', function (Blueprint $table) {
           $table->foreign('photo_id')
               ->references('id')
               ->on('photos')
               ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('photo_rooms', 'photo_id') || Schema::hasColumn('photo_rooms', 'room_id'))
        {
            Schema::table('photo_rooms', function (Blueprint $table) {
                $table->dropForeign(['photo_id']);
                $table->dropForeign(['room_id']);
            });
        }

        Schema::dropIfExists('photo_rooms');
    }
}
