<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\TranscriptFormatter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ToolController extends Controller
{
    /**
     * Display the Vacum (المخمة) content cleaning & formatting tool.
     */
    public function vacum(): View
    {
        return view('admin.tools.vacum');
    }

    /**
     * Process raw or Word-formatted text/HTML through TranscriptFormatter.
     */
    public function processVacum(Request $request): JsonResponse
    {
        $content = $request->input('content', '');

        if (empty(trim($content))) {
            return response()->json([
                'success' => false,
                'message' => 'يرجى تقديم محتوى لتنظيفه وتنسيقه.',
            ], 422);
        }

        $result = TranscriptFormatter::process($content);

        return response()->json([
            'success' => true,
            'html' => $result['html'],
            'clean_text' => $result['clean_text'],
            'stats' => $result['stats'],
        ]);
    }
}
