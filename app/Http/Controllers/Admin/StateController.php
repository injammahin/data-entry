<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StateController extends Controller
{
    public function index()
    {
        $states = State::latest()->paginate(10);
        return view('admin.states.index', compact('states'));
    }

    public function create()
    {
        return view('admin.states.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:states,name'],
        ]);

        $slug = Str::slug($request->name);

        $originalSlug = $slug;
        $count = 1;

        while (State::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        State::create([
            'name' => $request->name,
            'slug' => $slug,
        ]);

        return redirect()
            ->route('admin.states.index')
            ->with('success', 'State created successfully.');
    }

    public function show(State $state)
    {
        return redirect()->route('admin.states.index');
    }

    public function edit(State $state)
    {
        return view('admin.states.edit', compact('state'));
    }

    public function update(Request $request, State $state)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:states,name,' . $state->id],
        ]);

        $slug = Str::slug($request->name);

        $originalSlug = $slug;
        $count = 1;

        while (
            State::where('slug', $slug)
                ->where('id', '!=', $state->id)
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        $state->update([
            'name' => $request->name,
            'slug' => $slug,
        ]);

        return redirect()
            ->route('admin.states.index')
            ->with('success', 'State updated successfully.');
    }

    public function destroy(State $state)
    {
        $state->delete();

        return redirect()
            ->route('admin.states.index')
            ->with('success', 'State deleted successfully.');
    }
}