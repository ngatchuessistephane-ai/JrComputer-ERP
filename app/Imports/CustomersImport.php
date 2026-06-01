<?php

namespace App\Imports;

use App\Models\Module3\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CustomersImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new Customer([
            'name'  => $row['nom'],
            'email' => $row['email'],
            'phone' => $row['telephone'],
            'address' => $row['adresse'],
            'tax_number' => $row['num_tva'],
        ]);
    }

    public function rules(): array
    {
        return [
            'email' => 'nullable|email|unique:customers,email',
        ];
    }
}