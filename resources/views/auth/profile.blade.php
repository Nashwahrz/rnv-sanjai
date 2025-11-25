@extends('layouts.main')

@section('title', 'Profil Saya')

@section('content')

{{-- Leaflet CSS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            {{-- Header --}}
            <div class="text-center mb-5">
                <div class="avatar-circle mb-3">
                    <i class="bi bi-person-circle"></i>
                </div>
                <h2 class="fw-bold mb-2">Profil Saya</h2>
                <p class="text-muted">Kelola informasi profil Anda dengan mudah</p>
            </div>

            {{-- Alerts --}}
            @if (session('success'))
                <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form Card --}}
            <div class="card profile-card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">

                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf

                        {{-- Info Pribadi Section --}}
                        <div class="section-header mb-4">
                            <h5 class="fw-bold text-orange mb-1"><i class="bi bi-person-fill me-2"></i>Informasi Pribadi</h5>
                            <div class="divider"></div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control custom-input"
                                       value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" name="email" class="form-control custom-input"
                                       value="{{ old('email', $user->email) }}" required>
                            </div>
                        </div>

                        {{-- Wilayah Section --}}
                        <div class="section-header mb-4 mt-5">
                            <h5 class="fw-bold text-orange mb-1"><i class="bi bi-geo-alt-fill me-2"></i>Wilayah</h5>
                            <div class="divider"></div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Provinsi</label>
                                <select id="provinsi" name="provinsi_id" class="form-select custom-input">
                                    <option value="">-- Pilih Provinsi --</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Kabupaten/Kota</label>
                                <select id="kabupaten" name="kabupaten_id" class="form-select custom-input">
                                    <option value="">-- Pilih Kabupaten --</option>
                                </select>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Kecamatan</label>
                                <select id="kecamatan" name="kecamatan_id" class="form-select custom-input">
                                    <option value="">-- Pilih Kecamatan --</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Desa/Kelurahan</label>
                                <input type="text" name="desa_kelurahan" id="desa_kelurahan" class="form-control custom-input"
                                       value="{{ old('desa_kelurahan', $user->desa_kelurahan ?? '') }}"
                                       placeholder="RT 05/RW 02, Jalan Anggrek No. 10">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Alamat Lengkap</label>
                            <textarea name="alamat" id="alamat" class="form-control custom-input" rows="2">{{ old('alamat', $user->alamat) }}</textarea>
                            <small class="text-muted">Alamat akan terisi otomatis berdasarkan pilihan wilayah dan lokasi peta</small>
                        </div>

                        {{-- Map Section --}}
                        <div class="section-header mb-3 mt-5">
                            <h5 class="fw-bold text-orange mb-1"><i class="bi bi-map-fill me-2"></i>Lokasi pada Peta</h5>
                            <div class="divider"></div>
                        </div>

                        <button type="button" id="get-location-btn" class="btn btn-outline-orange btn-sm w-100 mb-3" onclick="getLocation()">
                            <i class="bi bi-geo-alt-fill me-2"></i> Gunakan Lokasi Saya Sekarang
                        </button>

                        <div id="map" class="map-container mb-4"></div>

                        <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', $user->latitude ?? '-0.9471') }}">
                        <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', $user->longitude ?? '100.4172') }}">

                        {{-- Kontak Section --}}
                        <div class="section-header mb-4 mt-5">
                            <h5 class="fw-bold text-orange mb-1"><i class="bi bi-telephone-fill me-2"></i>Kontak</h5>
                            <div class="divider"></div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Nomor HP</label>
                            <input type="text" name="no_hp" class="form-control custom-input"
                                   value="{{ old('no_hp', $user->no_hp) }}" placeholder="08xxxxxxxxxx">
                        </div>

                        <button type="submit" class="btn btn-orange w-100 mt-4">
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
    VARIABLE GLOBAL
======================= */
var lat = '{{ $user->latitude ?? "-0.9471" }}';
var lng = '{{ $user->longitude ?? "100.4172" }}';
var map;
var marker;

/* =======================
    FUNGSI UTILITY MAP
======================= */

function updateAddressText(lat, lon) {
    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}&zoom=18&addressdetails=1`)
        .then(r => r.json())
        .then(d => {
            if (d.display_name) {
                document.getElementById("alamat").value = d.display_name;
            }
        })
        .catch(error => console.error("Gagal mendapatkan alamat dari koordinat:", error));
}

function updateMapLocation(fullAddress, zoomLevel) {
    if (!map) {
        initMap();
    }

    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${fullAddress}`)
        .then(res => res.json())
        .then(data => {
            if (data.length > 0) {
                let lt = parseFloat(data[0].lat);
                let ln = parseFloat(data[0].lon);

                map.setView([lt, ln], zoomLevel);
                marker.setLatLng([lt, ln]);

                document.getElementById("latitude").value = lt;
                document.getElementById("longitude").value = ln;

                updateAddressText(lt, ln);

            } else {
                console.warn(`Lokasi tidak ditemukan untuk: ${fullAddress}`);
            }
        })
        .catch(error => console.error("Error fetching geocoding data:", error));
}

