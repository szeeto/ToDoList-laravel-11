<?php

namespace App\Http\Controllers\Todo;

use App\Models\Todo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $max_data = 5;

        if(request('search')){
            $data = Todo::where('task','like', '%'.request('search').'%')->where('user_id', Auth::user()->id)->orderBy('task','asc')->paginate($max_data)->withQueryString();
        }else{
          $data = Todo::orderBy('task','asc')->where('user_id', Auth::user()->id)->orderBy('task','asc')->paginate($max_data);
        }

        return view('todo.app',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
        'task' => 'required|min:3|max:50',
    ], [
        'task.required' => 'Isian task wajib diisi',
        'task.min' => 'Minimal isian untuk task adalah 3 karakter',
        'task.max' => 'Isian task maksimal 50 karakter',
    ]);

    $data =[
        'task' => $request->input('task'),
        'user_id' => Auth::user()->id,
    ];

    Todo::create($data);
    return redirect()->route('todo')->with('success', 'Berhasil simpan data');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
        'task' => 'required|min:3|max:50',
    ], [
        'task.required' => 'Isian task wajib diisi',
        'task.min' => 'Minimal isian untuk task adalah 3 karakter',
        'task.max' => 'Isian task maksimal 50 karakter',
    ]);
    $data =[
        'task' => $request->input('task'),
        'is_done' => $request->input('is_done'),
    ];

    Todo::where('id', $id)->update($data);
    return redirect()->route('todo')->with('success', 'Berhasil menyimpan perbaikan data');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Todo::where('id', $id)->where('user_id', Auth::user()->id)->delete();
        return redirect()->route('todo')->with('success', 'Berhasil menghapus data');
    }
}
