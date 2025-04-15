<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Eager load relasi 'typeService'
        $services = Service::with('typeService')->get();
        return response()->json($services); // Kembalikan hasil eager loading   
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi gambar
            'id_type' => 'required|integer|exists:type_services,id', // Validasi id_type
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {
            // Kode yang dijalankan jika validasi berhasil
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('services', 'public'); // Simpan ke storage/public/services
            }

            $service = Service::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'image' => $imagePath,
                'id_type' => $request->id_type, // Simpan id_type
            ]);

            return response()->json($service, 201); // Return 201 Created status code
        }
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

        // Eager load relasi 'typeService'
        $service = Service::with('typeService')->findOrFail($id);

        return response()->json([
            'id' => $service->id,
            'name' => $service->name,
            'description' => $service->description,
            'price' => $service->price,
            'image' => $service->image ? asset('storage/' . $service->image) : null,
            'id_type' => $service->id_type, // Tambahkan id_type di sini
            'type_service' => [ // Sertakan informasi type_service
                'id' => $service->typeService->id,
                'name' => $service->typeService->name,
                // Anda bisa menambahkan properti lain dari typeService jika diperlukan
            ],
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
        $service = Service::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'nullable|image|max:2048', // Pastikan ini sesuai dengan yang dikirim dari frontend
            'id_type' => 'required|integer|exists:type_services,id', // Tambahkan validasi untuk id_type
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('services', 'public');
            $validated['image'] = $path;
        }

        $service->update($validated);
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
        $services = Service::with('typeService')
            ->where('name', 'LIKE', "%$query%")
            ->get();

        if ($services->isEmpty()) {
            return response()->json(['message' => 'No services found'], 404);
        }

        return response()->json($services);
    }

}
