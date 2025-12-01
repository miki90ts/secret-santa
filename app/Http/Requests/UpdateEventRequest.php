<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('event'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $event = $this->route('event');

        return [
            'year' => 'required|integer|unique:events,year,' . $event->id,
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'registration_start' => 'nullable|date',
            'registration_end' => 'nullable|date|after:registration_start',
            'exchange_date' => 'nullable|date',
            'is_active' => 'boolean',
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
            'year.required' => 'Godina je obavezna.',
            'year.integer' => 'Godina mora biti broj.',
            'year.unique' => 'Event za ovu godinu već postoji.',
            'name.max' => 'Naziv ne može biti duži od 255 karaktera.',
            'registration_end.after' => 'Datum kraja prijava mora biti nakon datuma početka prijava.',
        ];
    }
}