function onLocationFound(e) {
    const newLat = e.coords.latitude;
    const newLng = e.coords.longitude;
    const newLatLng = L.latLng(newLat, newLng);

    map.setView(newLatLng, 17);
    marker.setLatLng(newLatLng);

    document.getElementById("latitude").value = newLat;
    document.getElementById("longitude").value = newLng;

    updateAddressText(newLat, newLng);

    let btn = document.getElementById("get-location-btn");
    btn.innerHTML = `<i class="bi bi-geo-alt-fill me-2"></i> Gunakan Lokasi Saya Sekarang`;
    btn.disabled = false;

    alert(`Lokasi Anda berhasil ditemukan!\nKoordinat: ${newLat.toFixed(4)}, ${newLng.toFixed(4)}`);
}

function onLocationError(error) {
    let btn = document.getElementById("get-location-btn");
    btn.innerHTML = `<i class="bi bi-geo-alt-fill me-2"></i> Gunakan Lokasi Saya Sekarang`;
    btn.disabled = false;

    let errorMessage = "Gagal mengambil lokasi Anda. Pastikan GPS/Lokasi diaktifkan dan Anda memberikan izin pada browser.";
    if (error.code === 1) {
        errorMessage = "Anda menolak permintaan Geolocation. Izinkan akses lokasi di pengaturan browser Anda.";
    }

    console.error("Geolocation error:", error);
    alert(errorMessage);
}

function getLocation() {
    let btn = document.getElementById("get-location-btn");
    btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Mencari Lokasi...`;
    btn.disabled = true;

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(onLocationFound, onLocationError, {
            enableHighAccuracy: true,
            timeout: 5000,
            maximumAge: 0
        });
    } else {
        alert("Geolocation tidak didukung oleh browser ini.");
        btn.innerHTML = `<i class="bi bi-geo-alt-fill me-2"></i> Gunakan Lokasi Saya Sekarang`;
        btn.disabled = false;
    }
}

/* =======================
    INISIALISASI PETA
======================= */

function initMap() {
    if (map) {
        map.remove();
    }

    map = L.map('map').setView([parseFloat(lat), parseFloat(lng)], 13);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    marker = L.marker([parseFloat(lat), parseFloat(lng)], { draggable: true }).addTo(map);

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

    map.invalidateSize();

    updateAddressText(lat, lng);
}

document.addEventListener("DOMContentLoaded", function() {
    initMap();
});

/* =======================
    LOGIKA DROPDOWN WILAYAH
======================= */

fetch("https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json")
    .then(res => res.json())
    .then(data => {
        let prov = document.getElementById("provinsi");
        data.forEach(item => {
            let isSelected = '{{ old('provinsi_id', $user->provinsi_id) }}' == item.id ? 'selected' : '';
            prov.innerHTML += `<option value="${item.id}" data-name="${item.name}" ${isSelected}>${item.name}</option>`;
        });
        if ('{{ old('provinsi_id', $user->provinsi_id) }}') {
             document.getElementById('provinsi').dispatchEvent(new Event('change'));
        }
    })
    .catch(error => console.error("Gagal memuat Provinsi:", error));

document.getElementById("provinsi").addEventListener("change", function () {
    let id = this.value;
    let provName = this.options[this.selectedIndex]?.dataset.name || '';
    let kab = document.getElementById("kabupaten");
    let kec = document.getElementById("kecamatan");
    let inputDesa = document.getElementById("desa_kelurahan");

    kab.innerHTML = `<option value="">-- Pilih Kabupaten --</option>`;
    kec.innerHTML = `<option value="">-- Pilih Kecamatan --</option>`;
    inputDesa.value = "";

    if (!id) {
        document.getElementById("alamat").value = "";
        map.setView([parseFloat(lat), parseFloat(lng)], 5);
        return;
    }

    updateMapLocation(provName, 8);

    fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${id}.json`)
        .then(res => res.json())
        .then(data => {
            let oldKabId = '{{ old('kabupaten_id', $user->kabupaten_id) }}';
            data.forEach(item => {
                let isSelected = oldKabId == item.id ? 'selected' : '';
                kab.innerHTML += `<option value="${item.id}" data-name="${item.name}" ${isSelected}>${item.name}</option>`;
            });
            if (oldKabId && !this.dataset.triggered) {
                document.getElementById('kabupaten').dispatchEvent(new Event('change'));
            }
        });

    document.getElementById("alamat").value = provName;
    this.dataset.triggered = true;
});

