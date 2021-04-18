<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    protected $searchables = ['name'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!Auth::user()->can('role-list'))
            return abort(403);

        return Inertia::render('Role/Index', [
            'data' => Role::when($request->term, function ($query, $term) {
                foreach ($this->searchables as $search) {
                    $query->orWhere($search, 'LIKE', '%'. $term .'%');
                }
            })->paginate($request->perPage ?? 10)
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
        if(!Auth::user()->can('role-create'))
            return abort(403);

        return Inertia::render('Role/Create',[
            'permissions'   => Permission::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!Auth::user()->can('role-create'))
            return abort(403);

        $request->validate([
            'name'          =>  'required|string',
            'permissions'   => 'array',
        ]);
        $role =  Role::create(['name' => $request->name]);
        if($request->permissions)
            $role->syncPermissions($request->permissions);
        return redirect(route('roles.index'));
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
        if(!Auth::user()->can('role-edit'))
            return abort(403);

        return Inertia::render('Role/Edit', [
            'role'  => Role::findOrfail($id),
            'permissions'   => Permission::all()
        ]);
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
        if(!Auth::user()->can('role-edit'))
            return abort(403);

        $request->validate([
            'name'          =>  'required|string',
            'permissions'   => 'array',
        ]);
        $role = Role::findOrFail($id);
        $role->fill(['name' => $request->name]);
        $role->save();
        $permissions = $request->permissions ?? [];
        $role->syncPermissions($permissions);
        return redirect(route('roles.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!Auth::user()->can('role-delete'))
            return abort(403);

        $role = Role::findOrFail($id);
        $role->delete();
        return redirect(route('roles.index'));
    }

    public function massDestroy(Request $request)
    {
        if(!Auth::user()->can('role-delete'))
            return abort(403);

        Role::whereIn('id', $request->ids)->delete();
        return redirect(route('roles.index'));
    }
}
