<?php

namespace App\Helpers;

class NumberToWords
{
    /**
     * Convertit un nombre en lettres en français
     * 
     * @param int|float $number Le nombre à convertir
     * @return string Le nombre en lettres
     */
    public static function convert($number)
    {
        // Arrondir et convertir en entier
        $number = (int) round($number);
        
        if ($number === 0) {
            return 'zéro';
        }

        // ✅ TABLEAUX DE RÉFÉRENCE
        $units = [
            0 => 'zéro', 1 => 'un', 2 => 'deux', 3 => 'trois', 4 => 'quatre',
            5 => 'cinq', 6 => 'six', 7 => 'sept', 8 => 'huit', 9 => 'neuf',
            10 => 'dix', 11 => 'onze', 12 => 'douze', 13 => 'treize',
            14 => 'quatorze', 15 => 'quinze', 16 => 'seize',
            17 => 'dix-sept', 18 => 'dix-huit', 19 => 'dix-neuf'
        ];
        
        $tens = [
            1 => 'dix', 2 => 'vingt', 3 => 'trente', 4 => 'quarante',
            5 => 'cinquante', 6 => 'soixante', 7 => 'soixante-dix',
            8 => 'quatre-vingt', 9 => 'quatre-vingt-dix'
        ];

        // ✅ GESTION DES MILLIONS
        if ($number >= 1000000) {
            $millions = floor($number / 1000000);
            $reste = $number % 1000000;
            
            if ($millions == 1) {
                $words = 'un million';
            } else {
                $words = self::convert($millions) . ' millions';
            }
            
            if ($reste > 0) {
                $words .= ' ' . self::convert($reste);
            }
            
            return $words;
        }

        // ✅ GESTION DES MILLIERS
        if ($number >= 1000) {
            $milliers = floor($number / 1000);
            $reste = $number % 1000;
            
            if ($milliers == 1) {
                $words = 'mille';
            } else {
                $words = self::convert($milliers) . ' mille';
            }
            
            if ($reste > 0) {
                // Si le reste est inférieur à 100, on ajoute un espace
                if ($reste < 100) {
                    $words .= ' ';
                } else {
                    $words .= ' ';
                }
                $words .= self::convert($reste);
            }
            
            return $words;
        }

        // ✅ GESTION DES CENTAINES
        if ($number >= 100) {
            $centaines = floor($number / 100);
            $reste = $number % 100;
            
            if ($centaines == 1) {
                $words = 'cent';
            } else {
                $words = $units[$centaines] . ' cent';
            }
            
            if ($reste > 0) {
                // "cent" prend un 's' quand il est suivi d'un nombre
                // Exemple: "deux cents" mais "deux cent cinq"
                $words .= ' ';
                $words .= self::convert($reste);
            }
            
            return $words;
        }

        // ✅ GESTION DES NOMBRES DE 0 À 99
        if ($number < 20) {
            return $units[$number];
        }

        // ✅ GESTION DES DIZAINES (20 à 99)
        $dizaine = floor($number / 10);
        $unite = $number % 10;

        // Cas particulier : 80 = quatre-vingts (avec 's' si pas d'unité)
        if ($dizaine == 8 && $unite == 0) {
            return 'quatre-vingts';
        }
        
        // Cas particulier : 70-79 (soixante-dix + unité)
        if ($dizaine == 7) {
            if ($unite == 0) {
                return 'soixante-dix';
            }
            return 'soixante-' . $units[$unite + 10];
        }
        
        // Cas particulier : 90-99 (quatre-vingt-dix + unité)
        if ($dizaine == 9) {
            if ($unite == 0) {
                return 'quatre-vingt-dix';
            }
            return 'quatre-vingt-' . $units[$unite + 10];
        }

        // Cas général : 21-29, 31-39, 41-49, 51-59, 61-69, 81-89
        $dizaineWord = $tens[$dizaine];
        
        // Règle du 's' pour vingt : pas de 's' si suivi d'une unité
        if ($dizaine == 2 && $unite > 0) {
            $dizaineWord = 'vingt';
        }
        
        // Règle du 's' pour quatre-vingt : pas de 's' si suivi d'une unité
        if ($dizaine == 8 && $unite > 0) {
            $dizaineWord = 'quatre-vingt';
        }
        
        if ($unite == 0) {
            return $dizaineWord;
        }
        
        // Règle du trait d'union pour les nombres composés
        return $dizaineWord . '-' . $units[$unite];
    }
}