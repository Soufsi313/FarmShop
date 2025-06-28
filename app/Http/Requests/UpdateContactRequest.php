<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Contact;

class UpdateContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Seuls les admins peuvent modifier les contacts
        return auth()->check() && 
               (auth()->user()->hasPermissionTo('manage contacts') || 
                auth()->user()->isAdmin() || 
                auth()->user()->isAdmin());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'status' => [
                'sometimes',
                'required',
                'string',
                Rule::in(array_keys(Contact::getStatuses()))
            ],
            'assigned_to' => [
                'sometimes',
                'nullable',
                'integer',
                'exists:users,id'
            ],
            'admin_notes' => [
                'sometimes',
                'nullable',
                'string',
                'max:1000'
            ],
            'priority' => [
                'sometimes',
                'nullable',
                'in:low,normal,high,urgent'
            ]
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
            'status.required' => 'Le statut est obligatoire.',
            'status.in' => 'Le statut sélectionné n\'est pas valide.',
            'assigned_to.exists' => 'L\'utilisateur assigné n\'existe pas.',
            'admin_notes.max' => 'Les notes admin ne peuvent pas dépasser 1000 caractères.',
            'priority.in' => 'La priorité sélectionnée n\'est pas valide.'
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
            'status' => 'statut',
            'assigned_to' => 'assigné à',
            'admin_notes' => 'notes administrateur',
            'priority' => 'priorité'
        ];
    }
}
