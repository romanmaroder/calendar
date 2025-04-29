<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Вы можете выполнить команду db:seed Artisan для наполнения вашей базы данных.
     * По умолчанию команда db:seed запускает класс Database\Seeders\DatabaseSeeder, который,
     * в свою очередь, может вызывать другие классы. Однако вы можете использовать параметр --class,
     * чтобы указать конкретный класс наполнителя для его индивидуального запуска:
     *
     * php artisan db:seed
     * php artisan db:seed --class=UserSeeder
     *
     * Вы также можете заполнить свою базу данных, используя команду migrate:fresh в сочетании с опцией --seed,
     * которая удалит все таблицы и перезапустит все миграции.
     * Эта команда полезна для полной перестройки вашей базы данных.
     * Опцию --seeder можно использовать для указания конкретного сида (seeder) для выполнения:
     *
     * php artisan migrate:fresh --seed
     *
     * php artisan migrate:fresh --seed --seeder=UserSeeder
     */
    public function run(): void
    {
        User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
