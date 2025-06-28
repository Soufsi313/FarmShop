<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductImageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check() && auth()->user()->can('manage products');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'images' => ['required', 'array', 'min:1', 'max:10'],
            'images.*' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'], // 5MB max
            'alt_texts' => ['nullable', 'array'],
            'alt_texts.*' => ['nullable', 'string', 'max:255'],
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
            'product_id.required' => 'L\'ID du produit est obligatoire.',
            'product_id.exists' => 'Le produit spécifié n\'existe pas.',
            'images.required' => 'Au moins une image est obligatoire.',
            'images.array' => 'Les images doivent être fournies sous forme de tableau.',
            'images.min' => 'Au moins une image est requise.',
            'images.max' => 'Vous ne pouvez pas télécharger plus de 10 images à la fois.',
            'images.*.required' => 'Chaque fichier image est obligatoire.',
            'images.*.image' => 'Chaque fichier doit être une image.',
            'images.*.mimes' => 'Les images doivent être au format jpeg, png, jpg, gif ou webp.',
            'images.*.max' => 'Chaque image ne doit pas dépasser 5 Mo.',
            'alt_texts.*.max' => 'Le texte alternatif ne doit pas dépasser 255 caractères.',
        ];
    }

    /**
     * Préparer les données pour la validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // S'assurer que alt_texts est un tableau même s'il n'est pas fourni
        if (!$this->has('alt_texts')) {
            $this->merge(['alt_texts' => []]);
        }
    }
}
