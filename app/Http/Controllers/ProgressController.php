<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Progress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProgressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user_id = $request->user_id;
        $lesson_id = $request->lesson_id;

        if ($user_id && $lesson_id) {
            $progress = DB::table('progress as pr')
                ->select("*", "pr.id as id_progress")
                ->join('lessons as ls', 'pr.lesson_id', '=', 'ls.id')
                ->where([['pr.user_id', '=', $user_id], ['pr.lesson_id', '=', $lesson_id]]);
        } else if ($user_id) {
            $progress = DB::table('progress as pr')
                ->select("*", "pr.id as id_progress")
                ->join('lessons as ls', 'pr.lesson_id', '=', 'ls.id')
                ->where([['pr.user_id', '=', $user_id]]);
        } else {
            $progress = DB::table("progress")->join('lessons', 'progress.lesson_id', '=', 'lessons.id');
        }

        return response()->json([
            "message" => "Berhasil boss",
            "data" => $progress->get(),
        ]);
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
                'read_chapter' => 'required',
                'length_chapter' => 'required',
            ],
            [
                'user_id.required' => 'Masukkan user id!',
                'lesson_id.required' => 'Masukkan lesson id!',
                'read_chapter.required' => 'Masukkan lengthchapter!',
                'length_chapter.required' => 'Masukkan lengthchapter!',
            ]
        );

        if ($chapter->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Silahkan isi bagian yang kosong',
                'data'    => $chapter->errors()
            ], 500);
        } else {
            $post = Progress::create([
                'user_id' => $request->input('user_id'),
                'lesson_id' => $request->input('lesson_id'),
                'read_chapter' => $request->input('read_chapter'),
                'length_chapter' => $request->input('length_chapter'),
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $progress = Progress::where('id', $id)->first();
        if ($progress) {
            return $progress;
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada detail data!',
                'data' => 'Kosong!'
            ], 401);
        }
    }


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
    // public function update(Request $request, $id)
    // {
    //     if (!is_null($progress = Progress::where('id', $id)->first())) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Data gagal diubah!',
    //         ], 200);
    //     }
    //     $progress->user_id = $request->user_id;
    //     $progress->lesson_id = $request->lesson_id;
    //     $progress->read_chapter = $request->read_chapter;

    //     if ($progress->update()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Berhasil Update data',
    //         ], 200);
    //     }
    // }

    public function update(Request $request, $id)
    {
        $progress = Progress::where('id', $id)->first();
        $progress->user_id = $request->user_id;
        $progress->lesson_id = $request->lesson_id;
        $progress->read_chapter = $request->read_chapter;
        $progress->length_chapter = $request->length_chapter;
        if ($progress->update()) {
            return response()->json([
                'success' => true,
                'message' => 'Berhasil Update data',
            ], 200);
        } else if ($progress->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Silahkan isi bagian yang kosong',
                'data'    => $progress->errors()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Chapter  $chapter
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $progress, $id)
    {
        $progress = Progress::findOrFail($id);
        if ($progress) {
            $progress->delete();
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
