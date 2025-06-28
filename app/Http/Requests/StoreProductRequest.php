<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('manage products');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'unit_symbol' => ['required', Rule::in([Product::UNIT_KG, Product::UNIT_PIECE, Product::UNIT_LITER])],
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'critical_stock_threshold' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'bulk_pricing' => 'nullable|array',
            'bulk_pricing.*.min_quantity' => 'required_with:bulk_pricing|integer|min:2',
            'bulk_pricing.*.price_per_unit' => 'required_with:bulk_pricing|numeric|min:0',
            'gallery_images' => 'nullable|array|max:5',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
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
            'name.required' => 'Le nom du produit est obligatoire.',
            'description.required' => 'La description du produit est obligatoire.',
            'category_id.required' => 'La catégorie est obligatoire.',
            'category_id.exists' => 'La catégorie sélectionnée n\'existe pas.',
            'price.required' => 'Le prix est obligatoire.',
            'price.numeric' => 'Le prix doit être un nombre.',
            'price.min' => 'Le prix ne peut pas être négatif.',
            'quantity.required' => 'La quantité est obligatoire.',
            'quantity.integer' => 'La quantité doit être un nombre entier.',
            'quantity.min' => 'La quantité ne peut pas être négative.',
            'unit_symbol.required' => 'L\'unité de mesure est obligatoire.',
            'unit_symbol.in' => 'L\'unité de mesure sélectionnée n\'est pas valide.',
            'main_image.image' => 'Le fichier doit être une image.',
            'main_image.mimes' => 'L\'image doit être au format JPEG, PNG, JPG ou GIF.',
            'main_image.max' => 'L\'image ne doit pas dépasser 2MB.',
            'critical_stock_threshold.required' => 'Le seuil critique de stock est obligatoire.',
            'critical_stock_threshold.integer' => 'Le seuil critique doit être un nombre entier.',
            'critical_stock_threshold.min' => 'Le seuil critique doit être au minimum de 1.',
            'bulk_pricing.array' => 'Les prix en vrac doivent être un tableau.',
            'bulk_pricing.*.min_quantity.required_with' => 'La quantité minimum est obligatoire pour les prix en vrac.',
            'bulk_pricing.*.min_quantity.integer' => 'La quantité minimum doit être un nombre entier.',
            'bulk_pricing.*.min_quantity.min' => 'La quantité minimum doit être au moins de 2.',
            'bulk_pricing.*.price_per_unit.required_with' => 'Le prix unitaire est obligatoire pour les prix en vrac.',
            'bulk_pricing.*.price_per_unit.numeric' => 'Le prix unitaire doit être un nombre.',
            'bulk_pricing.*.price_per_unit.min' => 'Le prix unitaire ne peut pas être négatif.',
            'gallery_images.array' => 'Les images de galerie doivent être un tableau.',
            'gallery_images.max' => 'Vous ne pouvez pas télécharger plus de 5 images de galerie.',
            'gallery_images.*.image' => 'Chaque fichier de galerie doit être une image.',
            'gallery_images.*.mimes' => 'Chaque image de galerie doit être au format JPEG, PNG, JPG ou GIF.',
            'gallery_images.*.max' => 'Chaque image de galerie ne doit pas dépasser 2MB.',
        ];
    }
}
