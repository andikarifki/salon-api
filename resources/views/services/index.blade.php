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

    <!-- Navigasi Pagination -->
    <div class="d-flex justify-content-between">
        <button id="prev-page" class="btn btn-primary" disabled>⏪ Next</button>
        <span id="page-info"></span>
        <button id="next-page" class="btn btn-primary" disabled>Prev ⏩</button>
    </div>
</div>

<script>
let currentPage = 1;
let lastPage = 1;

document.addEventListener("DOMContentLoaded", function () {
    loadServices(currentPage);
});

function loadServices(page = 1) {
    fetch(`http://127.0.0.1:8000/api/services?page=${page}`)
        .then(response => response.json())
        .then(data => {
            let tableBody = document.getElementById("service-list");
            tableBody.innerHTML = "";

            if (data.data.length === 0) {
                tableBody.innerHTML = "<tr><td colspan='4' class='text-center'>Layanan tidak ditemukan</td></tr>";
            }

            data.data.forEach(service => {
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

            // Update pagination
            currentPage = data.current_page;
            lastPage = data.last_page;
            updatePagination();
        })
        .catch(error => console.error("Error fetching services:", error));
}

function updatePagination() {
    document.getElementById("page-info").innerText = `Halaman ${currentPage} dari ${lastPage}`;
    document.getElementById("prev-page").disabled = currentPage === 1;
    document.getElementById("next-page").disabled = currentPage === lastPage;
}

// Event listener untuk tombol pagination
document.getElementById("prev-page").addEventListener("click", function () {
    if (currentPage > 1) {
        loadServices(currentPage - 1);
    }
});

document.getElementById("next-page").addEventListener("click", function () {
    if (currentPage < lastPage) {
        loadServices(currentPage + 1);
    }
});

function deleteService(id) {
    if (!confirm("Apakah Anda yakin ingin menghapus layanan ini?")) return;

    fetch(`http://127.0.0.1:8000/api/services/${id}`, {
        method: "DELETE",
        headers: { "Content-Type": "application/json" }
    })
    .then(response => {
        if (response.ok) {
            alert("Layanan berhasil dihapus!");
            loadServices(currentPage);
        } else {
            alert("Gagal menghapus layanan.");
        }
    })
    .catch(error => console.error("Error deleting service:", error));
}
</script>
@endsection
