<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class JobController extends Controller
{
    public function index()
    {
        if(Auth::user()->hasPermissionTo('manage-job')){
            $user = Auth::user();
            if ($user->hasRole('Admin')) {
                $jobs = Job::all();
            } else {
                $jobs = Job::where('created_by', $user->id)->get();
            }

            return view('jobs.index', compact('jobs'));
        }else{
            return Redirect::route('home')->with('error', 'Sorry! You do not have permission to access this page!');
        }
    }

    public function create()
    {
        if(Auth::user()->hasPermissionTo('manage-job')){
            return view('jobs.create');
        }else{
            return Redirect::route('home')->with('error', 'Sorry! You do not have permission to access this page!');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'job_post' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'company_name' => 'required|string|max:255',
            'job_type' => 'required|array',
            'job_type.*' => 'in:internship,work_from_home,part_time,full_time',
            'doj' => 'required|string|max:255',
            'apply_by' => 'required|date',
            'salary' => 'required|string|max:255',
            'hiring_from' => 'required|string|max:255',
            'about_company' => 'required|string',
            'about_job' => 'required|string',
            'who_can_apply' => 'required|string|max:255',
            'skill_required' => 'required|string|max:255',
            'add_perks_of_job' => 'required|string|max:255',
        ]);
    
        $job = new Job();
        $job->job_post = $request->job_post;
        $job->email = $request->email;
        $job->company_name = $request->company_name;
        $job->job_type = json_encode($request->job_type);
        $job->doj = $request->doj;
        $job->apply_by = $request->apply_by;
        $job->salary = $request->salary;
        $job->hiring_from = $request->hiring_from;
        $job->about_company = $request->about_company;
        $job->about_job = $request->about_job;
        $job->who_can_apply = $request->who_can_apply;
        $job->skill_required = $request->skill_required;
        $job->add_perks_of_job = $request->add_perks_of_job;
        $job->created_at = auth()->user()->id;
        $job->save();
    
        return redirect()->route('jobs.index')->with('success', 'Job created successfully.');
    }
    

    public function show(Job $job)
{
    $jobApplications = JobApplication::where('job_id', $job->id)->paginate(10);

    return view('jobs.show', compact('job', 'jobApplications'));
}

    public function edit(Job $job)
    {
        if(Auth::user()->hasPermissionTo('manage-job')){
            return view('jobs.edit', compact('job'));
        }else{
            return Redirect::route('home')->with('error', 'Sorry! You do not have permission to access this page!');
        }

    }

    public function update(Request $request, Job $job)
{
    $request->validate([
        'job_post' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'company_name' => 'required|string|max:255',
        'job_type' => 'required|array',
        'job_type.*' => 'in:internship,work_from_home,part_time,full_time',
        'doj' => 'required|string|max:255',
        'apply_by' => 'required|date',
        'salary' => 'required|string|max:255',
        'hiring_from' => 'required|string|max:255',
        'about_company' => 'required|string',
        'about_job' => 'required|string',
        'who_can_apply' => 'required|string|max:255',
        'skill_required' => 'required|string|max:255',
        'add_perks_of_job' => 'required|string|max:255',
    ]);

    $job->update([
        'job_post' => $request->job_post,
        'email' => $request->email,
        'company_name' => $request->company_name,
        'job_type' => json_encode($request->job_type),
        'doj' => $request->doj,
        'apply_by' => $request->apply_by,
        'salary' => $request->salary,
        'hiring_from' => $request->hiring_from,
        'about_company' => $request->about_company,
        'about_job' => $request->about_job,
        'who_can_apply' => $request->who_can_apply,
        'skill_required' => $request->skill_required,
        'add_perks_of_job' => $request->add_perks_of_job,
    ]);

    return redirect()->route('jobs.index')->with('success', 'Job updated successfully.');
}

    public function destroy(Job $job)
    {
        if(Auth::user()->hasPermissionTo('manage-job')){
            if ($job->resume) {
                Storage::delete($job->resume);
            }

            $job->delete();

            return redirect()->route('jobs.index')
                            ->with('success', 'Job deleted successfully.');
        }else{
            return Redirect::route('home')->with('error', 'Sorry! You do not have permission to access this page!');
        }
                
    }
}
