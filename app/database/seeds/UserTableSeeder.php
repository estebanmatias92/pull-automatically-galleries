<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class UserTableSeeder extends Seeder {

    public function run()
    {
        // Uncomment the below to wipe the table clean before populating
        DB::table('pal_users')->truncate();

        $faker = Faker::create();

        foreach(range(1, 5) as $index)
        {
            User::create([
                'id'          => (string) $index,
                'host_id'     => min($index, 3),
                'realname'    => $faker->name,
                'username'    => $faker->username,
                'url'         => $faker->url,
                'credentials' => implode('; ', $faker->words(4)),
            ]);
        }
    }

}
