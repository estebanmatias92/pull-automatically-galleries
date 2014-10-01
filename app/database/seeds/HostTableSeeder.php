<?php

class HostTableSeeder extends Seeder {

    public function run()
    {
        // Uncomment the below to wipe the table clean before populating
        DB::table('pal_hosts')->truncate();

        $hostnames = ['Flickr', 'Imgurl', 'Instagram'];

        foreach($hostnames as $name)
        {
            Host::create([
                'name' => $name,
            ]);
        }
    }

}
