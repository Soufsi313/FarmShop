<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check() && auth()->user()->can('manage categories');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $categoryId = $this->route('category')->id ?? $this->category;

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('categories', 'name')->ignore($categoryId)],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('categories', 'slug')->ignore($categoryId)],
            'description' => ['nullable', 'string', 'max:1000'],
            'type' => ['required', 'in:purchase,rental,both'],
            'food_type' => ['required', 'in:perishable,non_perishable,non_food'],
            'allows_returns' => ['boolean'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'is_active' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
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
            'name.required' => 'Le nom de la catégorie est obligatoire.',
            'name.unique' => 'Une catégorie avec ce nom existe déjà.',
            'slug.unique' => 'Une catégorie avec ce slug existe déjà.',
            'type.required' => 'Le type de catégorie est obligatoire.',
            'type.in' => 'Le type de catégorie doit être : achat, location ou les deux.',
            'food_type.required' => 'Le type alimentaire est obligatoire.',
            'food_type.in' => 'Le type alimentaire doit être : périssable, non périssable ou non alimentaire.',
            'image.image' => 'Le fichier doit être une image.',
            'image.mimes' => 'L\'image doit être au format jpeg, png, jpg ou gif.',
            'image.max' => 'L\'image ne doit pas dépasser 2 Mo.',
            'sort_order.min' => 'L\'ordre de tri doit être un nombre positif.',
        ];
    }

    /**
     * Préparer les données pour la validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // Convertir is_active en booléen si c'est une chaîne
        if ($this->has('is_active')) {
            $this->merge([
                'is_active' => filter_var($this->is_active, FILTER_VALIDATE_BOOLEAN)
            ]);
        }

        // Convertir allows_returns en booléen si c'est une chaîne
        if ($this->has('allows_returns')) {
            $this->merge([
                'allows_returns' => filter_var($this->allows_returns, FILTER_VALIDATE_BOOLEAN)
            ]);
        }
    }
}
