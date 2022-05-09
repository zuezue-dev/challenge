<?php

namespace App\Services\InternetServiceProvider;

interface Operator {
    public function setMonth(int $month);
    public function calculateTotalAmount();
}