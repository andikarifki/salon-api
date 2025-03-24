@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mt-3">Edit Layanan</h2>

    <form id="edit-service-form" enctype="multipart/form-data">
        <input type="hidden" id="service-id"> <!-- Menyimpan ID layanan -->

        <div class="mb-3">
            <label for="name" class="form-label">Nama Layanan</label>
            <input type="text" class="form-control" id="name" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="description" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Harga</label>
            <input type="number" class="form-control" id="price" required>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Gambar Layanan</label>
            <input type="file" class="form-control" id="image" accept="image/*">
            <img id="preview-image" src="" alt="Preview Gambar" class="img-thumbnail mt-2" style="max-width: 200px;">
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="/services" class="btn btn-secondary">Batal</a>
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Ambil ID dari URL
    const urlParams = new URLSearchParams(window.location.search);
    const serviceId = urlParams.get('id');

    if (!serviceId) {
        alert("ID layanan tidak ditemukan!");
        window.location.href = "/services"; // Redirect ke daftar layanan jika ID tidak ada
    }

    console.log("Mengambil data untuk ID:", serviceId);

    // Fetch data layanan berdasarkan ID
    fetch(`http://127.0.0.1:8000/api/services/${serviceId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log("Data dari API:", data);

            if (data.id) {
                document.getElementById("service-id").value = data.id;
                document.getElementById("name").value = data.name || '';
                document.getElementById("description").value = data.description || '';
                document.getElementById("price").value = data.price || 0;

                // Menampilkan gambar lama jika ada
                if (data.image) {
                    document.getElementById("preview-image").src = `http://127.0.0.1:8000/storage/${data.image}`;
                }
            } else {
                alert("Data layanan tidak ditemukan!");
                window.location.href = "/services";
            }
        })
        .catch(error => {
            console.error("Error fetching service:", error);
            alert("Gagal memuat data layanan!");
        });

    // Preview gambar saat memilih file
    document.getElementById("image").addEventListener("change", function (event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById("preview-image").src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // Submit Form (Update Data)
    document.getElementById("edit-service-form").addEventListener("submit", function(event) {
        event.preventDefault();

        let formData = new FormData();
        formData.append("_method", "PUT"); // Laravel butuh _method PUT
        formData.append("name", document.getElementById("name").value);
        formData.append("description", document.getElementById("description").value);
        formData.append("price", document.getElementById("price").value);

        let imageFile = document.getElementById("image").files[0];
        if (imageFile) {
            formData.append("image", imageFile);
        }

        fetch(`http://127.0.0.1:8000/api/services/${serviceId}`, {
            method: "POST", // Gunakan POST dengan _method=PUT
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log("Update sukses:", data);
            alert("Layanan berhasil diperbarui!");
            window.location.href = "/services"; // Redirect kembali ke daftar layanan
        })
        .catch(error => {
            console.error("Error:", error);
            alert("Gagal mengupdate layanan!");
        });
    });
});
</script>
@endsection
