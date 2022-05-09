<?php

namespace App\Services\EmployeeManagement;

class Applicant implements JobHunter
{
    public function applyJob() : bool
    {
        return true;
    }
}