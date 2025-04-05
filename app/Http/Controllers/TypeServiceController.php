<?php

namespace App\Http\Controllers;

use App\Models\TypeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TypeServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $typeServices = TypeService::all();
        return response()->json($typeServices);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:type_services,name',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $typeService = TypeService::create($request->all());
        return response()->json($typeService, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(TypeService $typeService)
    {
        return response()->json($typeService);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TypeService $typeService)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255|unique:type_services,name,' . $typeService->id,
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $typeService->update($request->all());
        return response()->json($typeService);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TypeService $typeService)
    {
        $typeService->delete();
        return response()->json(null, 204); // 204 No Content untuk sukses tanpa body response
    }
}