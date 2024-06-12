<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Ruas Jalan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Data Ruas Jalan</h1>
            <div class="space-x-4">
                <a href="{{ route('RuasJalan.create') }}" class="btn btn-outline btn-primary">Create Data</a>
                <a href="{{ route('dashboard') }}" class="btn btn-outline btn-primary">Dashboard</a>
            </div>
        </div>

        <div class="overflow-x-auto bg-white rounded-lg shadow-md">
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Nama Ruas</th>
                        <th class="px-4 py-2">Nama Desa</th>
                        <th class="px-4 py-2">Panjang</th>
                        <th class="px-4 py-2">Lebar</th>
                        <th class="px-4 py-2">Eksisting</th>
                        <th class="px-4 py-2">Jenis Jalan</th>
                        <th class="px-4 py-2">Kondisi</th>
                        <th class="px-4 py-2">Keterangan</th>
                        <th class="px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody id="polylineTableBody">
                    <!-- Data akan diisi menggunakan JavaScript -->
                </tbody>
            </table>
        </div>
    </div>
    
    <meta name="api-token" content="{{ session('token') }}">

    <script>
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
                    fetchData(api_main_url + "api/mkondisi")
                ]);

                console.log('ruasjalanData:', ruasjalanData);
                console.log('regionData:', regionData);
                console.log('eksistingData:', eksistingData);
                console.log('jenisjalanData:', jenisjalanData);
                console.log('kondisiData:', kondisiData);

                const ruasjalanList = ruasjalanData.ruasjalan;
                const desaMap = new Map(regionData.desa.map(item => [item.id, item.desa]));
                const eksistingMap = new Map(eksistingData.eksisting.map(item => [item.id, item.eksisting]));
                const jenisjalanMap = new Map(jenisjalanData.eksisting.map(item => [item.id, item.jenisjalan]));
                const kondisiMap = new Map(kondisiData.eksisting.map(item => [item.id, item.kondisi]));

                const tableBody = document.getElementById("polylineTableBody");

                if (Array.isArray(ruasjalanList)) {
                    ruasjalanList.forEach(ruas => {
                        const newRow = document.createElement("tr");
                        newRow.innerHTML = `
                            <td class="border px-4 py-2">${ruas.nama_ruas}</td>
                            <td class="border px-4 py-2">${desaMap.get(ruas.desa_id)}</td>
                            <td class="border px-4 py-2">${ruas.panjang}</td>
                            <td class="border px-4 py-2">${ruas.lebar}</td>
                            <td class="border px-4 py-2">${eksistingMap.get(ruas.eksisting_id)}</td>
                            <td class="border px-4 py-2">${jenisjalanMap.get(ruas.jenisjalan_id)}</td>
                            <td class="border px-4 py-2">${kondisiMap.get(ruas.kondisi_id)}</td>
                            <td class="border px-4 py-2">${ruas.keterangan}</td>
                            <td class="border px-4 py-2 flex space-x-2">
                                <a href="{{ route('RuasJalan.edit', ':id') }}" class="btn btn-primary" id="editBtn-${ruas.id}">Edit</a>
                                <button onclick="deleteData(${ruas.id})" class="btn btn-danger">Delete</button>
                            </td>
                        `;
                        tableBody.appendChild(newRow);

                        // Mengganti placeholder :id dengan ID sebenarnya
                        const editBtn = document.getElementById(`editBtn-${ruas.id}`);
                        editBtn.href = editBtn.href.replace(':id', ruas.id);
                    });
                } else {
                    console.error('Invalid data format:', ruasjalanList);
                }
            } catch (error) {
                console.error('Error fetching data:', error.message);
            }
        });
    </script>



