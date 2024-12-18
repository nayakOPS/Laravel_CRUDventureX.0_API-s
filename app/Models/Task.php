<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'status', 'projects_id', 'assigned_to'];

    public function project()
    {
        // 1:1 task belong to single project
        return $this->belongsTo(Project::class,'projects_id');

        // raw query : to get the project for a task
        /*
            SELECT projects.*
            FROM projects
            WHERE projects.id = :project_id;
        */
    }

    public function assignedUser()
    {
        // 1:1 task belong to single user
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
