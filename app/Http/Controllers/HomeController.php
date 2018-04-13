<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
	    $userFiles = Cache::rememberForever('userFiles_'.auth()->user()->id, function() {
		    return auth()->user()->files;
	    });

	    //@TODO Move below code to helper (collectionPaginate) method.
	    $page = $request->get('page', 1); // Get the ?page=1 from the url
	    $perPage = 15; // Number of items per page
	    $offset = ($page * $perPage) - $perPage;

	    $userFiles = new LengthAwarePaginator(
		    array_slice($userFiles->toArray(), $offset, $perPage, true), // Only grab the items we need
		    count($userFiles), // Total items
		    $perPage, // Items per page
		    $page, // Current page
		    ['path' => $request->url(), 'query' => $request->query()] // We need this so we can keep all old query parameters from the url
	    );

        return view('home', compact('userFiles'));
    }
}
