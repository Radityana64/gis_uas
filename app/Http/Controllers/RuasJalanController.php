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
    
    public function edit()
    {

        return view('RuasJalan.edit',[
        
        ]);
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