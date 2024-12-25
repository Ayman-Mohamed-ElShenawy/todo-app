<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Todo extends Model
{
    use HasFactory, SoftDeletes;
    protected $table= 'todos';
    protected $fillable=[
        'user_id',
        'title',
        'category_id',
        'description',
        'status',
    ];

    public function category(){
        return $this->belongsTo(Category::class,'category_id');
    }
    public function user(){
        return $this->belongs(User::class);
    }
    protected $dates = ['deleted_at'];
}
