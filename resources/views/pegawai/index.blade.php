@extends('layouts.layout')

@section('title', 'Daftar Pegawai')

@section('content')
<div class="container mt-5">
    <div class="row mb-4">
        <div class="col-md-6 d-flex mb-3 mb-md-0">
            <!-- Form Pencarian -->
            <form action="{{ route('pegawai.index') }}" method="GET" class="d-flex w-100">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Pegawai..." class="form-control me-2 shadow-sm">
                <button type="submit" class="btn btn-primary shadow-sm">Cari</button>
            </form>
        </div>
        <div class="col-md-6 d-flex justify-content-md-end justify-content-start">
            <!-- Tombol Tambah Pegawai -->
            <a href="{{ route('pegawai.create') }}" class="btn btn-success shadow-sm fw-bold">+ Tambah Pegawai Baru</a>
        </div>
    </div>

    @if($pegawai->isEmpty())
        <div class="alert alert-warning text-center">Tidak ada pegawai yang ditemukan.</div>
    @else
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @foreach($pegawai as $pegawaiItem)
                <div class="col" id="pegawai-card-{{ $pegawaiItem->id }}">
                    <div class="card shadow-sm h-100 border-0 rounded-lg overflow-hidden">
                        <div class="card-body text-center">
                            <div class="avatar mb-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($pegawaiItem->nama) }}&background=4e73df&color=fff&size=100" class="rounded-circle img-fluid" alt="{{ $pegawaiItem->nama }}">
                            </div>
                            <h5 class="card-title fw-bold text-primary">{{ ucfirst($pegawaiItem->nama) }}</h5>
                            <p class="card-text text-muted">Email: <span class="fw-bold">{{ $pegawaiItem->email }}</span></p>
                            <p class="card-text">Alamat: <span class="fw-bold">{{ $pegawaiItem->alamat }}</span></p>
                            <p class="card-text">Role: <span class="badge bg-secondary">{{ ucfirst($pegawaiItem->role->nama_role) }}</span></p>
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('pegawai.edit', $pegawaiItem->id) }}" class="btn btn-warning btn-sm text-white shadow-sm">Edit</a>
                                <button type="button" class="btn btn-danger btn-sm shadow-sm" onclick="confirmDelete({{ $pegawaiItem->id }})">Hapus</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Fungsi untuk konfirmasi hapus dengan SweetAlert
    function confirmDelete(pegawaiId) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data pegawai ini tidak dapat dikembalikan setelah dihapus.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#e63946',
            cancelButtonColor: '#d6d6d6'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/master/pegawai/${pegawaiId}`, {
                    method: 'DELETE',
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                        "Accept": "application/json"
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Pegawai berhasil dihapus.',
                            icon: 'success',
                            confirmButtonColor: '#4e73df'
                        }).then(() => {
                            document.getElementById(`pegawai-card-${pegawaiId}`).remove(); // Hapus kartu pegawai
                        });
                    } else {
                        Swal.fire({
                            title: 'Gagal!',
                            text: data.message || 'Terjadi kesalahan saat menghapus pegawai.',
                            icon: 'error',
                            confirmButtonColor: '#e63946'
                        });
                    }
                })
                .catch(() => {
                    Swal.fire({
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan pada server.',
                        icon: 'error',
                        confirmButtonColor: '#e63946'
                    });
                });
            }
        });
    }
</script>
@endpush

<style>
    body {
        background: #f9f5f0; /* Latar belakang dengan warna lembut */
    }
    .card {
        transition: transform 0.3s, box-shadow 0.3s; /* Efek transisi untuk kartu */
    }
    .card:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15); /* Efek bayangan pada hover */
    }
    .card-title {
        font-size: 1.5rem;
    }
    .avatar img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border: 4px solid #e9ecef;
    }
    .btn-success {
        background-color: #28a745;
        border: none;
        padding: 0.75rem 1.25rem;
        font-size: 1rem;
    }
    .btn-warning {
        background-color: #f4a261;
        border: none;
    }
    .btn-warning:hover {
        background-color: #e76f51;
    }
    .btn-primary {
        background-color: #4e73df;
        border: none;
    }
    .btn-danger {
        background-color: #e63946;
        border: none;
    }
    .btn-danger:hover {
        background-color: #d62828;
    }
</style>
@endsection
