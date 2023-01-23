<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignStudentToTeacher extends Model
{
    use HasFactory;
    /**
     * Write code on Method
     *
     * @return response()
     */
    protected $fillable = [
        'teacher_id', 'subject_id','assign_stu_ids','status'
    ];

    public function subject()
    {
       return $this->hasOne('App\Models\Subjects','id','subject_id')->where('status',1);
    }

    public function teacher()
    {
       return $this->hasOne('App\Models\Teachers','id','teacher_id')->where('status',1);
    }
}
