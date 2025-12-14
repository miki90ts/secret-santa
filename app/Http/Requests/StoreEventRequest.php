<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Event::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'organization_id' => ['required', 'exists:organizations,id'],
            'year' => [
                'required',
                'integer',
                Rule::unique('events')->where(function ($query) {
                    return $query->where('organization_id', $this->organization_id);
                }),
            ],
            'name' => ['nullable', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'unique:events,slug'],
            'description' => ['nullable', 'string'],
            'registration_start' => ['nullable', 'date'],
            'registration_end' => ['nullable', 'date', 'after:registration_start'],
            'exchange_date' => ['nullable', 'date'],
            'is_active' => ['boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'organization_id.required' => 'Organizacija je obavezna.',
            'organization_id.exists' => 'Izabrana organizacija ne postoji.',
            'year.required' => 'Godina je obavezna.',
            'year.integer' => 'Godina mora biti broj.',
            'year.unique' => 'Event za ovu godinu već postoji u ovoj organizaciji.',
            'slug.unique' => 'Ovaj slug već postoji.',
            'name.max' => 'Naziv ne može biti duži od 255 karaktera.',
            'registration_end.after' => 'Datum kraja prijava mora biti nakon datuma početka prijava.',
        ];
    }
}
