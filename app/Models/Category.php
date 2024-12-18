<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function projects()
    {
        // M:M relationship between categories and projects
        // made the pivot table too : categories_projects , do laravel creates pivot table automatically?
        return $this->belongsToMany(Project::class, 'categories_projects','categories_id', 'projects_id');

        // this is what the raw SQL queries looks like
        /*
        SELECT projects.*
        FROM projects
        INNER JOIN category_project ON projects.id = category_project.project_id
        WHERE category_project.category_id = :category_id;
        */
    }
}
