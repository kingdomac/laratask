<?php

namespace App\Enums;

enum StatusEnum: int
{
    case TODO = 1;
    case IN_PROGRESS = 2;
    case PENDING = 3;
    case COMPLETED = 4;
    case VERIFIED = 5;
    case CANCELED = 6;
}
