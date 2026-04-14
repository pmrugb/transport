<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class Controller
{
    protected function resolvePerPage(Request $request): int|string
    {
        $perPage = $request->input('per_page', 10);

        if ($perPage === 'all') {
            return 'all';
        }

        $perPage = (int) $perPage;

        return in_array($perPage, [10, 25, 50, 100], true) ? $perPage : 10;
    }

    protected function paginationSize(int|string $perPage, int $total): int
    {
        return $perPage === 'all' ? max(1, $total) : $perPage;
    }
}
