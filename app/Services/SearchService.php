<?php
namespace App\Services;
use App\Models\User;
use App\Models\VoicePost;
use App\Models\StatusPost;
use App\Models\Podcast;

class SearchService {
    public function search(string $query, string $type = 'all', array $filters = []): array {
        $results = ['users' => [], 'posts' => [], 'podcasts' => [], 'hashtags' => []];

        if ($type === 'all' || $type === 'users') {
            $results['users'] = User::where('is_active', true)->where('is_banned', false)
                ->where(function($q) use ($query) {
                    $q->where('username','like',"%$query%")
                      ->orWhere('display_name','like',"%$query%");
                })
                ->when(isset($filters['country']), fn($q) => $q->where('country', $filters['country']))
                ->when(isset($filters['verified']), fn($q) => $q->where('is_verified', true))
                ->limit(20)->get();
        }

        if ($type === 'all' || $type === 'posts') {
            $results['posts'] = VoicePost::with('user')->where('status','published')
                ->where('visibility','public')
                ->where(function($q) use ($query) {
                    $q->where('title','like',"%$query%")
                      ->orWhere('description','like',"%$query%");
                })
                ->orderByDesc('engagement_score')
                ->limit(20)->get();
        }

        if ($type === 'all' || $type === 'podcasts') {
            $results['podcasts'] = Podcast::with('user')->where('status','active')
                ->where(function($q) use ($query) {
                    $q->where('title','like',"%$query%")
                      ->orWhere('description','like',"%$query%");
                })
                ->orderByDesc('subscribers_count')
                ->limit(10)->get();
        }

        if ($type === 'all' || $type === 'hashtags') {
            $results['hashtags'] = \App\Models\Hashtag::where('name','like',"%$query%")
                ->orderByDesc('posts_count')->limit(10)->get();
        }

        return $results;
    }
}
