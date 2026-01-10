<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Str;

class Helper
{
    /**
     * Taux de conversion EUR vers CFA (1€ = 655,957 FCFA)
     */
    const CFA_EXCHANGE_RATE = 655.957;
    
    /**
     * Formater un montant en Francs CFA
     */
    public static function formatCFA($amount, $decimals = 0, $showSymbol = true)
    {
        if (!is_numeric($amount)) {
            return $showSymbol ? '0 FCFA' : '0';
        }
        
        // Arrondir et formater avec séparateur de milliers
        $formatted = number_format(round($amount), $decimals, ',', ' ');
        
        return $showSymbol ? $formatted . ' FCFA' : $formatted;
    }
    
    /**
     * Convertir un montant en Rupiah (IDR)
     */
    public static function convertToRupiah($price, $showSymbol = true)
    {
        if (!is_numeric($price)) {
            return $showSymbol ? 'Rp. 0' : '0';
        }
        
        $formatted = number_format($price, 2, ',', '.');
        
        return $showSymbol ? 'Rp. ' . $formatted : $formatted;
    }
    
    /**
     * Convertir EUR en CFA
     */
    public static function convertEuroToCFA($euros, $showSymbol = true)
    {
        if (!is_numeric($euros)) {
            return $showSymbol ? '0 FCFA' : '0';
        }
        
        $cfa = $euros * self::CFA_EXCHANGE_RATE;
        
        return self::formatCFA($cfa, 0, $showSymbol);
    }
    
    /**
     * Convertir CFA en EUR
     */
    public static function convertCFAToEuro($cfa, $showSymbol = true)
    {
        if (!is_numeric($cfa)) {
            return $showSymbol ? '0 €' : '0';
        }
        
        $euros = $cfa / self::CFA_EXCHANGE_RATE;
        
        $formatted = number_format($euros, 2, ',', ' ');
        
        return $showSymbol ? $formatted . ' €' : $formatted;
    }
    
    /**
     * Obtenir le mois actuel
     */
    public static function thisMonth()
    {
        return Carbon::parse(Carbon::now())->format('m');
    }
    
    /**
     * Obtenir l'année actuelle
     */
    public static function thisYear()
    {
        return Carbon::parse(Carbon::now())->format('Y');
    }
    
    /**
     * Formater une date avec le jour de la semaine
     */
    public static function dateDayFormat($date)
    {
        if (empty($date)) {
            return 'N/A';
        }
        
        try {
            return Carbon::parse($date)->isoFormat('dddd, D MMM YYYY');
        } catch (\Exception $e) {
            return 'Date invalide';
        }
    }
    
    /**
     * Formater une date simple
     */
    public static function dateFormat($date)
    {
        if (empty($date)) {
            return 'N/A';
        }
        
        try {
            return Carbon::parse($date)->isoFormat('D MMM YYYY');
        } catch (\Exception $e) {
            return 'Date invalide';
        }
    }
    
    /**
     * Formater une date avec l'heure
     */
    public static function dateFormatTime($date)
    {
        if (empty($date)) {
            return 'N/A';
        }
        
        try {
            return Carbon::parse($date)->isoFormat('D MMM YYYY H:mm:ss');
        } catch (\Exception $e) {
            return 'Date invalide';
        }
    }
    
    /**
     * Formater une date avec l'heure (sans année)
     */
    public static function dateFormatTimeNoYear($date)
    {
        if (empty($date)) {
            return 'N/A';
        }
        
        try {
            return Carbon::parse($date)->isoFormat('D MMM, hh:mm a');
        } catch (\Exception $e) {
            return 'Date invalide';
        }
    }
    
    /**
     * Calculer la différence de jours entre deux dates
     */
    /**
 * Calculer la différence de jours entre deux dates
 */
    public static function getDateDifference($check_in, $check_out)
    {
        if (empty($check_in) || empty($check_out)) {
            return 0;
        }
        
        try {
            $check_in = Carbon::parse($check_in);
            $check_out = Carbon::parse($check_out);
            
            // CORRECTION ICI : check_in -> check_out (pas check_out -> check_in)
            return $check_in->diffInDays($check_out);
            
        } catch (\Exception $e) {
            // Log l'erreur pour debug
            \Log::error('Erreur dans getDateDifference:', [
                'check_in' => $check_in,
                'check_out' => $check_out,
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }
    
    /**
     * Obtenir le pluriel d'un mot
     */
    public static function plural($value, $count)
    {
        return Str::plural($value, $count);
    }
    
    /**
     * Obtenir une couleur selon le nombre de jours
     */
    public static function getColorByDay($day)
    {
        if (!is_numeric($day)) {
            return 'bg-secondary';
        }
        
        if ($day == 1) {
            return 'bg-danger';
        } elseif ($day > 1 && $day < 4) {
            return 'bg-warning';
        } else {
            return 'bg-success';
        }
    }
    
    /**
     * Calculer le total d'un paiement
     */
    public static function getTotalPayment($day, $price)
    {
        if (!is_numeric($day) || !is_numeric($price)) {
            return 0;
        }
        
        return $day * $price;
    }
    
    /**
     * Formater une durée en jours avec pluriel
     */
    public static function formatDays($days)
    {
        if (!is_numeric($days)) {
            return '0 jours';
        }
        
        $days = intval($days);
        
        if ($days === 0) {
            return 'Moins d\'un jour';
        } elseif ($days === 1) {
            return '1 jour';
        } else {
            return $days . ' jours';
        }
    }
    
    /**
     * Formater un montant avec devise intelligente
     * Détecte automatiquement si c'est en CFA ou autre
     */
    public static function formatCurrency($amount, $currency = null)
    {
        if (!is_numeric($amount)) {
            return '0';
        }
        
        // Si aucune devise spécifiée, on formate simplement
        if (!$currency) {
            return number_format($amount, 0, ',', ' ');
        }
        
        // Formater selon la devise
        switch (strtoupper($currency)) {
            case 'CFA':
            case 'FCFA':
            case 'XOF':
                return self::formatCFA($amount);
                
            case 'EUR':
            case 'EURO':
            case '€':
                return number_format($amount, 2, ',', ' ') . ' €';
                
            case 'IDR':
            case 'RP':
            case 'RUPIAH':
                return self::convertToRupiah($amount);
                
            case 'USD':
            case '$':
                return '$ ' . number_format($amount, 2, '.', ',');
                
            default:
                return number_format($amount, 2, ',', ' ') . ' ' . $currency;
        }
    }
    
    /**
     * Générer un code de réservation unique
     */
    public static function generateReservationCode($prefix = 'RES')
    {
        return $prefix . '-' . strtoupper(Str::random(6)) . '-' . Carbon::now()->format('Ymd');
    }
    
    /**
     * Calculer la TVA (20% pour la France)
     */
    public static function calculateVAT($amount, $rate = 20)
    {
        if (!is_numeric($amount)) {
            return 0;
        }
        
        return ($amount * $rate) / 100;
    }
    
    /**
     * Formater un numéro de téléphone français
     */
    public static function formatPhoneNumber($phone)
    {
        if (empty($phone)) {
            return '';
        }
        
        // Nettoyer le numéro
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Formater selon la longueur
        if (strlen($phone) === 10) {
            return preg_replace('/(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/', '+33 $1 $2 $3 $4 $5', $phone);
        }
        
        return $phone;
    }
}