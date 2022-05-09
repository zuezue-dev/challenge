<?php

namespace App\Services\EmployeeManagement;

class Staff implements Employee
{
    public function salary() : int
    {
        return 200;
    }
}