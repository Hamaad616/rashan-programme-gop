<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromCollection, ShouldQueue, WithHeadings, WithMapping
{
    use Exportable;

    public function headings(): array
    {
        return [
            'CNIC',
            'Name',
            'Phone',
            'Address',
        ];
    }

    public function map($user): array
    {
        return [
            $user->cnic,
            $user->name,
            $user->phone,
            $user->address,
        ];
    }

    public function collection()
    {
        return User::all();
    }
}
