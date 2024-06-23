<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Ruas Jalan</title>
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

        /* .custom-card {
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
        } */
    </style>
</head>
<body class="bg-gray-200">
<div id="map"></div>

<div class="form-container absolute top-3 right-5 w-full max-w-lg z-10">
    <div class="bg-white rounded-lg shadow-lg p-5">
        <h1 class="text-xl font-bold text-gray-900 mb-6">Edit Data Jalan</h1>
        <form action="{{ route('RuasJalan.update', $ruasjalan['id']) }}" method="POST" enctype="multipart/form-data" id="form" name="form">
            @csrf
            @method('PUT')
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3 mb-6">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="nama_ruas">
                        Nama Ruas
                    </label>
                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="nama_ruas" type="text" name="nama_ruas" value="{{ $ruasjalan['nama_ruas'] ?? '' }}" required>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="province">
                        Pilih Provinsi
                    </label>
                    <select class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="province" name="province" required>
                        <option value="">Pilih Provinsi</option>
                        @foreach($regionData['provinsi'] as $provinsi)
                            <option value="{{ $provinsi['id'] }}" {{ isset($ruasjalan['province_id']) && $ruasjalan['province_id'] == $provinsi['id'] ? 'selected' : '' }}>{{ $provinsi['provinsi'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="kabupaten">
                        Pilih Kabupaten
                    </label>
                    <select class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="kabupaten" name="kabupaten" required>
                        <option value="">Pilih Kabupaten</option>
                        @foreach($regionData['kabupaten'] as $kabupaten)
                            <option value="{{ $kabupaten['id'] }}" {{ isset($ruasjalan['kabupaten_id']) && $ruasjalan['kabupaten_id'] == $kabupaten['id'] ? 'selected' : '' }}>{{ $kabupaten['kabupaten'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="kecamatan">
                        Pilih Kecamatan
                    </label>
                    <select class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="kecamatan" name="kecamatan" required>
                        <option value="">Pilih Kecamatan</option>
                        @foreach($regionData['kecamatan'] as $kecamatan)
                            <option value="{{ $kecamatan['id'] }}" {{ isset($ruasjalan['kecamatan_id']) && $ruasjalan['kecamatan_id'] == $kecamatan['id'] ? 'selected' : '' }}>{{ $kecamatan['kecamatan'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="desa">
                        Pilih Desa
                    </label>
                    <select class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="desa" name="desa" required>
                        <option value="">Pilih Desa</option>
                        @foreach($regionData['desa'] as $desa)
                            <option value="{{ $desa['id'] }}" {{ isset($ruasjalan['desa_id']) && $ruasjalan['desa_id'] == $desa['id'] ? 'selected' : '' }}>{{ $desa['desa'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="kode_ruas">
                        Kode Ruas
                    </label>
                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="kode_ruas" type="text" name="kode_ruas" value="{{ $ruasjalan['kode_ruas'] ?? '' }}" required>
                </div>
                <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="lebar">
                        Lebar Ruas
                    </label>
                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="lebar" type="text" name="lebar" value="{{ $ruasjalan['lebar'] ?? '' }}" required>
                </div>
                <div class="w-full md:w-1/3 px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="latlng">
                        Latlng
                    </label>
                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="latlng" type="text" name="latlng" value="{{ $ruasjalan['paths'] ?? '' }}" required>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/3 px-2">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="eksisting">
                        Eksisting
                    </label>
                    <select class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="eksisting" name="eksisting" required>
                        <option value="">Pilih Material</option>
                        @foreach($eksistingData as $eksisting)
                            <option value="{{ $eksisting['id'] }}" {{ isset($ruasjalan['eksisting_id']) && $ruasjalan['eksisting_id'] == $eksisting['id'] ? 'selected' : '' }}>{{ $eksisting['eksisting'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full md:w-1/3 px-2">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="kondisi">
                        Kondisi
                    </label>
                    <select class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="kondisi" name="kondisi" required>
                        <option value="">Pilih Kondisi</option>
                        @foreach($kondisiData as $kondisi)
                            <option value="{{ $kondisi['id'] }}" {{ isset($ruasjalan['kondisi_id']) && $ruasjalan['kondisi_id'] == $kondisi['id'] ? 'selected' : '' }}>{{ $kondisi['kondisi'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full md:w-1/3 px-2">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="jenis_jalan">
                        Jenis Jalan
                    </label>
                    <select class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="jenis_jalan" name="jenis_jalan" required>
                        <option value="">Pilih Jenis</option>
                        @foreach($jenisjalanData as $jenisjalan)
                            <option value="{{ $jenisjalan['id'] }}" {{ isset($ruasjalan['jenisjalan_id']) && $ruasjalan['jenisjalan_id'] == $jenisjalan['id'] ? 'selected' : '' }}>{{ $jenisjalan['jenisjalan'] }}</option>
                        @endforeach 
                    </select>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full mb-6 px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="keterangan">
                        Keterangan
                    </label>
                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="keterangan" type="text" name="keterangan" value="{{ $ruasjalan['keterangan'] ?? '' }}" required>
                </div>
            </div>
            <div class="flex justify-between">
                <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Simpan
                </button>
                <a class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" href="{{ route('RuasJalan.index') }}">
                    Kembali
                </a>
            </div>
        </form>
    </div>
</div>


<meta name="api-token" content="{{ session('token') }}">

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/leaflet-geometryutil@0.0.2/dist/leaflet.geometryutil.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const urlParams = new URLSearchParams(window.location.search);
    const previousPage = urlParams.get('previous') || 'index'; // Default ke 'index' jika tidak ada

    // Fungsi untuk menentukan URL redirect
    function getRedirectRoute() {
        switch(previousPage) {
            case 'dashboard':
                return "{{ route('dashboard') }}";
            case 'rjindex':
            default:
                return "{{ route('RuasJalan.index') }}";
        }
    }
    // Fungsi untuk menghitung panjang garis polyline
    function calculateLength(latlngs) {
        let length = 0;
        for (let i = 0; i < latlngs.length - 1; i++) {
            length += latlngs[i].distanceTo(latlngs[i + 1]);
        }
        return length;
    }

    var map = L.map('map').setView([-8.65, 115.22], 10);
    const tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 20,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    var drawnItems = new L.FeatureGroup();
    map.addLayer(drawnItems);

    var drawControl = new L.Control.Draw({
        edit: {
            featureGroup: drawnItems
        },
        draw: {
            polyline: true,
            polygon: false,
            circle: false,
            rectangle: false,
            marker: false,
            circlemarker: false
        }
    });
    map.addControl(drawControl);

    function updateLatLngInput(layer) {
        var latlngs = layer.getLatLngs();
        var latlngString = latlngs.map(function(latlng) {
            return `${latlng.lat},${latlng.lng}`;
        }).join(' ');

        document.getElementById('latlng').value = latlngString;

        var length = calculateLength(latlngs);
        console.log('Length:', length);
        Swal.fire({
            title: 'Edit Berhasil!',
            html: `Panjang Polyline yang dibuat: ${length.toFixed(2)} meter.`,
            icon: 'success',
            confirmButtonText: 'OK'
        });
    }

    map.on(L.Draw.Event.CREATED, function (event) {
        var layer = event.layer;
        updateLatLngInput(layer);
        drawnItems.addLayer(layer);
    });

    map.on(L.Draw.Event.EDITED, function (event) {
        var layers = event.layers;
        layers.eachLayer(function (layer) {
            updateLatLngInput(layer);
        });
    });

    // Assuming polylineData is the string of coordinates from your server
    var polylineData = "{{ $ruasjalan['paths'] }}";
    
    // Convert the string of coordinates into an array of LatLng objects
    var polylineLatLngs = polylineData.split(' ').map(function(coords) {
        var latlng = coords.split(',');
        return L.latLng(parseFloat(latlng[0]), parseFloat(latlng[1]));
    });

    // Create the polyline and add it to the map
    var existingPolyline = L.polyline(polylineLatLngs, {color: 'blue'}).addTo(map);
    drawnItems.addLayer(existingPolyline);   

    document.getElementById('form').addEventListener('submit', function(event) {
        event.preventDefault();
        const latlngInput = document.getElementById('latlng');
        const latlngValue = latlngInput ? latlngInput.value : '';
        const panjangInput = document.getElementById('panjang');
        const panjangValue = panjangInput ? parseFloat(panjangInput.value) : null;

        const formData = {
            paths: document.getElementById('latlng').value,
            desa_id: document.getElementById('desa').value,
            kode_ruas: document.getElementById('kode_ruas').value,
            nama_ruas: document.getElementById('nama_ruas').value,
            panjang: panjangValue ? panjangValue :calculateLength(drawnItems.getLayers()[0].getLatLngs()),
            lebar: parseFloat(document.getElementById('lebar').value),
            eksisting_id: parseInt(document.getElementById('eksisting').value),
            kondisi_id: parseInt(document.getElementById('kondisi').value),
            jenisjalan_id: parseInt(document.getElementById('jenis_jalan').value),
            keterangan: document.getElementById('keterangan').value
        };
        console.log('Form data to be sent:', formData);

        const idRuasJalan = "{{ $ruasjalan['id'] }}";
        const token = document.querySelector('meta[name="api-token"]').getAttribute('content');

        // Membuat permintaan HTTP PUT ke API
        fetch(`https://gisapis.manpits.xyz/api/ruasjalan/${idRuasJalan}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify(formData)
        })
        .then(response => {
            console.log('Raw response:', response);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json().then(data => ({ status: response.status, body: data }));
        })
        .then(({ status, body }) => {
            if (status !== 200) {
                console.error('Error data:', body);
                throw new Error(body.message || 'Gagal memperbarui data.');
            }
            console.log('Data berhasil diperbarui:', body);
            Swal.fire(
                    'Data Berhasil Diperbaharui!',
                    'Klik OK untuk melihat data.',
                    'success'
                ).then(() => {
                    // Refresh halaman dashboard atau navigasi kembali ke halaman dashboard
                    window.location.href = getRedirectRoute(); // Ganti dengan URL halaman dashboard yang sesuai
            });
        })
        .catch(error => {
            console.error('Terjadi kesalahan:', error);
            if (error.message.includes('Unexpected token')) {
                console.error('Respons API tidak valid:', error.message);
            } else if (error.message.includes('HTTP error')) {
                console.error('Server mengembalikan status error:', error.message);
            }
            alert(`Terjadi kesalahan: ${error.message}`);
        });
    });


</script>
</body>
</html>