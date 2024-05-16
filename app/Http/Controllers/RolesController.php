<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class RolesController extends Controller
{
    public function index(Request $request){

        $filter = $request->filter;

        if(!empty($request->records_per_page)) {

            $request->records_per_page = $request->records_per_page <= env('PAGINATION_MAX_SIZE')
                                                                    ? $request->records_per_page
                                                                    :  env('PAGINATION_MAX_SIZE');
        } else {

            $request->records_per_page = env('PAGINATION_DEFAULT_SIZE');
        }

        $roles = Role::where('name', 'LIKE', "%$filter%")
                     ->paginate($request->records_per_page);

        return view('roles.index', ['roles' => $roles,
                                    'data' => $request]);

    }

    public function create() {

        $modules = Permission::all()
                             ->groupBy('module');

        return view('roles.create', ['modules' => $modules]);
    }

    public function edit($id) {

        $role = Role::find($id);

        if (empty($role)) {

            Session::flash('message', ['content' => "El rol con id '$id' no existe.", 'type' => 'error']);
            return redirect()->back();
        }

        $permissions = Permission::all();

        $permissions = $permissions->map(function($item) use($id) {

            $item->selected = false;

            $rolePermission = RolePermission::where('permission_id', '=', $item->id)
                                            ->where('role_id', '=', $id)
                                            ->first();

            if (!empty($rolePermission)) {

                $item->selected = true;
            }

            return $item;

        });

        $modules = $permissions->groupBy('module');

        return view('roles.edit', ['role' => $role, 'modules' => $modules]);
    }

    public function delete($id) {

        try {

            $role = Role::find($id);

            if (empty($role)) {

                Session::flash('message', ['content' => "El rol con id '$id' no existe.", 'type' => 'error']);
                return redirect()->back();
            }

            $role->delete();

            Session::flash('message', ['content' => 'rol eliminado con éxito', 'type' => 'success']);
            return redirect()->action([RolesController::class, 'index']);

        } catch (Exception $ex) {

            Log::error($ex);
            Session::flash('message', ['content' => 'Ha ocurrido un error', 'type' => 'error']);
            return redirect()->back();
        }

    }

    public function store(Request $request) {

        Validator::make($request->all(), [

            'name' => 'required|max:64',
            'permissions' => 'required|json'
        ],
        [
            'name.required' => 'El nombre es requerido.',
            'name.max' => 'El nombre no puede ser mayor a :max carácteres.',
            'permissions.required' => 'debe seleccionar al menos 1 permiso.',
            'permissions.json' => 'el campo permissions tiene el formato incorrecto.',
        ])->validate();

        try {

            DB::transaction(function() use ($request){

                // Creación de rol
                $role = new Role();
                $role->name = $request->name;
                $role->save();

                // Crear la relación entreroles y permisos
                $permissions = json_decode($request->permissions);

                foreach($permissions as $permission) {

                    $rolePermission = new RolePermission();
                    $rolePermission->role_id = $role->id;
                    $rolePermission->permission_id = $permission;
                    $rolePermission->save();
                }
            });

            Session::flash('message', ['content' => 'Rol creado con éxito', 'type' => 'success']);
            return redirect()->action([RolesController::class, 'index']);

        } catch(Exception $ex){

            Log::error($ex);
            Session::flash('message', ['content' => 'Ha ocurrido un error', 'type' => 'error']);
            return redirect()->back();
        }

    }

    public function update(Request $request) {

        Validator::make($request->all(), [

            'role_id' => 'required|exists:roles,id',
            'name' => 'required|max:64',
            'permissions' => 'required|json'
        ],
        [
            'role_id.required' => 'El id del rol es requerido.',
            'role_id.exists' => 'El id dado para el rol no existe.',
            'name.required' => 'El nombre es requerido.',
            'name.max' => 'El nombre no puede ser mayor a :max carácteres.',
            'permissions.required' => 'debe seleccionar al menos 1 permiso.',
            'permissions.json' => 'el campo permissions tiene el formato incorrecto.',
        ])->validate();

        try {

            DB::transaction(function() use ($request){

                // Edición de rol
                $role = Role::find($request->role_id);
                $role->name = $request->name;
                $role->save();

                // Eliminación de permisos viejos
                RolePermission::where('role_id', '=', $role->id)
                              ->delete();

                // Crear la relación entreroles y permisos
                $permissions = json_decode($request->permissions);

                foreach($permissions as $permission) {

                    $rolePermission = new RolePermission();
                    $rolePermission->role_id = $role->id;
                    $rolePermission->permission_id = $permission;
                    $rolePermission->save();
                }
            });

            Session::flash('message', ['content' => 'Rol actualizado con éxito', 'type' => 'success']);
            return redirect()->action([RolesController::class, 'index']);

        } catch(Exception $ex){

            Log::error($ex);
            Session::flash('message', ['content' => 'Ha ocurrido un error', 'type' => 'error']);
            return redirect()->back();
        }

    }
}
