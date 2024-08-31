<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Diabetes;
use Carbon\Carbon;
use PDF;

class DiabetesController extends Controller
{
    public function store(Request $request, $user_id)
    {
        $request->validate([
            'blood_sugar_level' => 'required|numeric|min:0',
        ]);

       

        // Check the number of submissions in the last 24 hours
        $submissionCount = Diabetes::where('user_id', $user_id)
            ->where('created_at', '>=', Carbon::now()->subDay())
            ->count();

        if ($submissionCount >= 2) {
            return response()->json(['error' => 'You can only submit your blood sugar data twice within a 24-hour period.'], 429);
        }

        $diabetes = new Diabetes();
        $diabetes->user_id = $user_id;
        $diabetes->blood_sugar_level = $request->blood_sugar_level;

        if ($diabetes->blood_sugar_level >= 200) {
            $diabetes->category = "Very High";
        } elseif ($diabetes->blood_sugar_level >= 140 && $diabetes->blood_sugar_level < 200) {
            $diabetes->category = "High";
        } elseif ($diabetes->blood_sugar_level >= 70 && $diabetes->blood_sugar_level < 140) {
            $diabetes->category = "Normal";
        } elseif ($diabetes->blood_sugar_level < 70) {
            $diabetes->category = "Low";
        } else {
            $diabetes->category = "Blood sugar classification not found.";
        }

        $diabetes->save();

        return response()->json(['success' => 'Blood sugar data submitted successfully.'], 201);
    }

    public function show(Request $request, $user_id)
    {
        
        $timeFrame = $request->input('time_frame', 'month');

        switch ($timeFrame) {
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                break;
            case 'day':
            default:
                $startDate = Carbon::now()->startOfDay();
                break;
        }

        $diabetes = Diabetes::where('user_id', $user_id)
            ->where('created_at', '>=', $startDate)
            ->get();

        return response()->json($diabetes);
    }

    public function downloadPDF(Request $request, $user_id)
    {
        
        $timeFrame = $request->input('time_frame', 'month'); 

        switch ($timeFrame) {
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                break;
            case 'day':
            default:
                $startDate = Carbon::now()->startOfDay();
                break;
        }

        $diabetes = Diabetes::where('user_id', $user_id)
            ->where('created_at', '>=', $startDate)
            ->get();

        $pdf = PDF::loadView('dashboard.diabetes.blood-sugar-pdf', [
            'diabetes' => $diabetes,
            'timeFrame' => $timeFrame,
        ]);

        return $pdf->download('blood_sugar_results.pdf');
        
    }
}
