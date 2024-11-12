<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Redirect;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        //if request is filter employees by active status
        if ($request->has('active')) {
            $employees = User::where('role', 'employee')
                ->where('is_active', $request->active)
                ->paginate(10);
        } else {
            $employees = User::where('role', 'employee')->latest()->paginate(10);
        }

        $employees->load(['profile.department', 'profile.role']);

        // if ($request->has('export') && $request->export === 'excel') {
        //     return Excel::download(new EmployeesExport($employees), 'employees.xlsx');
        // }

        return Inertia::render(
            'Admin/Employees/Index',
            ['employees' => $employees]
        );
    }

    public function store(Request $request)
    {
        //dd($request->all());
        $validatedData = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ]);

        $validatedData['password'] = bcrypt($validatedData['password']);

        $user = User::create($validatedData);

        return redirect()->route('admin.employees.index')->with('success', 'Employee created successfully.');
    }

    public function update(Request $request)
    {

        $employee = User::findOrFail($request->id);
        //dd($request->all());
        $validatedData = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => "required|email|unique:users,email,{$employee->id}",
            'password' => 'nullable',
            'is_active' => 'boolean',
        ]);

        if ($request->filled('password')) {
            $validatedData['password'] = bcrypt($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }

        $employee->update($validatedData);
        return redirect()->route('admin.employees.index')->with('success', 'Employee updated successfully.');
    }
}
