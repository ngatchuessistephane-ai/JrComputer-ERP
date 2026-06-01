<?php

namespace App\Imports;

use App\Models\Module2\Supplier;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SuppliersImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new Supplier([
            'name'           => $row['nom'],
            'code'           => $row['code'],
            'contact_person' => $row['contact'] ?? null,
            'email'          => $row['email'],
            'phone'          => $row['telephone'],
            'address'        => $row['adresse'],
            'tax_number'     => $row['num_tva'] ?? null,
            'payment_terms'  => $row['delai_paiement'] ?? 30,
        ]);
    }

    public function rules(): array
    {
        return [
            'code' => 'required|unique:suppliers,code',
            'email' => 'nullable|email',
        ];
    }

    public function chunkSize(): int
    {
        return 100;
    }
}