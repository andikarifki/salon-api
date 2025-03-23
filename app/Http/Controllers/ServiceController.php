<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Service::all());
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
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);
    
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('services', 'public');
        }
    
        $service = Service::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $imagePath
        ]);
    
        return response()->json($service, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
{
    $service = Service::find($id);

    if (!$service) {
        return response()->json(['message' => 'Service not found'], 404);
    }

    return response()->json($service);
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $service = Service::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric',
        'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
    ]);

    if ($request->hasFile('image')) {
        if ($service->image) {
            Storage::disk('public')->delete($service->image);
        }
        $imagePath = $request->file('image')->store('services', 'public');
        $service->image = $imagePath;
    }

    $service->update($request->except('image') + ['image' => $service->image]);

    return response()->json($service, 200);
}

    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $service = Service::find($id);

        if (!$service) {
            return response()->json(['message' => 'Service not found'], 404);
        }
    
        $service->delete();
    
        return response()->json(['message' => 'Service deleted successfully']);
    }
    
    public function search($query)
{
    $services = Service::where('name', 'LIKE', "%$query%")->get();

    if ($services->isEmpty()) {
        return response()->json(['message' => 'No services found'], 404);
    }

    return response()->json($services);
}

}
