<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['customer', 'seller'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
        return view('frontend.admin.dashboard.users.users_all', compact('users'));
    }

    public function create()
    {
        return view('frontend.admin.dashboard.users.forms_users_all');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'user_type' => 'required|in:0,1,2',
            'gender' => 'nullable|in:0,1',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['active'] = $request->has('active') ? 1 : 0;

        if ($request->hasFile('profile_photo')) {
            $image = $request->file('profile_photo');
            $imageName = time().'_'.$image->getClientOriginalName(); 
            $destinationPath = public_path('static/images/users');
            $image->move($destinationPath, $imageName);
            $validated['profile_photo'] = 'static/images/users/' . $imageName;
        }

        $validated['password'] = bcrypt($validated['password']);
        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'تم إضافة المستخدم بنجاح.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('frontend.admin.dashboard.users.forms_users_all', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->user_id . ',user_id',
            'password' => 'nullable|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'user_type' => 'required|in:0,1,2',
            'gender' => 'nullable|in:0,1',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['active'] = $request->has('active') ? 1 : 0;

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo && file_exists(public_path($user->profile_photo))) {
                unlink(public_path($user->profile_photo));
            }
            $image = $request->file('profile_photo');
            $imageName = time().'_'.$image->getClientOriginalName(); 
            $destinationPath = public_path('static/images/users');
            $image->move($destinationPath, $imageName);
            $validated['profile_photo'] = 'static/images/users/' . $imageName;
        }

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'تم تحديث المستخدم بنجاح.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->profile_photo && file_exists(public_path($user->profile_photo))) {
            unlink(public_path($user->profile_photo));
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'تم حذف المستخدم بنجاح.');
    }


    public function editProfile()
    {
        $user = Auth::user(); 
        return view('frontend.admin.dashboard.users.admin-dashboard-settings', compact('user'));
    }

    // تحديث البيانات الشخصية
public function updateProfile(Request $request)
{
    $user = Auth::user();

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->user_id . ',user_id',
        'phone' => 'nullable|string|max:20',
        'gender' => 'nullable|in:0,1',
        'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // رفع الصورة
    if ($request->hasFile('profile_photo')) {
        if ($user->profile_photo && file_exists(public_path($user->profile_photo))) {
            unlink(public_path($user->profile_photo));
        }
        $image = $request->file('profile_photo');
        $imageName = time().'_'.$image->getClientOriginalName();
        $image->move(public_path('static/images/users'), $imageName);
        $validated['profile_photo'] = 'static/images/users/' . $imageName;
    }

    $user->update($validated);

    return redirect()->back()->with('success', 'تم تحديث بياناتك الشخصية بنجاح.');
}

// تغيير كلمة السر
public function updatePassword(Request $request)
{
    $user = Auth::user();

    $validated = $request->validate([
        'password' => 'required|min:6|confirmed',
    ]);

    $user->password = bcrypt($validated['password']);
    $user->save();

    return redirect()->back()->with('success', 'تم تغيير كلمة السر بنجاح.');
}

}
