<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Provinsi Bali</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map {
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 0;
        }
        .dropdown {
            position: relative;
            display: inline-block;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }
        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }
        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }
        .dropdown:hover .dropdown-content {
            display: block;
        }
    </style>
</head>
<body class="bg-white text-gray-900 relative">

    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="flex flex-col w-64 bg-blue-500 text-white shadow-lg mb-4 mt-4 ml-4 rounded-2xl">
            <div class="flex items-center p-4">
                <span class="text-lg font-semibold">Provinsi Bali</span>
            </div>
            <ul class="flex-1">
                <li class="px-4 py-2  hover:bg-blue-700 transition">Dashboard</li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('RuasJalan.index') }}">
                        <span class="px-4 py-2  hover:bg-blue-700 transition">
                            Tambah Ruas Jalan
                        </span>
                    </a>
                </li>
                <li class="px-4 py-2  hover:bg-blue-700 transition">Profil</li>
            </ul>
            <ul class="mt-auto">
                <li class="px-4 py-2 bg-blue-600  hover:bg-blue-700 transition rounded-b-2xl">Logout</li>
            </ul>
        </div>
        
        <!-- Content -->
        <div class="flex-1 flex flex-col relative">
            <!-- Header -->
            <div class="flex justify-between items-center bg-gray-100 p-4 shadow-md rounded-2xl mb-4 mt-4 mr-4 ml-4" >
                <div class="dropdown">
                    <span class="cursor-pointer">Telusuri</span>
                    <div class="dropdown-content">
                        <select id="provinsi" class="block w-full border border-gray-300 rounded-md p-2" onchange="updateKabupaten()">
                            <option value="" disabled selected>Pilih Provinsi</option>
                        </select>
                        <select id="kabupaten" class="block w-full border border-gray-300 rounded-md p-2 mt-2" onchange="updateKecamatan()" disabled>
                            <option value="" disabled selected>Pilih Kabupaten/Kota</option>
                        </select>
                        <select id="kecamatan" class="block w-full border border-gray-300 rounded-md p-2 mt-2" onchange="updateDesa()" disabled>
                            <option value="" disabled selected>Pilih Kecamatan</option>
                        </select>
                        <select id="desa" class="block w-full border border-gray-300 rounded-md p-2 mt-2" disabled>
                            <option value="" disabled selected>Pilih Desa</option>
                        </select>
                    </div>
                </div>
                <span>Nama User</span>
            </div>
            <!-- Map -->
            <div class="flex-1 relative">
                <div id="map"></div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([-8.65, 115.22], 10); // Contoh koordinat untuk Bali
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Data dummy untuk dropdown
        const data = {
            provinsi: ["Provinsi 1", "Provinsi 2"],
            kabupaten: {
                "Provinsi 1": ["Kabupaten 1-1", "Kabupaten 1-2"],
                "Provinsi 2": ["Kabupaten 2-1", "Kabupaten 2-2"]
            },
            kecamatan: {
                "Kabupaten 1-1": ["Kecamatan 1-1-1", "Kecamatan 1-1-2"],
                "Kabupaten 1-2": ["Kecamatan 1-2-1", "Kecamatan 1-2-2"],
                "Kabupaten 2-1": ["Kecamatan 2-1-1", "Kecamatan 2-1-2"],
                "Kabupaten 2-2": ["Kecamatan 2-2-1", "Kecamatan 2-2-2"]
            },
            desa: {
                "Kecamatan 1-1-1": ["Desa 1-1-1-1", "Desa 1-1-1-2"],
                "Kecamatan 1-1-2": ["Desa 1-1-2-1", "Desa 1-1-2-2"],
                "Kecamatan 1-2-1": ["Desa 1-2-1-1", "Desa 1-2-1-2"],
                "Kecamatan 1-2-2": ["Desa 1-2-2-1", "Desa 1-2-2-2"],
                "Kecamatan 2-1-1": ["Desa 2-1-1-1", "Desa 2-1-1-2"],
                "Kecamatan 2-1-2": ["Desa 2-1-2-1", "Desa 2-1-2-2"],
                "Kecamatan 2-2-1": ["Desa 2-2-1-1", "Desa 2-2-1-2"],
                "Kecamatan 2-2-2": ["Desa 2-2-2-1", "Desa 2-2-2-2"]
            }
        };

        // Populate Provinsi dropdown
        const provinsiSelect = document.getElementById("provinsi");
        data.provinsi.forEach(provinsi => {
            const option = document.createElement("option");
            option.value = provinsi;
            option.textContent = provinsi;
            provinsiSelect.appendChild(option);
        });

        // Update Kabupaten dropdown based on selected Provinsi
        function updateKabupaten() {
            const provinsi = provinsiSelect.value;
            const kabupatenSelect = document.getElementById("kabupaten");
            kabupatenSelect.innerHTML = '<option value="" disabled selected>Pilih Kabupaten/Kota</option>';
            data.kabupaten[provinsi].forEach(kabupaten => {
                const option = document.createElement("option");
                option.value = kabupaten;
                option.textContent = kabupaten;
                kabupatenSelect.appendChild(option);
            });
            kabupatenSelect.disabled = false;
            document.getElementById("kecamatan").innerHTML = '<option value="" disabled selected>Pilih Kecamatan</option>';
            document.getElementById("kecamatan").disabled = true;
            document.getElementById("desa").innerHTML = '<option value="" disabled selected>Pilih Desa</option>';
            document.getElementById("desa").disabled = true;
        }

        // Update Kecamatan dropdown based on selected Kabupaten
        function updateKecamatan() {
            const kabupaten = document.getElementById("kabupaten").value;
            const kecamatanSelect = document.getElementById("kecamatan");
            kecamatanSelect.innerHTML = '<option value="" disabled selected>Pilih Kecamatan</option>';
            data.kecamatan[kabupaten].forEach(kecamatan => {
                const option = document.createElement("option");
                option.value = kecamatan;
                option.textContent = kecamatan;
                kecamatanSelect.appendChild(option);
            });
            kecamatanSelect.disabled = false;
            document.getElementById("desa").innerHTML = '<option value="" disabled selected>Pilih Desa</option>';
            document.getElementById("desa").disabled = true;
        }

        // Update Desa dropdown based on selected Kecamatan
        function updateDesa() {
            const kecamatan = document.getElementById("kecamatan").value;
            const desaSelect = document.getElementById("desa");
            desaSelect.innerHTML = '<option value="" disabled selected>Pilih Desa</option>';
            data.desa[kecamatan].forEach(desa => {
                const option = document.createElement("option");
                option.value = desa;
                option.textContent = desa;
                desaSelect.appendChild(option);
            });
            desaSelect.disabled = false;
        }
    </script>

</body>
</html>
