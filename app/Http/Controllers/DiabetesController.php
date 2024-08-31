<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Diabetes;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PDF;

class DiabetesController extends Controller
{
    public function index()
    {
        return view('dashboard.diabetes.blood-sugar');
    }

    public function store(Request $request)
    {
        $request->validate([
            'blood_sugar_level' => 'required|numeric|min:0',
        ]);

        // Get the currently authenticated user
        $user = Auth::user();

        // Check the number of submissions in the last 24 hours
        $submissionCount = Diabetes::where('user_id', $user->id)
            ->where('created_at', '>=', Carbon::now()->subDay())
            ->count();

        if ($submissionCount >= 2) {
            return back()->with('error', 'You can only submit your blood sugar data twice within a 24-hour period.');
        }

        $diabetes = new Diabetes();
        $diabetes->user_id = $user->id;
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

        return back()->with('success', 'Blood sugar data submitted successfully.');
    }

    public function show(Request $request)
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

        $diabetes = Diabetes::where('user_id', $user->id)
            ->where('created_at', '>=', $startDate)
            ->get();

        return view('dashboard.diabetes.blood-sugar-result', [
            'diabetes' => $diabetes,
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

        $diabetes = Diabetes::where('user_id', $user->id)
            ->where('created_at', '>=', $startDate)
            ->get();

        $pdf = PDF::loadView('dashboard.diabetes.blood-sugar-pdf', [
            'diabetes' => $diabetes,
            'timeFrame' => $timeFrame,
        ]);

        return $pdf->download('blood_sugar_results.pdf');
    }
}
