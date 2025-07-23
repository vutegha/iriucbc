<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:job_applications,email,' . $this->route('job') . ',job_offer_id',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'birth_date' => 'nullable|date|before:today|after:' . now()->subYears(100)->toDateString(),
            'gender' => 'nullable|in:M,F,Autre',
            'nationality' => 'nullable|string|max:100',
            'education' => 'nullable|string|max:2000',
            'experience' => 'nullable|string|max:2000',
            'skills' => 'nullable|string|max:1000',
            'motivation_letter' => 'required|string|min:50|max:3000',
            'cv_file' => 'required|file|mimes:pdf,doc,docx|max:5120', // 5MB
            'portfolio_file' => 'nullable|file|mimes:pdf,doc,docx,zip|max:10240', // 10MB
            'criteria_responses' => 'nullable|array',
            'criteria_responses.*' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'Le prénom est obligatoire.',
            'last_name.required' => 'Le nom est obligatoire.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email doit être valide.',
            'email.unique' => 'Vous avez déjà postulé pour cette offre d\'emploi.',
            'birth_date.before' => 'La date de naissance doit être antérieure à aujourd\'hui.',
            'birth_date.after' => 'La date de naissance n\'est pas valide.',
            'motivation_letter.required' => 'La lettre de motivation est obligatoire.',
            'motivation_letter.min' => 'La lettre de motivation doit contenir au moins 50 caractères.',
            'motivation_letter.max' => 'La lettre de motivation ne peut pas dépasser 3000 caractères.',
            'cv_file.required' => 'Le CV est obligatoire.',
            'cv_file.mimes' => 'Le CV doit être au format PDF, DOC ou DOCX.',
            'cv_file.max' => 'Le CV ne peut pas dépasser 5MB.',
            'portfolio_file.mimes' => 'Le portfolio doit être au format PDF, DOC, DOCX ou ZIP.',
            'portfolio_file.max' => 'Le portfolio ne peut pas dépasser 10MB.',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Nettoyer les données avant validation
        $this->merge([
            'email' => strtolower(trim($this->email)),
            'first_name' => ucfirst(strtolower(trim($this->first_name))),
            'last_name' => strtoupper(trim($this->last_name)),
        ]);
    }
}
