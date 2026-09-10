<?php

namespace App\Imports;

use App\Models\Module1\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Validators\Failure;

class ProductsImport implements ToModel, WithHeadingRow, WithChunkReading, WithValidation, SkipsOnFailure
{
    use Importable;
    
    protected $errors = [];

    /**
     * Nettoie un prix (enlève les espaces, lettres, garde les chiffres)
     */
    private function cleanPrice($value)
    {
        if (empty($value)) return 0;
        
        // Enlève les espaces, les lettres, garde uniquement les chiffres
        $cleaned = preg_replace('/[^0-9]/', '', (string) $value);
        
        return (float) $cleaned;
    }

    /**
     * Nettoie un entier
     */
    private function cleanInteger($value)
    {
        if (empty($value)) return 0;
        
        // Enlève tout ce qui n'est pas un chiffre
        $cleaned = preg_replace('/[^0-9]/', '', (string) $value);
        
        return (int) $cleaned;
    }

    public function model(array $row)
    {
        // Support multi-colonnes (français ou anglais)
        $name = $row['nom'] ?? $row['name'] ?? null;
        $reference = $row['reference'] ?? null;
        $serialNumber = $row['numero_serie'] ?? $row['serial_number'] ?? null;
        $description = $row['description'] ?? null;
        $category = $row['categorie'] ?? $row['category'] ?? null;
        $supplier = $row['fournisseur'] ?? $row['supplier'] ?? null;
        
        // Prix avec nettoyage
        $purchasePrice = $this->cleanPrice($row['prix_achat'] ?? $row['purchase_price'] ?? 0);
        $sellingPrice = $this->cleanPrice($row['prix_vente'] ?? $row['selling_price'] ?? 0);
        
        // Quantités
        $quantity = $this->cleanInteger($row['quantite'] ?? $row['quantity'] ?? 0);
        $alertThreshold = $this->cleanInteger($row['seuil_alerte'] ?? $row['alert_threshold'] ?? 5);
        
        return new Product([
            'name'           => $name,
            'reference'      => $reference,
            'serial_number'  => $serialNumber,
            'description'    => $description,
            'purchase_price' => $purchasePrice,
            'selling_price'  => $sellingPrice,
            'quantity'       => $quantity,
            'alert_threshold'=> $alertThreshold,
            'category'       => $category,
            'supplier'       => $supplier,
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

    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $this->errors[] = "Ligne {$failure->row()} - " . implode(', ', $failure->errors());
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }
}