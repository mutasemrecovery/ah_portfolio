<?php

namespace App\Http\Controllers;

use App\Models\CardNumber;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\Student;
use App\Models\StudentAnswer;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\HeroSection;
use App\Models\Agency;
use App\Models\AboutSection;
use App\Models\Client;
use App\Models\SiteSetting;
use App\Services\ProgressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {

        // ── Portfolio dynamic data ────────────────────────────────────────
        $hero     = HeroSection::where('is_active', true)->first();
        $agencies = Agency::active()->with(['services' => fn($q) => $q->where('is_active', true)->orderBy('sort_order')])->get();
        $about    = AboutSection::where('is_active', true)->first();
        $clients  = Client::active()->get();
        $settings = SiteSetting::all()->keyBy('key');

        return view('front.home', compact(
      'hero', 'agencies', 'about', 'clients', 'settings'
        ));
    }

}
