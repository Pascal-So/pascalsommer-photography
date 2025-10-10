<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    private function loadOldBlogData()
    {
        // requires data format of my old blog. see commits from like 1yr ago for more info

        // expects the folder data_old to exist in project root (not laravel root)

        $data_old_path = 'data_old/';
        $public_photo_path = 'img/photos/';

        $files = File::allFiles(public_path() . '/' . $public_photo_path);
        foreach ($files as $file)
        {
            $file_path = (string) $file;
            if(basename($file_path) != '.gitignore'){
                Storage::delete($public_photo_path . basename($file_path));
            }
        }

        $posts = \DB::connection('sqlite')->table('posts')->select(['id', 'title', \DB::raw('created as date')])->get();
        $photos = \DB::connection('sqlite')->table('photos')->select(['id','post_id','description', 'path'])->get();
        $staging = \DB::connection('sqlite')->table('staging')->select(['description', 'path'])->orderBy('ordering', 'desc')->get();
        $comments = \DB::connection('sqlite')->table('comments')->select(['id', 'photo_id', 'name', 'comment', \DB::raw('created as created_at')])->get();

        $posts = $posts->map(function($x){ return (array) $x; });

        \DB::table('posts')->insert($posts->toArray());

        $photos = $photos->map(function($photo) use ($data_old_path, $public_photo_path){
            $old_name = $data_old_path . $photo->path;
            $new_name = $public_photo_path . Str::random(10) . '-' . basename($photo->path);

            Storage::copy($old_name, $new_name);

            $photo->weight = 0; // we'll fix this later
            $photo->path = $new_name;
            return (array) $photo;
        });

        $staging = $staging->map(function($photo, $index) use ($data_old_path, $public_photo_path){
            $old_name = $data_old_path . $photo->path;
            $new_name = $public_photo_path . Str::random(10) . '-' . basename($photo->path);

            Storage::copy($old_name, $new_name);

            $photo->weight = $index;
            $photo->path = $new_name;
            $photo = (array) $photo;
            //unset($photo['ordering']);
            return $photo;
        });

        \DB::table('photos')->insert($photos->toArray());
        \DB::table('photos')->insert($staging->toArray());

        \App\Photo::published()->orderBy('id', 'desc')->get()->map(function($photo){
            $photo->setHighestOrderNumber();
            $photo->save();
        });

        $comments = $comments->map(function($comment){ return (array) $comment; });

        \DB::table('comments')->insert($comments->toArray());
    }

    private function testData()
    {
        \DB::table('users')->insert([
            'name' => 'test',
            'password' => bcrypt('test'),
        ]);

        \DB::table('posts')->insert([
            [
                'title' => 'Snow',
                'date' => '2017-12-27',
            ],
            [
                'title' => 'New Year\'s Eve',
                'date' => '2018-01-01'
            ],
        ]);

        \DB::table('photos')->insert([
            [
                'path' => 'img/photos/pascalsommer_9.jpg',
                'description' => 'photo 1, post 1. Trees. äöü. я не говорю по-русский.',
                'post_id' => 1,
                'weight' => 1,
            ],
            [
                'path' => 'img/photos/pascalsommer_8.jpg',
                'description' => 'photo 2, post 1, LKAB',
                'post_id' => 1,
                'weight' => 2,
            ],
            [
                'path' => 'img/photos/pascalsommer_7.jpg',
                'description' => 'photo 1, post 2',
                'post_id' => 2,
                'weight' => 1,
            ],
        ]);

        \App\Tag::where('name', 'Landscape')->first()->photos()->attach([1,2]);
        \App\Tag::where('name', 'Architecture')->first()->photos()->attach([2]);
        \App\Tag::where('name', 'Travel')->first()->photos()->attach([1,2,3]);
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->testData();

        //$this->loadOldBlogData();

        \DB::table('tags')->insert([
            [ 'name' => 'Animals' ],
            [ 'name' => 'Birds' ],
            [ 'name' => 'People' ],
            [ 'name' => 'Landscape' ],
            [ 'name' => 'Trees' ],
            [ 'name' => 'Sky' ],
            [ 'name' => 'Water' ],
            [ 'name' => 'Travel' ],
            [ 'name' => 'Transport' ],
            [ 'name' => 'Architecture' ],
            [ 'name' => 'Infrastructure' ],
        ]);

        \DB::table('blacklist')->insert([
            [ 'regex' => '#http://www\.ttk-krasnodar\.ru#' ],
            [ 'regex' => '#http://создание-сайтов\d*\.рф#' ],
            [ 'regex' => '#https://fotograf\d*\.ru#' ],
            [ 'regex' => '#http://xn--.*\.xn#' ],
            [ 'regex' => '#https://metal-moscow\.ru#' ],
            [ 'regex' => '#https://agrohoztorg\.ru#' ],
            [ 'regex' => '#https://stekloforce\.ru#' ],
            [ 'regex' => '#\[url=.*\].*\[/url\].*\[link=.*\].*\[/link\]#' ],
        ]);
    }
}
