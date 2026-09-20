<?php

namespace App\Models;

use Database\Factories\RefLeaveTypeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'slug'])]
class RefLeaveType extends Model
{
    /** @use HasFactory<RefLeaveTypeFactory> */
    use HasFactory;
}