document.getElementById("kabupaten").addEventListener("change", function () {
    let id = this.value;
    let kabName = this.options[this.selectedIndex]?.dataset.name || '';
    let provName = document.querySelector("#provinsi option:checked")?.dataset.name || '';
    let kec = document.getElementById("kecamatan");
    let inputDesa = document.getElementById("desa_kelurahan");

    kec.innerHTML = `<option value="">-- Pilih Kecamatan --</option>`;
    inputDesa.value = "";

    if (!id) {
        document.getElementById("alamat").value = provName;
        updateMapLocation(provName, 8);
        return;
    }

    let fullAddress = `${kabName}, ${provName}`;

    updateMapLocation(fullAddress, 11);

    fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${id}.json`)
        .then(res => res.json())
        .then(data => {
            let oldKecId = '{{ old('kecamatan_id', $user->kecamatan_id) }}';
            data.forEach(item => {
                let isSelected = oldKecId == item.id ? 'selected' : '';
                kec.innerHTML += `<option value="${item.id}" data-name="${item.name}" ${isSelected}>${item.name}</option>`;
            });

            if (oldKecId && !this.dataset.triggered) {
                document.getElementById('kecamatan').dispatchEvent(new Event('change'));
            }
        });

    document.getElementById("alamat").value = fullAddress;
    this.dataset.triggered = true;
});

document.getElementById("kecamatan").addEventListener("change", function () {
    let kecName = this.options[this.selectedIndex]?.dataset.name || '';
    let kabName = document.querySelector("#kabupaten option:checked")?.dataset.name || '';
    let provName = document.querySelector("#provinsi option:checked")?.dataset.name || '';
    let inputDesa = document.getElementById("desa_kelurahan");

    if (!this.dataset.triggered) {
         inputDesa.value = '{{ old('desa_kelurahan', $user->desa_kelurahan) }}';
    }

    if (!this.value) {
        let fullAddress = `${kabName}, ${provName}`;
        document.getElementById("alamat").value = fullAddress;
        updateMapLocation(fullAddress, 11);
        return;
    }

    let desaKelurahan = inputDesa.value ? inputDesa.value + ', ' : '';
    let fullAddress = `${desaKelurahan}${kecName}, ${kabName}, ${provName}`;
    document.getElementById("alamat").value = fullAddress;

    updateMapLocation(fullAddress, 14);
    this.dataset.triggered = true;
});

document.getElementById("desa_kelurahan").addEventListener("input", function() {
    let kecElement = document.querySelector("#kecamatan option:checked");

    if (kecElement && kecElement.value) {
        let kecName = kecElement.dataset.name;
        let kabName = document.querySelector("#kabupaten option:checked").dataset.name;
        let provName = document.querySelector("#provinsi option:checked").dataset.name;

        let desaKelurahan = this.value ? this.value + ', ' : '';

        let fullAddress = `${desaKelurahan}${kecName}, ${kabName}, ${provName}`;
        document.getElementById("alamat").value = fullAddress;

        if (this.value.length > 3) {
            updateMapLocation(fullAddress, 15);
        }
    } else {
        document.getElementById("alamat").value = this.value;
    }
});
</script>

<style>
.text-orange { color: #ff6b35; }

.avatar-circle {
    width: 100px; height: 100px; margin: 0 auto;
    background: linear-gradient(135deg, #ff6b35, #ff8c5a);
    border-radius: 50%; display: flex; align-items: center; justify-content: center;
    box-shadow: 0 10px 30px rgba(255,107,53,0.25);
}
.avatar-circle i { font-size: 3.5rem; color: white; }

.profile-card {
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.3s ease;
}
.profile-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.1) !important;
}

.section-header h5 { margin: 0; }
.divider {
    height: 3px;
    width: 50px;
    background: linear-gradient(90deg, #ff6b35, #ff8c5a);
    border-radius: 2px;
    margin-top: 8px;
}

.custom-input {
    border: 2px solid #e9ecef;
    padding: 12px 16px;
    border-radius: 10px;
    transition: all 0.3s;
    font-size: 0.95rem;
}
.custom-input:focus {
    border-color: #ff6b35;
    box-shadow: 0 0 0 0.25rem rgba(255,107,53,0.1);
    background-color: #fff;
}

.map-container {
    height: 350px;
    border-radius: 12px;
    overflow: hidden;
    border: 2px solid #e9ecef;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

.btn-orange {
    background: linear-gradient(135deg, #ff6b35, #ff8c5a);
    border: none;
    color: white;
    padding: 14px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(255,107,53,0.3);
}
.btn-orange:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255,107,53,0.4);
    background: linear-gradient(135deg, #ff8c5a, #ff6b35);
}

.btn-outline-orange {
    border: 2px solid #ff6b35;
    color: #ff6b35;
    background: white;
    padding: 10px;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s;
}
.btn-outline-orange:hover {
    background: #ff6b35;
    color: white;
    transform: translateY(-1px);
}

.alert {
    border-radius: 10px;
    padding: 14px 18px;
}

@media (max-width: 768px) {
    .avatar-circle { width: 80px; height: 80px; }
    .avatar-circle i { font-size: 2.8rem; }
    .map-container { height: 280px; }
}
</style>

@endsection
