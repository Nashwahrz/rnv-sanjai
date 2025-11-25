@extends('layouts.main')

@section('title', 'Profil Saya')

@section('content')

{{-- Leaflet CSS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7">

            {{-- Header --}}
            <div class="text-center mb-4">
                <div class="avatar-circle mb-3">
                    <i class="bi bi-person-circle"></i>
                </div>
                <h3 class="fw-bold mb-1">Profil Saya</h3>
                <p class="text-muted">Kelola informasi profil Anda</p>
            </div>

            {{-- Alerts --}}
            @if (session('success'))
                <div class="alert alert-success border-0 shadow-sm">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger border-0 shadow-sm">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form --}}
            <div class="card border-0 shadow-sm profile-card">
                <div class="card-body p-4">

                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Nama --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control custom-input"
                                   value="{{ old('name', $user->name) }}" required>
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control custom-input"
                                   value="{{ old('email', $user->email) }}" required>
                        </div>

                        {{-- Provinsi --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Provinsi</label>
                            <select id="provinsi" class="form-select custom-input" required>
                                <option value="">-- Pilih Provinsi --</option>
                            </select>
                        </div>

                        {{-- Kabupaten --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kabupaten/Kota</label>
                            <select id="kabupaten" class="form-select custom-input" required>
                                <option value="">-- Pilih Kabupaten --</option>
                            </select>
                        </div>

                        {{-- Kecamatan --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kecamatan</label>
                            <select id="kecamatan" class="form-select custom-input" required>
                                <option value="">-- Pilih Kecamatan --</option>
                            </select>
                        </div>

                        {{-- Alamat Lengkap --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Alamat Lengkap</label>
                            <textarea name="alamat" id="alamat" class="form-control custom-input" rows="3">{{ old('alamat', $user->alamat) }}</textarea>
                        </div>

                        {{-- MAP --}}
                        <label class="form-label fw-semibold">
                            Pilih Lokasi pada Map
                        </label>

                        <div id="map" style="height: 300px; border-radius: 10px;" class="mb-3"></div>

                        {{-- Hidden latitude / longitude --}}
                        <input type="hidden" id="latitude" name="latitude"
                               value="{{ old('latitude', $user->latitude ?? -0.9471) }}">
                        <input type="hidden" id="longitude" name="longitude"
                               value="{{ old('longitude', $user->longitude ?? 100.4172) }}">

                        {{-- No HP --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">No HP</label>
                            <input type="text" name="no_hp" class="form-control custom-input"
                                   value="{{ old('no_hp', $user->no_hp) }}">
                        </div>

                        <button type="submit" class="btn btn-orange w-100">
                            <i class="bi bi-save me-2"></i> Simpan Perubahan
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

{{-- Leaflet JS --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
/* =======================
   MAP SETUP
======================= */
var lat = {{ $user->latitude ?? '-0.9471' }};
var lng = {{ $user->longitude ?? '100.4172' }};

var map = L.map('map').setView([lat, lng], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19
}).addTo(map);

var marker = L.marker([lat, lng], { draggable: true }).addTo(map);

function updateAddressText(lat, lon) {
    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`)
        .then(r => r.json())
        .then(d => {
            if (d.display_name) {
                document.getElementById("alamat").value = d.display_name;
            }
        });
}

marker.on("dragend", function() {
    var pos = marker.getLatLng();
    document.getElementById("latitude").value = pos.lat;
    document.getElementById("longitude").value = pos.lng;
    updateAddressText(pos.lat, pos.lng);
});

map.on("click", function(e) {
    marker.setLatLng(e.latlng);
    document.getElementById("latitude").value = e.latlng.lat;
    document.getElementById("longitude").value = e.latlng.lng;
    updateAddressText(e.latlng.lat, e.latlng.lng);
});

/* =======================
   LOADING WILAYAH API
======================= */

// 1. LOAD PROVINSI
fetch("https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json")
    .then(res => res.json())
    .then(data => {
        let prov = document.getElementById("provinsi");
        data.forEach(item => {
            prov.innerHTML += `<option value="${item.id}" data-name="${item.name}">${item.name}</option>`;
        });
    });

// 2. LOAD KABUPATEN
document.getElementById("provinsi").addEventListener("change", function () {
    let id = this.value;
    let provName = this.options[this.selectedIndex].dataset.name;

    fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${id}.json`)
        .then(res => res.json())
        .then(data => {
            let kab = document.getElementById("kabupaten");
            kab.innerHTML = `<option value="">-- Pilih Kabupaten --</option>`;

            data.forEach(item => {
                kab.innerHTML += `<option value="${item.id}" data-name="${item.name}">${item.name}</option>`;
            });
        });

    document.getElementById("alamat").value = provName;
});

// 3. LOAD KECAMATAN
document.getElementById("kabupaten").addEventListener("change", function () {
    let kabName = this.options[this.selectedIndex].dataset.name;
    let provName = document.querySelector("#provinsi option:checked").dataset.name;

    fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${this.value}.json`)
        .then(res => res.json())
        .then(data => {
            let kec = document.getElementById("kecamatan");
            kec.innerHTML = `<option value="">-- Pilih Kecamatan --</option>`;

            data.forEach(item => {
                kec.innerHTML += `<option value="${item.id}" data-name="${item.name}">${item.name}</option>`;
            });
        });

    document.getElementById("alamat").value = `${kabName}, ${provName}`;
});

// 4. KETIKA PILIH KECAMATAN → MAP ZOOM
document.getElementById("kecamatan").addEventListener("change", function () {

    let kecName = this.options[this.selectedIndex].dataset.name;
    let kabName = document.querySelector("#kabupaten option:checked").dataset.name;
    let provName = document.querySelector("#provinsi option:checked").dataset.name;

    let fullAddress = `${kecName}, ${kabName}, ${provName}`;
    document.getElementById("alamat").value = fullAddress;

    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${fullAddress}`)
        .then(res => res.json())
        .then(data => {
            if (data.length > 0) {
                let lt = parseFloat(data[0].lat);
                let ln = parseFloat(data[0].lon);

                map.setView([lt, ln], 14);
                marker.setLatLng([lt, ln]);

                document.getElementById("latitude").value = lt;
                document.getElementById("longitude").value = ln;
            }
        });
});
</script>

<style>
.text-orange { color: #ff6b35 !important; }

.avatar-circle {
    width: 90px; height: 90px; margin: 0 auto;
    background: linear-gradient(135deg, #ff6b35, #e85a2a);
    border-radius: 50%; display: flex; align-items: center; justify-content: center;
    box-shadow: 0 8px 20px rgba(255,107,53,0.3);
}
.avatar-circle i { font-size: 3rem; color: white; }

.profile-card { border-left: 4px solid #ff6b35 !important; transition: 0.3s; }
.profile-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 30px rgba(255,107,53,0.15) !important;
}

.custom-input {
    border: 2px solid #e9ecef; padding: 10px 14px;
    border-radius: 8px; transition: all 0.3s;
}
.custom-input:focus {
    border-color: #ff6b35;
    box-shadow: 0 0 0 0.2rem rgba(255,107,53,0.15);
}

.btn-orange {
    background: linear-gradient(135deg, #ff6b35, #e85a2a);
    border: none; color: white; padding: 12px;
    border-radius: 8px; font-weight: 600; transition: 0.3s;
}
.btn-orange:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(255,107,53,0.3);
}
</style>

@endsection
