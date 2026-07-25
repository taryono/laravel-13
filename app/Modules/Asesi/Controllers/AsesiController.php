<?php

namespace App\Modules\Asesi\Controllers;

use App\Http\Controllers\MainController;
use App\Http\Requests\StoreAsesi;
use Illuminate\Http\Request;
use Models\Asesi as Asesi;
use View;
use Yajra\Datatables\Datatables;

class AsesiController extends MainController
{
    public function __construct()
    {
        parent::__construct(new Asesi(), 'Asesi', say("Asesi"), null);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        return view('Asesi::index');
    }

    public function draw()
    {  
        if (request()->ajax()) {
            if ($this->fields) {
                $sql = Asesi::select(["users.id","users.name","users.cellphone", "users.email","users.created_at", "users.email_verified_at"]);
            } else {
                $sql = Asesi::select('*');
            } 
            if ($this->with) {
                $sql->with(["user"]);
            }
            if (request()->has('search')) {
                $sql = \App\Filters\GlobalSearch::search($sql, $this->with, $this->fields);
            } 
            $sql->whereHas('user'); 
            $sql->leftJoin('users', 'asesis.user_id', '=', 'users.id');

            $data = $sql->limit($this->_limit)
                ->orderBy('created_at', 'desc')->get();
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('cellphone', function ($row) {
                    if($row){
                        $phone = $row?->cellphone; 
                        $phone = str_replace(['62','+62'], ['0','0'], $phone);
                        $formatted = chunk_split($phone, 4, '-');
                        $formatted = rtrim($formatted, '-');
                        $formatted = str_replace(['62','+62'], ['0','0'], $formatted);
                        return (!empty($formatted))?'<span class="text-success"><i class="fab fa-whatsapp text-bold"></i> &nbsp;'.$formatted.'</span>':'-';
                    }else{
                        return '-';
                    }
                })->addColumn('status', function ($row) {
                    if($row){
                        return !is_null($row->email_verified_at)?1:0;
                    }else{
                        return '-';
                    }
                })->addColumn('register_date', function ($row) {
                     return  DateToIndo($row->created_at);
                })
                ->addColumn('action', function ($row) {
                    return  hapus(['url' => route('user.destroy', $row->id), 'title' => 'Hapus Asesi', 'can' => 'asesi-destroy']);
                })
                ->addColumn('verification_status', function ($row) {
                    if($row){ 
                        return checkbox('email_verification_status', 'email_verification_status', $row->id, !is_null($row->email_verified_at), !is_null($row->email_verified_at)?1:0, route('asesi.toggleStatusEmailVerification', $row->id));                        
                    }else{
                        return '-';
                    }
                })
                ->rawColumns(['action', 'verification_status', 'cellphone'])
                ->make(true);
        }

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->getForm();

        return view('Asesi::create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  request()
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAsesi $request)
    {
        $message = null;
        try {
            $asesor = Asesi::create($this->_serialize($request));
            if ($asesor) {

            } else {

            }

        } catch (\Throwable $e) {
            return getError($e);
        }

        return response()->json(['status' => 'success', 'message' => $message, 'redirectTo' => route('asesi.index')], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  $this->_model  $asesi
     * @return \Illuminate\Http\Response
     */
    public function show($asesi_id)
    {
        $asesi = $this->_model::find($asesi_id);
        $message = null;
        try {
            return view('Asesi::show', ['asesi' => $asesi]);
        } catch (\Throwable $e) {
            return getError($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  $this->_model  $asesi
     * @return \Illuminate\Http\Response
     */
    public function preview($asesi_id)
    {
        $asesi = $this->_model::find($asesi_id);
        return view('Asesi::preview', ['asesi' => $asesi]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  $this->_model  $asesi
     * @return \Illuminate\Http\Response
     */
    public function edit($asesi_id)
    {
        $asesi = $this->_model::find($asesi_id);
        $this->getForm($asesi);
        return view('Asesi::edit', ['asesi' => $asesi]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  request()
     * @param  $this->_model  $asesi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $asesi_id)
    {
        try {

            $asesi = $this->_model::find($asesi_id); 
            $users = request()->input("user");
            $institutions = request()->input("institution");
            $data_asesi = request()->input("asesi");

            if ($asesi) {
                $user = $asesi->user;
                $user->update($users);
                $asesi->update($data_asesi); 
                $institution =\Models\Institution::updateOrCreate([
                    "name"=> $institutions["name"]
                ], $institutions); 
                $asesi->institution_id = $institution->id;
                $asesi->position = $institution->position;
                $asesi->save();

                $area = $this->area();
                if(!is_null($area)){
                    $asesi->update($area);
                }

                if($asesi->country_id == 0 || $asesi->country_id == null){
                    $asesi->country_id = 100;
                    $asesi->save();
                }

            }

        } catch (\Throwable $e) {  
             return getError($e);
        }
        return response()->json(['status' => 'success', 'message' => 'Update Asesi Berhasil.'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $this->_model  $asesi
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $asesi = $this->_model::find($id);
        
            if ($asesi) { 
                $asesi->delete();
            } else {
                return response()->json(['status' => 'error', 'message' => 'Data Tidak Ditemukan.'], 400);
            }
        } catch (\Throwable $e) {
            return getError($e);

        }
        return response()->json(['status' => 'success', 'message' => 'Hapus Data Berhasil.', 'redirectTo' => route('asesor.index')], 200);
    }

    public function toggleStatusEmailVerification($id)
    { 
        try {
            $model = \App\Models\User::findOrFail($id);
            if ($model) {
                if($model->email_verified_at){
                    $model->email_verified_at = null;
                }else{
                    $model->email_verified_at = now();
                    $model->email_verification_ip =  request()->ip();
                } 
                $model->save();
            }
        } catch (\Throwable $e) {
            return getError($e);
        }
        return response()->json(['status' => 'success', 'message' => 'Update Status Verifikasi Email Berhasil.'], 200);
    }
}