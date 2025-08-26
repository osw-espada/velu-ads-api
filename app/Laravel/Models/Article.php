<?php namespace App\Laravel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Article extends Model{
    use SoftDeletes;
    /**
     * Enable soft delete in table
     * @var boolean
     */
    protected $softDelete = true;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'articles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that created within the model.
     *
     * @var array
     */
    protected $appends = [];

    public function getDirectoryAttribute($value){
        return str_replace(env("AWS_S3_URL"),env("AWS_S3_CDN"),$value);
    }

    public function user(){
        return $this->belongsTo('App\Laravel\Models\User', 'user_id','id');
    }


}
