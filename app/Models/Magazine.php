<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Magazine
 * @package App\Models
 */
class Magazine extends Model
{
    use HasFactory;



    protected $table='magazines';


    /**
     * @var string[]
     */
    protected $fillable=['name','img','date'];

    /**
     * @var array
     */
    protected $guarded=[];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function authors(){

        return $this->hasMany(Author::class,'magazine_id','id');
    }



}
