<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class FillAuditEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

           $events = [
               [
                   'info' => 'Добавление пользователя'
               ],
               [
                   'info' => 'Редактирование пользователя'
               ],
               [
                   'info' => 'Удаление пользователя'
               ],
               [
                   'info' => 'Добавление комнаты'
               ],
               [
                   'info' => 'Редактирование комнаты'
               ],
               [
                   'info' => 'Удаление комнаты'
               ],
               [
                   'info' => 'Добавление работника'
               ],
               [
                   'info' => 'Редактирование работника'
               ],
               [
                   'info' => 'Удаление работника'
               ],
               [
                   'info' => 'Загрузка фотографии'
               ],
               [
                   'info' => 'Удаление фотографии'
               ],
               [
                   'info' => 'Добавление должности'
               ],
               [
                   'info' => 'Редактирование должности'
               ],
               [
                   'info' => 'Удаление должности'
               ],
               [
                   'info' => 'Добавление заказа'
               ],
               [
                   'info' => 'Редактирование заказа'
               ],
               [
                   'info' => 'Удаление заказа'
               ],
               [
                   'info' => 'Регистрация нового пользователя'
               ],
               [
                   'info' => 'Вход в систему под учётной записью'
               ],
               [
                   'info' => 'Выход из системы под учётной записью'
               ],
           ];

        DB::table('audit_events')->insert($events);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DB::table('audit_events')->truncate();
    }
}
