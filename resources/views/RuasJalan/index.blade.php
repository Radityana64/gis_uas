<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Polyline</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <style>
        /* #map {
            height: 600px;
            margin-top: 20px;
        } */

        .custom-card {
            background-color: #1a1a1a;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
            margin-bottom: 20px;
        }

        .custom-card-header {
            padding: 20px;
            border-bottom: 2px solid #333333;
            border-radius: 10px 10px 0px 0px;
            background-color: #1a1a1a;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .custom-card-body {
            padding: 20px;
        }

        .custom-label {
            font-weight: bold;
        }

        .form-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .form-section>div {
            flex: 1;
            margin: 0 10px;
        }
    </style>
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
            <!-- <div id="map" class="w-full h-96"></div> -->
            <table class="table w-full">
                <thead>
                    <tr>
                        <th class="py-3 px-6 text-left bg-gray-200 font-semibold text-sm uppercase">Nama Ruas</th>
                        <th class="py-3 px-6 text-left bg-gray-200 font-semibold text-sm uppercase">Koordinat</th>
                        <th class="py-3 px-6 text-left bg-gray-200 font-semibold text-sm uppercase">Panjang</th>
                        <th class="py-3 px-6 text-left bg-gray-200 font-semibold text-sm uppercase">Lebar</th>
                        <th class="py-3 px-6 text-left bg-gray-200 font-semibold text-sm uppercase">Eksisting</th>
                        <th class="py-3 px-6 text-left bg-gray-200 font-semibold text-sm uppercase">Kondisi</th>
                        <th class="py-3 px-6 text-left bg-gray-200 font-semibold text-sm uppercase">Jenis Jalan</th>
                        <th class="py-3 px-6 text-left bg-gray-200 font-semibold text-sm uppercase">Keterangan</th>
                        <th class="py-3 px-6 text-left bg-gray-200 font-semibold text-sm uppercase">Action</th>
                    </tr>
                </thead>
                <tbody id="polylineTableBody">
                    @if(isset($ruasjalans['ruasjalan']) && is_array($ruasjalans['ruasjalan']))
                    @foreach ($ruasjalans['ruasjalan'] as $ruasjalan)
                    <tr class="border-b border-gray-200">
                        <td class="py-3 px-6 text-left whitespace-nowrap">{{ $ruasjalan['nama_ruas'] }}</td>
                        <td class="py-3 px-6 text-left whitespace-nowrap">{{ $ruasjalan['paths'] }}</td>
                        <td class="py-3 px-6 text-left whitespace-nowrap">{{ $ruasjalan['panjang'] }}</td>
                        <td class="py-3 px-6 text-left whitespace-nowrap">{{ $ruasjalan['lebar'] }}</td>
                        <td class="py-3 px-6 text-left whitespace-nowrap">{{ $ruasjalan['eksisting_id'] }}</td>
                        <td class="py-3 px-6 text-left whitespace-nowrap">{{ $ruasjalan['kondisi_id'] }}</td>
                        <td class="py-3 px-6 text-left whitespace-nowrap">{{ $ruasjalan['jenisjalan_id'] }}</td>
                        <td class="py-3 px-6 text-left whitespace-nowrap">{{ $ruasjalan['keterangan'] }}</td>
                        <td class="py-3 px-6 text-left whitespace-nowrap space-x-2">
                            <a href="{{ route('RuasJalan.edit', $ruasjalan['id']) }}" class="btn btn-primary">Edit</a>
                            <form action="{{ route('RuasJalan.destroy', $ruasjalan['id']) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet-geometryutil@0.0.2/dist/leaflet.geometryutil.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', async function () {
            const token = localStorage.getItem("token");
            const api_main_url = localStorage.getItem("api_main_url");

            if (!token || !api_main_url) {
                console.error('Token or API URL is missing');
                return;
            }

            const headers = {
                "Authorization": `Bearer ${token}`,
                "Content-Type": "application/json"
            };

            async function fetchData(url) {
                const response = await fetch(url, { headers });
                if (!response.ok) {
                    throw new Error('Network response was not ok ' + response.statusText);
                }
                return response.json();
            }

            try {
                const data_region = await fetchData(api_main_url + "api/mregion");
                const data_ruas = await fetchData(api_main_url + "api/ruasjalan");
                const eksistingData = await fetchData(api_main_url + "api/meksisting");
                const kondisiData = await fetchData(api_main_url + "api/mkondisi");
                const jenisJalanData = await fetchData(api_main_url + "api/mjenisjalan");

                const tableBody = document.getElementById("polylineTableBody");
                const map = L.map('map').setView([-0.789275, 113.921327], 5);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                if (Array.isArray(data_ruas.ruasjalan)) {
                    data_ruas.ruasjalan.forEach(ruas => {
                        if (typeof ruas === 'object' && ruas !== null && 'nama_ruas' in ruas) {
                            const polyline = L.polyline(JSON.parse(ruas.paths), { color: 'blue' }).addTo(map);
                            map.fitBounds(polyline.getBounds());

                            const eksisting = eksistingData.eksisting.find(e => e.id == ruas.eksisting_id);
                            const kondisi = kondisiData.kondisi.find(k => k.id == ruas.kondisi_id);
                            const jenisjalan = jenisJalanData.jenisjalan.find(j => j.id == ruas.jenisjalan_id);

                            const newRow = document.createElement("tr");
                            newRow.innerHTML = `
                                <td>${ruas.nama_ruas}</td>
                                <td>${ruas.paths}</td>
                                <td>${ruas.panjang}</td>
                                <td>${ruas.lebar}</td>
                                <td>${eksisting ? eksisting.eksisting : '-'}</td>
                                <td>${kondisi ? kondisi.kondisi : '-'}</td>
                                <td>${jenisjalan ? jenisjalan.jenisjalan : '-'}</td>
                                <td>${ruas.keterangan}</td>
                                <td class="flex space-x-2">
                                    <a href="edit.html?id=${ruas.id}" class="btn btn-primary">Edit</a>
                                    <form action="/polyline/destroy/${ruas.id}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
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
                console.error('Error fetching data:', error);
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

        // var map = L.map('map').setView([-8.409518, 115.188919], 13);

        // const tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        //     maxZoom: 20,
        //     attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        // }).addTo(map);

        // var Esri_World = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        //     attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
        // });

        // var Esri_Map = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/NatGeo_World_Map/MapServer/tile/{z}/{y}/{x}', {
        //     attribution: 'Tiles &copy; Esri &mdash; National Geographic, Esri, DeLorme, NAVTEQ, UNEP-WCMC, USGS, NASA, ESA, METI, NRCAN, GEBCO, NOAA, iPC',
        //     maxZoom: 16
        // });

        // var Stadia_Dark = L.tileLayer('https://tiles.stadiamaps.com/tiles/alidade_smooth_dark/{z}/{x}/{y}{r}.{ext}', {
        //     minZoom: 0,
        //     maxZoom: 20,
        //     attribution: '&copy; <a href="https://www.stadiamaps.com/" target="_blank">Stadia Maps</a> &copy; <a href="https://openmaptiles.org/" target="_blank">OpenMapTiles</a> &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        //     ext: 'png'
        // });

        // var baseLayers = {
        //     "OSM Tiles": tiles,
        //     "ESRI World Imagery": Esri_World,
        //     "ESRI Map": Esri_Map,
        //     "Stadia Dark": Stadia_Dark
        // };

        // L.control.layers(baseLayers).addTo(map);

        // var drawnItems = new L.FeatureGroup();
        // map.addLayer(drawnItems);

        // var drawControl = new L.Control.Draw({
        //     edit: {
        //         featureGroup: drawnItems
        //     },
        //     draw: {
        //         polyline: true,
        //         polygon: true,
        //         circle: false,
        //         rectangle: false,
        //         marker: false,
        //         circlemarker: false
        //     }
        // });
        // map.addControl(drawControl);

        // map.on('draw:created', function (event) {
        //     var layer = event.layer;
        //     drawnItems.addLayer(layer);

        //     var latlngs;
        //     if (layer instanceof L.Polyline) {
        //         latlngs = layer.getLatLngs();
        //     } else if (layer instanceof L.Polygon) {
        //         latlngs = layer.getLatLngs()[0];
        //     }

        //     var latlngString = latlngs.map(function (latlng) {
        //         return `${latlng.lat}, ${latlng.lng}`;
        //     }).join('\n');

        //     document.getElementById('latlng').value = latlngString;

        //     var length = calculateLength(latlngs);
        //     alert(`Panjang Polyline: ${length.toFixed(2)} meters`);
        // });

        // map.on('draw:edited', function (event) {
        //     var layers = event.layers;
        //     var latlngs = [];

        //     layers.eachLayer(function (layer) {
        //         if (layer instanceof L.Polyline) {
        //             latlngs = latlngs.concat(layer.getLatLngs());
        //         } else if (layer instanceof L.Polygon) {
        //             latlngs = latlngs.concat(layer.getLatLngs()[0]);
        //         }
        //     });

        //     var latlngString = latlngs.map(function (latlng) {
        //         return `${latlng.lat}, ${latlng.lng}`;
        //     }).join('\n');

        //     document.getElementById('latlng').value = latlngString;

        //     var length = calculateLength(latlngs);
        //     alert(`Panjang Polyline: ${length.toFixed(2)} meters`);
        // });

        // function calculateLength(latlngs) {
        //     var length = 0;
        //     for (var i = 0; i < latlngs.length - 1; i++) {
        //         length += latlngs[i].distanceTo(latlngs[i + 1]);
        //     }
        //     return length;
        // }

        // document.getElementById('form').addEventListener('reset', function () {
        //     drawnItems.clearLayers();
        //     document.getElementById('latlng').value = '';
        // });
    </script>
</body>

</html>
