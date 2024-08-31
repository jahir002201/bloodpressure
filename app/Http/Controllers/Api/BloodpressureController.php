<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Bloodpressure;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PDF;

class BloodpressureController extends Controller
{
    public function store(Request $request, $user_id)
    {
        $request->validate([
            'systolic' => 'required|integer|min:0',
            'diastolic' => 'required|integer|min:0',
        ]);


        $submissionCount = Bloodpressure::where('user_id', $user_id)
            ->where('created_at', '>=', Carbon::now()->subDay())
            ->count();

        if ($submissionCount >= 2) {
            return response()->json(['error' => 'You can only submit your blood pressure data twice within a 24-hour period.'], 429);
        }

        $bp = new Bloodpressure();
        $bp->user_id = $user_id;
        $bp->systolic = $request->systolic;
        $bp->diastolic = $request->diastolic;

        if ($bp->systolic >= 180 || $bp->diastolic >= 110) {
            $bp->category = "High blood pressure (Hypertensive crisis)";
        } elseif (($bp->systolic >= 160 && $bp->systolic < 180) || ($bp->diastolic >= 100 && $bp->diastolic < 110)) {
            $bp->category = "High blood pressure (Stage 2)";
        } elseif (($bp->systolic >= 140 && $bp->systolic < 160) || ($bp->diastolic >= 90 && $bp->diastolic < 100)) {
            $bp->category = "High blood pressure (Stage 1)";
        } elseif (($bp->systolic >= 130 && $bp->systolic < 140) || ($bp->diastolic >= 80 && $bp->diastolic < 90)) {
            $bp->category = "Pre-high blood pressure";
        } elseif ($bp->systolic < 90 || $bp->diastolic < 60) {
            $bp->category = "Low blood pressure";
        } elseif ($bp->systolic < 120 && $bp->diastolic < 80) {
            $bp->category = "Ideal blood pressure";
        } else {
            $bp->category = "Blood pressure classification not found.";
        }

        $bp->save();
        return response()->json(['success' => 'Blood pressure data submitted successfully.'], 201);
    }

    public function show(Request $request, $user_id)
    {

        $user = User::find($user_id);
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

        $bloodpressure = Bloodpressure::where('user_id', $user->id)
            ->where('created_at', '>=', $startDate)
            ->get();

        return response()->json($bloodpressure);
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

        $bloodpressure = Bloodpressure::where('user_id', $user_id)
            ->where('created_at', '>=', $startDate)
            ->get();

        $pdf = PDF::loadView('dashboard.bloodpressure.blood-pressure-pdf', [
            'bloodpressure' => $bloodpressure,
            'timeFrame' => $timeFrame,
        ]);

        return $pdf->download('blood_pressure_results.pdf');
    }
}
