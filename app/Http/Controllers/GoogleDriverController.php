<?php

namespace App\Http\Controllers;

use App\Http\Traits\GoogleClientTrait;
use App\Http\Traits\GoogleDriveTrait;
use Google_Client;

class GoogleDriverController extends Controller
{
	use GoogleClientTrait;
	use GoogleDriveTrait;

	/** @var Google_Client $client */
    private $client;

	/**
	 * GoogleClientController constructor.
	 */
    public function __construct() {
	    $this->middleware('auth');

    	$this->client = $this->initClient();
    }


	/**
	 * Fetch Drive Files.
	 */
    public function fetch()
    {
    	try {
		    $this->client = $this->getClient($this->client);
		    $drive = $this->getDrive($this->client);

		    $pageToken = null;
		    $files = [];
		    do {
			    $optParams = $this->setOptParams($pageToken);
			    $results = $drive->files->listFiles($optParams);
			    $this->fetchFiles($results, $files);
			    $pageToken = $results->getNextPageToken();
		    } while($pageToken);

		    auth()->user()->updateFiles($files);

		    return redirect()->route('gd.files.index');
	    } catch (\Exception $exception) {
		    session()->flash('error' ,$exception->getMessage());
	    }
    }

	/**
	 * Show User files.
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
    public function index()
    {
	    $userFiles = auth()->user()->files()->paginate(10);

	    return view('home', compact('userFiles'));
    }
}
