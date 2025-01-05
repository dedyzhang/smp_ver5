<?php

namespace App\Http\Controllers;

use App\Imports\SiswaImport;
use App\Models\Kelas;
use App\Models\Nis;
use App\Models\Orangtua;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Maatwebsite\Excel\Facades\Excel;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $kelas = Kelas::get()->sortBy('tingkat')->sortBy('kelas');
        $siswa = Siswa::with('kelas')->orderBy('nis', 'ASC')->get();

        return View('siswa.index', compact('siswa', 'kelas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $nis = Nis::get()->first();
        return View('siswa.create', compact('nis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama' => 'required',
            'jk' => 'required'
        ]);
        $password = Hash::make("pass." . $request->nis);

        $user = User::create([
            'username' => $request->nis,
            'password' => $password,
            'access' => 'siswa',
            'token' => '1'
        ]);

        $siswa = Siswa::create([
            'id_login' => $user->uuid,
            'nis' => $request->nis,
            'nama' => $request->nama,
            'jk' => $request->jk
        ]);
        $usernameOrtu = "P." . $request->nis;
        $passwordOrtu = Hash::make("passortu." . $request->nis);

        $orangtuaUser = User::create([
            'username' => $usernameOrtu,
            'password' => $passwordOrtu,
            'access' => 'orangtua',
            'token' => '1',
        ]);

        $orangtua = Orangtua::create([
            'id_login' => $orangtuaUser->uuid,
            'id_siswa' => $siswa->uuid,
        ]);
        $nis = explode('.', $request->nis);
        $nis = end($nis);
        $number = intval($nis) + 1;
        $nis = sprintf('%04d', $number);

        $newNis = Nis::query()->update([
            'third_nis' => $nis,
        ]);

        return redirect()->route('siswa.create')->with(['success' => 'Data ' . $request->nama . ' Berhasil Disimpan']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $siswa = Siswa::findOrFail($id);

        return compact('siswa');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $siswa = Siswa::findOrFail($id);

        return View('siswa.edit', compact('siswa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $siswa = Siswa::findOrFail($id);

        $siswa->update($request->all());

        return redirect()->back()->with(['success' => 'Data ' . $request->nama . ' Berhasil Disimpan']);
    }
    /**
     * Reset Password Siswa
     */
    public function resetSiswa(string $id)
    {
        $siswa = Siswa::with('users')->findOrFail($id);
        $seed = str_split('abcdefghijklmnopqrstuvwxyz'
            . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
            . '0123456789'); // and any other characters
        shuffle($seed); // probably optional since array_is randomized; this may be redundant
        $rand = '';
        foreach (array_rand($seed, 6) as $k) $rand .= $seed[$k];
        $passwordHash = Hash::make($rand);
        $siswa->users->update([
            'password' => $passwordHash,
            'token' => 1,
        ]);

        return response()->json([
            'success' => true,
            'password' => $rand
        ]);
    }

    /**
     * Reset Password Orangtua
     */
    public function resetOrangtua(string $id)
    {
        $siswa = Siswa::with('orangtua')->findOrFail($id);
        $seed = str_split('abcdefghijklmnopqrstuvwxyz'
            . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
            . '0123456789'); // and any other characters
        shuffle($seed); // probably optional since array_is randomized; this may be redundant
        $rand = '';
        foreach (array_rand($seed, 6) as $k) $rand .= $seed[$k];
        $passwordHash = Hash::make($rand);
        $siswa->orangtua[0]->update([
            'password' => $passwordHash,
            'token' => 1,
        ]);
        return response()->json([
            'success' => true,
            'password' => $rand
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $siswa = Siswa::with(['users', 'orangtua', 'ortu'])->findOrFail($id);

        $siswa->orangtua[0]->delete();
        $siswa->ortu->delete();
        $siswa->users->delete();
        $siswa->delete();

        return response()->json([
            'success' => true
        ]);
    }
    /**
     * Import Siswa
     */
    public function import(Request $request)
    {
        $excel = $request->file('file');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($excel);

        $cellValue = $spreadsheet->getSheet(0)->getCellByColumnAndRow(1, 3)->getValue();

        if ($cellValue == '06668f444f7dcd632645f3fbece588fb') {
            //Get data from excel
            $array = Excel::toCollection(new SiswaImport, $excel, 's3', \Maatwebsite\Excel\Excel::XLSX);

            //get data from database to check
            $old_siswa = Siswa::all();
            $nis_old_siswa = $old_siswa->pluck('nis')->toArray();


            //add data to database
            $siswa = $array[0]->toArray();
            $tanggal_lahir_array = array();

            //Remove Null
            $removeNull = $array[0]->filter(function ($item) {
                return $item['nama'] != null;
            });
            //Remove NIS yang Duplikat
            $removeNis = $removeNull->unique('nis_3');

            //Proses Penambahan Data
            $duplicate_data = array();
            foreach ($removeNis as $row) {
                $nis = $row['nis_1'] . "." . $row['nis_2'] . "." . $row['nis_3'];
                if (in_array($nis, $nis_old_siswa)) {
                    //Masukkan data Ke duplicate Data
                    array_push($duplicate_data, array(
                        'nis' => $nis,
                        'nama' => $row['nama'],
                        'jk' => $row['jk']
                    ));
                } else {
                    //Validasi Suddah Lengkap Proses Penambahan Data

                    //Baca Tanggal
                    if (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($row['tanggal_lahir']))->format('Y-m-d') !== '1970-01-01') {
                        $tanggal_lahir = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($row['tanggal_lahir']))->format('Y-m-d');
                    } else {
                        $tanggal_lahir = null;
                    }
                    if (\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($row['tanggal_lahir_ijazah']))->format('Y-m-d') !== '1970-01-01') {
                        $tanggal_lahir_ijazah = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(intval($row['tanggal_lahir_ijazah']))->format('Y-m-d');
                    } else {
                        $tanggal_lahir_ijazah = null;
                    }
                    array_push($tanggal_lahir_array, $row);

                    $password = Hash::make("pass." . $nis);

                    $user = User::create([
                        'username' => $nis,
                        'password' => $password,
                        'access' => 'siswa',
                        'token' => '1'
                    ]);

                    $siswa = Siswa::create([
                        'id_login' => $user->uuid,
                        'nis' => $nis,
                        'nama' => $row['nama'],
                        'jk' => $row['jk'],
                        'tempat_lahir' => $row['tempat_lahir'],
                        'tanggal_lahir' => $tanggal_lahir,
                        'agama' => $row['agama'],
                        'alamat' => $row['alamat'],
                        'no_handphone' => $row['telp_rumah'],
                        'nama_ayah' => $row['nama_ayah'],
                        'pekerjaan_ayah' => $row['pekerjaan_ayah'],
                        'no_telp_ayah' => $row['no_telp_ayah'],
                        'nama_ibu' => $row['nama_ibu'],
                        'pekerjaan_ibu' => $row['pekerjaan_ibu'],
                        'no_telp_ibu' => $row['no_telp_ibu'],
                        'nama_wali' => $row['nama_wali'],
                        'pekerjaan_wali' => $row['pekerjaan_wali'],
                        'no_telp_wali' => $row['no_telp_wali'],
                        'nisn' => $row['nisn'],
                        'sekolah_asal' => $row['sekolah_asal'],
                        'nama_ijazah' => $row['nama_ijazah'],
                        'ortu_ijazah' => $row['ortu_ijazah'],
                        'tempat_lahir_ijazah' => $row['tempat_lahir_ijazah'],
                        'tanggal_lahir_ijazah' => $tanggal_lahir_ijazah,
                    ]);


                    $usernameOrtu = "P." . $nis;
                    $passwordOrtu = Hash::make("passortu." . $nis);

                    $orangtuaUser = User::create([
                        'username' => $usernameOrtu,
                        'password' => $passwordOrtu,
                        'access' => 'orangtua',
                        'token' => '1',
                    ]);

                    $orangtua = Orangtua::create([
                        'id_login' => $orangtuaUser->uuid,
                        'id_siswa' => $siswa->uuid,
                    ]);
                }
            }
            return response()->json([
                'success' => true,
                'duplicate_data' => $duplicate_data,
                'message' => 'Data Berhasil Diimport'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Format file yang dikirimkan tidak sesuai. Gunakan format yang sudah disediakan.'
            ]);
        }
    }
}
