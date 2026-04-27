<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\DashboardService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    use ResponseTrait;
    public $dashboardService;

    public function __construct()
    {
        $this->dashboardService = new DashboardService();
    }

    public function index(Request $request)
    {

        return view('admin.dashboard', [
            'pageTitle' => 'Dashboard',
        ]);
    }

}
