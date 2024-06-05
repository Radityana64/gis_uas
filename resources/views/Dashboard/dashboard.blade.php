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
    <style>
        #map {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }
        .form-container {
            position: absolute;
            z-index: 10;
            top: 0;
            left: 0;
            right: 0;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .search-container {
            display: flex;
            align-items: center;
        }
        .search-container input[type="text"] {
            padding: 0.5rem;
            border: none;
            border-radius: 0.5rem;
            margin-right: 1rem;
            margin-left: 16rem;
        }
        .content-container {
            overflow-y: auto;
            z-index: 1;
            width: calc(100% - 5rem);
            max-height: calc(100% - 2rem);
            margin-top: 2rem;
            margin-left: 1rem;
            border-radius: 1rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }
        .sidebar {
            position: fixed;
            top: 1rem;
            left: 1rem;
            bottom: 1rem;
            width: 14rem;
            z-index: 10;
            background-color: #3B82F6;
            border-radius: 1rem;
            padding: 1rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .sidebar-item {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: background-color 0.3s ease;
        }
        .sidebar-item:hover {
            background-color: #2563EB;
        }
        .sidebar-item a {
            color: white;
            text-decoration: none;
        }
        .sidebar-item.active {
            background-color: #2563EB;
        }
    </style>
</head>
<body>

<div id="map"></div>

<div class="form-container">    
    <div class="search-container">
        <input type="text" placeholder="Search...">
        <button><i class="fas fa-search"></i></button>
    </div>
    <div class="flex justify-between items-center bg-gray-100 p-4 rounded-tl-2xl rounded-tr-2xl rounded-bl-2xl rounded-br-2xl " >
        <span class="ml-auto mr-4">Nama User</span>
    </div>
</div>  

<div class="content-container">    
    <!-- Content -->
    <div class="flex justify-between items-center bg-gray-100 p-4 rounded-tl-2xl rounded-tr-2xl">
        <div>
            <span class="cursor-pointer">Search</span>
        </div>
    </div>
</div>

<div class="sidebar bg-gray-800 text-white h-full py-6 px-4 rounded-tl-2xl rounded-tr-2xl">
    <div class="text-lg font-semibold mb-6">Provinsi Bali</div>
    <ul>
        <li class="sidebar-item mb-2">
            <a href="{{ route('dashboard')}}" class="flex items-center">
                <span class="mr-3"><i class="fas fa-chart-line"></i></span>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="sidebar-item mb-2">
            <a href="{{ route('RuasJalan.index') }}" class="flex items-center">
                <span class="mr-3"><i class="fas fa-road"></i></span>
                <span>Data Ruas Jalan</span>
            </a>
        </li>
        <li class="sidebar-item mb-2">
            <a href="#" class="flex items-center">
                <span class="mr-3"><i class="fas fa-user"></i></span>
                <span>Profil</span>
            </a>
        </li>
    </ul>
    <ul class="mt-auto">
        <li class="sidebar-item bg-blue-900 rounded-lg py-2 px-4 text-center mb-2">
            <a href="#" class="flex items-center justify-center">
                <span class="mr-3"><i class="fas fa-sign-out-alt"></i></span>
                <span>Logout</span>
            </a>
        </li>
    </ul>
</div>

<!-- Token API -->
<meta name="api-token" content="{{ session('token') }}">

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/leaflet-geometryutil@0.0.2/dist/leaflet.geometryutil.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        var map = L.map('map').setView([-8.65, 115.22], 10);
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

        // Function to draw polylines on the map
        // Function to draw polylines on the map
        function drawPolylines(polylineData) {
            polylineData.forEach(polyline => {
                const coordinates = polyline.paths.split(' ').map(coord => {
                    const [lat, lng] = coord.trim().split(',').map(parseFloat);
                    return [lat, lng];
                });

                // Check if coordinates are valid
                if (!coordinates.every(coord => !isNaN(coord[0]) && !isNaN(coord[1]))) {
                    console.error('Invalid coordinates:', coordinates);
                    return; // Skip invalid coordinates
                }

                const line = L.polyline(coordinates, { color: 'red' }).addTo(map);
            });
        }
        
        const token = document.querySelector('meta[name="api-token"]').getAttribute('content');

        // Fetch polyline data from the API
        fetch('https://gisapis.manpits.xyz/api/ruasjalan', {
            headers: {
                Authorization: `Bearer ${token}`,
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log('Data polylines:', data); // Log polyline data to ensure it's received correctly
            drawPolylines(data.ruasjalan);
        })
        .catch(error => {
            console.error('Error fetching polyline data:', error);
        });
    });

</script>

</body>
</html>
