<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Services\Frontend\HomeService;
use App\Models\Batch;
use App\Models\CommitteeCategory;
use App\Models\Department;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    use ResponseTrait;
    public $homeService;

    public function __construct()
    {
        $this->homeService = new HomeService();
    }

    public function index(Request $request)
    {
        return redirect('/login');
    }

    public function page($slug)
    {
        $data['pageTitle'] = __(getOption($slug.'_title'));
        $data['description'] = getOption($slug.'_description');
        return view('frontend.page', $data);
    }

}
