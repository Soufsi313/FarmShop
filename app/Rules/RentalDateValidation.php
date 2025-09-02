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
        // Vérifier que la date de début est au minimum demain
        if ($date->lt(now()->addDay()->startOfDay())) {
            $fail("❌ Les locations doivent commencer au minimum demain. Veuillez choisir une date à partir du " . now()->addDay()->format('d/m/Y'));
            return;
        }

        // Vérifier que le jour est disponible
        $dayOfWeek = $date->dayOfWeek === 0 ? 7 : $date->dayOfWeek;
        if (!$this->product->isDayAvailable($dayOfWeek)) {
            $dayName = $this->getDayName($dayOfWeek);
            if ($dayOfWeek === 7) { // Dimanche spécifiquement
                $fail("🚫 Notre boutique est fermée le dimanche. Veuillez choisir une autre date (Lundi - Samedi)");
            } else {
                $fail("❌ Location non disponible le {$dayName}. Jours ouverts : Lundi - Samedi");
            }
            return;
        }
    }

    private function validateEndDate(Carbon $date, Closure $fail): void
    {
        if (!$this->startDate) {
            return; // Cannot validate end date without start date
        }

        // MODIFICATION TEMPORAIRE POUR TESTS : Permettre les locations d'un jour  
        // Vérifier que la date de fin est après ou égale à la date de début
        if ($date->lt($this->startDate)) {
            $fail("📅 La date de fin ({$date->format('d/m/Y')}) doit être après la date de début ({$this->startDate->format('d/m/Y')})");
            return;
        }

        // Vérifier que le jour est disponible
        $dayOfWeek = $date->dayOfWeek === 0 ? 7 : $date->dayOfWeek;
        if (!$this->product->isDayAvailable($dayOfWeek)) {
            $dayName = $this->getDayName($dayOfWeek);
            if ($dayOfWeek === 7) { // Dimanche spécifiquement
                $fail("🚫 Notre boutique est fermée le dimanche. Impossible de terminer une location ce jour-là. Veuillez choisir lundi-samedi");
            } else {
                $fail("❌ Location non disponible le {$dayName}. Jours ouverts : Lundi - Samedi");
            }
            return;
        }

        // Utiliser la nouvelle méthode de calcul qui exclut les dimanches
        $duration = $this->product->calculateRentalDuration($this->startDate, $date);
        
        if ($duration < $this->product->min_rental_days) {
            $totalDays = $this->startDate->diffInDays($date) + 1;
            $sundaysExcluded = $totalDays - $duration;
            $message = "⏱️ Durée de location insuffisante : {$duration} jour(s) ouvrés";
            if ($sundaysExcluded > 0) {
                $message .= " (sur {$totalDays} jours au total, {$sundaysExcluded} dimanche(s) exclus)";
            }
            $message .= ". Minimum requis : {$this->product->min_rental_days} jour(s) ouvrés";
            $fail($message);
            return;
        }

        // Vérifier max_rental_days seulement si défini (pas de limite si NULL)
        if ($this->product->max_rental_days !== null && $duration > $this->product->max_rental_days) {
            $fail("⏱️ Durée de location trop longue : {$duration} jour(s) ouvrés. Maximum autorisé : {$this->product->max_rental_days} jour(s) ouvrés");
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
        $unavailableDays = [];
        
        while ($current->lte($this->endDate)) {
            $dayOfWeek = $current->dayOfWeek === 0 ? 7 : $current->dayOfWeek;
            
            if (!$this->product->isDayAvailable($dayOfWeek)) {
                $dayName = $this->getDayName($dayOfWeek);
                $unavailableDays[] = "{$dayName} {$current->format('d/m/Y')}";
            }
            
            $current->addDay();
        }
        
        if (!empty($unavailableDays)) {
            if (count($unavailableDays) === 1 && strpos($unavailableDays[0], 'Dimanche') !== false) {
                $fail("🚫 Votre période inclut un dimanche (" . $unavailableDays[0] . "). Notre boutique est fermée ce jour-là. Les dimanches seront automatiquement exclus du calcul");
            } else {
                $fail("❌ Certains jours de votre période ne sont pas disponibles : " . implode(', ', $unavailableDays) . ". Jours ouverts : Lundi - Samedi");
            }
            return;
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
