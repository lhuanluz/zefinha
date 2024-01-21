<?php

namespace App\Http\Controllers;

use App\Models\Round;
use Illuminate\Http\Request;

class RoundController extends Controller
{
    public function index()
    {
        $rounds = Round::all();
        return view('rounds.index', compact('rounds'));
    }

    public function create()
    {
        return view('rounds.create');
    }

    public function store(Request $request)
    {
        Round::create($request->all());
        return redirect()->route('rounds.index');
    }

    public function show(Round $round)
    {
        return view('rounds.show', compact('round'));
    }

    public function edit(Round $round)
    {
        return view('rounds.edit', compact('round'));
    }

    public function update(Request $request, Round $round)
    {
        $round->update($request->all());
        return redirect()->route('rounds.index');
    }

    public function destroy(Round $round)
    {
        $round->delete();
        return redirect()->route('rounds.index');
    }
}
