<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Barryvdh\Snappy\Facades\SnappyPdf as pdf;
// use Barryvdh\DomPDF\Facade as PDF;


use Carbon\Carbon;
// use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class CertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sertifikat = Certificate::get();
        return $sertifikat;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $certificate = Validator::make(
            $request->all(),
            [
                'user_id' => 'required',
                'lesson_id' => 'required',
                'nilai_ujian' => 'required',
            ],
            [
                'user_id.required' => 'Masukkan tingkat kesulitan!',
                'lesson_id.required' => 'Masukkan lesson_id!',
                'nilai_ujian.required' => 'Masukkan nilai_ujian!',
            ]
        );

        if ($certificate->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Silahkan isi bagian yang kosong',
                'data'    => $certificate->errors()
            ], 401);
        } else {
            $post = Certificate::create([
                'user_id' => $request->input('user_id'),
                'lesson_id' => $request->input('lesson_id'),
                'nilai_ujian' => $request->input('nilai_ujian'),
            ]);
            if ($post) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data berhasil disimpan!',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Data gagal disimpan!',
                ], 401);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Certificate  $certificate
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $certificate = Certificate::where('id', $id)->first();
        if ($certificate) {
            return $certificate;
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada detail data!',
                'data' => 'Kosong!'
            ], 401);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Certificate  $certificate
     * @return \Illuminate\Http\Response
     */
    public function edit(Certificate $certificate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Certificate  $certificate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $certif = Certificate::where('id', $id)->first();
        $certif->user_id = $request->user_id;
        $certif->lesson_id = $request->lesson_id;
        $certif->nilai_ujian = $request->nilai_ujian;

        if ($certif->update()) {
            return response()->json([
                'success' => true,
                'message' => 'Berhasil Update data',
            ], 200);
        } else if ($certif->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Silahkan isi bagian yang kosong',
                'data'    => $certif->errors()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Certificate  $certificate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $certificate = Certificate::findOrFail($id);
        if ($certificate) {
            $certificate->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus!',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data gagal dihapus!',
            ], 401);
        }
    }

    // export pdf
    public function exportPDF(Request $request)
    {

        $lessonId = $request->lesson_id;

        if ($lessonId) {
            $data = DB::table('certificates as cf')
                ->select('*', 'cf.id as cf_id')
                ->join('users as us', 'us.id', '=', 'cf.user_id')
                ->join('lessons as ls', 'ls.id', '=', 'cf.lesson_id')
                ->where([['cf.user_id', '=', $request->user_id], ['cf.lesson_id', '=', $lessonId]])
                ->get();
        } else {
            $data = DB::table('certificates as cf')
                ->where('cf.user_id', '=', $request->user_id)
                ->join('users as us', 'us.id', '=', 'cf.user_id')
                ->join('lessons as ls', 'ls.id', '=', 'cf.lesson_id')
                ->get();
        }


        if (count($data) > 0) {
            $predikat = [
                'arab' => 'default',
                'latin' => 'default',
            ];
            if ($lessonId) {
                $nilai_ujian = $data[0]->nilai_ujian;

                if ($nilai_ujian <= 79) {
                    $predikat = [
                        'arab' => 'جَيِّدٌ',
                        'latin' => 'jayyidun',
                    ];
                } else if ($nilai_ujian <= 89) {
                    $predikat = [
                        'arab' => 'جَيّدٌ جِدًّا',
                        'latin' => 'jayyid jiddan',
                    ];
                } else if ($nilai_ujian <= 100) {
                    $predikat = [
                        'arab' => 'مُمْتَازٌ',
                        'latin' => 'mumtaz',
                    ];
                }
            }

            $created = Carbon::parse($data[0]->created_at)->format('Y-m-d');

            return response()->json([
                'data' => $data,
                'predikat' => $predikat,
                'created' => $created,
            ]);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'Tidak ada detail data!',
                'data' => $data
            ], 200);
        }
    }
}
