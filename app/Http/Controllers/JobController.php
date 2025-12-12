<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PDF;
use Illuminate\Support\Facades\File;

class JobController extends Controller
{
    /** Display a listing of the jobs. */
    public function index()
    {
        $jobs = Job::with(['customer', 'jobType'])->latest()->paginate(20);
        return view('jobs.index', compact('jobs'));
    }

    /** Display all jobs with filters. */
    public function list(Request $request)
    {
        $query = Job::with(['customer', 'jobType']);

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        // Apply date range filters
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $jobs = $query->latest()->paginate(20)->withQueryString();

        return view('jobs.list', compact('jobs'));
    }

    /** Show the form for creating a new job. */
    public function create()
    {
        // The index page now contains the create form inline. Redirect to index so users see the all-in-one page.
        return redirect()->route('jobs.index');
    }

    /** Store a newly created job in storage. */
    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'new_customer_name' => 'required_without:customer_id|string|max:150',
            'new_customer_phone' => 'required_without:customer_id|string|max:50',
            'new_customer_address' => 'required_without:customer_id|string|max:500',
            'type' => 'nullable|string|max:150',
            'job_type_id' => 'nullable|exists:job_types,id',
            'description' => 'nullable|string',
            'estimated_duration' => 'nullable|integer|min:0',
            'status' => 'nullable|in:' . implode(',', Job::statuses()),
        ]);

        // If no existing customer chosen, create a new customer from provided details
        if (empty($data['customer_id'])) {
            $customer = \App\Models\Customer::create([
                'name' => $data['new_customer_name'] ?? 'Walk-in',
                'phone' => $data['new_customer_phone'] ?? null,
                'address' => $data['new_customer_address'] ?? null,
                'created_by' => auth()->id(),
                'shop_id' => auth()->user()->shop_id ?? null,
            ]);
            $data['customer_id'] = $customer->id;
        }

        // If estimated_duration not provided, and a job_type_id is selected, use job type default_days
        if (empty($data['estimated_duration']) && !empty($data['job_type_id'])) {
            $jt = \App\Models\JobType::find($data['job_type_id']);
            if ($jt && $jt->default_days !== null) {
                $data['estimated_duration'] = $jt->default_days;
            }
        }

        // If estimated_duration not provided, and a job_type_id is selected, use job type default_days
        if (empty($data['estimated_duration']) && !empty($data['job_type_id'])) {
            $jt = \App\Models\JobType::find($data['job_type_id']);
            if ($jt && $jt->default_days !== null) {
                $data['estimated_duration'] = $jt->default_days;
            }
        }

        // Generate reference number with APFJS prefix
        $lastJob = Job::latest('id')->first();
        $nextNumber = $lastJob ? $lastJob->id + 1 : 1;
        $data['reference_number'] = 'APFJS' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
        $data['status'] = $data['status'] ?? Job::STATUS_PENDING;

        // Only keep job fields
        $jobData = array_filter($data, function ($k) {
            return in_array($k, ['reference_number', 'type', 'description', 'estimated_duration', 'status', 'shop_id', 'job_type_id', 'customer_id']);
        }, ARRAY_FILTER_USE_KEY);

        $job = Job::create($jobData);

        return redirect()->route('jobs.show', $job)->with('success', 'Job created successfully');
    }

    /** Display the specified job. */
    public function show(Job $job)
    {
        return view('jobs.show', compact('job'));
    }

    /**
     * Return JSON payload for a job receipt (used by the client-side modal/print flow)
     */
    public function showReceipt(Job $job)
    {
        $job->load(['customer', 'jobType']);

        // Return a simple JSON structure the frontend can use to build the printable receipt
        return response()->json([
            'success' => true,
            'job' => [
                'id' => $job->id,
                'reference_number' => $job->reference_number,
                'type' => $job->type,
                'description' => $job->description,
                'estimated_duration' => $job->estimated_duration,
                'status' => $job->status,
                'created_at' => $job->created_at->toIso8601String(),
                'updated_at' => $job->updated_at->toIso8601String(),
                'customer' => $job->customer ? [
                    'name' => $job->customer->name,
                    'phone' => $job->customer->phone,
                    'address' => $job->customer->address,
                ] : null,
                'job_type' => $job->jobType ? [
                    'name' => $job->jobType->name ?? $job->jobType->type ?? null,
                    'default_days' => $job->jobType->default_days ?? null,
                ] : null,
            ],
        ]);
    }

    /** Show the form for editing the specified job. */
    public function edit(Job $job)
    {
        $statuses = Job::statuses();
        $customers = \App\Models\Customer::orderBy('name')->get();
        return view('jobs.edit', compact('job', 'statuses', 'customers'));
    }

    /** Update the specified job in storage. */
    public function update(Request $request, Job $job)
    {
        // Quick status update (from dropdown in list)
        if ($request->has('status') && count($request->all()) <= 2) {
            $request->validate([
                'status' => 'required|in:' . implode(',', Job::statuses()),
            ]);

            $job->update(['status' => $request->status]);

            return redirect()->route('jobs.index')->with('success', 'Job status updated to ' . ucfirst(str_replace('_', ' ', $request->status)));
        }

        // Full job update
        $data = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'new_customer_name' => 'required_without:customer_id|string|max:150',
            'new_customer_phone' => 'required_without:customer_id|string|max:50',
            'new_customer_address' => 'required_without:customer_id|string|max:500',
            'type' => 'nullable|string|max:150',
            'job_type_id' => 'nullable|exists:job_types,id',
            'description' => 'nullable|string',
            'estimated_duration' => 'nullable|integer|min:0',
            'status' => 'nullable|in:' . implode(',', Job::statuses()),
        ]);

        if (empty($data['customer_id'])) {
            $customer = \App\Models\Customer::create([
                'name' => $data['new_customer_name'] ?? 'Walk-in',
                'phone' => $data['new_customer_phone'] ?? null,
                'address' => $data['new_customer_address'] ?? null,
                'created_by' => auth()->id(),
                'shop_id' => auth()->user()->shop_id ?? null,
            ]);
            $data['customer_id'] = $customer->id;
        }

        $jobData = array_filter($data, function ($k) {
            return in_array($k, ['type', 'description', 'estimated_duration', 'status', 'job_type_id', 'customer_id']);
        }, ARRAY_FILTER_USE_KEY);

        $job->update($jobData);

        return redirect()->route('jobs.show', $job)->with('success', 'Job updated successfully');
    }

    /** Remove the specified job from storage. */
    public function destroy(Job $job)
    {
        $job->delete();
        return redirect()->route('jobs.index')->with('success', 'Job removed');
    }

    /**
     * Download PDF job sheet
     */
    public function downloadPdfJobSheet($jobId)
    {
        try {
            $job = Job::with(['customer', 'jobType'])->findOrFail($jobId);

            // Get shop JOB letterhead configuration (separate from order letterhead)
            $shop = auth()->user()->shop ?? \App\Models\Shop::first();
            $letterheadConfig = is_array($shop->job_letterhead_config) ? $shop->job_letterhead_config : [];

            $hasLetterhead = isset($letterheadConfig['letterhead_file']) && !empty($letterheadConfig['letterhead_file']);
            $letterheadType = $letterheadConfig['letterhead_type'] ?? 'image';
            $letterheadFile = $letterheadConfig['letterhead_file'] ?? null;

            // For PDF letterheads, don't merge - just show content without background
            // Image letterheads will embed directly
            if ($letterheadType === 'pdf') {
                \Log::info('PDF letterhead detected - skipping merge, content-only generation');
                $letterheadConfig['letterhead_file'] = null; // Disable letterhead for PDF generation
                $hasLetterhead = false;
            }

            // Try embedding image as data URI
            if ($hasLetterhead && $letterheadType === 'image' && $letterheadFile) {
                try {
                    $imagePath = public_path('letterheads/' . $letterheadFile);
                    if (File::exists($imagePath)) {
                        $contents = File::get($imagePath);
                        $mime = @mime_content_type($imagePath) ?: 'image/png';
                        $letterheadConfig['preview_image_data'] = 'data:' . $mime . ';base64,' . base64_encode($contents);
                    }
                } catch (\Throwable $e) {
                    \Log::warning('Failed to embed image letterhead', ['error' => $e->getMessage()]);
                }
            }

            // Generate PDF with content
            $pdf = PDF::loadView('jobs.pdf-job-sheet', [
                'job' => $job,
                'shop' => $shop,
                'letterheadConfig' => $letterheadConfig,
            ]);

            $pdf->setPaper('A5', 'portrait');
            $pdf->setOptions([
                'dpi' => 150,
                'defaultFont' => 'DejaVu Sans',
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
                'isRemoteEnabled' => true,
                'isFontSubsettingEnabled' => false,
            ]);

            // Get PDF content
            $pdfContent = $pdf->output();

            // Generate filename
            $filename = "JobSheet_{$job->reference_number}_{$job->created_at->format('Y-m-d')}.pdf";

            // Return PDF download
            return response($pdfContent, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]);

        } catch (\Throwable $e) {
            \Log::error('Job sheet PDF generation failed', [
                'job_id' => $jobId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Failed to generate job sheet PDF: ' . $e->getMessage());
        }
    }
}
