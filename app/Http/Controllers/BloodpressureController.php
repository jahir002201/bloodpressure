<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bloodpressure;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PDF;

class BloodpressureController extends Controller
{
    public function index()
    {
        // $base = BloodPressure::checkPressure(120,90);
        // dd($base);
        return view('dashboard.bloodpressure.blood-pressure');
    }

    public function store(Request $request)
    {
        $request->validate([
            'systolic' => 'required|integer|min:0',
            'diastolic' => 'required|integer|min:0',
        ]);

     // Get the currently authenticated user
     $user = Auth::user();

     // Check the number of submissions in the last 24 hours
     $submissionCount = Bloodpressure::where('user_id', $user->id)
         ->where('created_at', '>=', Carbon::now()->subDay())
         ->count();

     if ($submissionCount >= 2) {
         return back()->with('error', 'You can only submit your blood pressure data twice within a 24-hour period.');
     }

      $bp =  new Bloodpressure();
      $bp->user_id=$user->id;
      $bp->systolic=$request->systolic;
      $bp->diastolic=$request->diastolic;
        if ($bp->systolic >= 180 || $bp->diastolic >= 110)
        $bp->category = "High blood pressure (Hypertensive crisis)";
        elseif (($bp->systolic >= 160 && $bp->systolic < 180) || ($bp->diastolic >= 100 && $bp->diastolic < 110))
            $bp->category = "High blood pressure (Stage 2)";
        elseif (($bp->systolic >= 140 && $bp->systolic < 160) || ($bp->diastolic >= 90 && $bp->diastolic < 100))
            $bp->category = "High blood pressure (Stage 1)";
        elseif (($bp->systolic >= 130 && $bp->systolic < 140) || ($bp->diastolic >= 80 && $bp->diastolic < 90))
            $bp->category = "Pre-high blood pressure";
        elseif ($bp->systolic < 90 || $bp->diastolic < 60)
            $bp->category = "Low blood pressure";
        elseif ($bp->systolic < 120 && $bp->diastolic < 80)
            $bp->category = "Ideal blood pressure";
        else{

            $bp->category = "Blood pressure classification not found.";
        }

      $bp->save();
      return back()->with('success', 'Blood pressure data submitted successfully.');
    }

    public function show(Request $request){
        $user = Auth::user();
        $timeFrame = $request->input('time_frame', 'day'); // default to 'day'

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

        return view('dashboard.bloodpressure.blood-pressure-result', [
            'bloodpressure' => $bloodpressure,
            'timeFrame' => $timeFrame,
        ]);
    }
    
    public function downloadPDF(Request $request)
    {
        $user = Auth::user();
        $timeFrame = $request->input('time_frame', 'day'); // default to 'day'

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

        $pdf = PDF::loadView('dashboard.bloodpressure.blood-pressure-pdf', [
            'bloodpressure' => $bloodpressure,
            'timeFrame' => $timeFrame,
        ]);

        return $pdf->download('blood_pressure_results.pdf');
    }

}
