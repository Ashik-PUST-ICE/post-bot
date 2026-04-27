<?php


namespace App\Http\Services;

use App\Models\News;
use App\Models\Package;
use App\Models\Post;
use App\Models\User;
use App\Models\Event;
use App\Models\Alumni;
use App\Models\EventTicket;
use App\Models\Notice;
use App\Models\JobPost;
use App\Models\Transaction;
use App\Models\UserPackage;
use App\Traits\ResponseTrait;
use App\Models\UserMembershipPlan;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    use ResponseTrait;

}
