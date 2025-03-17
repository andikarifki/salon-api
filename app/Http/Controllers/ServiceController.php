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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric'
        ]);

        $service = Service::create($validated);
        return response()->json($service, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
{
    $service = Service::find($id);

    if (!$service) {
        return response()->json(['message' => 'Layanan tidak ditemukan'], 404);
    }

    return response()->json([
        'id' => $service->id,
        'name' => $service->name,
        'description' => $service->description,
        'price' => $service->price,
    ]);
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
        // Cari service berdasarkan ID
        $service = Service::find($id);
    
        // Jika tidak ditemukan, return error
        if (!$service) {
            return response()->json(['message' => 'Service not found'], 404);
        }
    
        // Validasi request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
        ]);
    
        // Update data service
        $service->update($validatedData);
    
        return response()->json($service);
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
