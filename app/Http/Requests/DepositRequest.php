<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Setting;

class DepositRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // Se vier como string "10,50" ou "10.50", tenta normalizar.
        // Se você trabalha em CENTAVOS, comente isso e envie sempre inteiro do front.
        if ($this->has('amount')) {
            $amount = $this->input('amount');

            if (is_string($amount)) {
                $amount = str_replace(['.', ','], ['', '.'], $amount);
            }

            $this->merge([
                'amount' => is_numeric($amount) ? (float) $amount : $amount,
            ]);
        }
    }

    public function rules(): array
    {
        $settings = Setting::query()->first();

        // Ajuste conforme seus campos reais no banco:
        $min = (float) ($settings?->min_deposit ?? 0);
        $max = (float) ($settings?->maximum_deposit ?? 0); // se existir

        $rules = [
            'amount' => ['required', 'numeric', 'gt:0', 'min:' . $min],
        ];

        // se você tiver máximo configurado e for > 0, aplica
        if ($max > 0) {
            $rules['amount'][] = 'max:' . $max;
        }

        // Se seu gateway espera CENTAVOS (inteiro):
        // Troque as regras acima por:
        // 'amount' => ['required', 'integer', 'gt:0', 'min:' . (int)$min],
        // e garanta que min/max também estão em centavos.
        return $rules;
    }

    public function messages(): array
    {
        $settings = Setting::query()->first();
        $min = (float) ($settings?->min_deposit ?? 0);
        $max = (float) ($settings?->maximum_deposit ?? 0);

        $messages = [
            'amount.required' => 'Informe o valor do depósito.',
            'amount.numeric'   => 'O valor do depósito deve ser um número válido.',
            'amount.integer'   => 'O valor do depósito deve ser um número inteiro.',
            'amount.gt'        => 'O valor do depósito deve ser maior que zero.',
            'amount.min'       => 'O valor mínimo para depósito é :min.',
        ];

        if ($max > 0) {
            $messages['amount.max'] = 'O valor máximo para depósito é :max.';
        }

        return $messages;
    }

    public function attributes(): array
    {
        return [
            'amount' => 'valor do depósito',
        ];
    }
}
