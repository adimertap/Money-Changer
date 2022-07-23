<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ModalTransaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class ModalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->role == 'Owner'){
            $modal = ModalTransaksi::get();
        }else{
            $modal = ModalTransaksi::where('tanggal_modal', Carbon::now()->format('Y-m-d'))->orWhere('riwayat_modal','>', '0')->get();
        }
        $modal_today = ModalTransaksi::where('tanggal_modal', Carbon::now()->format('Y-m-d'))->get();
        $modal_tf = ModalTransaksi::where('tanggal_modal', Carbon::now()->format('Y-m-d'))->first();
        $jumlah_modal_today = ModalTransaksi::where('tanggal_modal', Carbon::now()->format('Y-m-d'))->where('status_modal','Terima')->first();
        $today = Carbon::now()->format('Y-m-d');
       

        return view('pages.modal.index', compact('modal','modal_today','today','jumlah_modal_today','modal_tf'));
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
        $modal = new ModalTransaksi();
        $modal->tanggal_modal = Carbon::now();
        $modal->jumlah_modal = $request->jumlah_modal;
        $modal->status_modal = 'Pending';
        $modal->id_pegawai = Auth::user()->id;
        $modal->riwayat_modal = $request->jumlah_modal;

        $modal->save();
        Alert::success('Success Title', 'Data Modal Berhasil Ditambahkan');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $modal = ModalTransaksi::find($id);
        $modal->pengajuan_tambah = $request->jumlah_modal;
        $modal->status_modal = "Pending";
        $modal->update();

        Alert::success('Success Title', 'Data Pengajuan Penambahan Modal Berhasil Diajukan');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function hapus(Request $request)
    {
        $modal = ModalTransaksi::find($request->modal_delete_id);
        $modal->delete();

        Alert::success('Success Title', 'Data Modal Berhasil Terhapus');
        return redirect()->back();
    }

    public function transfer(Request $request)
    {
        $modal = ModalTransaksi::find($request->modal_transfer_id);
        $modal_tuju = ModalTransaksi::where('tanggal_modal', Carbon::now()->format('Y-m-d'))->first();

        $perhitungan = $modal->riwayat_modal + $modal_tuju->riwayat_modal;
        $modal_tuju->riwayat_modal = $perhitungan;
        $modal_tuju->save();

        $modal->riwayat_modal = 0;
        $modal->save();

        Alert::success('Success Title', 'Data Modal Berhasil Ditransfer');
        return redirect()->back();

    }
}
