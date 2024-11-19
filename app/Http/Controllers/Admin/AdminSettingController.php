<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use Inertia\Inertia;
use App\Models\Sport;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminSettingController extends Controller
{
    public function account()
    {
        return Inertia::render('Admin/Settings/Account');
    }

    public function updateProfile(Request $request)
    {
        request()->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        auth()->user()->update([
            'first_name' => request('first_name'),
            'last_name' => request('last_name'),
            'email' => request('email'),
        ]);

        return redirect()->route('admin.settings.account')->with('success', 'Profile updated successfully.');
    }

    public function changePassword(Request $request)
    {
        //dd($request->all());
        request()->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8',
        ]);

        if (!\Hash::check(request('current_password'), auth()->user()->password)) {
            //dd('here');
            return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }
        auth()->user()->update([
            'password' => bcrypt(request('new_password'))
        ]);

        return redirect()->route('admin.settings.account')->with('success', 'Password updated successfully.');
    }

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

    public function sport()
    {
        
        $sports = Sport::withCount('user')->get();
        $categories = Sport::CATEGORIES;
        $focus = Sport::FOCUS;
        return Inertia::render('Admin/Settings/Sport',
            ['sports' => $sports,
            'categories' => $categories,
            'focus' => $focus]);
    }

    public function storeSport()
    {
        request()->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:' . implode(',', Sport::CATEGORIES),
            'focus' => 'required|string|in:' . implode(',', Sport::FOCUS)
        ]);

        Sport::create([
            'name' => request('name'),
            'category' => request('category'),
            'focus' => request('focus')
        ]);

        return redirect()->route('admin.settings.sport')->with('success', 'Sport created successfully.');
    }

    public function updateSport($id)
    {
        request()->validate([
            'name' => 'required|string|max:255',
        ]);

        Sport::find($id)->update([
            'name' => request('name'),
        ]);

        return redirect()->route('admin.settings.sport')->with('success', 'Sport updated successfully.');
    }


}
