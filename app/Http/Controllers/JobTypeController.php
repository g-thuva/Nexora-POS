<?php

namespace App\Http\Controllers;

use App\Models\JobType;
use Illuminate\Http\Request;

class JobTypeController extends Controller
{
    public function index()
    {
        $types = JobType::latest()->paginate(20);
        return view('jobtypes.index', compact('types'));
    }

    public function create()
    {
        return view('jobtypes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150|unique:job_types,name',
            'description' => 'nullable|string',
            'default_days' => 'nullable|integer|min:0',
        ]);

        $type = JobType::create($data);

        return redirect()->route('job-types.index')->with('success', 'Job type created');
    }

    public function show(JobType $jobType)
    {
        return view('jobtypes.show', ['type' => $jobType]);
    }

    public function edit(JobType $jobType)
    {
        return view('jobtypes.edit', ['type' => $jobType]);
    }

    public function update(Request $request, JobType $jobType)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150|unique:job_types,name,' . $jobType->id,
            'description' => 'nullable|string',
            'default_days' => 'nullable|integer|min:0',
        ]);

        $jobType->update($data);

        return redirect()->route('job-types.index')->with('success', 'Job type updated');
    }

    public function destroy(JobType $jobType)
    {
        $jobType->delete();
        return redirect()->route('job-types.index')->with('success', 'Job type removed');
    }
}
