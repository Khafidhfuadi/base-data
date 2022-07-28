<?php

namespace App\Http\Controllers;

use App\Models\Quotes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuotesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $quotes = Quotes::get();
      return $quotes;
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
      $quotes = Validator::make(
        $request->all(), [
          'arab' => 'required',
          'arti' => 'required',
          'latin' => 'required',
        ],
        [
          'arab.required' => 'Masukkan arabnya!',
          'arti.required' => 'Masukkan artinya!',
          'latin.required' => 'Masukkan latinnya!',
        ]
      );

      if($quotes->fails()) {
        return response()->json([
          'success' => false,
          'message' => 'Silahkan isi bagian yang kosong',
          'data'    => $quotes->errors()
        ],500);
      }else {
        $post = Quotes::create([
          'arab' => $request->input('arab'),
          'arti' => $request->input('arti'),
          'latin' => $request->input('latin'),
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
     * @param  \App\Models\Quotes  $quotes
     * @return \Illuminate\Http\Response
     */
    public function show(Quotes $quotes, $id)
    {
      $quotes = Quotes::where('id', $id)->first();
      if ($quotes) {
        return $quotes;
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
     * @param  \App\Models\Quotes  $quotes
     * @return \Illuminate\Http\Response
     */
    public function edit(Quotes $quotes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Quotes  $quotes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Quotes $quotes, $id)
    {
      $quotes = Quotes::where('id', $id)->first();
      $quotes -> arab = $request -> arab;
      $quotes -> arti = $request -> arti;
      $quotes -> latin = $request -> latin;
      if($quotes->update()) {
          return response()->json([
          'success' => false,
          'message' => 'Berhasil Update data',
          ],200);
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Quotes  $quotes
     * @return \Illuminate\Http\Response
     */
    public function destroy(Quotes $quotes, $id)
    {
      $quotes = Quotes::findOrFail($id);
      if($quotes) {
        $quotes->delete();
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
