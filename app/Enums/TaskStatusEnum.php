<?php

namespace App\Enums;

enum TaskStatusEnum: string
{
    case ToDo = 'todo';
    case In_Progress = 'in_progress';
    case Done = 'done';

}
