<?php

namespace App\Http\Controllers;

use App\Imports\UsersExport;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{


    public function searchUser(Request $request)
    {
        $request->validate([
            'cnic' => 'required|max:13'
        ]);

        $user = User::where('cnic', $request->input('cnic'))->first();

        if($user){
            return Inertia::render('Welcome', [
                'status' => [
                    'message' => 'Record Found',
                    'status' => true
                ]
            ]);
        }else{
            return Inertia::render('Welcome', [
                'status' => [
                    'message' => 'Not verified',
                    'status' => false
                ]
            ]);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'cnic' => 'required',
            'name' => 'required|max:255',
            'address' => 'required',
            'phone' => 'required|max:13'
        ]);

        $user = User::where('cnic', $request->input('cnic'))->first();

        if($user && !$user->verified){
            $user->name = $request->input('name');
            $user->address = $request->input('address');
            $user->phone = $request->input('phone');
            $user->verified = true;
            $user->save();

            return Inertia::render('Welcome', [
               'status' => [
                   'message' => 'Record Updated Successfully',
                   'status' => true
               ]
            ]);
        }else{
            return Inertia::render('Welcome', [
                'status' => [
                    'message' => 'Record Already Verified',
                    'status' => false
                ]
            ]);
        }

    }

    public function export(Request $request)
    {
        if(!$request->has('passcode')){
            return back();
        }

        $passcode = $request->passcode;
        if($passcode !== env('DOWNLOAD_PASSCODE')){
            return back();
        }
        return Excel::download(new UsersExport, 'rashan-programme-data.xlsx');
    }
}
