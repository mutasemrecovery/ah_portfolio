<?php

namespace App\Http\Controllers;

use App\Models\HeroSection;
use App\Models\Agency;
use App\Models\AboutSection;
use App\Models\AgencyMedia;
use App\Models\Client;
use App\Models\SiteSetting;

class HomeController extends Controller
{
    public function index()
    {

        // ── Portfolio dynamic data ────────────────────────────────────────
        $hero     = HeroSection::where('is_active', true)->first();
        $agencies = Agency::active()->with([
            'services' => fn($q) => $q->where('is_active', true)->orderBy('sort_order'),
            'media'    => fn($q) => $q->where('is_active', true)->orderBy('sort_order'),
        ])->get();
        $about    = AboutSection::where('is_active', true)->first();
        $clients  = Client::active()->get();
        $settings = SiteSetting::all()->keyBy('key');

        $allSectors = AgencyMedia::where('is_active', true)
            ->whereNotNull('sector')
            ->where('sector', '!=', '')
            ->orderBy('sector')
            ->distinct()
            ->pluck('sector');

        return view('front.home', compact(
            'hero', 'agencies', 'about', 'clients', 'settings', 'allSectors'
        ));
    }

}
