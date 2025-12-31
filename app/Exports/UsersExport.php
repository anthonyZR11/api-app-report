<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromQuery, WithHeadings
{
    public function __construct(public $startDate, public $endDate) {}

    public function query()
    {
        return User::query()
            ->whereBetween('birth_date', [$this->startDate, $this->endDate])
            ->select('id', 'name', 'birth_date', 'created_at');
    }

    public function headings(): array
    {
        return ['ID', 'Nombre', 'Fecha Nacimiento', 'Creado'];
    }
}