<!-- 
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet-geometryutil@0.0.2/dist/leaflet.geometryutil.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="api-token" content="{{ session('token') }}">
    <script>
        document.addEventListener('DOMContentLoaded', async function () {
            const api_main_url = "https://gisapis.manpits.xyz/";
            const token = document.querySelector('meta[name="api-token"]').getAttribute('content');

            async function fetchData(url) {
                const response = await fetch(url, {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    }
                });
                if (!response.ok) {
                    throw new Error('Network response was not ok ' + response.statusText);
                }
                return response.json();
            }

            try {
                const data_region = await fetchData(api_main_url + "api/mregion");
                const data_ruas = await fetchData(api_main_url + "api/ruasjalan");

                const tableBody = document.getElementById("polylineTableBody");
                // Matikan bagian peta
                // const map = L.map('map').setView([-0.789275, 113.921327], 5);
                // L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                //     attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                // }).addTo(map);

                if (Array.isArray(data_ruas.ruasjalan)) {
                    data_ruas.ruasjalan.forEach(ruas => {
                        if (typeof ruas === 'object' && ruas !== null && 'nama_ruas' in ruas) {
                            // Matikan bagian yang berkaitan dengan peta
                            // const polyline = L.polyline(JSON.parse(ruas.paths), { color: 'blue' }).addTo(map);
                            // map.fitBounds(polyline.getBounds());

                            const newRow = document.createElement("tr");
                            newRow.innerHTML = `
                                <td>${ruas.nama_ruas}</td>
                                <td>${ruas.paths}</td>
                                <td>${ruas.panjang}</td>
                                <td>${ruas.lebar}</td>
                                <td>${ruas.eksisting_id}</td>
                                <td>${ruas.kondisi_id}</td>
                                <td>${ruas.jenisjalan_id}</td>
                                <td>${ruas.keterangan}</td>
                                <td class="flex space-x-2">
                                    <a href="${api_main_url}RuasJalan/edit/${ruas.id}" class="btn btn-primary">Edit</a>
                                    <button onclick="deleteData(${ruas.id})" class="btn btn-danger">Delete</button>
                                </td>
                            `;
                            tableBody.appendChild(newRow);
                        } else {
                            console.error('Invalid ruas data:', ruas);
                        }
                    });
                } else {
                    console.error('Invalid data_ruas.ruasjalan:', data_ruas.ruasjalan);
                }

            } catch (error) {
                console.error('Error fetching data:', error.message);
            }
        });

        function deleteData(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Perform delete operation here
                    // Example: axios.delete(`api_url/polyline/${id}`)
                    Swal.fire(
                        'Deleted!',
                        'Your data has been deleted.',
                        'success'
                    );
                }
            });
        }

        var map = L.map('map').setView([-8.409518, 115.188919], 13);

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

        L.control.layers(baseLayers).addTo(map);

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

        map.on('draw:created', function (event) {
            var layer = event.layer;
            drawnItems.addLayer(layer);

            var latlngs;
            if (layer instanceof L.Polyline) {
                latlngs = layer.getLatLngs();
            } else if (layer instanceof L.Polygon) {
                latlngs = layer.getLatLngs()[0];
            }

            var latlngString = latlngs.map(function (latlng) {
                return `${latlng.lat}, ${latlng.lng}`;
            }).join('\n');

            document.getElementById('latlng').value = latlngString;

            var length = calculateLength(latlngs);
            alert(`Panjang Polyline: ${length.toFixed(2)} meters`);
        });

        map.on('draw:edited', function (event) {
            var layers = event.layers;
            var latlngs = [];

            layers.eachLayer(function (layer) {
                if (layer instanceof L.Polyline) {
                    latlngs = latlngs.concat(layer.getLatLngs());
                } else if (layer instanceof L.Polygon) {
                    latlngs = latlngs.concat(layer.getLatLngs()[0]);
                }
            });

            var latlngString = latlngs.map(function (latlng) {
                return `${latlng.lat}, ${latlng.lng}`;
            }).join('\n');

            document.getElementById('latlng').value = latlngString;

            var length = calculateLength(latlngs);
            alert(`Panjang Polyline: ${length.toFixed(2)} meters`);
        });

        function calculateLength(latlngs) {
            var length = 0;
            for (var i = 0; i < latlngs.length - 1; i++) {
                length += latlngs[i].distanceTo(latlngs[i + 1]);
            }
            return length;
        }

        document.getElementById('form').addEventListener('reset', function () {
            drawnItems.clearLayers();
            document.getElementById('latlng').value = '';
        });
    </script> -->
</body>

</html>
