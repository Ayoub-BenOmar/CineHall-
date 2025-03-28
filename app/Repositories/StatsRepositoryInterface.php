<?php
namespace App\Repositories\Contracts;

interface StatsRepositoryInterface
{
    public function getTotalCounts(): array;
}