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
    public function index(Request $request)
    {
        $user = Auth::user();
        //dd($user);

        if($user->hasRole('Admin')){
            return $this->superAdminDashboard($request);
        }else if ($user->hasRole('Investor')){
            return $this->investorDashboard($request);
        }else if ($user->hasRole('Startup')){
            return $this->startupDashboard($request);
        }else if ($user->hasRole('Employee')){
            return $this->employeeDashboard($request);
        }else if ($user->hasRole('Employer')){
            return $this->employerDashboard($request);
        }else{
            return $this->welcomeDashboard($request);
        }
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

    protected function investorDashboard($request)
    {

        $searchTerm = $request->input('search');
        $selectedSector = $request->input('sector');
        $query = Investor::where('sector','!=', null)
        ->when($selectedSector, function ($query, $selectedSector) {
            return $query->where('sector', $selectedSector);
        });
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name_of_venture', 'like', '%' . $search . '%')
                  ->orWhere('problem_opportunity', 'like', '%' . $search . '%')
                  ->orWhere('sector', 'like', '%' . $search . '%');
            });
        }

        $pitches = $query->get();
        $sectors = [
            'Financial services',
            'Retail',
            'Consumer goods/fmcg',
            'Healthcare',
            'Software services',
            'Transportation and logistics',
            'Education',
            'Media/entertainment',
            'Consumer niche services',
            'Hospitality',
            'AI/VR',
            'Agriculture and tech',
            'Aeronautics and aerospace tech',
            'Defense'
        ];

        return view('dashboards.investor', compact('pitches', 'sectors', 'searchTerm', 'selectedSector'));
    }

    protected function startupDashboard($request)
    {
        $query = Investor::where('created_by', auth()->user()->id);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name_of_venture', 'like', '%' . $search . '%')
                  ->orWhere('problem_opportunity', 'like', '%' . $search . '%')
                  ->orWhere('sector', 'like', '%' . $search . '%');
            });
        }

        $pitches = $query->get();



        return view('dashboards.startup', compact('pitches'));
    }
    public function employeeDashboard($request)
   {
    $searchTerm = $request->input('search');


    $jobs = Job::query()
        ->where('apply_by', '>=', now())
        ->when($searchTerm, function ($query, $searchTerm) {
            return $query->where('job_post', 'LIKE', "%{$searchTerm}%")
                         ->orWhere('company_name', 'LIKE', "%{$searchTerm}%")
                         ->orWhere('about_job', 'LIKE', "%{$searchTerm}%")
                         ->orWhere('job_type', 'LIKE', "%{$searchTerm}%");
        })
        ->paginate(10);

    return view('dashboards.employee', compact('jobs'));
    }

    protected function employerDashboard()
    {

        $applications = JobApplication::with('job')->get();

        return view('dashboards.employer', compact('applications'));
    }
    public function welcomeDashboard(Request $request)
    {
        $products = Subscription::all();

        return view('dashboards.welcome', compact('products'));
    }
    public function productsDashboard(Request $request)
    {
        $products = Subscription::all();

            return response()->json([
                'success' => true,
                'message' => 'Products retrieved successfully',
                'data' => $products
            ]);

    }
    public function job(Request $request)
    {
     $searchTerm = $request->input('search');


     $jobs = Job::query()
         ->where('apply_by', '>=', now())
         ->when($searchTerm, function ($query, $searchTerm) {
             return $query->where('job_post', 'LIKE', "%{$searchTerm}%")
                          ->orWhere('company_name', 'LIKE', "%{$searchTerm}%")
                          ->orWhere('about_job', 'LIKE', "%{$searchTerm}%")
                          ->orWhere('job_type', 'LIKE', "%{$searchTerm}%");
         })
         ->paginate(10);

     return view('dashboards.employee', compact('jobs'));
     }
}
