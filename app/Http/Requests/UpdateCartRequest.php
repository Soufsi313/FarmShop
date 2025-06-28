<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCartRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'quantity' => 'required|integer|min:1|max:100',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'quantity.required' => 'La quantité est obligatoire.',
            'quantity.integer' => 'La quantité doit être un nombre entier.',
            'quantity.min' => 'La quantité minimum est 1.',
            'quantity.max' => 'La quantité maximum est 100.',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Récupérer l'élément du panier depuis la route
            $cartItem = $this->route('cart');
            
            if ($cartItem && $cartItem->product) {
                // Vérifier si le produit est toujours actif
                if (!$cartItem->product->is_active) {
                    $validator->errors()->add('quantity', 'Ce produit n\'est plus disponible.');
                }
                
                // Vérifier le stock disponible
                if ($cartItem->product->quantity < $this->quantity) {
                    $validator->errors()->add('quantity', "Stock insuffisant. Stock disponible: {$cartItem->product->quantity}");
                }
            }
        });
    }
}
