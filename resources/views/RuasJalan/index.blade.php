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
                        newRow.id = `row-${ruas.id}`;
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
</body>

</html>
