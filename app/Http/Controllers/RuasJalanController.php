<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RuasJalan;
use GuzzleHttp\Client;
 // tambahkan use statement untuk model RuasJalan

class RuasJalanController extends Controller
{
    public function index()
    {
        $token = session('token');
    
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'https://gisapis.manpits.xyz/api/ruasjalan', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
            ],
        ]);
    
        if ($response->getStatusCode() == 200) {
            $ruasjalans = json_decode($response->getBody(), true);
            // dd($ruasjalans); // Tambahkan dd() untuk melihat struktur data
            return view('RuasJalan.index', compact('ruasjalans'));
        } else {
            // Handle error
            return redirect()->back()->with('error', 'Failed to fetch data from API');
        }
    }

    public function create()
    {
        return view('RuasJalan.create');
    }

    public function store(Request $request)
    {
        // Validasi data
        $request->validate([
            'nama_ruas' => 'required|string|max:255',
            'paths' => 'required|array',
            'desa_id' => 'required|numeric',
            'kode_ruas' => 'required|string|max:255',
            'panjang' => 'required|numeric',
            'lebar' => 'required|numeric',
            'eksisting_id' => 'required|numeric',
            'kondisi_id' => 'required|numeric',
            'jenisjalan_id' => 'required|numeric',
            'keterangan' => 'nullable|string|max:255',
        ]);
    
        // Simpan data ke database
        $ruasJalan = new RuasJalan();
        $ruasJalan->nama_ruas = $request->nama_ruas;
        $ruasJalan->paths = $request->paths;
        $ruasJalan->desa_id = $request->desa_id;
        $ruasJalan->kode_ruas = $request->kode_ruas;
        $ruasJalan->panjang = $request->panjang;
        $ruasJalan->lebar = $request->lebar;
        $ruasJalan->eksisting_id = $request->eksisting_id;
        $ruasJalan->kondisi_id = $request->kondisi_id;
        $ruasJalan->jenisjalan_id = $request->jenisjalan_id;
        $ruasJalan->keterangan = $request->keterangan;
        $ruasJalan->save();
    
        // Beri respons sukses
        return redirect()->route('RuasJalan.index')->with('success', 'Data ruas jalan berhasil disimpan.');
    }
    
    // public function edit($id)
    // {
    //     $ruasjalan = RuasJalan::findOrFail($id);
    //     return view('RuasJalan.edit', compact('ruasjalan'));
    // }
    // Contoh di controller

    public function edit($id)
    {
        $token = session('token');
        $client = new Client();

        try {
            // Permintaan untuk mendapatkan data ruas jalan berdasarkan ID
            $response = $client->request('GET', 'https://gisapis.manpits.xyz/api/ruasjalan/' . $id, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                ],
            ]);

            if ($response->getStatusCode() == 200) {
                $data = json_decode($response->getBody(), true);
                $ruasjalan = $data['ruasjalan'];

                // Permintaan untuk mendapatkan data tambahan
                $regionResponse = $client->request('GET', 'https://gisapis.manpits.xyz/api/mregion', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                        'Accept' => 'application/json',
                    ],
                ]);
                $eksistingResponse = $client->request('GET', 'https://gisapis.manpits.xyz/api/meksisting', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                        'Accept' => 'application/json',
                    ],
                ]);
                $jenisjalanResponse = $client->request('GET', 'https://gisapis.manpits.xyz/api/mjenisjalan', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                        'Accept' => 'application/json',
                    ],
                ]);
                $kondisiResponse = $client->request('GET', 'https://gisapis.manpits.xyz/api/mkondisi', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                        'Accept' => 'application/json',
                    ],
                ]);

                if ($regionResponse->getStatusCode() == 200 && $eksistingResponse->getStatusCode() == 200 && $jenisjalanResponse->getStatusCode() == 200 && $kondisiResponse->getStatusCode() == 200) {
                    $regionData = json_decode($regionResponse->getBody(), true);
                    $eksistingData = json_decode($eksistingResponse->getBody(), true)['eksisting'];
                    $jenisjalanData = json_decode($jenisjalanResponse->getBody(), true)['eksisting'];
                    $kondisiData = json_decode($kondisiResponse->getBody(), true)['eksisting'];

                    // Mencari kecamatan_id, kabupaten_id, dan province_id
                    $desa_id = $ruasjalan['desa_id'];
                    $kecamatan_id = null;
                    $kabupaten_id = null;
                    $province_id = null;

                    foreach ($regionData['desa'] as $desa) {
                        if ($desa['id'] == $desa_id) {
                            $kecamatan_id = $desa['kec_id'];
                            break;
                        }
                    }

                    foreach ($regionData['kecamatan'] as $kecamatan) {
                        if ($kecamatan['id'] == $kecamatan_id) {
                            $kabupaten_id = $kecamatan['kab_id'];
                            break;
                        }
                    }

                    foreach ($regionData['kabupaten'] as $kabupaten) {
                        if ($kabupaten['id'] == $kabupaten_id) {
                            $province_id = $kabupaten['prov_id'];
                            break;
                        }
                    }

                    $ruasjalan['kecamatan_id'] = $kecamatan_id;
                    $ruasjalan['kabupaten_id'] = $kabupaten_id;
                    $ruasjalan['province_id'] = $province_id;


                    return view('RuasJalan.edit', compact('ruasjalan', 'regionData', 'eksistingData', 'jenisjalanData', 'kondisiData'));
                } else {
                    return redirect()->route('RuasJalan.index')->with('error', 'Failed to retrieve data from one or more sources');
                }
            } else {
                return redirect()->route('RuasJalan.index')->with('error', 'Failed to retrieve ruas jalan data');
            }
        } catch (\Exception $e) {
            return redirect()->route('RuasJalan.edit', ['id' => $id])->with([
                'error' => 'An error occurred: ' . $e->getMessage(),
                'ruasjalan' => $ruasjalan, // Mengirim kembali data ruasjalan
                'regionData' => $regionData, // Mengirim kembali data tambahan
                'eksistingData' => $eksistingData, // Mengirim kembali data tambahan
                'jenisjalanData' => $jenisjalanData, // Mengirim kembali data tambahan
                'kondisiData' => $kondisiData, // Mengirim kembali data tambahan
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_ruas' => 'required|string|max:255',
            'paths' => 'required|string', // Ubah menjadi string karena latlng adalah string
            'desa_id' => 'required|numeric',
            'kode_ruas' => 'required|string|max:255',
            'panjang' => 'required|numeric',
            'lebar' => 'required|numeric',
            'eksisting_id' => 'required|numeric',
            'kondisi_id' => 'required|numeric',
            'jenisjalan_id' => 'required|numeric',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $token = session('token');

        if (!$token) {
            return redirect()->route('login')->with('error', 'Please login first');
        }

        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->request('PUT', 'https://gisapis.manpits.xyz/api/ruasjalan/' . $id, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                ],
                'json' => $request->all(), // Kirim data yang sudah divalidasi langsung
            ]);

            if ($response->getStatusCode() == 200) {
                return redirect()->route('RuasJalan.index')->with('success', 'Data ruas jalan berhasil diupdate.');
            } else {
                return redirect()->back()->with('error', 'Failed to update data.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }



    public function destroy($id)
    {
        $token = session('token');
        $client = new Client();
        $response = $client->request('DELETE', 'https://gisapis.manpits.xyz/api/ruasjalan/' . $id, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
            ],
        ]);

        if ($response->getStatusCode() == 200) {
            return redirect()->route('RuasJalan.index')->with('success', 'Data polyline berhasil dihapus.');
        } else {
            return redirect()->back()->with('error', 'Gagal menghapus data polyline.');
        }
    }
}