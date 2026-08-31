<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InquiryController extends Controller
{
    public function index(Request $request)
    {
        $query = Inquiry::published();

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('question', 'like', "%{$search}%")
                  ->orWhere('answer', 'like', "%{$search}%");
            });
        }

        $inquiries = $query->latest()->paginate(10);
        $categories = Category::where('module', 'inquiry')->active()->get();

        return view('pages.inquiries', compact('inquiries', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|max:150',
            'country'  => 'nullable|string|max:100',
            'question' => 'required|string|min:15',
        ]);

        $trackingCode = 'INQ-' . date('Y') . '-' . strtoupper(Str::random(6));

        Inquiry::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'country'       => $request->country,
            'tracking_code' => $trackingCode,
            'question'      => $request->question,
            'status'        => 'new',
            'is_published'  => false,
        ]);

        return redirect()->back()->with('success', "تم إرسال استفساركم بنجاح! رقم المتابعة الخاص بكم هو: {$trackingCode}");
    }

    public function track(Request $request)
    {
        $code = $request->get('code');
        $inquiry = null;

        if (!empty($code)) {
            $inquiry = Inquiry::where('tracking_code', trim($code))->first();
        }

        return view('pages.inquiry_track', compact('inquiry', 'code'));
    }
}
