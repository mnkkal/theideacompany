<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JobApplicationController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->input('search');

       
        $jobApplications = JobApplication::query()
            ->when($searchTerm, function ($query, $searchTerm) {
                return $query->whereHas('job', function ($query) use ($searchTerm) {
                        $query->where('experience', 'LIKE', "%{$searchTerm}%");
                    })
                    ->orWhere('name', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('email', 'LIKE', "%{$searchTerm}%");
            })
            ->paginate(10);
    
        return view('job_applications.index', compact('jobApplications', 'searchTerm'));
    }

    public function create()
    {
        $jobs = Job::all();
        return view('job_applications.create', compact('jobs'));
    }
    public function apply($id)
    {
        $jobs = Job::where('id', $id)->get();
        return view('job_applications.create', compact('jobs'));
    }

    public function store(Request $request)
    { 
       
        $request->validate([
            'job_id' => 'required|exists:job_post,id',
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'experience' => 'required',
            'notice_period' => 'required',
            'current_job' => 'required',
            'resume' => 'required|mimes:pdf,doc,docx|max:2048'
        ]);
    
       
        $existingApplication = JobApplication::where('job_id', $request->job_id)
                                              ->where('email', $request->email)
                                              ->first();
    
        if ($existingApplication) {
            return redirect()->back()->with('error', 'You have already applied for this job.');
        }
    
      
        if ($request->hasFile('resume')) {
            $resumePath = $request->file('resume')->store('resumes');
        }
    
       
        JobApplication::create([
            'job_id' => $request->job_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'experience' => $request->experience,
            'notice_period' => $request->notice_period,
            'current_job' => $request->current_job,
            'resume' => $resumePath ?? null,
        ]);
    
        return redirect()->route('job-applications.index')->with('success', 'Application submitted successfully.');
    }
    

    public function show(JobApplication $jobApplication)
    {
        return view('job_applications.show', compact('jobApplication'));
    }

    public function edit(JobApplication $jobApplication)
    {
        $jobs = Job::all();
        return view('job_applications.create', compact('jobApplication', 'jobs'));
    }

    public function update(Request $request, JobApplication $jobApplication)
    {
        $request->validate([
            'job_id' => 'required|exists:job_post,id',
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'experience' => 'required',
            'notice_period' => 'required',
            'current_job' => 'required',
            'resume' => 'nullable|mimes:pdf,doc,docx|max:2048'
        ]);

        if ($request->hasFile('resume')) {
            // Delete the old resume if exists
            if ($jobApplication->resume) {
                Storage::delete($jobApplication->resume);
            }
            $resumePath = $request->file('resume')->store('resumes');
        }

        $jobApplication->update([
            'job_id' => $request->job_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'experience' => $request->experience,
            'notice_period' => $request->notice_period,
            'current_job' => $request->current_job,
            'resume' => $resumePath ?? $jobApplication->resume,
        ]);

        return redirect()->route('job-applications.index')
                         ->with('success', 'Job application updated successfully.');
    }

    public function destroy(JobApplication $jobApplication)
    {
        if ($jobApplication->resume) {
            Storage::delete($jobApplication->resume);
        }

        $jobApplication->delete();

        return redirect()->route('job-applications.index')
                         ->with('success', 'Job application deleted successfully.');
    }
}
