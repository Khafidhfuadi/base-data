<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Response;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cari = $request->cari;
        $role = $request->role;
        if ($cari) {
            $lesson = Lesson::with(['Chapter', 'Progress' => function ($query) use ($cari) {
                $query->where('user_id', '=', $cari);
            }])->where('is_active', '=', 1)->get();
        } else if ($role === 'admin' || $role === 'teacher') {
            $lesson = Lesson::with('Chapter', 'Quiz', 'Progress')->get();
        } else {
            $lesson = Lesson::with('Chapter', 'Quiz', 'Progress')->where('is_active', '=', 1)->get();
        }
        return $lesson;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
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
        $Lesson = Validator::make(
            $request->all(),
            [
                'user_id' => 'required',
                'pelajaran' => 'required',
                'guru' => 'required',
                'tingkatan' => 'required',
                'deskripsi' => 'required',
                'is_active' => 'required',
            ],
            [
                'user_id.required' => 'Masukkan user id!',
                'pelajaran.required' => 'Masukkan nama pelajaran Post!',
                'guru.required' => 'Masukkan nama guru!',
                'tingkatan.required' => 'Masukkan tingkat kesulitan!',
                'deskripsi.required' => 'Masukkan deskripsi pelajaran!',
                'is_active.required' => 'Masukkan is_active pelajaran!',
            ]
        );

        if ($Lesson->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Silahkan isi bagian yang kosong',
                'data'    => $Lesson->errors()
            ], 500);
        } else {
            $post = Lesson::create([
                'user_id' => $request->input('user_id'),
                'pelajaran' => $request->input('pelajaran'),
                'guru' => $request->input('guru'),
                'tingkatan' => $request->input('tingkatan'),
                'deskripsi' => $request->input('deskripsi'),
                'is_active' => $request->input('is_active'),
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
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function show(Lesson $lesson, $id)
    {
        $lesson = Lesson::with('Chapter', 'Quiz')->where('id', $id)->first();
        if ($lesson) {
            return $lesson;
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
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function edit(Lesson $lesson)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $lesson = Lesson::where('id', $id)->first();
        $lesson->pelajaran = $request->pelajaran;
        $lesson->guru = $request->guru;
        $lesson->tingkatan = $request->tingkatan;
        $lesson->deskripsi = $request->deskripsi;
        $lesson->is_active = $request->is_active;
        if ($lesson->update()) {
            return response()->json([
                'success' => true,
                'message' => 'Berhasil Update data',
            ], 200);
        } else if ($lesson->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Silahkan isi bagian yang kosong',
                'data'    => $lesson->errors()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lesson $lesson, $id)
    {
        $lesson = Lesson::findOrFail($id);
        if ($lesson) {
            $lesson->delete();
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
}
