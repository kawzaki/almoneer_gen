<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('username', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            });
        }

        $users = $query->orderBy('id', 'asc')->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:50|alpha_dash|unique:users,username',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'role'     => 'required|in:admin,super_admin',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'username.alpha_dash' => 'اسم المستخدم يجب أن يحتوي على أحرف وأرقام وشرطات فقط دون مسافات.',
            'username.unique'     => 'اسم المستخدم هذا مستخدم بالفعل.',
            'email.unique'        => 'البريد الإلكتروني هذا مسجل مسبقاً.',
            'password.min'        => 'يجب أن لا تقل كلمة المرور عن 8 أحرف.',
            'password.confirmed'  => 'تأكيد كلمة المرور غير متطابق.',
        ]);

        User::create([
            'name'     => $validated['name'],
            'username' => $validated['username'],
            'email'    => $validated['email'],
            'role'     => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'تم إنشاء المستخدم بنجاح.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique('users', 'username')->ignore($user->id)],
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role'     => 'required|in:admin,super_admin',
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'username.alpha_dash' => 'اسم المستخدم يجب أن يحتوي على أحرف وأرقام وشرطات فقط دون مسافات.',
            'username.unique'     => 'اسم المستخدم هذا مستخدم بالفعل.',
            'email.unique'        => 'البريد الإلكتروني هذا مسجل مسبقاً.',
            'password.min'        => 'يجب أن لا تقل كلمة المرور عن 8 أحرف.',
            'password.confirmed'  => 'تأكيد كلمة المرور غير متطابق.',
        ]);

        // Prevent demoting the last super admin
        if ($user->role === 'super_admin' && $validated['role'] !== 'super_admin') {
            $superAdminsCount = User::where('role', 'super_admin')->count();
            if ($superAdminsCount <= 1) {
                return back()->withErrors(['role' => 'لا يمكن تغيير دور المشرف العام الوحيد المتبقي في النظام.']);
            }
        }

        $user->name = $validated['name'];
        $user->username = $validated['username'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'تم تحديث بيانات المستخدم بنجاح.');
    }

    public function destroy(User $user)
    {
        if (Auth::id() === $user->id) {
            return back()->withErrors(['error' => 'لا يمكنك حذف حسابك الحالي أثناء تسجيل الدخول به.']);
        }

        if ($user->id === 1) {
            return back()->withErrors(['error' => 'لا يمكن حذف الحساب الإداري الأساسي للنظام.']);
        }

        if ($user->role === 'super_admin' && User::where('role', 'super_admin')->count() <= 1) {
            return back()->withErrors(['error' => 'لا يمكن حذف المشرف العام الوحيد المتبقي في النظام.']);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'تم حذف المستخدم بنجاح.');
    }
}
