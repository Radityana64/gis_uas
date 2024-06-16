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
        <input type="text" id="search-input" placeholder="Search...">
        <button id="search-button"><i class="fas fa-search"></i></button>
    </div>
    <div class="search-container">
        <select class="select select-bordered w-full border-gray-300 rounded-lg shadow-sm" id="filter" name="filter" required>
            <option value="">Tandai Berdasarkan...</option>
            <option value="jenis">Jenis</option>
            <option value="kondisi">Kondisi</option>
        </select>
    </div>
    <div class="flex justify-between items-center bg-gray-100 p-4 rounded-tl-2xl rounded-tr-2xl rounded-bl-2xl rounded-br-2xl " >
        <span class="ml-auto mr-4" id = "nama_user" name="nama_user" >Nama User</span>

        <div id="jenis-legend" style="display: none;">
            <div><span class="color-box" style="background-color: red;"></span> Merah: Provinsi</div>
            <div><span class="color-box" style="background-color: yellow;"></span> Kuning: Kabupaten</div>
            <div><span class="color-box" style="background-color: green;"></span> Hijau: Desa</div>
        </div>
        <div id="kondisi-legend" style="display: none;">
            <div><span class="color-box" style="background-color: red;"></span> Merah: Rusak</div>
            <div><span class="color-box" style="background-color: yellow;"></span> Kuning: Sedang</div>
            <div><span class="color-box" style="background-color: green;"></span> Hijau: Baik</div>
        </div>
    </div>
</div> 
<!-- <div id="legend" class="bg-white p-4 rounded shadow-md absolute top-20 left-1/2 transform -translate-x-1/2 z-10">

</div>  -->

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
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="flex items-center justify-center w-full">
                    <span class="mr-3"><i class="fas fa-sign-out-alt"></i></span>
                    <span>Logout</span>
                </button>
            </form>
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
        async function deleteData(id) {
            try {
                const token = document.querySelector("meta[name='api-token']").getAttribute('content');
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
                console.log('Data berhasil dihapus');
            } catch (error) {
                console.error('Error deleting data:', error.message);
            }
        }
    
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

        // Function to draw polylines on the map with popups
        function drawPolylines(polylineData, filterType) {
            let foundPolyline = false; // Flag to check if any polyline matched the search
            const searchInput = document.getElementById('search-input').value.trim().toLowerCase();
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

                let color = 'blue'; // Default color
                if (filterType === 'jenis') {
                    switch(polyline.jenisjalan_id) {
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
                    switch(polyline.kondisi_id) {
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

                // Create a popup with the road information
                const popupContent = `
                    <div>
                        <strong>Nama Jalan:</strong> ${polyline.nama_ruas}<br>
                        <strong>Panjang:</strong> ${polyline.panjang} km<br>
                        <strong>Lebar:</strong> ${polyline.lebar} m<br>
                        <strong>Desa:</strong> ${polyline.desa_id}<br>
                        <a href="/ruasjalan/${polyline.id}/edit" class="btn btn-primary">Edit</a>
                        <button onclick="deleteData(${polyline.id})" class="btn btn-danger">Delete</button>
                    </div>
                `;
                line.bindPopup(popupContent);

                // Add polyline to map if it matches the search criteria
                // const searchInput = document.getElementById('search-input').value.trim().toLowerCase();
                if (searchInput !== '' && polyline.nama_ruas.toLowerCase().includes(searchInput)) {
                    line.addTo(map);
                    foundPolyline = true;

                    // Zoom to the polyline's bounds
                    const polylineBounds = line.getBounds();
                    map.fitBounds(polylineBounds);
                }
            });

            // // If no polyline was found matching the search, alert the user
            // if (!foundPolyline) {
            //     alert('No polyline found with that name.');
            // }
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
            if (filterType === 'jenis') {
                document.getElementById('jenis-legend').style.display = 'block';
                document.getElementById('kondisi-legend').style.display = 'none';
            } else if (filterType === 'kondisi') {
                document.getElementById('jenis-legend').style.display = 'none';
                document.getElementById('kondisi-legend').style.display = 'block';
            } else {
                document.getElementById('jenis-legend').style.display = 'none';
                document.getElementById('kondisi-legend').style.display = 'none';
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
    });
</script>

</body>
</html>
