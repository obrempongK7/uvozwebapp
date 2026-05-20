<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Services\SearchService;
use Illuminate\Http\Request;

class SearchController extends Controller {
    public function __construct(private SearchService $search) {}
    public function index(Request $request) {
        $query = $request->get('q','');
        $type  = $request->get('type','all');
        $results = $query ? $this->search->search($query,$type,['country'=>$request->country,'verified'=>$request->verified]) : [];
        return view('user.search', compact('results','query','type'));
    }
}
