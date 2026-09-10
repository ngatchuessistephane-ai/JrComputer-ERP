<?php

namespace App\Imports;

use App\Models\Module2\Supplier;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithValidation;

class SuppliersImport implements ToModel, WithHeadingRow, WithChunkReading, WithValidation
{
    public function model(array $row)
    {
        return new Supplier([
            'name'           => $row['nom'] ?? $row['name'] ?? null,
            'code'           => $row['code'] ?? $row['reference'] ?? null,
            'contact_person' => $row['contact'] ?? $row['contact_person'] ?? null,
            'email'          => $row['email'] ?? null,
            'phone'          => $row['telephone'] ?? $row['phone'] ?? null,
            'address'        => $row['adresse'] ?? $row['address'] ?? null,
            'tax_number'     => $row['num_tva'] ?? $row['tax_number'] ?? null,
            'payment_terms'  => $row['delai_paiement'] ?? $row['payment_terms'] ?? 30,
        ]);
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function rules(): array
    {
        return [
            'code' => 'required|unique:suppliers,code',
            'email' => 'nullable|email',
        ];
    }
}