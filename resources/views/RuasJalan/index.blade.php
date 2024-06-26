<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Ruas Jalan</title>
    <meta name="api-token" content="{{ session('token') }}">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
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
        .overflow-y-scroll {
            max-height: 500px; /* Atur ketinggian maksimum sesuai kebutuhan */
            overflow-y: auto;
        }
        
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Data Ruas Jalan</h1>
            <div class="space-x-4">
                <a href="{{ route('RuasJalan.create', ['previous' => 'rjindex']) }}" class="w-full px-4 py-2 text-white bg-indigo-600 rounded-md shadow-sm hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Create Data</a>
                <a href="{{ route('dashboard') }}" class="w-full px-4 py-2 text-white bg-blue-500 rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-400">Dashboard</a>
            </div>
        </div>
        <div class="mb-6 flex flex-wrap gap-4" id="summary">
        <!-- populate data summary about kondisi and jenisjalan  -->
        </div>
        <div>
            <input type="text" id="searchInput" class="search-input border border-gray-300 shadow-sm px-3 py-2 rounded-lg mb-2" placeholder="Search...">
        </div>
        <div class="overflow-x-auto overflow-y-scroll bg-white rounded-lg shadow-md max-h-screen">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2 text-left cursor-pointer" data-sort="no">No <span id="sortIconNo"></span></th>
                        <th class="px-4 py-2 text-left cursor-pointer" data-sort="nama_ruas">Nama Ruas <span id="sortIconNamaRuas"></span></th>
                        <th class="px-4 py-2 text-left cursor-pointer" data-sort="kode_ruas">Kode Ruas <span id="sortIconKodeRuas"></span></th>
                        <th class="px-4 py-2 text-left cursor-pointer" data-sort="nama_desa">Nama Desa <span id="sortIconNamaDesa"></span></th>
                        <th class="px-4 py-2 text-left cursor-pointer" data-sort="panjang">Panjang <span id="sortIconPanjang"></span></th>
                        <th class="px-4 py-2 text-left cursor-pointer" data-sort="lebar">Lebar <span id="sortIconLebar"></span></th>
                        <th class="px-4 py-2 text-left cursor-pointer" data-sort="eksisting">Eksisting <span id="sortIconEksisting"></span></th>
                        <th class="px-4 py-2 text-left cursor-pointer" data-sort="jenis_jalan">Jenis Jalan <span id="sortIconJenisJalan"></span></th>
                        <th class="px-4 py-2 text-left cursor-pointer" data-sort="kondisi">Kondisi <span id="sortIconKondisi"></span></th>
                        <th class="px-4 py-2 text-left" style= "width: 300px;">Keterangan</th>
                        <th class="px-4 py-2 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody id="polylineTableBody">
                    <!-- Data akan diisi menggunakan JavaScript -->
                </tbody>
            </table>
        </div>
    </div>

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
                        window.location.href = '/RuasJalan'; // Ganti dengan URL halaman dashboard yang sesuai
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

                // Pastikan data yang diperlukan tersedia dan dalam format yang benar
                const ruasjalanList = ruasjalanData.ruasjalan;
                const desaMap = new Map(regionData.desa.map(item => [item.id, item.desa]));
                const eksistingMap = new Map(eksistingData.eksisting.map(item => [item.id, item.eksisting]));
                const jenisjalanMap = new Map(jenisjalanData.eksisting.map(item => [item.id, item.jenisjalan]));
                const kondisiMap = new Map(kondisiData.eksisting.map(item => [item.id, item.kondisi]));

                const tableBody = document.getElementById("polylineTableBody");
                const summary = document.getElementById("summary");
                const searchInput = document.getElementById("searchInput");

                let totalData = 0;
                let kondisiCounts = { baik: 0, sedang: 0, buruk: 0 };
                let jenisJalanCounts = { desa: 0, kabupaten: 0, provinsi: 0 };

                ruasjalanList.forEach(ruas => {
                    if (ruas.kondisi_id === 1) {
                        kondisiCounts.baik++;
                    } else if (ruas.kondisi_id === 2) {
                        kondisiCounts.sedang++;
                    } else if (ruas.kondisi_id === 3) {
                        kondisiCounts.buruk++;
                    }
                });

                // Hitung jumlah jenis jalan
                ruasjalanList.forEach(ruas => {
                    if (ruas.jenisjalan_id === 1) {
                        jenisJalanCounts.desa++;
                    } else if (ruas.jenisjalan_id === 2) {
                        jenisJalanCounts.kabupaten++;
                    } else if (ruas.jenisjalan_id === 3) {
                        jenisJalanCounts.provinsi++;
                    }
                });

                const displayData = (data) => {
                    // Kosongkan tabel sebelum menambahkan data baru
                    tableBody.innerHTML = '';

                    data.forEach((ruas, index) => {
                        totalData++;

                        const newRow = document.createElement("tr");
                        newRow.id = `row-${ruas.id}`;
                        newRow.className = index % 2 === 0 ? 'bg-gray-100' : 'bg-white';
                        newRow.innerHTML = `
                            <td class="border px-4 py-2">${index + 1}</td>
                            <td class="border px-4 py-2">${ruas.nama_ruas}</td>
                            <td class="border px-4 py-2">${ruas.kode_ruas}</td>
                            <td class="border px-4 py-2">${desaMap.get(ruas.desa_id)}</td>
                            <td class="border px-4 py-2">${parseFloat(ruas.panjang).toFixed(2)}</td>
                            <td class="border px-4 py-2">${parseFloat(ruas.lebar).toFixed(2)}</td>
                            <td class="border px-4 py-2">${eksistingMap.get(ruas.eksisting_id)}</td>
                            <td class="border px-4 py-2">${jenisjalanMap.get(ruas.jenisjalan_id)}</td>
                            <td class="border px-4 py-2">${kondisiMap.get(ruas.kondisi_id)}</td>
                            <td class="border px-4 py-2">${ruas.keterangan}</td>
                            <td class="border px-4 py-2">
                                <button class="btn btn-primary bg-blue-500 text-white" onclick="window.location.href='/ruasjalan/${ruas.id}/edit?previous=rjindex'">Edit</button>
                                <button onclick="deleteData(${ruas.id})" class="btn btn-danger bg-red-500 text-white">Delete</button>
                            </td>
                        `;
                        tableBody.appendChild(newRow);
                    });
                };

                // Inisialisasi data pertama kali
                displayData(ruasjalanList);

                // Sorting function
                const sortTable = (sortBy) => {
                    let sortedData = [...ruasjalanList];

                    switch (sortBy) {
                        case 'no':
                            sortedData.sort((a, b) => a.id - b.id);
                            break;
                        case 'no_desc':
                            sortedData.sort((a, b) => b.id - a.id);
                            break;
                        case 'nama_ruas':
                            sortedData.sort((a, b) => a.nama_ruas.localeCompare(b.nama_ruas));
                            break;
                        case 'nama_ruas_desc':
                            sortedData.sort((a, b) => b.nama_ruas.localeCompare(a.nama_ruas));
                            break;
                        case 'kode_ruas':
                            sortedData.sort((a, b) => a.kode_ruas.localeCompare(b.kode_ruas));
                            break;
                        case 'kode_ruas_desc':
                            sortedData.sort((a, b) => b.kode_ruas.localeCompare(a.kode_ruas));
                            break;
                        case 'nama_desa':
                            sortedData.sort((a, b) => desaMap.get(a.desa_id).localeCompare(desaMap.get(b.desa_id)));
                            break;
                        case 'nama_desa_desc':
                            sortedData.sort((a, b) => desaMap.get(b.desa_id).localeCompare(desaMap.get(a.desa_id)));
                            break;
                        case 'panjang':
                            sortedData.sort((a, b) => a.panjang - b.panjang);
                            break;
                        case 'panjang_desc':
                            sortedData.sort((a, b) => b.panjang - a.panjang);
                            break;
                        case 'lebar':
                            sortedData.sort((a, b) => a.lebar - b.lebar);
                            break;
                        case 'lebar_desc':
                            sortedData.sort((a, b) => b.lebar - a.lebar);
                            break;
                        case 'eksisting':
                            sortedData.sort((a, b) => eksistingMap.get(a.eksisting_id).localeCompare(eksistingMap.get(b.eksisting_id)));
                            break;
                        case 'eksisting_desc':
                            sortedData.sort((a, b) => eksistingMap.get(b.eksisting_id).localeCompare(eksistingMap.get(a.eksisting_id)));
                            break;
                        case 'jenis_jalan':
                            sortedData.sort((a, b) => jenisjalanMap.get(a.jenisjalan_id).localeCompare(jenisjalanMap.get(b.jenisjalan_id)));
                            break;
                        case 'jenis_jalan_desc':
                            sortedData.sort((a, b) => jenisjalanMap.get(b.jenisjalan_id).localeCompare(jenisjalanMap.get(a.jenisjalan_id)));
                            break;
                        case 'kondisi':
                            sortedData.sort((a, b) => kondisiMap.get(a.kondisi_id).localeCompare(kondisiMap.get(b.kondisi_id)));
                            break;
                        case 'kondisi_desc':
                            sortedData.sort((a, b) => kondisiMap.get(b.kondisi_id).localeCompare(kondisiMap.get(a.kondisi_id)));
                            break;
                        default:
                            break;
                    }
                    // Tampilkan data yang sudah diurutkan
                    displayData(sortedData);

                };

                // Event listener untuk setiap header tabel agar bisa diurutkan
                const tableHeaders = document.querySelectorAll('thead th');
                    tableHeaders.forEach(header => {
                        header.addEventListener('click', () => {
                            const sortBy = header.getAttribute('data-sort'); // Ambil nilai data-sort dari th
                            const currentSortOrder = header.getAttribute('data-sort-order') || 'asc'; // Ambil nilai data-sort-order atau default ke 'asc'
                            resetSortIcons();

                            // Atur ikon sort sesuai dengan urutan saat ini
                            if (currentSortOrder === 'asc') {
                                header.querySelector('span').innerHTML = ' &#x25B2;'; // Up arrow
                                header.setAttribute('data-sort-order', 'desc'); // Ubah data-sort-order menjadi 'desc'
                                sortTable(sortBy);
                            } else {
                                header.querySelector('span').innerHTML = ' &#x25BC;'; // Down arrow
                                header.setAttribute('data-sort-order', 'asc'); // Ubah data-sort-order menjadi 'asc'
                                sortTable(sortBy + '_desc');
                            }
                        });
                    });

                // Fungsi untuk mengatur ulang icon sort
                const resetSortIcons = () => {
                    const sortIcons = document.querySelectorAll('[id^="sortIcon"]');
                    sortIcons.forEach(icon => icon.innerHTML = '');
                };

                // Searching function
                searchInput.addEventListener('input', () => {
                    const searchValue = searchInput.value.trim().toLowerCase();
                    const filteredData = ruasjalanList.filter(ruas =>
                        ruas.nama_ruas.toLowerCase().includes(searchValue) ||
                        ruas.kode_ruas.toLowerCase().includes(searchValue) ||
                        desaMap.get(ruas.desa_id).toLowerCase().includes(searchValue) ||
                        eksistingMap.get(ruas.eksisting_id).toLowerCase().includes(searchValue) ||
                        jenisjalanMap.get(ruas.jenisjalan_id).toLowerCase().includes(searchValue) ||
                        kondisiMap.get(ruas.kondisi_id).toLowerCase().includes(searchValue) ||
                        ruas.keterangan.toLowerCase().includes(searchValue)
                    );
                    displayData(filteredData);
                });


                // Tampilkan ringkasan data
                summary.innerHTML = `
                    <div class="w-full max-w-md p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <h5 class="text-xl font-bold leading-none text-gray-900 dark:text-white">Kondisi Jalan</h5>
                        </div>
                        <div class="flow-root">
                            <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                                <li class="py-3 sm:py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 rounded-full bg-green-500"></div>
                                        </div>
                                        <div class="flex-1 min-w-0 ms-4 ml-4">
                                            <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                                Baik
                                            </p>
                                        </div>
                                        <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                            ${kondisiCounts.baik}
                                        </div>
                                    </div>
                                </li>
                                <li class="py-3 sm:py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 rounded-full bg-yellow-500"></div>
                                        </div>
                                        <div class="flex-1 min-w-0 ms-4 ml-4">
                                            <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                                Sedang
                                            </p>
                                        </div>
                                        <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                            ${kondisiCounts.sedang}
                                        </div>
                                    </div>
                                </li>
                                <li class="py-3 sm:py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 rounded-full bg-red-500"></div>
                                        </div>
                                        <div class="flex-1 min-w-0 ms-4 ml-4">
                                            <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                                Buruk
                                            </p>
                                        </div>
                                        <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                            ${kondisiCounts.buruk}
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="w-full max-w-md p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <h5 class="text-xl font-bold leading-none text-gray-900 dark:text-white">Jenis Jalan</h5>
                        </div>
                        <div class="flow-root">
                            <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                                <li class="py-3 sm:py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 rounded-full bg-green-500"></div>
                                        </div>
                                        <div class="flex-1 min-w-0 ms-4 ml-4">
                                            <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                                Desa
                                            </p>
                                        </div>
                                        <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                            ${jenisJalanCounts.desa}
                                        </div>
                                    </div>
                                </li>

                                <li class="py-3 sm:py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 rounded-full bg-yellow-500"></div>
                                        </div>
                                        <div class="flex-1 min-w-0 ms-4 ml-4">
                                            <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                                Kabupaten
                                            </p>
                                        </div>
                                        <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                            ${jenisJalanCounts.kabupaten}
                                        </div>
                                    </div>
                                </li>
                                <li class="py-3 sm:py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 rounded-full bg-red-500"></div>
                                        </div>
                                        <div class="flex-1 min-w-0 ms-4 ml-4">
                                            <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                                Provinsi
                                            </p>
                                        </div>
                                        <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                            ${jenisJalanCounts.provinsi}
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                `;

            } catch (error) {
                console.error('Error fetching data:', error.message);
            }
        });
    </script>
</body>
</html>
