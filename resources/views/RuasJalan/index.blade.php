<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ruas Jalan</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Ruas Jalan</h1>
            <a href="{{ route('RuasJalan.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Create Data</a>
        </div>
        <div class="bg-white shadow-md rounded my-6">
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2 px-4 bg-gray-200 text-gray-600 font-bold uppercase text-sm text-left">Kabupaten/Kota</th>
                        <th class="py-2 px-4 bg-gray-200 text-gray-600 font-bold uppercase text-sm text-left">Kecamatan</th>
                        <th class="py-2 px-4 bg-gray-200 text-gray-600 font-bold uppercase text-sm text-left">Desa</th>
                        <th class="py-2 px-4 bg-gray-200 text-gray-600 font-bold uppercase text-sm text-left">Nama Jalan</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Contoh data, ganti dengan data sebenarnya -->
                    <tr class="border-b">
                        <td class="py-2 px-4">Kabupaten A</td>
                        <td class="py-2 px-4">Kecamatan X</td>
                        <td class="py-2 px-4">Desa Alpha</td>
                        <td class="py-2 px-4">Jalan 1</td>
                    </tr>
                    <tr class="border-b">
                        <td class="py-2 px-4">Kabupaten B</td>
                        <td class="py-2 px-4">Kecamatan Y</td>
                        <td class="py-2 px-4">Desa Beta</td>
                        <td class="py-2 px-4">Jalan 2</td>
                    </tr>
                    <tr class="border-b">
                        <td class="py-2 px-4">Kabupaten C</td>
                        <td class="py-2 px-4">Kecamatan Z</td>
                        <td class="py-2 px-4">Desa Gamma</td>
                        <td class="py-2 px-4">Jalan 3</td>
                    </tr>
                    <!-- Tambahkan lebih banyak data sesuai kebutuhan -->
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
