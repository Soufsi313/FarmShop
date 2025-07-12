<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Carbon\Carbon;
use App\Models\Product;

class RentalDateValidation implements ValidationRule
{
    private $product;
    private $startDate;
    private $endDate;
    private $type;

    public function __construct(Product $product, ?Carbon $startDate = null, ?Carbon $endDate = null, string $type = 'start')
    {
        $this->product = $product;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->type = $type; // 'start', 'end', 'period'
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $date = Carbon::parse($value);
        
        switch ($this->type) {
            case 'start':
                $this->validateStartDate($date, $fail);
                break;
            case 'end':
                $this->validateEndDate($date, $fail);
                break;
            case 'period':
                $this->validatePeriod($fail);
                break;
        }
    }

    private function validateStartDate(Carbon $date, Closure $fail): void
    {
        // Vérifier que la date n'est pas aujourd'hui ou dans le passé
        if ($date->lte(now()->startOfDay())) {
            $fail("La location ne peut pas commencer aujourd'hui. Date minimum : " . now()->addDay()->format('d/m/Y'));
            return;
        }

        // Vérifier que le jour est disponible
        $dayOfWeek = $date->dayOfWeek === 0 ? 7 : $date->dayOfWeek;
        if (!$this->product->isDayAvailable($dayOfWeek)) {
            $dayName = $this->getDayName($dayOfWeek);
            $fail("Location non disponible le {$dayName}. Jours disponibles : Lundi - Samedi");
            return;
        }
    }

    private function validateEndDate(Carbon $date, Closure $fail): void
    {
        if (!$this->startDate) {
            return; // Cannot validate end date without start date
        }

        // Vérifier que la date de fin est après la date de début
        if ($date->lte($this->startDate)) {
            $fail("La date de fin doit être après la date de début");
            return;
        }

        // Vérifier que le jour est disponible
        $dayOfWeek = $date->dayOfWeek === 0 ? 7 : $date->dayOfWeek;
        if (!$this->product->isDayAvailable($dayOfWeek)) {
            $dayName = $this->getDayName($dayOfWeek);
            $fail("Location non disponible le {$dayName}. Jours disponibles : Lundi - Samedi");
            return;
        }

        // Vérifier la durée
        $duration = $this->startDate->diffInDays($date) + 1;
        
        if ($duration < $this->product->min_rental_days) {
            $fail("Durée minimale de location : {$this->product->min_rental_days} jour(s)");
            return;
        }

        if ($duration > $this->product->max_rental_days) {
            $fail("Durée maximale de location : {$this->product->max_rental_days} jour(s)");
            return;
        }
    }

    private function validatePeriod(Closure $fail): void
    {
        if (!$this->startDate || !$this->endDate) {
            return;
        }

        // Vérifier que tous les jours de la période sont disponibles
        $current = $this->startDate->copy();
        while ($current->lte($this->endDate)) {
            $dayOfWeek = $current->dayOfWeek === 0 ? 7 : $current->dayOfWeek;
            
            if (!$this->product->isDayAvailable($dayOfWeek)) {
                $dayName = $this->getDayName($dayOfWeek);
                $fail("Location non disponible le {$dayName} ({$current->format('d/m/Y')}). Jours disponibles : Lundi - Samedi");
                return;
            }
            
            $current->addDay();
        }
    }

    private function getDayName(int $dayOfWeek): string
    {
        $days = [
            1 => 'Lundi',
            2 => 'Mardi', 
            3 => 'Mercredi',
            4 => 'Jeudi',
            5 => 'Vendredi',
            6 => 'Samedi',
            7 => 'Dimanche'
        ];
        
        return $days[$dayOfWeek] ?? 'Jour inconnu';
    }
}
