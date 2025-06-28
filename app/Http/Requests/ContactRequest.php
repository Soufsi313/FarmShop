<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Contact;

class ContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Accessible à tous les visiteurs
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'regex:/^[a-zA-ZÀ-ÿ\s\-\'\.]+$/' // Lettres, espaces, tirets, apostrophes, points
            ],
            'email' => [
                'required',
                'email:rfc,dns',
                'max:255'
            ],
            'phone' => [
                'nullable',
                'string',
                'regex:/^(?:(?:\+|00)33[\s\-]?(?:\(0\)[\s\-]?)?|0)[1-9](?:[\s\-]?\d{2}){4}$/' // Format français
            ],
            'subject' => [
                'required',
                'string',
                'min:5',
                'max:200'
            ],
            'reason' => [
                'required',
                'string',
                Rule::in(array_keys(Contact::getReasons()))
            ],
            'message' => [
                'required',
                'string',
                'min:10',
                'max:2000'
            ],
            'attachments' => [
                'nullable',
                'array',
                'max:3' // Maximum 3 fichiers
            ],
            'attachments.*' => [
                'file',
                'max:5120', // 5MB max par fichier
                'mimes:pdf,doc,docx,jpg,jpeg,png,gif'
            ],
            // Protection anti-spam basique
            'honeypot' => 'nullable|max:0', // Champ caché qui doit rester vide
            'submit_time' => 'nullable|integer|min:3' // Temps minimum pour remplir le formulaire (3 secondes)
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
            'name.required' => 'Le nom est obligatoire.',
            'name.min' => 'Le nom doit contenir au moins 2 caractères.',
            'name.max' => 'Le nom ne peut pas dépasser 100 caractères.',
            'name.regex' => 'Le nom ne peut contenir que des lettres, espaces, tirets et apostrophes.',
            
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email doit être valide.',
            'email.max' => 'L\'adresse email ne peut pas dépasser 255 caractères.',
            
            'phone.regex' => 'Le numéro de téléphone doit être au format français valide.',
            
            'subject.required' => 'L\'objet est obligatoire.',
            'subject.min' => 'L\'objet doit contenir au moins 5 caractères.',
            'subject.max' => 'L\'objet ne peut pas dépasser 200 caractères.',
            
            'reason.required' => 'La raison de votre demande est obligatoire.',
            'reason.in' => 'La raison sélectionnée n\'est pas valide.',
            
            'message.required' => 'Le message est obligatoire.',
            'message.min' => 'Le message doit contenir au moins 10 caractères.',
            'message.max' => 'Le message ne peut pas dépasser 2000 caractères.',
            
            'attachments.max' => 'Vous ne pouvez joindre que 3 fichiers maximum.',
            'attachments.*.file' => 'Le fichier joint doit être valide.',
            'attachments.*.max' => 'Chaque fichier ne peut pas dépasser 5MB.',
            'attachments.*.mimes' => 'Les fichiers autorisés sont : PDF, DOC, DOCX, JPG, JPEG, PNG, GIF.',
            
            'honeypot.max' => 'Erreur de sécurité détectée.',
            'submit_time.min' => 'Veuillez prendre le temps de remplir correctement le formulaire.'
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
            'name' => 'nom',
            'email' => 'adresse email',
            'phone' => 'téléphone',
            'subject' => 'objet',
            'reason' => 'raison',
            'message' => 'message',
            'attachments' => 'fichiers joints'
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // Nettoyer et normaliser les données
        $this->merge([
            'name' => trim($this->name),
            'email' => strtolower(trim($this->email)),
            'phone' => $this->phone ? preg_replace('/[\s\-\(\)]/', '', $this->phone) : null,
            'subject' => trim($this->subject),
            'message' => trim($this->message)
        ]);
    }
}
