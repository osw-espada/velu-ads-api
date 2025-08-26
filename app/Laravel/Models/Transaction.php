<?php namespace App\Laravel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model{
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
    protected $table = 'transactions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    protected $casts = [
        'expired_at' => 'datetime',
        'paid_at' => 'datetime'
    ];


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

}
