<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use Inertia\Inertia;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminSettingController extends Controller
{
    // public function account()
    // {
    //     return Inertia::render('Admin/Setting/Account');
    // }

    public function department()
    {
        // Get all departments and number of employees in each department
        $departments = Department::withCount('profiles')->get();
        return Inertia::render('Admin/Settings/Department',
            ['departments' => $departments]);
    }

    public function storeDepartment()
    {
        request()->validate([
            'name' => 'required|string|max:255'
        ]);

        Department::create([
            'name' => request('name')
        ]);

        return redirect()->route('admin.settings.department')->with('success', 'Department created successfully.');
    }

    public function updateDepartment($id)
    {

        request()->validate([
            'name' => 'required|string|max:255'
        ]);

        Department::find($id)->update([
            'name' => request('name')
        ]);

        return redirect()->route('admin.settings.department')->with('success', 'Department updated successfully.');
    }

    public function role()
    {
        $roles = Role::withCount('profiles')->get();
        return Inertia::render('Admin/Settings/Role',
            ['roles' => $roles]);
    }

    public function storeRole()
    {
        request()->validate([
            'title' => 'required|string|max:255'
        ]);

        Role::create([
            'title' => request('title')
        ]);

        return redirect()->route('admin.settings.role')->with('success', 'Role created successfully.');
    }

    public function updateRole($id)
    {
        request()->validate([
            'title' => 'required|string|max:255'
        ]);

        Role::find($id)->update([
            'title' => request('title')
        ]);

        return redirect()->route('admin.settings.role')->with('success', 'Role updated successfully.');
    }


}
