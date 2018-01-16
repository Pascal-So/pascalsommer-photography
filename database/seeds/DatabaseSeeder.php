<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'test',
            'password' => bcrypt('test'),
        ]);

        DB::table('posts')->insert([
            [
                'title' => 'Snow',
                'date' => '2017-12-27',
            ],
            [
                'title' => 'New Year\'s Eve',
                'date' => '2018-01-01'
            ],
        ]);

        DB::table('photos')->insert([
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

        DB::table('tags')->insert([
            [ 'name' => 'Animals' ],
            [ 'name' => 'Architecture' ],
            [ 'name' => 'Birds' ],
            [ 'name' => 'Landscape' ],
            [ 'name' => 'Sky' ],
            [ 'name' => 'People' ],
            [ 'name' => 'Transport' ],
            [ 'name' => 'Infrastructure' ],
            [ 'name' => 'Travel' ],
            [ 'name' => 'Water' ],
        ]);

        App\Tag::where('name', 'Landscape')->first()->photos()->attach([1,2]);
        App\Tag::where('name', 'Architecture')->first()->photos()->attach([2]);
        App\Tag::where('name', 'Travel')->first()->photos()->attach([1,2,3]);

        DB::table('blacklist')->insert([
            [ 'regex' => '#http://www\.ttk-krasnodar\.ru#' ],
            [ 'regex' => '#http://создание-сайтов161\.рф#' ],
            [ 'regex' => '#https://fotograf23\.ru#' ],
            [ 'regex' => '#http://xn--.*\.xn#' ],
            [ 'regex' => '#https://metal-moscow\.ru#' ],
            [ 'regex' => '#https://agrohoztorg\.ru#' ],
            [ 'regex' => '#https://stekloforce\.ru#' ],
            [ 'regex' => '#\[url=.*\].*\[/url\].*\[link=.*\].*\[/link\]#' ],
        ]);
    }
}
