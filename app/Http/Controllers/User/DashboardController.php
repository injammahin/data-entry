<?php

namespace App\Http\Controllers\User;
use App\Models\SearchList;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $quickActions = [
            [
                'title' => 'U.S. Business',
                'description' => 'Build a targeted business list with custom search filters and location-based criteria.',
                'icon' => 'fa-building',
                'url' => route('user.us-business.index'),
                'active' => true,
            ],
            [
                'title' => 'Import Lists',
                'description' => 'Enhance maps, campaigns, or suppress custom entries from previous searches.',
                'icon' => 'fa-file-import',
                'url' => '#',
                'active' => false,
            ],
            [
                'title' => 'Follow Ups',
                'description' => 'Review reminder activities and keep track of the leads you want to revisit.',
                'icon' => 'fa-calendar-check',
                'url' => '#',
                'active' => false,
            ],
            [
                'title' => 'Manage Campaigns',
                'description' => 'Create and manage beautiful outreach campaigns for your business audience.',
                'icon' => 'fa-bullhorn',
                'url' => '#',
                'active' => false,
            ],
            [
                'title' => 'Help Center',
                'description' => 'Learn how to perform the most common tasks with smooth guided workflows.',
                'icon' => 'fa-circle-question',
                'url' => '#',
                'active' => false,
            ],
        ];

        $savedSearches = SearchList::where('user_id', auth()->id())->get();
        return view('user.dashboard', compact('quickActions', 'savedSearches'));
    }
    
    public function openSavedList(SearchList $searchList)
    {
        // Ensure that the search belongs to the logged-in user
        if ($searchList->user_id !== auth()->id()) {
            return redirect()->route('user.dashboard')->with('error', 'Unauthorized access to this saved list.');
        }

        // Redirect to results page, passing the saved search criteria as query parameters
        return redirect()->route('user.us-business.results', [
            'business_name' => $searchList->criteria_json['business_name'] ?? '',
            'executive_first_name' => $searchList->criteria_json['executive_first_name'] ?? '',
            'executive_last_name' => $searchList->criteria_json['executive_last_name'] ?? '',
            'state_id' => $searchList->criteria_json['state_id'] ?? '',
            'city' => $searchList->criteria_json['city'] ?? '',
            'address' => $searchList->criteria_json['address'] ?? '',
            'zip_code' => $searchList->criteria_json['zip_code'] ?? '',
            'phone_number' => $searchList->criteria_json['phone_number'] ?? '',
            'columns' => $searchList->visible_columns,  // Passing the visible columns for display
        ]);
    }
}