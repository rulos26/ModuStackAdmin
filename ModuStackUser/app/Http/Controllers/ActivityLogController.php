<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserActivityLog;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ActivityLogController extends Controller
{
    public function __construct(
        protected ActivityLogService $activityLogService
    ) {
        $this->middleware(['auth', 'activity.log']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = UserActivityLog::with('user');

        // Filter by user
        if ($request->has('user_id')) {
            $query->where('user_id', $request->get('user_id'));
        }

        // Filter by action
        if ($request->has('action')) {
            $query->where('action', $request->get('action'));
        }

        // Filter by date range
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(50);
        $users = User::all();

        return view('activity-logs.index', compact('logs', 'users'));
    }

    /**
     * Display the specified resource.
     */
    public function show(UserActivityLog $activityLog): View
    {
        $activityLog->load('user');
        return view('activity-logs.show', compact('activityLog'));
    }

    /**
     * Export logs to PDF.
     */
    public function exportPdf(Request $request)
    {
        $logs = UserActivityLog::with('user')
            ->when($request->has('user_id'), function ($q) use ($request) {
                $q->where('user_id', $request->get('user_id'));
            })
            ->when($request->has('date_from'), function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->get('date_from'));
            })
            ->when($request->has('date_to'), function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->get('date_to'));
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $pdf = Pdf::loadView('activity-logs.pdf', compact('logs'));
        return $pdf->download('activity-logs-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export logs to Excel.
     */
    public function exportExcel(Request $request)
    {
        $logs = UserActivityLog::with('user')
            ->when($request->has('user_id'), function ($q) use ($request) {
                $q->where('user_id', $request->get('user_id'));
            })
            ->when($request->has('date_from'), function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->get('date_from'));
            })
            ->when($request->has('date_to'), function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->get('date_to'));
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $exportData = $this->activityLogService->exportLogs($logs->all());

        return Excel::download(new \App\Exports\ActivityLogsExport($exportData), 'activity-logs-' . date('Y-m-d') . '.xlsx');
    }
}
