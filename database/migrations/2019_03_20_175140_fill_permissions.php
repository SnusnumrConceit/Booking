<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class FillPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permissions = [
            /** права на заказы **/
            [
                'name' => 'Просмотр заказов',
                'slug' => 'orders:show'
            ],
            [
                'name' => 'Добавление заказов',
                'slug' => 'orders:add'
            ],
            [
                'name' => 'Редактирование заказов',
                'slug' => 'orders:edit'
            ],
            [
                'name' => 'Удаление заказов',
                'slug' => 'orders:remove'
            ],

            /** права на комнаты **/
            [
                'name' => 'Просмотр комнат',
                'slug' => 'rooms:show'
            ],
            [
                'name' => 'Добавление комнат',
                'slug' => 'rooms:add'
            ],
            [
                'name' => 'Редактирование комнат',
                'slug' => 'rooms:edit'
            ],
            [
                'name' => 'Удаление комнат',
                'slug' => 'rooms:remove'
            ],

            /** права на персонал **/
            [
                'name' => 'Просмотр персонала',
                'slug' => 'employees:show'
            ],
            [
                'name' => 'Добавление персонала',
                'slug' => 'employees:add'
            ],
            [
                'name' => 'Редактирование персонала',
                'slug' => 'employees:edit'
            ],
            [
                'name' => 'Удаление персонала',
                'slug' => 'employees:remove'
            ],

            /** права на должности **/
            [
                'name' => 'Просмотр должностей',
                'slug' => 'appointments:show'
            ],
            [
                'name' => 'Добавление должностей',
                'slug' => 'appointments:add'
            ],
            [
                'name' => 'Редактирование должностей',
                'slug' => 'appointments:edit'
            ],
            [
                'name' => 'Удаление должностей',
                'slug' => 'appointments:remove'
            ],

            /** права на пользователей **/
            [
                'name' => 'Просмотр пользователей',
                'slug' => 'users:show'
            ],
            [
                'name' => 'Добавление пользователей',
                'slug' => 'users:add'
            ],
            [
                'name' => 'Редактирование пользователей',
                'slug' => 'users:edit'
            ],
            [
                'name' => 'Удаление пользователей',
                'slug' => 'users:remove'
            ],
        ];

        DB::table('permissions')->insert($permissions);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Illuminate\Support\Facades\DB::table('permissions')->truncate();
    }
}
