<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Investor;
use App\Models\Startup;
use App\Models\Pitch;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Role;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // $user = Auth::user();

        // switch ($user->role) {
        //     case 'Admin':
        //         return $this->superAdminDashboard();
        //     case 'Investor':
        //         return $this->investorDashboard();
        //     case 'Startup':
        //         return $this->startupDashboard();
        //     case 'Employee':
        //             return $this->employeeDashboard();
        //     case 'Employer':
        //                 return $this->employerDashboard();
         //   default:
           
        //    return $this->welcomeDashboard();
        //}
        return $this->superAdminDashboard();
    }
    protected function superAdminDashboard()
    {
        $userCount = User::count();

        // Get user count by roles
        $roles = Role::withCount('users')->get();

        // Get transaction count and income for the current month
        $currentMonth = Carbon::now()->month;
        $transactionCount = Transaction::whereMonth('created_at', $currentMonth)->count();
        $income = Transaction::whereMonth('created_at', $currentMonth)->sum('amount');

        return view('dashboards.super_admin', compact('userCount', 'roles', 'transactionCount', 'income'));
       
    }

    protected function investorDashboard()
    {
        $pitches = Investor::All();

        return view('dashboards.investor', compact('pitches'));
    }

    protected function startupDashboard()
    {
        $pitches = Investor::where('created_by', auth()->user()->id)->get();
       

        return view('dashboards.startup', compact('pitches'));
    }
    protected function employeeDashboard()
    {
       
        $jobs = Job::all();

        return view('dashboards.employee', compact('jobs'));
    }
    protected function employerDashboard()
    {
      
        $applications = JobApplication::with('job')->get();
       
        return view('dashboards.employer', compact('applications'));
    }
    protected function welcomeDashboard()
    {
        $products = Subscription::all();

        return view('dashboards.welcome', compact('products'));
    }
}
