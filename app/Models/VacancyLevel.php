<?php

namespace App\Models;

class VacancyLevel
{
    private $remainingCount;

    public function __construct(int $remainingCount)
    {
        $this->remainingCount = $remainingCount;
    }

    public function mark(): string
    {
        if ($this->remainingCount === 0) {
            return '×';
        }
        if ($this->remainingCount === 4) {
            return '△';
        }
        if ($this->remainingCount === 5) {
            return '◎';
        }
    }

    public function slug(): string
    {
        if ($this->remainingCount === 0) {
            return 'empty';
        }
        if ($this->remainingCount < 5) {
            return 'few';
        }
        return 'enough';
    }
}