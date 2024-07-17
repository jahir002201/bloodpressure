<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BloodPressure;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PDF;

class BloodPressureController extends Controller
{
    public function index()
    {
        // $base = BloodPressure::checkPressure(120,90);
        // dd($base);
        return view('bloodpressure.blood-pressure');
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
     $submissionCount = BloodPressure::where('user_id', $user->id)
         ->where('created_at', '>=', Carbon::now()->subDay())
         ->count();

     if ($submissionCount >= 2) {
         return back()->with('error', 'You can only submit your blood pressure data twice within a 24-hour period.');
     }

      $bloodpressure =  new BloodPressure();
      $bloodpressure->user_id=$user->id;
      $bloodpressure->systolic=$request->systolic;
      $bloodpressure->diastolic=$request->diastolic;
      $bloodpressure->save();
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

        $bloodpressure = BloodPressure::where('user_id', $user->id)
            ->where('created_at', '>=', $startDate)
            ->get();

        return view('bloodpressure.blood-pressure-result', [
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

        $bloodpressure = BloodPressure::where('user_id', $user->id)
            ->where('created_at', '>=', $startDate)
            ->get();

        $pdf = PDF::loadView('bloodpressure.blood-pressure-pdf', [
            'bloodpressure' => $bloodpressure,
            'timeFrame' => $timeFrame,
        ]);

        return $pdf->download('blood_pressure_results.pdf');
    }

}
