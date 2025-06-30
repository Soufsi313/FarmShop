<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check(); // Seuls les utilisateurs connectés peuvent ajouter au panier
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:100',
            'type' => 'nullable|string|in:purchase,rental',
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
            'product_id.required' => 'Le produit est obligatoire.',
            'product_id.exists' => 'Le produit sélectionné n\'existe pas.',
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
            if ($this->product_id) {
                $product = \App\Models\Product::find($this->product_id);
                
                if ($product) {
                    // Vérifier si le produit est actif
                    if (!$product->is_active) {
                        $validator->errors()->add('product_id', 'Ce produit n\'est plus disponible.');
                    }
                    
                    // Vérifier le stock disponible
                    if ($product->quantity < $this->quantity) {
                        $validator->errors()->add('quantity', "Stock insuffisant. Stock disponible: {$product->quantity}");
                    }
                }
            }
        });
    }
}
