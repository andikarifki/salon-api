@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mt-3">Daftar Layanan Salon</h2>
    
    <div class="mb-3">
        <input type="text" id="search-input" class="form-control" placeholder="Cari layanan..." onkeyup="searchService()">
    </div>
    
    <a href="/services/create" class="btn btn-success mb-3">Tambah Layanan</a>
    
    <table class="table mt-4">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="service-list">
            <!-- Data akan dimuat dengan JavaScript -->
        </tbody>
    </table>
</div>

<!-- Modal Detail Layanan -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Detail Layanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="service-detail">
                <!-- Data detail akan dimuat di sini -->
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    loadServices();
});

function loadServices() {
    fetch("http://127.0.0.1:8000/api/services")
        .then(response => response.json())
        .then(data => {
            let tableBody = document.getElementById("service-list");
            tableBody.innerHTML = "";

            data.forEach(service => {
                let row = `<tr id="row-${service.id}">
                    <td>${service.name}</td>
                    <td>${service.description || '-'}</td>
                    <td>Rp ${parseFloat(service.price).toLocaleString()}</td>
                    <td>
                        <button class="btn btn-info btn-sm" onclick="viewDetail(${service.id})">Detail</button>
                        <a href="/services/edit?id=${service.id}" class="btn btn-warning btn-sm">Edit</a>
                        <button class="btn btn-danger btn-sm" onclick="deleteService(${service.id})">Delete</button>
                    </td>
                </tr>`;
                tableBody.innerHTML += row;
            });
        })
        .catch(error => console.error("Error fetching services:", error));
}

function viewDetail(id) {
    fetch(`http://127.0.0.1:8000/api/services/${id}`)
        .then(response => response.json())
        .then(service => {
            let detailModalBody = document.getElementById("service-detail");

            let imageUrl = service.image && service.image.startsWith("services/") 
                ? `/storage/${service.image}` 
                : 'https://via.placeholder.com/300?text=No+Image';

            detailModalBody.innerHTML = `
                <div class="text-center">
                    <img src="${imageUrl}" alt="Gambar Layanan" class="img-fluid rounded mb-3" style="max-width: 300px;">
                </div>
                <p><strong>Nama:</strong> ${service.name}</p>
                <p><strong>Deskripsi:</strong> ${service.description || '-'}</p>
                <p><strong>Harga:</strong> Rp ${parseFloat(service.price).toLocaleString()}</p>
            `;

            new bootstrap.Modal(document.getElementById('detailModal')).show();
        })
        .catch(error => console.error("Error fetching service details:", error));
}

function deleteService(id) {
    if (!confirm("Apakah Anda yakin ingin menghapus layanan ini?")) return;

    fetch(`http://127.0.0.1:8000/api/services/${id}`, {
        method: "DELETE",
        headers: { "Content-Type": "application/json" }
    })
    .then(response => {
        if (response.ok) {
            alert("Layanan berhasil dihapus!");
            document.getElementById(`row-${id}`).remove();
        } else {
            alert("Gagal menghapus layanan.");
        }
    })
    .catch(error => console.error("Error deleting service:", error));
}

function searchService() {
    let query = document.getElementById("search-input").value.trim();
    
    if (query === "") {
        loadServices(); // Jika input kosong, tampilkan semua layanan
        return;
    }

    fetch(`http://127.0.0.1:8000/api/services/search/${query}`)
        .then(response => response.json())
        .then(data => {
            let tableBody = document.getElementById("service-list");
            tableBody.innerHTML = "";

            if (Array.isArray(data) && data.length > 0) {
                data.forEach(service => {
                    let row = `<tr>
                        <td>${service.name}</td>
                        <td>${service.description || '-'}</td>
                        <td>Rp ${parseFloat(service.price).toLocaleString()}</td>
                        <td>
                            <button class="btn btn-info btn-sm" onclick="viewDetail(${service.id})">Detail</button>
                            <a href="/services/edit?id=${service.id}" class="btn btn-warning btn-sm">Edit</a>
                            <button class="btn btn-danger btn-sm" onclick="deleteService(${service.id})">Delete</button>
                        </td>
                    </tr>`;
                    tableBody.innerHTML += row;
                });
            } else {
                tableBody.innerHTML = "<tr><td colspan='4' class='text-center'>Layanan tidak ditemukan</td></tr>";
            }
        })
        .catch(error => console.error("Error searching services:", error));
}
</script>
@endsection