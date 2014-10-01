<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class PostMetaTableSeeder extends Seeder {

    public function run()
    {
        // Uncomment the below to wipe the table clean before populating
        DB::table('wp_postmeta')->truncate();

        $faker = Faker::create();

        foreach(range(1, 10) as $index)
        {
            PostMeta::create([
                'post_id'    => $index,
                'meta_key'   => 'pal_user_id',
                'meta_value' => min($index, 5),
            ]);
        }

    }

}
