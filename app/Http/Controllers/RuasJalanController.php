<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RuasJalanController extends Controller
{
    public function index()
    {
        // $ruasjalans = RuasJalan::all();
        return view('RuasJalan.index');
    }

    public function create()
    {
        return view('RuasJalan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'coordinates' => 'required|array',
            'coordinates.*.lat' => 'required|numeric',
            'coordinates.*.lng' => 'required|numeric',
        ]);

        $ruasjalans = new RuasJalan();
        $ruasjalans->name = $request->name;
        $ruasjalans->coordinates = json_encode($request->coordinates);
        $ruasjalans->save();

        return redirect()->route('RuasJalan.index')->with('success', 'RuasJalan created successfully.');
    }

    public function edit()
    {

        return view('RuasJalan.edit',[
        
        ]);
    }
}
