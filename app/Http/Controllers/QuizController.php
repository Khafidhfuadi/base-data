<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $quiz = Quiz::select("*");
      if ($request->cari) {
        $query = $request->cari;
        $quiz->where(function($key) use($query)
        {
          $key->where('lesson_id', 'LIKE', "%".$query."%");
        });
      }

      return response()->json([
        "message" => "Berhasil boss",
        "data" => $quiz->get(),
      ]);
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
      $quiz = Validator::make(
        $request->all(), [
          'user_id' => 'required',
          'lesson_id' => 'required',
          'pelajaran' => 'required',
          'question_text' => 'required',
          'answer_options' => 'required',
        ],
        [
          'user_id.required' => 'Masukkan user id!',
          'lesson_id.required' => 'Masukkan lesson id!',
          'pelajaran.required' => 'Masukkan pelajaran!',
          'question_text.required' => 'Masukkan Soal!',
          'answer_options.required' => 'Masukkan jawaban!',
        ]
      );

      if($quiz->fails()) {
        return response()->json([
          'success' => false,
          'message' => 'Silahkan isi bagian yang kosong',
          'data'    => $quiz->errors()
        ],500);
      }else {
        $post = Quiz::create([
          'user_id' => $request->input('user_id'),
          'lesson_id' => $request->input('lesson_id'),
          'pelajaran' => $request->input('pelajaran'),
          'question_text' => $request->input('question_text'),
          'answer_options' => $request->input('answer_options'),
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
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      if (Quiz::where('id', $id)->exists()) {
        $quiz = Quiz::where('id', $id)->get();
        return $quiz;
      } else {
        return response()->json([
          "message" => "Quiz not found"
        ], 404);
      }
      // $quiz = Quiz::where('id', $id)->first();
      // if ($quiz) {
      //   return $quiz;
      // } else {
      //   return response()->json([
      //     'success' => false,
      //     'message' => 'Tidak ada detail data!',
      //     'data' => 'Kosong!'
      //   ], 401);
      // }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function edit(Quiz $quiz)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $quiz = Quiz::where('id', $id)->first();
      $quiz -> user_id = $request -> user_id;
      $quiz -> lesson_id = $request -> lesson_id;
      $quiz -> pelajaran = $request -> pelajaran;
      $quiz -> question_text = $request -> question_text;
      $quiz -> answer_options = $request -> answer_options;
      if($quiz->update()) {
          return response()->json([
          'success' => false,
          'message' => 'Berhasil Update data',
          ],200);
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $quiz = Quiz::findOrFail($id);
      if($quiz) {
        $quiz->delete();
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
