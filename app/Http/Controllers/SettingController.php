<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSettingRequest;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    protected $resource = 'settings';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view("{$this->resource}.index");
    }

    public function useDatatables()
    {
        return (new Setting)->datatables();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSettingRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Setting $setting)
    {
        //
        $settings = $setting;

        return view("{$this->resource}.edit", compact('settings'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Setting $setting)
    {
        $request->validate([
            'configKey' => 'required|string|max:255',
            'configValue' => 'required|string|max:255',
            'name' => 'nullable|string|max:255',
            'path' => 'nullable|string|max:255',
        ]);
        $setting->update($request->only(['configKey', 'configValue', 'name', 'path']));

        return redirect()->route("{$this->resource}.index")->with('success', 'Setting updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Setting $setting)
    {
        //
    }
}
