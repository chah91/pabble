<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Moderator extends Model
{
    protected $table = 'moderators';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'sub_pabble_id'
    ];

    public function getBySubPabbleId($id)
    {
        return $this->select('user_id', 'sub_pabble_id', 'username')
            ->join('users', 'moderators.user_id', '=', 'users.id')
            ->where('sub_pabble_id', $id)->get();
    }

    public function validateMods($mods_string) {
        $mods = explode(',', $mods_string);

        $invalid = '';
        foreach ($mods as $mod) {
            $u = User::where('username', $mod)->first();
            if (!$u) {
                $invalid.= $mod . ',';
            }
        }

        if ($invalid == '') {
            return true;
        }
        return false;
    }

    public function isMod($user_id, $sub_pabble)
    {
        if (env('ADMIN_ID') == $user_id) {
            return true;
        }
        if ($user_id == $sub_pabble->owner_id) {
            return true;
        }
        $check = $this->where('user_id', $user_id)->where('sub_pabble_id', $sub_pabble->id)->first();
        if ($check) {
            return true;
        } else {
            return false;
        }
    }

}
