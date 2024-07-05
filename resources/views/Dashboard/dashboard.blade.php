<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <style>
        #map {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }
        .color-box {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 2px;
            margin-right: 8px;
        }
        #legend-container {
            display: none;
            position: absolute;
            top: 90px;
            right: 4.5px;
            width: 180px;
            background-color: white;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 10px;
            z-index: 20;
        }
        #legend-container.active {
            display: block;
        }
        .legend-item {
            margin-bottom: 10px;
        }
        .form-container {
            position: relative;
            z-index: 10;
        }
        .select:focus, .select option:focus {
            outline: none;
        }
        .select option {
            padding: 0.5rem;
            background: #fff;
            color: #000;
        }
        .select option:hover {
            background: #f3f4f6;
            color: #111827;
        }
        .btn-group {
            margin-top: 10px;
        }

        .btn {
            display: inline-block;
            padding: 8px 16px;
            font-size: 14px;
            cursor: pointer;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            color: #fff; /* Warna teks putih untuk semua tombol */
        }

        .btn.btn-primary {
            background-color: #007bff;
            border: 1px solid #007bff;
        }

        .btn.btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn.btn-danger {
            background-color: #dc3545;
            border: 1px solid #dc3545;
        }

        .btn.btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
        .sidebar-details {
            display: none;
        }

        .sidebar-details.active {
            display: block;
        }
        .label {
            background-color: rgba(255, 255, 255, 0.5); 
            border: 2px solid white; 
            border-radius: 5px; 
            font-size: 12px; 
            font-weight: bold; 
            color: black; 
            text-align: center;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div id="map"></div>

    <div class="sidebar fixed top-1 bottom-1 left-1 w-56 z-10 bg-blue-600 text-white p-4 rounded-lg shadow-xl flex flex-col">
        <div class="text-lg font-semibold mb-6">Provinsi Bali</div>
        <ul>
            <li class="sidebar-item mb-2 hover:bg-blue-500 rounded-lg">
                <a href="{{ route('dashboard')}}" class="flex items-center p-2">
                    <span class="mr-3"><i class="fas fa-chart-line"></i></span>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="sidebar-item mb-2 hover:bg-blue-500 rounded-lg">
                <a href="{{ route('RuasJalan.index') }}" class="flex items-center p-2">
                    <span class="mr-3"><i class="fas fa-road"></i></span>
                    <span>Data Ruas Jalan</span>
                </a>
            </li>
            <li class="sidebar-item mb-2 hover:bg-blue-500 rounded-lg">
                <a href="{{route ('RuasJalan.create', ['previous' => 'dashboard']) }}" class="flex items-center p-2">
                    <span class="mr-3"><i class="fas fa-user"></i></span>
                    <span>Tambah Ruas Jalan</span>
                </a>
            </li>
        </ul>
        <ul class="mt-auto">
            <li class="sidebar-item bg-blue-800 hover:bg-blue-900 rounded-lg py-2 text-center">
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="flex items-center justify-center w-full text-white">
                        <span class="mr-3"><i class="fas fa-sign-out-alt"></i></span>
                        <span>Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>
    <div class="sidebar-details fixed top-1 bottom-1 left-60 z-10 bg-white p-6 rounded-lg shadow-xl hidden overflow-hidden w-80" id="road-details">
        <!-- Content will be dynamically populated here -->
    </div>
    <div class="form-container flex justify-between items-center bg-white p-4 shadow-lg rounded-lg ml-60 mr-1 mt-1">
        <div class="flex items-center space-x-4">
            <div class="search-container flex items-center">
                <input type="text" id="search-input" class="border border-gray-300 shadow-sm px-3 py-2 rounded-lg" placeholder="Search...">
                <button id="search-button" class="bg-blue-500 text-white px-4 py-2 rounded-lg ml-2"><i class="fas fa-search"></i></button>
            </div>
            <div class="flex items-center">
                <select class="select border border-gray-300 rounded-lg shadow-sm px-3 py-2" id="filter" name="filter" required>
                    <option value="semua">Tandai...</option>
                    <option value="jenis">Jenis</option>
                    <option value="kondisi">Kondisi</option>
                    <option value="item">Filter Kategori</option>
                </select>
                <i class="fas fa-filter ml-2 text-gray-500"></i>
            </div>
        </div>
        <div class="flex items-center ml-auto">
            <span id="nama_user" class="text-gray-700 font-semibold">Nama User</span>
        </div>
    </div>
    <div id="legend-container" class="rounded-2xl">
        <div id="jenis-legend">
            <div class="legend-item">
                <input type="checkbox" id="jenis-provinsi" value="provinsi">
                <label for="jenis-provinsi"><span class="color-box bg-red-500"></span>Provinsi</label>
            </div>
            <div class="legend-item">
                <input type="checkbox" id="jenis-kabupaten" value="kabupaten">
                <label for="jenis-kabupaten"><span class="color-box bg-yellow-500"></span>Kabupaten</label>
            </div>
            <div class="legend-item">
                <input type="checkbox" id="jenis-desa" value="desa">
                <label for="jenis-desa"><span class="color-box bg-green-500"></span>Desa</label>
            </div>
        </div>
        <div id="kondisi-legend">
            <div class="legend-item">
                <input type="checkbox" id="kondisi-rusak" value="rusak">
                <label for="kondisi-rusak"><span class="color-box bg-red-500"></span>Rusak</label>
            </div>
            <div class="legend-item">
                <input type="checkbox" id="kondisi-sedang" value="sedang">
                <label for="kondisi-sedang"><span class="color-box bg-yellow-500"></span>Sedang</label>
            </div>
            <div class="legend-item">
                <input type="checkbox" id="kondisi-baik" value="baik">
                <label for="kondisi-baik"><span class="color-box bg-green-500"></span>Baik</label>
            </div>
        </div>
        <div id="item-legend">
            <!-- Jenis -->
            <div class="legend-item">
                <input type="checkbox" id="item-provinsi" value="provinsi">
                <label for="item-provinsi">Provinsi</label>
            </div>
            <div class="legend-item">
                <input type="checkbox" id="item-kabupaten" value="kabupaten">
                <label for="item-kabupaten">Kabupaten</label>
            </div>
            <div class="legend-item">
                <input type="checkbox" id="item-desa" value="desa">
                <label for="item-desa">Desa</label>
            </div>
            <hr class="border-t-4 border-gray-500 my-4">
            <!-- eksisiting -->
            <div class="legend-item">
                <input type="checkbox" id="item-tanah" value="tanah">
                <label for="item-tanah">Tanah</label>
            </div>
            <div class="legend-item">
                <input type="checkbox" id="item-tanah-beton" value="tanah-beton">
                <label for="item-tanah-beton">Tanah/Beton</label>
            </div>
            <div class="legend-item">
                <input type="checkbox" id="item-perkerasan" value="perkerasan">
                <label for="item-perkerasan">Perkerasan</label>
            </div>
            <div class="legend-item">
                <input type="checkbox" id="item-koral" value="koral">
                <label for="item-koral">Koral</label>
            </div>
            <div class="legend-item">
                <input type="checkbox" id="item-lapen" value="lapen">
                <label for="item-lapen">Lapen</label>
            </div>
            <div class="legend-item">
                <input type="checkbox" id="item-paving" value="paving">
                <label for="item-paving">Paving</label>
            </div>
            <div class="legend-item">
                <input type="checkbox" id="item-hotmix" value="hotmix">
                <label for="item-hotmix">Hotmix</label>
            </div>
            <div class="legend-item">
                <input type="checkbox" id="item-beton" value="beton">
                <label for="item-beton">Beton</label>
            </div>
            <div class="legend-item">
                <input type="checkbox" id="item-beton-lapen" value="beton-lapen">
                <label for="item-beton-lapen">Beton/Lapen</label>
            </div>
            <hr class="border-t-4 border-gray-500 my-4">
            <!-- Kondisi -->
            <div class="legend-item">
                <input type="checkbox" id="item-rusak" value="rusak">
                <label for="item-rusak">Rusak</label>
            </div>
            <div class="legend-item">
                <input type="checkbox" id="item-sedang" value="sedang">
                <label for="item-sedang">Sedang</label>
            </div>
            <div class="legend-item">
                <input type="checkbox" id="item-baik" value="baik">
                <label for="item-baik">Baik</label>
            </div>
        </div>
    </div>

    <!-- Token API -->
    <meta name="api-token" content="{{ session('token') }}">

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet-geometryutil@0.0.2/dist/leaflet.geometryutil.min.js"></script>
    <!-- Memuat SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        async function deleteData(id) {
            try {
                const token = document.querySelector("meta[name='api-token']").getAttribute('content');
                // Konfirmasi penghapusan dengan sweetalert2
                const confirmed = await Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Anda tidak dapat mengembalikan ini setelah dihapus!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus saja!',
                    cancelButtonText: 'Batal'
                });

                if (confirmed.isConfirmed) {
                    const response = await fetch(`https://gisapis.manpits.xyz/api/ruasjalan/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Accept': 'application/json'
                        }
                    });

                    if (!response.ok) {
                        throw new Error('Network response was not ok ' + response.statusText);
                    }

                    const deletedRow = document.getElementById(`row-${id}`);
                    if (deletedRow) {
                        deletedRow.remove(); 
                    }

                    // Tampilkan alert data berhasil dihapus
                    Swal.fire(
                        'Deleted!',
                        'Data berhasil dihapus.',
                        'success'
                    ).then(() => {
                        // Refresh halaman dashboard atau navigasi kembali ke halaman dashboard
                        window.location.href = '/dashboard'; // Ganti dengan URL halaman dashboard yang sesuai
                    });
                }
            } catch (error) {
                console.error('Error deleting data:', error.message);
                Swal.fire(
                    'Error!',
                    'Terjadi kesalahan saat menghapus data.',
                    'error'
                );
            }
        }
    
    // document.addEventListener('DOMContentLoaded', () => {
        document.addEventListener('DOMContentLoaded', async function () {
            const api_main_url = "https://gisapis.manpits.xyz/";
            const token = document.querySelector("meta[name='api-token']").getAttribute('content');

            async function fetchData(url) {
                const response = await fetch(url, {
                    headers: {
                        Authorization: `Bearer ${token}`,
                        Accept: 'application/json'
                    }
                });
                if (!response.ok) {
                    throw new Error('Network response was not ok ' + response.statusText);
                }
                return response.json();
            }

            try {
                // Fetch data from all necessary APIs
                const [ruasjalanData, regionData, eksistingData, jenisjalanData, kondisiData] = await Promise.all([
                    fetchData(api_main_url + "api/ruasjalan"),
                    fetchData(api_main_url + "api/mregion"),
                    fetchData(api_main_url + "api/meksisting"),
                    fetchData(api_main_url + "api/mjenisjalan"),
                    fetchData(api_main_url + "api/mkondisi"),
                ]);

                console.log('ruasjalanData:', ruasjalanData);
                console.log('regionData:', regionData);

                var map = L.map('map').setView([-8.65, 115.22], 10);
                var markerIcon = L.icon({
                    iconUrl: '/storage/images/marker.png', // URL icon marker yang sudah disiapkan
                    iconSize: [30, 46], // Sesuaikan dengan ukuran icon marker Anda
                    iconAnchor: [16, 32] // Sesuaikan anchor icon marker jika diperlukan
                });

                // Adding multiple basemaps
                const tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 20,
                    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(map);

                var Esri_World = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                    attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
                });

                var Esri_Map = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/NatGeo_World_Map/MapServer/tile/{z}/{y}/{x}', {
                    attribution: 'Tiles &copy; Esri &mdash; National Geographic, Esri, DeLorme, NAVTEQ, UNEP-WCMC, USGS, NASA, ESA, METI, NRCAN, GEBCO, NOAA, iPC',
                    maxZoom: 16
                });

                var Stadia_Dark = L.tileLayer('https://tiles.stadiamaps.com/tiles/alidade_smooth_dark/{z}/{x}/{y}{r}.{ext}', {
                    minZoom: 0,
                    maxZoom: 20,
                    attribution: '&copy; <a href="https://www.stadiamaps.com/" target="_blank">Stadia Maps</a> &copy; <a href="https://openmaptiles.org/" target="_blank">OpenMapTiles</a> &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                    ext: 'png'
                });

                var baseLayers = {
                    "OSM Tiles": tiles,
                    "ESRI World Imagery": Esri_World,
                    "ESRI Map": Esri_Map,
                    "Stadia Dark": Stadia_Dark
                };

                // Adding layer control to map
                L.control.layers(baseLayers).addTo(map);

                const desaMap = new Map(regionData.desa.map(item => [item.id, item.desa]));
                const eksistingMap = new Map(eksistingData.eksisting.map(item => [item.id, item.eksisting]));
                const jenisjalanMap = new Map(jenisjalanData.eksisting.map(item => [item.id, item.jenisjalan]));
                const kondisiMap = new Map(kondisiData.eksisting.map(item => [item.id, item.kondisi]));

                async function getLocationDetails(desaId) {
                    const kecamatanResponse = await fetchData(api_main_url + `api/kecamatanbydesaid/${desaId}`);
                    const kabupatenResponse = await fetchData(api_main_url + `api/kabupatenbykecamatanid/${kecamatanResponse.kecamatan.id}`);
                    const provinsiResponse = await fetchData(api_main_url + `api/provinsibykabupatenid/${kabupatenResponse.kabupaten.id}`);
                    return {
                        kecamatan: kecamatanResponse.kecamatan.kecamatan,
                        kabupaten: kabupatenResponse.kabupaten.kabupaten,
                        provinsi: provinsiResponse.provinsi.provinsi
                    };
                }
                
                function displayAllPolylines() {
                    fetchPolylineData('all');
                }

                displayAllPolylines();

                // Function to draw polylines on the map with popups
                // Function to draw polylines on the map
                async function drawPolylines(polylineData, filterType) {
                    try {
                        if (!Array.isArray(polylineData)) {
                            console.error('Polyline data is not an array or is undefined:', polylineData);
                            return;
                        }

                        map.eachLayer(layer => {
                            if (layer instanceof L.Polyline || layer instanceof L.Marker) {
                                map.removeLayer(layer);
                            }
                        });

                        let foundPolyline = false;
                        const searchInput = document.getElementById('search-input').value.trim().toLowerCase();
                        let currentMarkers = [];
                        let currentLabels = [];

                        function removeCurrentMarkersAndLabels() {
                            currentMarkers.forEach(marker => map.removeLayer(marker));
                            currentMarkers = [];
                        }

                        const checkedItems = {
                            jenis: [],
                            eksisting: [],
                            kondisi: []
                        };
                        if (filterType === 'item') {
                            document.querySelectorAll('#item-legend input[type="checkbox"]:checked').forEach(checkbox => {
                                if (['provinsi', 'kabupaten', 'desa'].includes(checkbox.value)) {
                                    checkedItems.jenis.push(checkbox.value);
                                } else if (['rusak', 'sedang', 'baik'].includes(checkbox.value)) {
                                    checkedItems.kondisi.push(checkbox.value);
                                } else {
                                    checkedItems.eksisting.push(checkbox.value);
                                }
                            });
                        }
                        polylineData.forEach(polyline => {
                            let shouldDraw = true;

                            if (filterType !== 'all') {
                                if (filterType === 'jenis') {
                                    switch (polyline.jenisjalan_id) {
                                        case 1:
                                            shouldDraw = document.getElementById('jenis-desa').checked;
                                            break;
                                        case 2:
                                            shouldDraw = document.getElementById('jenis-kabupaten').checked;
                                            break;
                                        case 3:
                                            shouldDraw = document.getElementById('jenis-provinsi').checked;
                                            break;
                                    }
                                } else if (filterType === 'kondisi') {
                                    switch (polyline.kondisi_id) {
                                        case 1:
                                            shouldDraw = document.getElementById('kondisi-baik').checked;
                                            break;
                                        case 2:
                                            shouldDraw = document.getElementById('kondisi-sedang').checked;
                                            break;
                                        case 3:
                                            shouldDraw = document.getElementById('kondisi-rusak').checked;
                                            break;
                                    }
                                }else if (filterType === 'item') {
                                    shouldDraw = (
                                        (checkedItems.jenis.length === 0 || 
                                        (checkedItems.jenis.includes('provinsi') && polyline.jenisjalan_id === 3) ||
                                        (checkedItems.jenis.includes('kabupaten') && polyline.jenisjalan_id === 2) ||
                                        (checkedItems.jenis.includes('desa') && polyline.jenisjalan_id === 1)) &&
                                        (checkedItems.eksisting.length === 0 || 
                                        checkedItems.eksisting.includes(eksistingMap.get(polyline.eksisting_id).toLowerCase())) &&
                                        (checkedItems.kondisi.length === 0 || 
                                        checkedItems.kondisi.includes(kondisiMap.get(polyline.kondisi_id).toLowerCase()))
                                    );
                                }
                            }

                            if (!shouldDraw) {
                                return;
                            }

                            const coordinates = polyline.paths.split(' ').map(coord => {
                                const [lat, lng] = coord.trim().split(',').map(parseFloat);
                                return L.latLng(lat, lng);
                            });

                            if (!coordinates.every(coord => !isNaN(coord.lat) && !isNaN(coord.lng))) {
                                console.error('Invalid coordinates:', coordinates);
                                return;
                            }

                            let color = 'blue';
                            if (filterType === 'jenis') {
                                switch (polyline.jenisjalan_id) {
                                    case 1:
                                        color = 'green';
                                        break;
                                    case 2:
                                        color = 'yellow';
                                        break;
                                    case 3:
                                        color = 'red';
                                        break;
                                }
                            } else if (filterType === 'kondisi') {
                                switch (polyline.kondisi_id) {
                                    case 1:
                                        color = 'green';
                                        break;
                                    case 2:
                                        color = 'yellow';
                                        break;
                                    case 3:
                                        color = 'red';
                                        break;
                                }
                            }

                            const line = L.polyline(coordinates, { color }).addTo(map);

                            // Create label marker and add to the map
                            const label = L.divIcon({
                                className: 'label',
                                html: `<div>${polyline.kode_ruas}</div>`,
                                iconSize: [40, 20],
                                iconAnchor: [20, 10]
                            });
                            const labelMarker = L.marker(line.getCenter(), { icon: label }).addTo(map);
                            currentLabels.push(labelMarker);

                            // Event listener for polyline click
                            line.on('click', async () => {
                                const sidebarDetails = document.getElementById('road-details');

                                // Fetch additional location details
                                const locationDetails = await getLocationDetails(polyline.desa_id);

                                // Update sidebar with polyline details
                                sidebarDetails.innerHTML = `
                                    <h2 class="text-lg font-semibold mb-4 mt-20">Detail Ruas Jalan</h2>
                                    <div class="space-y-2">
                                        <div class="flex justify-between border-b pb-2">
                                            <strong class="text-gray-600">Nama Jalan:</strong> 
                                            <span class="text-gray-800">${polyline.nama_ruas}</span>
                                        </div>
                                        <div class="flex justify-between border-b pb-2">
                                            <strong class="text-gray-600">Kode:</strong> 
                                            <span class="text-gray-800">${polyline.kode_ruas}</span>
                                        </div>
                                        <div class="flex justify-between border-b pb-2">
                                            <strong class="text-gray-600">Nama Desa:</strong> 
                                            <span class="text-gray-800">${desaMap.get(polyline.desa_id)}</span>
                                        </div>
                                        <div class="flex justify-between border-b pb-2">
                                            <strong class="text-gray-600">Kecamatan:</strong> 
                                            <span class="text-gray-800">${locationDetails.kecamatan}</span>
                                        </div>
                                        <div class="flex justify-between border-b pb-2">
                                            <strong class="text-gray-600">Kabupaten:</strong> 
                                            <span class="text-gray-800">${locationDetails.kabupaten}</span>
                                        </div>
                                        <div class="flex justify-between border-b pb-2">
                                            <strong class="text-gray-600">Provinsi:</strong> 
                                            <span class="text-gray-800">${locationDetails.provinsi}</span>
                                        </div>
                                        <div class="flex justify-between border-b pb-2">
                                            <strong class="text-gray-600">Panjang:</strong> 
                                            <span class="text-gray-800">${parseFloat(polyline.panjang).toFixed(2)} m</span>
                                        </div>
                                        <div class="flex justify-between border-b pb-2">
                                            <strong class="text-gray-600">Lebar:</strong> 
                                            <span class="text-gray-800">${parseFloat(polyline.lebar).toFixed(2)} m</span>
                                        </div>
                                        <div class="flex justify-between border-b pb-2">
                                            <strong class="text-gray-600">Material:</strong> 
                                            <span class="text-gray-800">${eksistingMap.get(polyline.eksisting_id)}</span>
                                        </div>
                                        <div class="flex justify-between border-b pb-2">
                                            <strong class="text-gray-600">Jenis:</strong> 
                                            <span class="text-gray-800">${jenisjalanMap.get(polyline.jenisjalan_id)}</span>
                                        </div>
                                        <div class="flex justify-between border-b pb-2">
                                            <strong class="text-gray-600">Kondisi:</strong> 
                                            <span class="text-gray-800">${kondisiMap.get(polyline.kondisi_id)}</span>
                                        </div>
                                        <div class="flex border-b pb-2">
                                            <strong class="text-gray-600">Keterangan:</strong> 
                                            <span class="text-gray-800 whitespace-pre-line">${polyline.keterangan}</span>
                                        </div>
                                    </div>
                                    <div class="flex justify-between mt-6">
                                        <button class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600" onclick="window.location.href='/ruasjalan/${polyline.id}/edit?previous=dashboard'">Edit</button>
                                        <button class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600" onclick="deleteData(${polyline.id})">Delete</button>
                                    </div>
                                `;

                                // Show sidebar details
                                sidebarDetails.classList.add('active');

                                // Remove current markers and labels
                                removeCurrentMarkersAndLabels();

                                // Add start and end markers to the map
                                const startMarker = L.marker(coordinates[0], { icon: markerIcon }).addTo(map);
                                const endMarker = L.marker(coordinates[coordinates.length - 1], { icon: markerIcon }).addTo(map);
                                currentMarkers.push(startMarker, endMarker);

                                // Bind popups to markers
                                startMarker.bindPopup(`<b>Start</b><br>Coordinates: ${coordinates[0].lat.toFixed(6)}, ${coordinates[0].lng.toFixed(6)}`);
                                endMarker.bindPopup(`<b>End</b><br>Coordinates: ${coordinates[coordinates.length - 1].lat.toFixed(6)}, ${coordinates[coordinates.length - 1].lng.toFixed(6)}`);
                            });

                            // Add polyline to the map if it matches search input
                            if (searchInput !== '' && (polyline.nama_ruas.toLowerCase().includes(searchInput) || polyline.kode_ruas.includes(searchInput))) {
                                line.addTo(map);
                                labelMarker.addTo(map);
                                foundPolyline = true;

                                // Zoom to the bounds of the polyline
                                const polylineBounds = line.getBounds();
                                map.fitBounds(polylineBounds);
                            }
                        });

                        // Event listener to remove sidebar details and markers on map click
                        map.on('click', e => {
                            const sidebarDetails = document.getElementById('road-details');
                            const clickedOnPolyline = e.originalEvent.target.classList.contains('leaflet-interactive');

                            if (!clickedOnPolyline) {
                                sidebarDetails.classList.remove('active'); // Hide sidebar details
                                removeCurrentMarkersAndLabels(); // Remove current markers and labels
                            }
                        });
                    } catch (error) {
                        console.error('Error in drawPolylines:', error.message);
                    }
                }

                // Function to fetch polyline data from API
                function fetchPolylineData(filterType) {
                    fetchData(api_main_url + "api/ruasjalan")
                        .then(data => {
                            console.log('Fetched polyline data:', data); // Log fetched polyline data
                            drawPolylines(data.ruasjalan, filterType); // Draw polylines based on fetched data and filterType
                        })
                        .catch(error => {
                            console.error('Error fetching polyline data:', error);
                        });
                }

                // Event listener for select change
                document.getElementById('filter').addEventListener('change', event => {
                    const filterType = event.target.value;
                    const legendContainer = document.getElementById('legend-container');
                    
                    document.getElementById('jenis-legend').style.display = 'none';
                    document.getElementById('kondisi-legend').style.display = 'none';
                    document.getElementById('item-legend').style.display = 'none';
                    legendContainer.classList.remove('active');

                    if (filterType === 'jenis') {
                        document.getElementById('jenis-legend').style.display = 'block';
                        legendContainer.classList.add('active');
                    } else if (filterType === 'kondisi') {
                        document.getElementById('kondisi-legend').style.display = 'block';
                        legendContainer.classList.add('active');
                    } else if (filterType === 'item') {
                        document.getElementById('item-legend').style.display = 'block';
                        legendContainer.classList.add('active');
                    } else {
                        // Jika filter dikembalikan ke default, tampilkan semua polyline
                        displayAllPolylines();
                        return;
                    }

                    fetchPolylineData(filterType);
                });

                function setupCheckboxListeners() {
                    const checkboxes = document.querySelectorAll('#legend-container input[type="checkbox"]');
                    checkboxes.forEach(checkbox => {
                        checkbox.addEventListener('change', () => {
                            const filterType = document.getElementById('filter').value;
                            fetchPolylineData(filterType);
                        });
                    });
                }

                // Panggil fungsi ini setelah DOM selesai dimuat
                setupCheckboxListeners();

                function setupItemCheckboxListeners() {
                    const itemCheckboxes = document.querySelectorAll('#item-legend input[type="checkbox"]');
                    itemCheckboxes.forEach(checkbox => {
                        checkbox.addEventListener('change', () => {
                            fetchPolylineData('item');
                        });
                    });
                }

                setupItemCheckboxListeners()

                // Event listener for search button
                document.getElementById('search-button').addEventListener('click', () => {
                    map.eachLayer(layer => {
                        if (layer instanceof L.Polyline) {
                            map.removeLayer(layer);
                        }
                    });
                    fetchPolylineData(document.getElementById('filter').value);
                });

                // Fetch user data
                const getUser = document.getElementById('nama_user');
                fetchData(api_main_url + "api/user")
                    .then(userdata => {
                        console.log('User data:', userdata.data.user.name);
                        getUser.innerHTML = userdata.data.user.name;
                    })
                    .catch(error => {
                        console.error('Error fetching user data:', error);
                        getUser.innerHTML = 'Failed to load user data';
                    });

            } catch (error) {
                console.error('Error fetching data:', error.message);
            }
        });
    </script>
</body>
</html>
