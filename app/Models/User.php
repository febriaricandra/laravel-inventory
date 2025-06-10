<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Yajra\DataTables\Facades\DataTables;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function isStaff()
    {
        return $this->hasRole('staff');
    }
    
    public static function datatables()
    {
        return DataTables::of(self::query())
            ->addColumn('roles', function ($user) {
                return $user->getRoleNames()->implode(', ');
            })
            ->addColumn('actions', function ($user) {
                if (auth()->user()->id === $user->id) {
                    return '#';
                }
                $actionbtn = '<a href="' . route('users.edit', $user->id) . '" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">Edit</a> ';
                $actionbtn .= '<form action="' . route('users.destroy', $user->id) . '" method="POST" style="display:inline-block;" onsubmit="return confirm(\'Are you sure you want to delete this user?\');">';
                $actionbtn .= csrf_field();
                $actionbtn .= method_field('DELETE');
                $actionbtn .= '<button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:border-red-700 focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150">Delete</button>';
                $actionbtn .= '</form>';
                return $actionbtn;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
