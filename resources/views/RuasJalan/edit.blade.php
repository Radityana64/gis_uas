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
<div id="map"></div>

<div class="form-container">
    <div class="custom-card">
        <div class="custom-card-header">
            <h1 class="text-xl font-bold text-gray-900">Edit Data Jalan</h1>
        </div>
        
        <div class="custom-card-body">
            <form action="{{ route('RuasJalan.update', $ruasjalan['id']) }}" method="POST" enctype="multipart/form-data" id="form" name="form">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div class="form-control">
                        <label class="label" for="province">
                            <span class="label-text text-gray-700"><b>Pilih Provinsi</b></span>
                        </label>
                        <select class="select select-bordered w-full border-gray-300 rounded-lg shadow-sm" id="province" name="province" required>
                            <option value="">Pilih Provinsi</option>
                            @foreach($regionData['provinsi'] as $provinsi)
                                <option value="{{ $provinsi['id'] }}" {{ isset($ruasjalan['province_id']) && $ruasjalan['province_id'] == $provinsi['id'] ? 'selected' : '' }}>{{ $provinsi['provinsi'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label" for="kabupaten">
                            <span class="label-text text-gray-700"><b>Pilih Kabupaten</b></span>
                        </label>
                        <select class="select select-bordered w-full border-gray-300 rounded-lg shadow-sm" id="kabupaten" name="kabupaten" required>
                            <option value="">Pilih Kabupaten</option>
                            @foreach($regionData['kabupaten'] as $kabupaten)
                                <option value="{{ $kabupaten['id'] }}" {{ isset($ruasjalan['kabupaten_id']) && $ruasjalan['kabupaten_id'] == $kabupaten['id'] ? 'selected' : '' }}>{{ $kabupaten['kabupaten'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label" for="kecamatan">
                            <span class="label-text text-gray-700"><b>Pilih Kecamatan</b></span>
                        </label>
                        <select class="select select-bordered w-full border-gray-300 rounded-lg shadow-sm" id="kecamatan" name="kecamatan" required>
                            <option value="">Pilih Kecamatan</option>
                            @foreach($regionData['kecamatan'] as $kecamatan)
                                <option value="{{ $kecamatan['id'] }}" {{ isset($ruasjalan['kecamatan_id']) && $ruasjalan['kecamatan_id'] == $kecamatan['id'] ? 'selected' : '' }}>{{ $kecamatan['kecamatan'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label" for="desa">
                            <span class="label-text text-gray-700"><b>Pilih Desa</b></span>
                        </label>
                        <select class="select select-bordered w-full border-gray-300 rounded-lg shadow-sm" id="desa" name="desa" required>
                            <option value="">Pilih Desa</option>
                            @foreach($regionData['desa'] as $desa)
                                <option value="{{ $desa['id'] }}" {{ isset($ruasjalan['desa_id']) && $ruasjalan['desa_id'] == $desa['id'] ? 'selected' : '' }}>{{ $desa['desa'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label" for="nama_ruas">
                            <span class="label-text text-gray-700"><b>Nama Ruas</b></span>
                        </label>
                        <input type="text" class="input input-bordered w-full border-gray-300 rounded-lg shadow-sm" id="nama_ruas" name="nama_ruas" value="{{ $ruasjalan['nama_ruas'] ?? '' }}" required />
                    </div>

                    <div class="form-control">
                        <label class="label" for="lebar">
                            <span class="label-text text-gray-700"><b>Lebar Ruas</b></span>
                        </label>
                        <input type="text" class="input input-bordered w-full border-gray-300 rounded-lg shadow-sm" id="lebar" name="lebar" value="{{ $ruasjalan['lebar'] ?? '' }}" required />
                    </div>

                    <div class="form-control">
                        <label class="label" for="kode_ruas">
                            <span class="label-text text-gray-700"><b>Kode Ruas</b></span>
                        </label>
                        <input type="text" class="input input-bordered w-full border-gray-300 rounded-lg shadow-sm" id="kode_ruas" name="kode_ruas" value="{{ $ruasjalan['kode_ruas'] ?? '' }}" required />
                    </div>

                    <div class="form-control">
                        <label class="label" for="eksisting">
                            <span class="label-text text-gray-700"><b>Eksisting</b></span>
                        </label>
                        <select class="select select-bordered w-full border-gray-300 rounded-lg shadow-sm" id="eksisting" name="eksisting" required>
                            <option value="">Pilih Material</option>
                            @foreach($eksistingData as $eksisting)
                                <option value="{{ $eksisting['id'] }}" {{ isset($ruasjalan['eksisting_id']) && $ruasjalan['eksisting_id'] == $eksisting['id'] ? 'selected' : '' }}>{{ $eksisting['eksisting'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label" for="kondisi">
                            <span class="label-text text-gray-700"><b>Kondisi</b></span>
                        </label>
                        <select class="select select-bordered w-full border-gray-300 rounded-lg shadow-sm" id="kondisi" name="kondisi" required>
                            <option value="">Pilih Kondisi</option>
                            @foreach($kondisiData as $kondisi)
                                <option value="{{ $kondisi['id'] }}" {{ isset($ruasjalan['kondisi_id']) && $ruasjalan['kondisi_id'] == $kondisi['id'] ? 'selected' : '' }}>{{ $kondisi['kondisi'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label" for="jenis_jalan">
                            <span class="label-text text-gray-700"><b>Jenis Jalan</b></span>
                        </label>
                        <select class="select select-bordered w-full border-gray-300 rounded-lg shadow-sm" id="jenis_jalan" name="jenis_jalan" required>
                            <option value="">Pilih Jenis</option>
                            @foreach($jenisjalanData as $jenisjalan)
                                <option value="{{ $jenisjalan['id'] }}" {{ isset($ruasjalan['jenisjalan_id']) && $ruasjalan['jenisjalan_id'] == $jenisjalan['id'] ? 'selected' : '' }}>{{ $jenisjalan['jenisjalan'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label" for="keterangan">
                            <span class="label-text text-gray-700"><b>Keterangan</b></span>
                        </label>
                        <textarea class="input input-bordered w-full border-gray-300 rounded-lg shadow-sm" id="keterangan" name="keterangan" required>{{ $ruasjalan['keterangan'] ?? '' }}</textarea>
                    </div>

                    <div class="form-control">
                        <label class="label" for="latlng">
                            <span class="label-text text-gray-700"><b>Latlng</b></span>
                        </label>
                        <input type="text" class="input input-bordered w-full border-gray-300 rounded-lg shadow-sm" id="latlng" name="latlng" value="{{ $ruasjalan['paths'] ?? '' }}" required />
                    </div>

                    <button type="submit" class="btn btn-primary w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-lg">Update Jalan</button>

                </div>
            </form>
        <div>
    </div>
</div>


<meta name="api-token" content="{{ session('token') }}">

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/leaflet-geometryutil@0.0.2/dist/leaflet.geometryutil.min.js"></script>

<script>
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
        alert(`Panjang Polyline: ${length.toFixed(2)} meters`);
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
            alert('Data berhasil diperbarui.');

            window.location.href = "{{ route('RuasJalan.index') }}";
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