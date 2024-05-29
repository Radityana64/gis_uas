<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <style>
        #map {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        .custom-card {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
            margin-bottom: 10px;
        }

        .custom-card-header {
            padding: 10px;
            border-radius: 10px 10px 0px 0px;
            background-color: #f8f9fa;
            border-bottom: 1px solid #e2e8f0;
        }

        .custom-card-body {
            padding: 10px;
        }

        .form-container {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 300px;
            z-index: 10;
        }
    </style>
</head>
<body class="bg-gray-200">
<div id="map"></div> <!-- Move map outside and above other elements -->

<div class="form-container"> <!-- Add relative positioning and z-index to the container -->
    <div class="custom-card">
        <div class="custom-card-header">
            <h1 class="text-xl font-bold text-gray-900">Tambah Data Jalan</h1>
        </div>
        
        <div class="custom-card-body">
            <form enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <div class="form-control">
                        <label class="label" for="province">
                            <span class="label-text text-gray-700"><b>Pilih Provinsi</b></span>
                        </label>
                        <select class="select select-bordered w-full border-gray-300 rounded-lg shadow-sm" id="province" name="province" required>
                            <option value="">Pilih Provinsi</option>
                        </select>
                    </div>
                    
                    <div class="form-control">
                        <label class="label" for="kabupaten">
                            <span class="label-text text-gray-700"><b>Pilih Kabupaten</b></span>
                        </label>
                        <select class="select select-bordered w-full border-gray-300 rounded-lg shadow-sm" id="kabupaten" name="kabupaten" required>
                            <option value="">Pilih Kabupaten</option>
                        </select>
                    </div>
                    
                    <div class="form-control">
                        <label class="label" for="kecamatan">
                            <span class="label-text text-gray-700"><b>Pilih Kecamatan</b></span>
                        </label>
                        <select class="select select-bordered w-full border-gray-300 rounded-lg shadow-sm" id="kecamatan" name="kecamatan" required>
                            <option value="">Pilih Kecamatan</option>
                        </select>
                    </div>
                    
                    <div class="form-control">
                        <label class="label" for="desa">
                            <span class="label-text text-gray-700"><b>Pilih Desa</b></span>
                        </label>
                        <select class="select select-bordered w-full border-gray-300 rounded-lg shadow-sm" id="desa" name="desa" required>
                            <option value="">Pilih Desa</option>
                        </select>
                    </div>
                    
                    <div class="form-control">
                        <label class="label" for="nama_ruas">
                            <span class="label-text text-gray-700"><b>Nama Ruas</b></span>
                        </label>
                        <input type="text" class="input input-bordered w-full border-gray-300 rounded-lg shadow-sm" id="nama_ruas" name="nama_ruas" required />
                    </div>

                    <div class="form-control">
                        <label class="label" for="lebar">
                            <span class="label-text text-gray-700"><b>Lebar Ruas</b></span>
                        </label>
                        <input type="text" class="input input-bordered w-full border-gray-300 rounded-lg shadow-sm" id="lebar" name="lebar" required />
                    </div>

                    <div class="form-control">
                        <label class="label" for="kode_ruas">
                            <span class="label-text text-gray-700"><b>Kode Ruas</b></span>
                        </label>
                        <input type="text" class="input input-bordered w-full border-gray-300 rounded-lg shadow-sm" id="kode_ruas" name="kode_ruas" required />
                    </div>

                    <div class="form-control">
                        <label class="label" for="eksisting">
                            <span class="label-text text-gray-700"><b>Eksisting</b></span>
                        </label>
                        <select class="select select-bordered w-full border-gray-300 rounded-lg shadow-sm" id="eksisting" name="eksisting" required>
                            <option value="">Pilih Material</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label" for="kondisi">
                            <span class="label-text text-gray-700"><b>Kondisi</b></span>
                        </label>
                        <select class="select select-bordered w-full border-gray-300 rounded-lg shadow-sm" id="kondisi" name="kondisi" required>
                            <option value="">Pilih Kondisi</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label" for="jenis_jalan">
                            <span class="label-text text-gray-700"><b>Jenis Jalan</b></span>
                        </label>
                        <select class="select select-bordered w-full border-gray-300 rounded-lg shadow-sm" id="jenis_jalan" name="jenis_jalan" required>
                            <option value="">Pilih Jenis</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label" for="keterangan">
                            <span class="label-text text-gray-700"><b>Keterangan</b></span>
                        </label>
                        <input type="text" class="input input-bordered w-full border-gray-300 rounded-lg shadow-sm" id="keterangan" name="keterangan" required />
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-lg">Tambah Jalan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<meta name="api-token" content="{{ session('token') }}">

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        var map = L.map('map').setView([-8.65, 115.22], 10); // Contoh koordinat untuk Bali
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const token = document.querySelector('meta[name="api-token"]').getAttribute('content');

        const provinceSelect = document.getElementById('province');
        const kabupatenSelect = document.getElementById('kabupaten');
        const kecamatanSelect = document.getElementById('kecamatan');
        const desaSelect = document.getElementById('desa');

        fetch('https://gisapis.manpits.xyz/api/mregion', {
            headers: {
                Authorization: `Bearer ${token}`,
            }
        })
        .then(response => response.json())
        .then(data => {
            provinceSelect.innerHTML = '<option value="">Pilih Provinsi</option>';
            data.provinsi.forEach(province => {
                const option = document.createElement('option');
                option.value = province.id;
                option.textContent = province.provinsi;
                provinceSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error fetching provinces:', error);
        });

        provinceSelect.addEventListener('change', function() {
            const provinceId = this.value;
            kabupatenSelect.innerHTML = '<option value="">Pilih Kabupaten</option>';
            kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
            desaSelect.innerHTML = '<option value="">Pilih Desa</option>';
            
            if (provinceId) {
                fetch(`https://gisapis.manpits.xyz/api/kabupaten/${provinceId}`, {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    }
                })
                .then(response => response.json())
                .then(data => {
                    kabupatenSelect.innerHTML = '<option value="">Pilih Kabupaten</option>';
                    data.kabupaten.forEach(kabupaten => {
                        const option = document.createElement('option');
                        option.value = kabupaten.id;
                        option.textContent = kabupaten.value;
                        kabupatenSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching kabupaten:', error);
                });
            }
        });

        kabupatenSelect.addEventListener('change', function() {
            const kabupatenId = this.value;
            kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
            desaSelect.innerHTML = '<option value="">Pilih Desa</option>';

            if (kabupatenId) {
                fetch(`https://gisapis.manpits.xyz/api/kecamatan/${kabupatenId}`, {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    }
                })
                .then(response => response.json())
                .then(data => {
                    kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                    data.kecamatan.forEach(kecamatan => {
                        const option = document.createElement('option');
                        option.value = kecamatan.id;
                        option.textContent = kecamatan.value;
                        kecamatanSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching kecamatan:', error);
                });
            }
        });

        kecamatanSelect.addEventListener('change', function() {
            const kecamatanId = this.value;
            desaSelect.innerHTML = '<option value="">Pilih Desa</option>';

            if (kecamatanId) {
                fetch(`https://gisapis.manpits.xyz/api/desa/${kecamatanId}`, {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    }
                })
                .then(response => response.json())
                .then(data => {
                    desaSelect.innerHTML = '<option value="">Pilih Desa</option>';
                    data.desa.forEach(desa => {
                        const option = document.createElement('option');
                        option.value = desa.id;
                        option.textContent = desa.value;
                        desaSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching desa:', error);
                });
            }
        });
    });
</script>
</body>
</html>
