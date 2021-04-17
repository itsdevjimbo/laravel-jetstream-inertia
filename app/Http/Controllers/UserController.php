<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class UserController extends Controller
{
    protected $searchables = ['name','email'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Inertia::render('User/Index', [
            'data' => User::when($request->term, function ($query, $term) {
                foreach ($this->searchables as $search) {
                    $query->orWhere($search, 'LIKE', '%'. $term .'%');
                }
            })->where('id', '!=', auth()->user()->id)
                ->paginate($request->perPage??10)
                ->appends($request->except('page'))
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('User/Create',[
            'roles' => Role::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt(Str::random(14));
        $user = User::create($data);

        if($request->roles)
            $user->syncRoles($request->roles);
        return redirect(route('users.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Inertia::render('User/Edit', [
            'user'  => User::findOrfail($id),
            'roles' => Role::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        //
        $data = $request->validated();
        $user = User::findOrFail($id);
        $user->fill($data);
        $user->save();

        $roles = $request->roles ?? [];
        $user->syncRoles($roles);

        return redirect(route('users.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect(route('users.index'));
    }

    public function massDestroy(Request $request)
    {
        User::whereIn('id', $request->ids)->delete();
        return redirect(route('users.index'));
    }
}
