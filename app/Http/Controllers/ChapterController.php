<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChapterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $chapter = Chapter::select("*");
        if ($request->cari) {
            $query = $request->cari;
            $chapter->where(function ($key) use ($query) {
                $key->where('lesson_id', 'LIKE', "%" . $query . "%");
            });
            return response()->json(
                count($chapter->get()),
            );
        } else {
            return response()->json([
                "message" => "Berhasil boss",
                "data" => $chapter->get(),
            ]);
        }
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
        $chapter = Validator::make(
            $request->all(),
            [
                'user_id' => 'required',
                'lesson_id' => 'required',
                'pelajaran' => 'required',
                'judul_bab' => 'required',
                'materi' => 'required',
            ],
            [
                'user_id.required' => 'Masukkan user id!',
                'lesson_id.required' => 'Masukkan lesson id!',
                'pelajaran.required' => 'Masukkan nama Pelajaran!',
                'judul_bab.required' => 'Masukkan nama judul bab!',
                'materi.required' => 'Masukkan materi!',
            ]
        );

        if ($chapter->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Silahkan isi bagian yang kosong',
                'data'    => $chapter->errors()
            ], 500);
        } else {
            $post = Chapter::create([
                'user_id' => $request->input('user_id'),
                'lesson_id' => $request->input('lesson_id'),
                'pelajaran' => $request->input('pelajaran'),
                'judul_bab' => $request->input('judul_bab'),
                'materi' => $request->input('materi'),
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
     * @param  \App\Models\Chapter  $chapter
     * @return \Illuminate\Http\Response
     */
    public function show(Chapter $chapter, $id)
    {
        $chapter = Chapter::where('id', $id)->first();

        if ($chapter) {
            return $chapter;
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
     * @param  \App\Models\Chapter  $chapter
     * @return \Illuminate\Http\Response
     */
    public function edit(Chapter $chapter)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Chapter  $chapter
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Chapter $chapter, $id)
    {
        $quiz = Chapter::where('id', $id)->first();
        $quiz->user_id = $request->user_id;
        $quiz->lesson_id = $request->lesson_id;
        $quiz->pelajaran = $request->pelajaran;
        $quiz->judul_bab = $request->judul_bab;
        $quiz->materi = $request->materi;
        if ($quiz->update()) {
            return response()->json([
                'success' => false,
                'message' => 'Berhasil Update data',
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Chapter  $chapter
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chapter $chapter, $id)
    {
        $chapter = Chapter::findOrFail($id);
        if ($chapter) {
            $chapter->delete();
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
