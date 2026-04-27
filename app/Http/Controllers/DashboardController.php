<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $sections = [
            "feature" => "Feature",
            "sdgs" => "SDGs",
            "equityproject" => "Equity Projects",
            "about-us" => "About Us",
            "faq" => "FAQ",
            "contact" => "Contact Us"
        ];

        $faqs = [
            "What is the EQUITY project?" => "EQUITY is a project that focuses on environmental quality monitoring using IoT technology.",
            "How does the EQUITY project contribute to SDGs?" => "It supports clean water, sanitation, health, and sustainable communities.",
            "Who can benefit from the EQUITY project?" => "Students, researchers, communities, and decision-makers can benefit from this project.",
            "How can I get involved with the EQUITY project?" => "You can contact the team through the contact form.",
            "Where can I find more information about the EQUITY project?" => "More information is available on this website.",
            "How can I support the EQUITY project?" => "You can support by collaboration, research, data collection, or community participation."
        ];

        $teamMembers = [
            ["role" => "Team Leader", "name" => "ROSYID HANAFITRI UTOMO"],
            ["role" => "Secretary", "name" => "FATHIKA ARUM MAULIDA"],
            ["role" => "Secretary", "name" => "YATIN HARSU WINARTI"],
            ["role" => "Finance", "name" => "ANGGERTHA NURROSYID"],
            ["role" => "Public Relations", "name" => "GUSTAMA DILLO BHESIETO"],
            ["role" => "Media & Documentation", "name" => "AZZAM TSABITUL JAMIL"],
            ["role" => "General Affair", "name" => "ASQI SYAHRUL ANWAR"],
            ["role" => "General Affair", "name" => "YUSUP C DERMAWAN"]
        ];

        return view('dashboard', compact('sections', 'faqs', 'teamMembers'));
    }
}