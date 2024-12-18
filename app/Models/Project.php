<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'imgLink'];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'categories_projects','projects_id', 'categories_id');

        // the raw SQL looks like this
        /*
            SELECT categories.*
            FROM categories
            INNER JOIN category_project ON categories.id = category_project.category_id
            WHERE category_project.project_id = :project_id;
        */
    }

    public function tasks()
    {
        // 1:M between projects:tasks
        return $this->hasMany(Task::class,'projects_id');

        // raw sql query
        /*
            SELECT tasks.*
            FROM tasks
            WHERE tasks.project_id = :project_id;
        */
    }
}
