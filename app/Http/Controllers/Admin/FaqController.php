<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FaqItem;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $items = FaqItem::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('admin.faq.index', compact('items'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'question' => 'nullable|string|max:255',
            'answer' => 'nullable|string',
            'question_id' => 'nullable|string|max:255',
            'answer_id' => 'nullable|string',
            'question_en' => 'nullable|string|max:255',
            'answer_en' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $data['question'] = $data['question_id'] ?? $data['question_en'] ?? $data['question'] ?? 'FAQ Question';
        $data['answer'] = $data['answer_id'] ?? $data['answer_en'] ?? $data['answer'] ?? 'FAQ Answer';
        $data['is_active'] = (bool) ($data['is_active'] ?? false);
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        FaqItem::create($data);

        return redirect()->back()->with('success', 'FAQ item created successfully.');
    }

    public function update(Request $request, FaqItem $faqItem)
    {
        $data = $request->validate([
            'question' => 'nullable|string|max:255',
            'answer' => 'nullable|string',
            'question_id' => 'nullable|string|max:255',
            'answer_id' => 'nullable|string',
            'question_en' => 'nullable|string|max:255',
            'answer_en' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $data['question'] = $data['question_id'] ?? $data['question_en'] ?? $data['question'] ?? $faqItem->question;
        $data['answer'] = $data['answer_id'] ?? $data['answer_en'] ?? $data['answer'] ?? $faqItem->answer;
        $data['is_active'] = (bool) ($data['is_active'] ?? false);
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        $faqItem->update($data);

        return redirect()->back()->with('success', 'FAQ item updated successfully.');
    }

    public function destroy(FaqItem $faqItem)
    {
        $faqItem->delete();

        return redirect()->back()->with('success', 'FAQ item deleted.');
    }
}
