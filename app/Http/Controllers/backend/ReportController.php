<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function all_reports() {
        $oldYear = Order::orderBy('created_at', 'asc')->first();
        if ($oldYear) {
            $oldYear = $oldYear->created_at->format('Y');
        } else {
            $oldYear = null;
        }
        return view('admin.backend.reports.get_reports', compact('oldYear'));
    }



    public function date_reports(Request $request) {
        $endDate = $request->input('to'); // End of the range
        $startDate = $request->input('from'); // Start of the range

        // Fetch records created between the specified start and end year, ignoring time components
        $reports = Order::whereDate('created_at', '>=', $startDate)
                        ->whereDate('created_at', '<=', $endDate)
                        ->with('payment')
                        ->latest()
                        ->get();

        foreach($reports as $report){
            $report->date =  Carbon::parse($report->created_at)->format('j F Y');
        }
    
        return response()->json(['reports' => $reports, 'startDate' => $startDate, 'endDate' => $endDate]);
    }

}
