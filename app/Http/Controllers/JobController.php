<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Job;

class JobController extends Controller
{
    use AuthorizesRequests;

    private const JOB_TYPES = [
        'full_time',
        'part_time',
        'contract',
        'temporary',
        'internship',
        'volunteer',
        'on_call',
    ];

    // @desc Show all job listings
    // @route GET /jobs
    public function index(): View
    {
        $jobs = Job::all();
        return view('jobs.index')->with('jobs', $jobs);
    }

    // @desc Show the form for creating a new job
    // @route GET /jobs/create
    public function create(): View
    {
        return view('jobs.create');
    }

    // @desc Store a newly created job
    // @route POST /jobs
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'salary' => 'required|integer',
            'tags' => 'nullable|string',
            'job_type' => ['required', Rule::in(self::JOB_TYPES)],
            'remote' => 'required|boolean',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zipcode' => 'nullable|string',
            'contact_email' => 'required|string',
            'contact_phone' => 'nullable|string',
            'company_name' => 'required|string',
            'company_description' => 'nullable|string',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'company_website' => 'nullable|url',
        ]);

        // Hardcoded User ID
        $validatedData['user_id'] = auth()->user->id;

        // Handle file upload for company logo
        if ($request->hasFile('company_logo')) {
            // Store the file and get path
            $path = $request->file('company_logo')->store('logos', 'public');

            // Add path to validated data
            $validatedData['company_logo'] = $path;
        }

        // Submit to the database
        Job::create($validatedData);

        return redirect()->route('jobs.index')->with('success', 'Job Listing created successfully.');
    }

    // @desc Show a specific job
    // @route GET /jobs/{ID}
    public function show(Job $job): View
    {
        return view('jobs.show')->with('job', $job);
    }

    // @desc Show the form for editing a specific job
    // @route GET /jobs/{ID}/edit
    public function edit(Job $job): View
    {
        // Check if the user is authorized to update the job
        $this->authorize('update', $job);


        return view('jobs.edit')->with('job', $job);
    }

    // @desc Update a specific job
    // @route PUT /jobs/{ID}
    public function update(Request $request, Job $job): string
    {
        // Check if the user is authorized to update the job
        $this->authorize('update', $job);

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'salary' => 'required|integer',
            'tags' => 'nullable|string',
            'job_type' => ['required', Rule::in(self::JOB_TYPES)],
            'remote' => 'required|boolean',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'address' => 'nullable|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zipcode' => 'nullable|string',
            'contact_email' => 'required|string',
            'contact_phone' => 'nullable|string',
            'company_name' => 'required|string',
            'company_description' => 'nullable|string',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'company_website' => 'nullable|url',
        ]);



        // Handle file upload for company logo
        if ($request->hasFile('company_logo')) {
            // Delete old logo 
            Storage::delete('public/logos/' . basename($job->company_logo));

            // Store the file and get path
            $path = $request->file('company_logo')->store('logos', 'public');

            // Add path to validated data
            $validatedData['company_logo'] = $path;
        }

        // Submit to the database
        $job->update($validatedData);

        return redirect()->route('jobs.index')->with('success', 'Job Listing updated successfully.');
    }

    // @desc Delete a specific job
    // @route DELETE /jobs/{ID}
    public function destroy(Job $job): RedirectResponse
    {
        // Check if the user is authorized to update the job
        $this->authorize('delete', $job);


        // Delete logo file if exists
        if ($job->company_logo) {
            Storage::delete('public/logos/' . basename($job->company_logo));
        }

        $job->delete();

        // Check if request came from the dashboard
        if (request()->query('from') == 'dashboard') {
            return redirect()->route('dashboard')->with('success', 'Job listing deleted successfully!');
        }

        return redirect()->route('jobs.index')->with('success', 'Job Listing deleted successfully.');
    }
}
