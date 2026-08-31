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
        $query = Inquiry::with(['category', 'responder'])->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $inquiries = $query->paginate(15);
        return view('admin.inquiries.index', compact('inquiries', 'status'));
    }

    public function edit(Inquiry $inquiry)
    {
        $categories = Category::where('module', 'inquiry')->get();
        return view('admin.inquiries.edit', compact('inquiry', 'categories'));
    }

    public function update(Request $request, Inquiry $inquiry)
    {
        $request->validate([
            'answer' => 'nullable|string',
            'status' => 'required|in:new,in_review,answered',
        ]);

        $inquiry->update([
            'category_id'  => $request->category_id,
            'answer'       => $request->answer,
            'status'       => $request->status,
            'is_published' => $request->boolean('is_published'),
            'answered_by'  => Auth::id(),
            'answered_at'  => $request->filled('answer') ? now() : null,
        ]);

        return redirect()->route('admin.inquiries.index')->with('success', 'تم حفظ الرد وتحديث حالة الاستفسار بنجاح!');
    }

    public function destroy(Inquiry $inquiry)
    {
        $inquiry->delete();
        return redirect()->route('admin.inquiries.index')->with('success', 'تم حذف الاستفسار بنجاح!');
    }
}
