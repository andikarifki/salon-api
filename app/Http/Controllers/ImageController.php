<?php

namespace App\Http\Controllers; // Namespace yang benar

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * Upload gambar untuk carousel.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadCarousel(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'carousel_images' => 'required|array',
            'carousel_images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Proses upload gambar
        if ($request->hasFile('carousel_images')) {
            $uploadedImages = [];
            foreach ($request->file('carousel_images') as $imageFile) {
                // Simpan gambar ke storage (gunakan disk 'public')
                $path = $imageFile->store('public/carousel');
                $filename = basename($path);

                // Buat record di database
                $image = Image::create([
                    // Simpan path relatif ke storage, Storage::url akan buat url lengkap.
                    'path' => 'carousel/' . $filename,
                    'is_carousel' => true,
                ]);

                $uploadedImages[] = [
                    'id' => $image->id,
                    'url' => Storage::url($path), // Generate URL dari path
                    'path' => $image->path,
                    'is_carousel' => $image->is_carousel,
                    'created_at' => $image->created_at,
                    'updated_at' => $image->updated_at,
                ];
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Gambar carousel berhasil diunggah.',
                'data' => $uploadedImages,
            ], 200);
        }

        // Handle jika tidak ada gambar yang diunggah
        return response()->json([
            'status' => 'error',
            'message' => 'Tidak ada gambar yang diunggah.',
        ], 400);
    }

    /**
     * Mendapatkan semua gambar carousel.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCarouselImages()
    {
        $images = Image::where('is_carousel', true)->get();

        $formattedImages = $images->map(function ($image) {
            return [
                'id' => $image->id,
                'url' => Storage::url($image->path), // Generate URL dari path
                'path' => $image->path,
                'is_carousel' => $image->is_carousel,
                'created_at' => $image->created_at,
                'updated_at' => $image->updated_at,
            ];
        });
        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil mendapatkan gambar carousel',
            'data' => $formattedImages,
        ]);
    }

    /**
     * Menghapus gambar carousel.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteCarouselImage($id)
    {
        $image = Image::find($id);

        if (!$image) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gambar tidak ditemukan.',
            ], 404);
        }

        // Hapus file dari storage
        if (Storage::exists('public/' . $image->path)) { //cek apakah filenya ada
            Storage::delete('public/' . $image->path);
        }

        $image->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Gambar berhasil dihapus.',
        ], 200);
    }
}
