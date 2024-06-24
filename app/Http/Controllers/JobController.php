<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JobController extends Controller
{
    public function index()
    {
        $jobs = Job::all();
        return view('jobs.index', compact('jobs'));
    }

    public function create()
    {
       
        return view('jobs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'company' => 'required',
            'location' => 'required',
           
           
           
            
        ]);

        if ($request->hasFile('resume')) {
            $resumePath = $request->file('resume')->store('resumes');
        }

        Job::create([
            'title' => $request->title,
            'description' => $request->description,
            'company' => $request->company,
            'location' => $request->location,
            'experience' => $request->experience,
            'notice_period' => $request->notice_period,
            'current_job' => $request->current_job,
            'resume' => $resumePath ?? null,
        ]);

        return redirect()->route('jobs.index')
                         ->with('success', 'Job created successfully.');
    }

    public function show(Job $job)
    {
        return view('jobs.show', compact('job'));
    }

    public function edit(Job $job)
    {
        return view('jobs.edit', compact('job'));
    }

    public function update(Request $request, Job $job)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'company' => 'required',
            'location' => 'required',
           
        ]);

        if ($request->hasFile('resume')) {
            
            if ($job->resume) {
                Storage::delete($job->resume);
            }
            $resumePath = $request->file('resume')->store('resumes');
        }

        $job->update([
            'title' => $request->title,
            'description' => $request->description,
            'company' => $request->company,
            'location' => $request->location,
            'experience' => $request->experience,
            'notice_period' => $request->notice_period,
            'current_job' => $request->current_job,
            'resume' => $resumePath ?? $job->resume,
        ]);

        return redirect()->route('jobs.index')
                         ->with('success', 'Job updated successfully.');
    }

    public function destroy(Job $job)
    {
        if ($job->resume) {
            Storage::delete($job->resume);
        }

        $job->delete();

        return redirect()->route('jobs.index')
                         ->with('success', 'Job deleted successfully.');
    }
}
