<?php

namespace App\Http\Controllers;

use App\Models\VumeProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VumeProductController extends Controller
{
    public function index()
    {
        return VumeProduct::orderBy('id')->get();
    }

    public function update(Request $request, VumeProduct $vume)
    {
        $data = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'top' => 'nullable|string|max:255',
            'middle' => 'nullable|string|max:255',
            'base' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:30',
            'image' => 'nullable|image|max:4096',
            'remove_image' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($vume->image) {
                Storage::disk('public')->delete($vume->image);
            }
            $data['image'] = $request->file('image')->store('vume', 'public');
        } elseif ($request->boolean('remove_image')) {
            if ($vume->image) {
                Storage::disk('public')->delete($vume->image);
            }
            $data['image'] = null;
        } else {
            unset($data['image']);
        }
        unset($data['remove_image']);

        $vume->update($data);

        return $vume->fresh();
    }
}
