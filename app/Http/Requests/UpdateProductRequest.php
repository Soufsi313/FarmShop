<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
            'price' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'unit_symbol' => ['required', Rule::in([Product::UNIT_KG, Product::UNIT_PIECE, Product::UNIT_LITER])],
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'critical_stock_threshold' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_rentable' => 'boolean',
            'is_returnable' => 'boolean',
            'rental_price_per_day' => 'nullable|numeric|min:0|required_if:is_rentable,1',
            'deposit_amount' => 'nullable|numeric|min:0',
            'min_rental_days' => 'nullable|integer|min:1',
            'max_rental_days' => 'nullable|integer|min:1',
            'rental_conditions' => 'nullable|string|max:1000',
            'bulk_pricing' => 'nullable|array',
            'bulk_pricing.*.min_quantity' => 'required_with:bulk_pricing|integer|min:2',
            'bulk_pricing.*.price_per_unit' => 'required_with:bulk_pricing|numeric|min:0',
            'gallery_images' => 'nullable|array|max:5',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_gallery_images' => 'nullable|array',
            'remove_gallery_images.*' => 'exists:product_images,id',
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
            'rental_price_per_day.required_if' => 'Le prix de location par jour est obligatoire pour les produits en location.',
            'rental_price_per_day.numeric' => 'Le prix de location doit être un nombre.',
            'rental_price_per_day.min' => 'Le prix de location ne peut pas être négatif.',
            'deposit_amount.numeric' => 'La caution doit être un nombre.',
            'deposit_amount.min' => 'La caution ne peut pas être négative.',
            'min_rental_days.integer' => 'Le nombre minimum de jours doit être un nombre entier.',
            'min_rental_days.min' => 'Le nombre minimum de jours doit être au moins de 1.',
            'max_rental_days.integer' => 'Le nombre maximum de jours doit être un nombre entier.',
            'max_rental_days.min' => 'Le nombre maximum de jours doit être au moins de 1.',
            'rental_conditions.string' => 'Les conditions de location doivent être du texte.',
            'rental_conditions.max' => 'Les conditions de location ne peuvent pas dépasser 1000 caractères.',
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
            'remove_gallery_images.array' => 'Les images à supprimer doivent être un tableau.',
            'remove_gallery_images.*.exists' => 'Une des images à supprimer n\'existe pas.',
        ];
    }
}
