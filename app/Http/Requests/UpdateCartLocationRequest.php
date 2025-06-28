<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\CartLocation;
use App\Models\Product;
use Carbon\Carbon;

class UpdateCartLocationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $cartLocation = $this->route('cart_location');
        
        // L'utilisateur doit être connecté et propriétaire de cet article du panier
        return auth()->check() && 
               $cartLocation && 
               $cartLocation->user_id === auth()->id() &&
               $cartLocation->status === CartLocation::STATUS_PENDING;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $cartLocation = $this->route('cart_location');
        
        return [
            'quantity' => [
                'sometimes',
                'required',
                'integer',
                'min:1',
                'max:100',
                function ($attribute, $value, $fail) use ($cartLocation) {
                    if ($cartLocation && $cartLocation->product) {
                        if (!$cartLocation->product->hasStock($value)) {
                            $fail("Stock insuffisant. Stock disponible: {$cartLocation->product->quantity}");
                        }
                    }
                }
            ],
            'rental_duration_days' => [
                'sometimes',
                'required',
                'integer',
                'min:1',
                'max:365',
                function ($attribute, $value, $fail) use ($cartLocation) {
                    if ($cartLocation && $cartLocation->product) {
                        $product = $cartLocation->product;
                        if (!$product->isValidRentalDuration($value)) {
                            $fail("Durée de location invalide. Min: {$product->min_rental_days} jours, Max: {$product->max_rental_days} jours");
                        }
                    }
                }
            ],
            'rental_start_date' => [
                'sometimes',
                'required',
                'date',
                'after_or_equal:today',
                function ($attribute, $value, $fail) use ($cartLocation) {
                    if ($cartLocation && $cartLocation->product) {
                        $startDate = Carbon::parse($value);
                        $duration = $this->input('rental_duration_days', $cartLocation->rental_duration_days);
                        $endDate = $startDate->copy()->addDays($duration);
                        $quantity = $this->input('quantity', $cartLocation->quantity);
                        
                        if (!$cartLocation->product->isAvailableForPeriod($startDate, $endDate, $quantity)) {
                            $fail('Ce produit n\'est pas disponible pour la période sélectionnée.');
                        }
                    }
                }
            ],
            'deposit_amount' => 'sometimes|nullable|numeric|min:0|max:10000',
        ];
    }

    /**
     * Get custom messages for validation errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'quantity.required' => 'La quantité est obligatoire.',
            'quantity.min' => 'La quantité doit être d\'au moins 1.',
            'quantity.max' => 'La quantité ne peut pas dépasser 100.',
            'rental_duration_days.required' => 'La durée de location est obligatoire.',
            'rental_duration_days.min' => 'La durée de location doit être d\'au moins 1 jour.',
            'rental_duration_days.max' => 'La durée de location ne peut pas dépasser 365 jours.',
            'rental_start_date.required' => 'La date de début de location est obligatoire.',
            'rental_start_date.after_or_equal' => 'La date de début de location ne peut pas être dans le passé.',
            'deposit_amount.numeric' => 'Le montant de la caution doit être un nombre.',
            'deposit_amount.min' => 'Le montant de la caution ne peut pas être négatif.',
        ];
    }

    /**
     * Get custom attributes for validation errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'quantity' => 'quantité',
            'rental_duration_days' => 'durée de location',
            'rental_start_date' => 'date de début',
            'deposit_amount' => 'caution',
        ];
    }
}
