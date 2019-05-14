<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangePriceColumnIntoRooms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE rooms MODIFY COLUMN price INTEGER ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('rooms', 'price'))
        {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE rooms MODIFY COLUMN price FLOAT ');
        }
    }
}
