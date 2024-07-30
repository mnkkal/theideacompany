<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Investor;
use App\Models\Startup;
use App\Models\Pitch;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        switch ($user->role) {
            case 'Admin':
                return $this->superAdminDashboard();
            case 'Investor':
                return $this->investorDashboard();
            case 'Startup':
                return $this->startupDashboard();
            case 'Employee':
                    return $this->employeeDashboard();
            case 'Employer':
                        return $this->employerDashboard();
            default:
           
                return $this->welcomeDashboard();
        }
    }
    protected function superAdminDashboard()
    {
        $investorCount = Investor::count();
        $startupCount = Startup::count();
        // Add more counts if needed

        return view('dashboards.super_admin', compact('investorCount', 'startupCount'));
    }

    protected function investorDashboard()
    {
        $pitches = Pitch::where('active', true)->get();

        return view('dashboards.investor', compact('pitches'));
    }

    protected function startupDashboard()
    {
        $pitches = Auth::user()->pitches()->where('active', true)->get();

        return view('dashboards.startup', compact('pitches'));
    }
    protected function employeeDashboard()
    {
        // Add logic to fetch data relevant to employees
        $tasks = Task::where('assigned_to', Auth::id())->get(); // Example: fetching tasks assigned to the employee

        return view('dashboards.employee', compact('tasks'));
    }
    protected function employerDashboard()
    {
        // Add logic to fetch data relevant to employees
        $tasks = Task::where('assigned_to', Auth::id())->get(); // Example: fetching tasks assigned to the employee

        return view('dashboards.employee', compact('tasks'));
    }
    protected function welcomeDashboard()
    {
        // Add logic to fetch data relevant to employees
        $tasks = Task::where('assigned_to', Auth::id())->get(); // Example: fetching tasks assigned to the employee

        return view('dashboards.employee', compact('tasks'));
    }
}
