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
        /* .legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
        } */
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
            <a href="{{route ('RuasJalan.create') }}" class="flex items-center p-2">
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
<div class="sidebar-details fixed top-1 bottom-1 left-60 w-1/5 z-10 bg-white p-4 rounded-lg shadow-xl hidden" id="road-details">
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
                <option value="">Tandai Berdasarkan...</option>
                <option value="jenis">Jenis</option>
                <option value="kondisi">Kondisi</option>
            </select>
            <i class="fas fa-filter ml-2 text-gray-500"></i>
        </div>
    </div>
    <div class="flex items-center ml-auto">
        <span id="nama_user" class="text-gray-700 font-semibold">Nama User</span>
    </div>
</div>
<div id="legend-container" class="rounded-2xl">
    <div id="jenis-legend" style="display: none;">
        <div class="legend-item"><span class="color-box bg-red-500"></span>Provinsi</div>
        <div class="legend-item"><span class="color-box bg-yellow-500"></span>Kabupaten</div>
        <div class="legend-item"><span class="color-box bg-green-500"></span>Desa</div>
    </div>
    <div id="kondisi-legend" style="display: none;">
        <div class="legend-item"><span class="color-box bg-red-500"></span>Rusak</div>
        <div class="legend-item"><span class="color-box bg-yellow-500"></span>Sedang</div>
        <div class="legend-item"><span class="color-box bg-green-500"></span>Baik</div>
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
                        deletedRow.remove(); // Hapus baris dari tabel
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
            const [ruasjalanData, regionData] = await Promise.all([
                fetchData(api_main_url + "api/ruasjalan"),
                fetchData(api_main_url + "api/mregion")
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
            const kecamatanMap = new Map(regionData.kecamatan.map(item => [item.id, item.kecamatan]));
            const kabupatenMap = new Map(regionData.kabupaten.map(item => [item.id, item.kabupaten]));

            // Function to draw polylines on the map with popups
            // Function to draw polylines on the map with popups
            async function drawPolylines(polylineData, filterType) {
                let foundPolyline = false; // Flag untuk mengecek apakah ada polyline yang sesuai dengan pencarian
                const searchInput = document.getElementById('search-input').value.trim().toLowerCase();

                // Loop through each polyline data
                polylineData.forEach(async (polyline) => {
                    const coordinates = polyline.paths.split(' ').map(coord => {
                        const [lat, lng] = coord.trim().split(',').map(parseFloat);
                        return L.latLng(lat, lng); // Menggunakan L.latLng untuk membuat objek LatLng Leaflet
                    });

                    // Check if coordinates are valid
                    if (!coordinates.every(coord => !isNaN(coord.lat) && !isNaN(coord.lng))) {
                        console.error('Invalid coordinates:', coordinates);
                        return; // Skip invalid coordinates
                    }

                    let color = 'blue'; // Warna default
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

                    // Event listener untuk klik pada polyline
                    line.on('click', (e) => {
                        const sidebarDetails = document.getElementById('road-details');

                        sidebarDetails.innerHTML = `
                            <h2 class="text-lg font-semibold mb-4 mt-20">Detail Ruas Jalan</h2>
                            <div><strong>Nama Jalan:</strong> ${polyline.nama_ruas}</div>
                            <div><strong>Panjang:</strong> ${polyline.panjang} m</div>
                            <div><strong>Lebar:</strong> ${polyline.lebar} m</div>
                            <div><strong>Jenis:</strong> ${polyline.jenisjalan_id}</div>
                            <div><strong>Kondisi:</strong> ${polyline.kondisi_id}</div>
                            <div class="btn-group">
                                <button class="btn btn-primary" onclick="window.location.href='/ruasjalan/${polyline.id}/edit'" style="color: white;">Edit</button>
                                <button onclick="deleteData(${polyline.id})" class="btn btn-danger">Delete</button>
                            </div>
                        `;

                        sidebarDetails.classList.add('active'); // Tampilkan sidebar detail
                        // Tambahkan marker pada titik awal dan akhir polyline
                        const startMarker = L.marker(coordinates[0], { icon: markerIcon }).addTo(map);
                        const endMarker = L.marker(coordinates[coordinates.length - 1], { icon: markerIcon }).addTo(map);

                        // Bind popup pada marker
                        startMarker.bindPopup(`<b>Start</b><br>Coordinates: ${coordinates[0].lat.toFixed(6)}, ${coordinates[0].lng.toFixed(6)}`);
                        endMarker.bindPopup(`<b>End</b><br>Coordinates: ${coordinates[coordinates.length - 1].lat.toFixed(6)}, ${coordinates[coordinates.length - 1].lng.toFixed(6)}`);

                        // Hapus marker sebelumnya jika ada
                        if (map.hasLayer(startMarker)) {
                            map.removeLayer(startMarker);
                        }
                        if (map.hasLayer(endMarker)) {
                            map.removeLayer(endMarker);
                        }
                        
                        
                    });

                    // Event listener untuk menghapus sidebar detail ketika peta diklik di tempat lain
                    map.on('click', (e) => {
                        const sidebarDetails = document.getElementById('road-details');
                        const clickedOnPolyline = e.originalEvent.target.classList.contains('leaflet-interactive');

                        if (!clickedOnPolyline) {
                            sidebarDetails.classList.remove('active'); // Sembunyikan sidebar detail
                        }
                    });

                    // Tambahkan polyline ke peta jika sesuai dengan kriteria pencarian
                    if (searchInput !== '' && polyline.nama_ruas.toLowerCase().includes(searchInput)) {
                        line.addTo(map);
                        foundPolyline = true;

                        // Zoom ke batas polyline
                        const polylineBounds = line.getBounds();
                        map.fitBounds(polylineBounds);
                    }
                });
            }


        const token = document.querySelector('meta[name="api-token"]').getAttribute('content');
        const GetUser = document.getElementById('nama_user');

        fetch('https://gisapis.manpits.xyz/api/user', {
            headers: {
                Authorization: `Bearer ${token}`
            }
        })
        .then(response => response.json())
        .then(userdata => {
            console.log('Data polylines:', userdata.data.user.name);
            GetUser.innerHTML = userdata.data.user.name;
        })
        .catch(error => {
            console.error('There has been a problem with your fetch operation:', error);
            GetUser.innerHTML = 'Failed to load user data';
        });

        // Fetch polyline data from the API
        function fetchPolylineData(filterType) {
            fetch('https://gisapis.manpits.xyz/api/ruasjalan', {
                headers: {
                    Authorization: `Bearer ${token}`,
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log('Data polylines:', data); // Log polyline data to ensure it's received correctly
                drawPolylines(data.ruasjalan, filterType);
            })
            .catch(error => {
                console.error('Error fetching polyline data:', error);
            });
        }

        fetchPolylineData();

        // Event listener for select change
        document.getElementById('filter').addEventListener('change', (event) => {
            const filterType = event.target.value;
            map.eachLayer((layer) => {
                if (layer instanceof L.Polyline) {
                    map.removeLayer(layer);
                }
            });
            fetchPolylineData(filterType);

            // Update legend display
            const legendContainer = document.getElementById('legend-container');
            if (filterType === 'jenis') {
                document.getElementById('jenis-legend').style.display = 'block';
                document.getElementById('kondisi-legend').style.display = 'none';
                legendContainer.classList.add('active');
            } else if (filterType === 'kondisi') {
                document.getElementById('jenis-legend').style.display = 'none';
                document.getElementById('kondisi-legend').style.display = 'block';
                legendContainer.classList.add('active');
            } else {
                document.getElementById('jenis-legend').style.display = 'none';
                document.getElementById('kondisi-legend').style.display = 'none';
                legendContainer.classList.remove('active');
            }
        });

        // Event listener for search button
        document.getElementById('search-button').addEventListener('click', () => {
            map.eachLayer((layer) => {
                if (layer instanceof L.Polyline) {
                    map.removeLayer(layer);
                }
            });
            fetchPolylineData(document.getElementById('filter').value);
        });
        } catch (error) {
            console.error('Error fetching data:', error.message);
        }
    });
    
</script>

</body>
</html>
