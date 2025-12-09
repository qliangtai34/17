<?php
    
namespace App\Http\Controllers\Admin;

    use App\Http\Controllers\Controller;
    use App\Models\User;

    class UserController extends Controller
    {
        public function index()
        {
            $users = User::where('role', 'user') // ← 管理者を除外
                 ->orderBy('name')->get();
            return view('admin.users.index', compact('users'));
        }
    }
