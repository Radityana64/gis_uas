<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RuasJalan;
use GuzzleHttp\Client;


class RuasJalanController extends Controller
{
    public function index()
    {
        return view('RuasJalan.index');
    }

    public function create()
    {
        return view('RuasJalan.create');
    }

    public function store(Request $request)
    {
        
    }

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

    }



    public function destroy($id)
    {

    }
}