<?php

namespace App\Models;

use App\Enums\TaskStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'user_id',
        'parent_id',
        'completed_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(Task::class);
    }

    public function children()
    {
        return $this->hasMany(Task::class, 'parent_id', );
    }

    public function scopeSearch($query, $search)
    {
        return $query->whereRaw("MATCH(title, description) AGAINST(? IN BOOLEAN MODE)", [$search]);
    }

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($model) {
            if ($model->isDirty('status') && $model->status !== TaskStatusEnum::Done->value) {
                $model->load('parent');
                $parent = $model->parent;
                if (isset($parent)) {
                    if ($parent->status === TaskStatusEnum::Done->value) {
                        $parent->status = TaskStatusEnum::ToDo->value;
                        $parent->completed_at = null;
                        $parent->save();
                    }
                }
            }
        });
    }
}
