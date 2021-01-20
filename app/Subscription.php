<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Math;

class Subscription extends Model
{
    protected $table = 'subscriptions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'sub_pabble_id'
    ];

    public function subscribed($user_id, $sub_pabble_id)
    {
        return $this->where('user_id', $user_id)->where('sub_pabble_id', $sub_pabble_id)->first();
    }

    public function subscriptions($user_id)
    {
        return $this->select('user_id', 'sub_pabble_id', 'name')
            ->join('sub_pabbles', 'subscriptions.sub_pabble_id', '=', 'sub_pabbles.id')
            ->where('user_id', $user_id)->get();
    }

}
