<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
       /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'images';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'path',
        'alt_text',
        'caption',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        // Anda bisa menambahkan atribut yang tidak ingin ditampilkan saat model diubah menjadi JSON atau array
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        // Anda bisa menambahkan casting tipe data di sini, misalnya 'id' => 'integer'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    // Anda bisa menambahkan relasi (relationships) dengan model lain di sini
    // Contoh: jika sebuah gambar dimiliki oleh seorang user
    /*
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    */
}
