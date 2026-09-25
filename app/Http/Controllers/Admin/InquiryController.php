<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InquiryController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $search = Inquiry::normalizeDigits(trim($request->get('search', '')));
        $query = Inquiry::with(['category', 'responder'])->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('tracking_code', 'like', "%{$search}%")
                  ->orWhere('question', 'like', "%{$search}%")
                  ->orWhere('answer', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $inquiries = $query->paginate(15)->withQueryString();
        return view('admin.inquiries.index', compact('inquiries', 'status', 'search'));
    }

    public function create()
    {
        $categories = Category::where('module', 'inquiry')->get();
        return view('admin.inquiries.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:150',
            'email'           => 'nullable|email|max:150',
            'country'         => 'nullable|string|max:100',
            'question'        => 'required|string',
            'answer'          => 'nullable|string',
            'status'          => 'required|in:new,in_review,answered',
            'category_id'     => 'nullable|exists:categories,id',
            'responder_title' => 'nullable|string|max:100',
        ]);

        $trackingCode = 'INQ-' . date('Y') . '-' . strtoupper(\Illuminate\Support\Str::random(6));

        $inquiry = Inquiry::create([
            'tracking_code'   => $trackingCode,
            'name'            => $request->name,
            'email'           => $request->email ?: 'inquiry@almoneer.org',
            'country'         => $request->country,
            'question'        => $request->question,
            'answer'          => $request->answer,
            'category_id'     => $request->category_id,
            'responder_title' => $request->responder_title ?? 'إدارة الموقع',
            'status'          => $request->status,
            'is_published'    => $request->boolean('is_published'),
            'answered_by'     => Auth::id(),
            'answered_at'     => $request->filled('answer') ? now() : null,
        ]);

        return redirect()->route('admin.inquiries.index')->with('success', "تمت إضافة السؤال والجواب بنجاح! رقم التتبع: {$trackingCode}");
    }

    public function edit(Inquiry $inquiry)
    {
        $categories = Category::where('module', 'inquiry')->get();
        return view('admin.inquiries.edit', compact('inquiry', 'categories'));
    }

    public function update(Request $request, Inquiry $inquiry)
    {
        $request->validate([
            'question'        => 'required|string',
            'answer'          => 'nullable|string',
            'status'          => 'required|in:new,in_review,answered',
            'category_id'     => 'nullable|exists:categories,id',
            'responder_title' => 'nullable|string|max:100',
        ]);

        $inquiry->update([
            'question'        => $request->question,
            'category_id'     => $request->category_id,
            'responder_title' => $request->responder_title ?? 'إدارة الموقع',
            'answer'          => $request->answer,
            'status'          => $request->status,
            'is_published'    => $request->boolean('is_published'),
            'answered_by'     => Auth::id(),
            'answered_at'     => $request->filled('answer') ? ($inquiry->answered_at ?? now()) : null,
        ]);

        return redirect()->route('admin.inquiries.index')->with('success', 'تم حفظ التعديلات والرد بنجاح!');
    }

    public function destroy(Inquiry $inquiry)
    {
        $inquiry->delete();
        return redirect()->route('admin.inquiries.index')->with('success', 'تم حذف الاستفسار بنجاح!');
    }
}
