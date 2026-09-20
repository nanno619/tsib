<?php

namespace App\Enums;

enum ChildStatus: string
{
    case Draft = 'draft';
    case Submitted = 'submitted';
    case Approved = 'approved';
}
