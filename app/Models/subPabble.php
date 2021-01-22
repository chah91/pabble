<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class subPabble extends Model
{
    protected $table = 'sub_pabbles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'name_metaphone', 'description', 'description_social', 'icon', 'header', 'header_type', 'custom_css' , 'owner_id',
    ];

    public function searchByName($query)
    {
        return $this->select('name')->where('name', 'LIKE', '%' . $query . '%')->orderBy('name', 'asc')->take(10)->get();
    }

}
