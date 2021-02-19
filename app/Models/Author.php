<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Author
 * @package App\Models
 */
class Author extends Model
{
    use HasFactory;


    /**
     * @var string
     */
    protected $table='authors';

    /**
     * @var string[]
     */
    protected $fillable=['name','surname','magazine_id'];

    /**
     * @var array
     */
    protected $guarded=[];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function magazine(){

        return $this->belongsTo(Magazine::class,'magazine_id','id');
    }
}
