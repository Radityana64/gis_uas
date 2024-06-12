<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Ruas Jalan</title>
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
            <form action="{{ route('RuasJalan.store') }}" method="POST" enctype="multipart/form-data" id="form" name="form">
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

                    <div class="form-control">
                        <label class="label" for="latlng">
                            <span class="label-text text-gray-700"><b>Latlng</b></span>
                        </label>
                        <input type="text" class="input input-bordered w-full border-gray-300 rounded-lg shadow-sm" id="latlng" name="latlng" required />
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

    document.addEventListener('DOMContentLoaded', () => {
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
                polygon: true,
                circle: false,
                rectangle: false,
                marker: false,
                circlemarker: false
            }
        });
        map.addControl(drawControl);

        map.on(L.Draw.Event.CREATED, function (event) {
            var layer = event.layer;
            drawnItems.addLayer(layer);

            var latlngs;
            if (layer instanceof L.Polyline) {
                latlngs = layer.getLatLngs();
            } else if (layer instanceof L.Polygon) {
                latlngs = layer.getLatLngs()[0]; // outer ring
            }

            var latlngString = latlngs.map(function(latlng) {
                return `${latlng.lat},${latlng.lng}`;
            }).join(' ');

            document.getElementById('latlng').value = latlngString;

            // Calculate the length of the polyline
            var length = calculateLength(latlngs);
            console.log('Length:', length);

            alert(`Panjang Polyline: ${length.toFixed(2)} meters`);
        });

        map.on(L.Draw.Event.EDITED, function (event) {
            var layers = event.layers;
            var latlngs = [];

            layers.eachLayer(function (layer) {
                if (layer instanceof L.Polyline) {
                    latlngs = latlngs.concat(layer.getLatLngs());
                } else if (layer instanceof L.Polygon) {
                    latlngs = latlngs.concat(layer.getLatLngs()[0]); // outer ring
                }
            });

            var latlngString = latlngs.map(function(latlng) {
                return `${latlng.lat},${latlng.lng}`;
            }).join(' ');

            document.getElementById('latlng').value = latlngString;

            // Calculate the length of the polyline
            var length = calculateLength(latlngs);
            console.log('Length:', length);

            alert(`Panjang Polyline: ${length.toFixed(2)} meters`);
        });

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

        const eksistingSelect = document.getElementById('eksisting');
        fetch('https://gisapis.manpits.xyz/api/meksisting', {
            headers: {
                Authorization: `Bearer ${token}`,
            }
        })
        .then(response => response.json())
        .then(data => {
            eksistingSelect.innerHTML = '<option value="">Pilih Material</option>';
            data.eksisting.forEach(eksisting => {
                const option = document.createElement('option');
                option.value = eksisting.id;
                option.textContent = eksisting.eksisting;
                eksistingSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error fetching Material:', error);
        });

        const kondisiSelect = document.getElementById('kondisi');
        fetch('https://gisapis.manpits.xyz/api/mkondisi', {
            headers: {
                Authorization: `Bearer ${token}`,
            }
        })
        .then(response => response.json())
        .then(data => {
            kondisiSelect.innerHTML = '<option value="">Pilih Kondisi</option>';
           
            data.eksisting.forEach(kondisi => {
                const option = document.createElement('option');
                option.value = kondisi.id;
                option.textContent = kondisi.kondisi;
                kondisiSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error fetching Kondisi:', error);
        });
        
        const jenisjalanSelect = document.getElementById('jenis_jalan');
        fetch('https://gisapis.manpits.xyz/api/mjenisjalan', {
            headers: {
                Authorization: `Bearer ${token}`,
            }
        })
        .then(response => response.json())
        .then(data => {
            jenisjalanSelect.innerHTML = '<option value="">Pilih Jenis</option>';
            data.eksisting.forEach(jenisjalan => {
                const option = document.createElement('option');
                option.value = jenisjalan.id;
                option.textContent = jenisjalan.jenisjalan;
                jenisjalanSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error fetching jenis_jalan:', error);
        });

        document.getElementById('form').addEventListener('submit', function(event) {
            event.preventDefault(); // Mencegah formulir dikirimkan secara langsung

            // Mengumpulkan data dari formulir
            const formData = {
                paths: document.getElementById('latlng').value,
                desa_id: document.getElementById('desa').value,
                kode_ruas: document.getElementById('kode_ruas').value,
                nama_ruas: document.getElementById('nama_ruas').value,
                panjang: calculateLength(drawnItems.getLayers()[0].getLatLngs()), // Menggunakan fungsi calculateLength untuk menghitung panjang
                lebar: parseFloat(document.getElementById('lebar').value),
                eksisting_id: parseInt(document.getElementById('eksisting').value),
                kondisi_id: parseInt(document.getElementById('kondisi').value),
                jenisjalan_id: parseInt(document.getElementById('jenis_jalan').value),
                keterangan: document.getElementById('keterangan').value
            };

            console.log('Form data to be sent:', formData);

            const token = document.querySelector('meta[name="api-token"]').getAttribute('content');

            // Membuat permintaan HTTP POST ke API
            fetch('https://gisapis.manpits.xyz/api/ruasjalan', {
                method: 'POST',
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
                    throw new Error(body.message || 'Gagal menyimpan data.');
                }
                console.log('Data berhasil disimpan:', body);
                alert('Data berhasil disimpan.');

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

    });
</script>

</body>
</html>
