@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mt-3">Tambah Layanan Baru</h2>

    <form id="service-form" enctype="multipart/form-data">
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
            <input type="file" class="form-control" id="image" accept="image/*" required>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>

<script>
document.getElementById("service-form").addEventListener("submit", function(event) {
    event.preventDefault();

    let formData = new FormData();
    formData.append("name", document.getElementById("name").value);
    formData.append("description", document.getElementById("description").value);
    formData.append("price", document.getElementById("price").value);
    formData.append("image", document.getElementById("image").files[0]); // Ambil file gambar

    fetch("http://127.0.0.1:8000/api/services", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert("Layanan berhasil ditambahkan!");
        window.location.href = "/services"; // Redirect ke halaman daftar layanan
    })
    .catch(error => {
        console.error("Error:", error);
        alert("Gagal menambahkan layanan!");
    });
});
</script>
@endsection
