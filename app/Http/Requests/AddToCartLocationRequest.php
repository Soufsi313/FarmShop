<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Product;
use Carbon\Carbon;

class AddToCartLocationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check(); // L'utilisateur doit être connecté
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_id' => [
                'required',
                'integer',
                'exists:products,id',
                function ($attribute, $value, $fail) {
                    $product = Product::find($value);
                    if (!$product || !$product->isAvailableForRental()) {
                        $fail('Ce produit n\'est pas disponible pour la location.');
                    }
                }
            ],
            'quantity' => [
                'required',
                'integer',
                'min:1',
                'max:100',
                function ($attribute, $value, $fail) {
                    if ($this->input('product_id')) {
                        $product = Product::find($this->input('product_id'));
                        if ($product && !$product->hasStock($value)) {
                            $fail("Stock insuffisant. Stock disponible: {$product->quantity}");
                        }
                    }
                }
            ],
            'rental_duration_days' => [
                'required',
                'integer',
                'min:1',
                'max:365',
                function ($attribute, $value, $fail) {
                    if ($this->input('product_id')) {
                        $product = Product::find($this->input('product_id'));
                        if ($product && !$product->isValidRentalDuration($value)) {
                            $fail("Durée de location invalide. Min: {$product->min_rental_days} jours, Max: {$product->max_rental_days} jours");
                        }
                    }
                }
            ],
            'rental_start_date' => [
                'required',
                'date',
                'after_or_equal:today',
                function ($attribute, $value, $fail) {
                    $startDate = Carbon::parse($value);
                    $endDate = $startDate->copy()->addDays($this->input('rental_duration_days', 1));
                    $productId = $this->input('product_id');
                    $quantity = $this->input('quantity', 1);
                    
                    if ($productId) {
                        $product = Product::find($productId);
                        if ($product && !$product->isAvailableForPeriod($startDate, $endDate, $quantity)) {
                            $fail('Ce produit n\'est pas disponible pour la période sélectionnée.');
                        }
                    }
                }
            ],
            'deposit_amount' => 'nullable|numeric|min:0|max:10000',
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
            'product_id.required' => 'Le produit est obligatoire.',
            'product_id.exists' => 'Le produit sélectionné n\'existe pas.',
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
            'product_id' => 'produit',
            'quantity' => 'quantité',
            'rental_duration_days' => 'durée de location',
            'rental_start_date' => 'date de début',
            'deposit_amount' => 'caution',
        ];
    }
}
