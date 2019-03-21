<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class FillPermissionRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $perms_roles = [
            /**
             *
             * Права пользователя
             *
             */
            /** права на заказы **/
            [
                'permission_id' => '1',
                'role_id' => '1'
            ],
            [
                'permission_id' => '2',
                'role_id' => '1'
            ],
            [
                'permission_id' => '3',
                'role_id' => '1'
            ],
            [
                'permission_id' => '4',
                'role_id' => '1'
            ],

            /** права на комнаты **/
            [
                'permission_id' => '5',
                'role_id' => '1'
            ],
            [
                'permission_id' => '6',
                'role_id' => '1'
            ],
            [
                'permission_id' => '7',
                'role_id' => '1'
            ],
            [
                'permission_id' => '8',
                'role_id' => '1'
            ],

            /**
             *
             * Права админа
             *
             */
            /** права на заказы **/
            [
                'permission_id' => '1',
                'role_id' => '2'
            ],
            [
                'permission_id' => '2',
                'role_id' => '2'
            ],
            [
                'permission_id' => '3',
                'role_id' => '2'
            ],
            [
                'permission_id' => '4',
                'role_id' => '2'
            ],

            /** права на комнаты **/
            [
                'permission_id' => '5',
                'role_id' => '2'
            ],
            [
                'permission_id' => '6',
                'role_id' => '2'
            ],
            [
                'permission_id' => '7',
                'role_id' => '2'
            ],
            [
                'permission_id' => '8',
                'role_id' => '2'
            ],

            /**
             * 
             * Права суперадмина
             * 
             */
            /** права на заказы **/
            [
                'permission_id' => '1',
                'role_id' => '3'
            ],
            [
                'permission_id' => '2',
                'role_id' => '3'
            ],
            [
                'permission_id' => '3',
                'role_id' => '3'
            ],
            [
                'permission_id' => '4',
                'role_id' => '3'
            ],

            /** права на комнаты **/
            [
                'permission_id' => '5',
                'role_id' => '3'
            ],
            [
                'permission_id' => '6',
                'role_id' => '3'
            ],
            [
                'permission_id' => '7',
                'role_id' => '3'
            ],
            [
                'permission_id' => '8',
                'role_id' => '3'
            ],

            /** права на персонал **/
            [
                'permission_id' => '9',
                'role_id' => '3'
            ],
            [
                'permission_id' => '10',
                'role_id' => '3'
            ],
            [
                'permission_id' => '11',
                'role_id' => '3'
            ],
            [
                'permission_id' => '12',
                'role_id' => '3'
            ],

            /** права на должности **/
            [
                'permission_id' => '13',
                'role_id' => '3'
            ],
            [
                'permission_id' => '14',
                'role_id' => '3'
            ],
            [
                'permission_id' => '15',
                'role_id' => '3'
            ],
            [
                'permission_id' => '16',
                'role_id' => '3'
            ],

            /** права на пользователей **/
            [
                'permission_id' => '17',
                'role_id' => '3'
            ],
            [
                'permission_id' => '18',
                'role_id' => '3'
            ],
            [
                'permission_id' => '19',
                'role_id' => '3'
            ],
            [
                'permission_id' => '20',
                'role_id' => '3'
            ],
        ];

        DB::table('permission_role')->insert($perms_roles);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('permission_role')->truncate();
    }
}
