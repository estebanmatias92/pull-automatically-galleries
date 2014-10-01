<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class GalleryTableSeeder extends Seeder {

    public function run()
    {
        // Uncomment the below to wipe the table clean before populating
        DB::table('wp_posts')->where('post_type', '=', 'gallery')->delete();

        $faker = Faker::create();

        foreach(range(1, 10) as $index)
        {
            Gallery::create([
                'ID'                    => (string) $index,
                'post_author'           => 1,
                'post_content'          => $faker->paragraph(),
                'post_title'            => ($title = $faker->sentence(4)),
                'post_excerpt'          => '',
                'post_date'             => ($date = date("Y-m-d H:i:s")),
                'post_date_gmt'         => $date,
                'post_status'           => 'publish',
                'post_name'             => Str::slug($title),
                'to_ping'               => '',
                'pinged'                => '',
                'post_modified'         => $date,
                'post_modified_gmt'     => $date,
                'post_content_filtered' => '',
                'post_type'             => 'gallery'
            ]);
        }

    }

}
