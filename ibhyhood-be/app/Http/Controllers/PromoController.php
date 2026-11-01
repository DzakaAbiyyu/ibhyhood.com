<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PromoController extends Controller
{
    public function show()
    {
        return Promo::first() ?? (object) [];
    }

    // Promo hanya 1 baris
    public function update(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'ends_at' => 'nullable|date',
            'image' => 'nullable|image|max:4096',
        ]);

        $promo = Promo::first() ?? new Promo();

        if ($request->hasFile('image')) {
            if ($promo->image) {
                Storage::disk('public')->delete($promo->image);
            }
            $data['image'] = $request->file('image')->store('promo', 'public');
        } else {
            unset($data['image']);
        }

        $promo->fill($data)->save();

        return $promo->fresh();
    }
}
