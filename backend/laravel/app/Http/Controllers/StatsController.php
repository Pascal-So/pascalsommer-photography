<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Photo;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function index()
    {
        $nr_photos = Photo::count();
        $nr_photos_published = Photo::published()->count();
        $nr_photos_staged = $nr_photos - $nr_photos_published;

        $nr_posts = Post::count();

        $nr_weeks_for_stat = 13; // 3 months
        $start_date = date('Y-m-d', strtotime("-{$nr_weeks_for_stat} week"));

        $nr_posts_per_week = Post::where('date', '>=', $start_date)->count() / $nr_weeks_for_stat;
        $nr_photos_per_week = Photo::whereHas('post', function($query) use ($start_date) {
            $query->where('date', '>=', $start_date);
        })->count() / $nr_weeks_for_stat;

        $years_until_1k = 0;
        if ($nr_photos_per_week > 0) {
            $years_until_1k = (1000 - $nr_photos_published) / $nr_photos_per_week / (365.25 / 7);
        }

        $nr_comments = Comment::count();


        $stats = [
            'Total Photos' => $nr_photos,
            'Published Photos' => $nr_photos_published,
            'Staged Photos' => $nr_photos_staged,
            '' => '',
            'Posts' => $nr_posts,
            '  ' => '',
            'Comments' => $nr_comments,
            ' ' => '',
            'avg. over 3 months' => '',
            'Posts per week' => round($nr_posts_per_week, 2),
            'Photos per week' => round($nr_photos_per_week, 2),
            'Years until 1k' => round($years_until_1k, 2),
        ];

        return view('stats.index', compact('stats'));
    }
}
