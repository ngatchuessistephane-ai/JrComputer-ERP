<?php

namespace App\Imports;

use App\Models\Module1\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductsImport implements ToModel, WithHeadingRow, WithChunkReading, WithValidation
{
    public function model(array $row)
    {
        return new Product([
            'name'          => $row['nom'],
            'reference'     => $row['reference'],
            'serial_number' => $row['numero_serie'] ?? null,
            'description'   => $row['description'] ?? null,
            'purchase_price'=> $row['prix_achat'],
            'selling_price' => $row['prix_vente'],
            'quantity'      => $row['quantite'] ?? 0,
            'alert_threshold'=> $row['seuil_alerte'] ?? 5,
            'category'      => $row['categorie'] ?? null,
            'supplier'      => $row['fournisseur'] ?? null,
        ]);
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function rules(): array
    {
        return [
            'reference' => 'required|unique:products,reference',
        ];
    }
}