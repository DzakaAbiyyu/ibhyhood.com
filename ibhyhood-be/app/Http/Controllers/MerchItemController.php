<?php

namespace App\Http\Controllers;

use App\Models\MerchItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MerchItemController extends Controller
{
    public function index()
    {
        return MerchItem::latest()->get();
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('merch', 'public');
        }

        return response()->json(MerchItem::create($data), 201);
    }

    public function update(Request $request, MerchItem $merch)
    {
        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            if ($merch->image) {
                Storage::disk('public')->delete($merch->image);
            }
            $data['image'] = $request->file('image')->store('merch', 'public');
        } else {
            unset($data['image']);
        }

        $merch->update($data);

        return $merch->fresh();
    }

    public function destroy(MerchItem $merch)
    {
        if ($merch->image) {
            Storage::disk('public')->delete($merch->image);
        }
        $merch->delete();

        return response()->noContent();
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'theme' => 'nullable|string|max:100',
            'price' => 'required|integer|min:0',
            'badge' => 'nullable|string|max:30',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:3072',
        ]);
    }
}
