<?php

namespace App\Http\Controllers;

use App\Models\MerchFilter;
use Illuminate\Http\Request;

class MerchFilterController extends Controller
{
    // Bentuk respons sama seperti MERCH_FILTERS di frontend: { type: [...], theme: [...] }
    public function index()
    {
        $all = MerchFilter::orderBy('name')->get();

        return [
            'type' => $all->where('group', 'type')->pluck('name')->values(),
            'theme' => $all->where('group', 'theme')->pluck('name')->values(),
        ];
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'group' => 'required|in:type,theme',
            'name' => 'required|string|max:100',
        ]);

        MerchFilter::firstOrCreate($data);

        return response()->json($this->index(), 201);
    }

    public function destroy(Request $request)
    {
        $data = $request->validate([
            'group' => 'required|in:type,theme',
            'name' => 'required|string|max:100',
        ]);

        MerchFilter::where($data)->delete();

        return $this->index();
    }
}
