<?php

namespace App\Http\Requests;

use App\Rules\IsbnRule;
use Illuminate\Foundation\Http\FormRequest;

class BestSellersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'author' => 'sometimes|string',
            'isbn' => 'sometimes|array',
            'isbn.*' => ['string', new IsbnRule],
            'title' => 'sometimes|string',
            'offset' => 'sometimes|integer|multiple_of:20|min:0',
        ];
    }
}
