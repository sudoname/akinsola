<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Cycle;
use App\Models\MemorialPhoto;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Show the home/landing page.
     */
    public function home()
    {
        $activeCycles = Cycle::where('status', 'published')
            ->where('deadline_at', '>', now())
            ->latest()
            ->take(3)
            ->get();

        return view('public.home', compact('activeCycles'));
    }

    /**
     * Show the about page.
     */
    public function about()
    {
        return view('public.about');
    }

    /**
     * Show the eligibility page.
     */
    public function eligibility()
    {
        return view('public.eligibility');
    }

    /**
     * Show the awardees page (with time-gated results).
     */
    public function awardees()
    {
        // Get all cycles with visible results
        $cycles = Cycle::whereHas('applications', function ($query) {
            $query->whereIn('status', ['approved', 'rejected', 'waitlisted']);
        })
        ->get()
        ->filter(function ($cycle) {
            return $cycle->resultsAreVisible();
        });

        // Get approved applications with awardee information for cycles with visible results
        $awardees = collect();
        foreach ($cycles as $cycle) {
            $cycleAwardees = Application::where('cycle_id', $cycle->id)
                ->where('status', 'approved')
                ->whereNotNull('awardee_photo')
                ->whereNotNull('awardee_profile')
                ->with(['user', 'cycle', 'educationRecord'])
                ->get();

            $awardees = $awardees->merge($cycleAwardees);
        }

        // Group by cycle
        $awardeesByCycle = $awardees->groupBy('cycle_id');

        return view('public.awardees', compact('cycles', 'awardeesByCycle'));
    }

    /**
     * Show the memorial page.
     */
    public function inMemory()
    {
        $memorial = \App\Models\MemorialSetting::current();
        $photos = MemorialPhoto::getActivePhotos();
        return view('public.in-memory', compact('memorial', 'photos'));
    }

    /**
     * Show the privacy policy page.
     */
    public function privacyPolicy()
    {
        return view('public.privacy-policy');
    }

    /**
     * Show the terms of service page.
     */
    public function termsOfService()
    {
        return view('public.terms-of-service');
    }

    /**
     * Facebook data deletion callback page.
     */
    public function facebookDataDeletion()
    {
        return view('public.facebook-deletion');
    }
}
