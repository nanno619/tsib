<?php

namespace App\Enums;

enum LeaveApplicationStatus: string
{
    case Draft = 'draft';
    case Submitted = 'submitted';
    case Returned = 'returned';
    case Approved = 'approved';
    case Rejected = 'rejected';
}
