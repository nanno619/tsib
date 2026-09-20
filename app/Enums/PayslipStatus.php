<?php

namespace App\Enums;

enum PayslipStatus: string
{
    case Draft = 'draft';
    case Submitted = 'submitted';
    case Published = 'published';
}
