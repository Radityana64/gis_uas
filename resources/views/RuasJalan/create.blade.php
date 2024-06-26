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
    </style>
</head>
<body class="bg-gray-200">
    <div id="map"></div> <!-- Move map outside and above other elements -->

    <div class="form-container absolute top-3 right-5 w-full max-w-lg z-10">
        <div class="bg-white rounded-lg shadow-lg p-5">
            <h1 class="text-xl font-bold text-gray-900 mb-6">Tambah Data Jalan</h1>
            <form action="{{ route('RuasJalan.store') }}" method="POST" enctype="multipart/form-data" id="form" name="form">
                @csrf
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full px-3 mb-6">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="nama_ruas">
                            Nama Ruas
                        </label>
                        <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="nama_ruas" type="text" name="nama_ruas" placeholder="Nama Ruas" required>
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="province">
                            Pilih Provinsi
                        </label>
                        <select class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="province" name="province" required>
                            <option value="">Pilih Provinsi</option>
                        </select>
                    </div>
                    <div class="w-full md:w-1/2 px-3">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="kabupaten">
                            Pilih Kabupaten
                        </label>
                        <select class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="kabupaten" name="kabupaten" required>
                            <option value="">Pilih Kabupaten</option>
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
                        </select>
                    </div>
                    <div class="w-full md:w-1/2 px-3">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="desa">
                            Pilih Desa
                        </label>
                        <select class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="desa" name="desa" required>
                            <option value="">Pilih Desa</option>
                        </select>
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="kode_ruas">
                            Kode Ruas
                        </label>
                        <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="kode_ruas" type="text" name="kode_ruas" placeholder="Kode Ruas" required>
                    </div>
                    <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="lebar">
                            Lebar Ruas
                        </label>
                        <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="lebar" type="text" name="lebar" placeholder="Lebar Ruas" required>
                    </div>
                    <div class="w-full md:w-1/3 px-3">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="latlng">
                            Latlng
                        </label>
                        <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="latlng" type="text" name="latlng" placeholder="Latlng" required>
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full md:w-1/3 px-2">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="eksisting">
                            Eksisting
                        </label>
                        <select class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="eksisting" name="eksisting" required>
                            <option value="">Pilih Material</option>
                        </select>
                    </div>
                    <div class="w-full md:w-1/3 px-2">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="kondisi">
                            Kondisi
                        </label>
                        <select class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="kondisi" name="kondisi" required>
                            <option value="">Pilih Kondisi</option>
                        </select>
                    </div>
                    <div class="w-full md:w-1/3 px-2">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="jenis_jalan">
                            Jenis Jalan
                        </label>
                        <select class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="jenis_jalan" name="jenis_jalan" required>
                            <option value="">Pilih Jenis</option>
                        </select>
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3 mb-6">
                    <div class="w-full mb-6 px-3">
                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="keterangan">
                            Keterangan
                        </label>
                        <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="keterangan" type="text" name="keterangan" placeholder="Keterangan" required>
                    </div>
                </div>
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded leading-tight focus:outline-none focus:shadow-outline">Tambah Jalan</button>
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
                    polygon: false,
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

                Swal.fire({
                    title: 'Polyline Berhasil Ditambahkan',
                    html: `Panjang Polyline yang dibuat: ${length.toFixed(2)} meter.`,
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
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

                Swal.fire({
                    title: 'Edit Berhasil!',
                    html: `Panjang Polyline yang dibuat: ${length.toFixed(2)} meter.`,
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
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
                    Swal.fire(
                            'Data Berhasil Ditambahkan!',
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

        });
    </script>
</body>
</html>
