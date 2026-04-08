<?php

namespace App\Http\Requests;

use App\Models\KnowledgeEntry;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateKnowledgeEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique(KnowledgeEntry::class)->ignore($this->route('knowledge')),
            ],
            'system' => ['required', 'string', 'max:50', Rule::in(['elasticsearch', 'kibana', 'logstash', 'zabbix', 'docker'])],
            'category' => ['required', 'string', 'max:50', Rule::in(KnowledgeEntry::validCategories())],
            'title' => ['required', 'string', 'max:255'],
            'status' => ['required', Rule::in([
                KnowledgeEntry::STATUS_DRAFT,
                KnowledgeEntry::STATUS_REVIEWED,
                KnowledgeEntry::STATUS_PUBLISHED,
            ])],
            'version' => ['required', 'string', 'max:20'],
            'last_verified_at' => ['nullable', 'date'],
            'description' => ['required', 'string'],
            'steps' => ['required', 'array'],
            'steps.*' => ['required', 'string'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['nullable', 'string'],
        ];
    }
}
