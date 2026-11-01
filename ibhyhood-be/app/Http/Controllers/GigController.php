<?php

namespace App\Http\Controllers;

use App\Models\Gig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GigController extends Controller
{
    private const TIERS = ['basic', 'standart', 'premium'];

    public function index()
    {
        return Gig::with('packages')->orderBy('id')->get();
    }

    public function show(Gig $gig)
    {
        return $gig->load('packages');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        $gig = DB::transaction(function () use ($request, $data) {
            $gig = Gig::create($this->gigFields($request, $data));
            $this->syncPackages($gig, $data['packages'] ?? []);

            return $gig;
        });

        return response()->json($gig->load('packages'), 201);
    }

    public function update(Request $request, Gig $gig)
    {
        $data = $this->validated($request);

        DB::transaction(function () use ($request, $data, $gig) {
            $gig->update($this->gigFields($request, $data, $gig));
            $this->syncPackages($gig, $data['packages'] ?? []);
        });

        return $gig->fresh()->load('packages');
    }

    public function destroy(Gig $gig)
    {
        foreach (array_filter([$gig->cover_image, ...($gig->gallery ?? [])]) as $path) {
            Storage::disk('public')->delete($path);
        }
        $gig->delete();

        return response()->noContent();
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'starting_price' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|max:4096',
            'gallery' => 'nullable|array|max:8',
            'gallery.*' => 'image|max:4096',
            'packages' => 'nullable|array',
            'packages.*.price' => 'required_with:packages|integer|min:0',
            'packages.*.description' => 'required_with:packages|string|max:255',
        ]);
    }

    private function gigFields(Request $request, array $data, ?Gig $gig = null): array
    {
        $fields = [
            'title' => $data['title'],
            'starting_price' => $data['starting_price'],
            'description' => $data['description'] ?? null,
        ];

        if ($request->hasFile('cover_image')) {
            if ($gig?->cover_image) {
                Storage::disk('public')->delete($gig->cover_image);
            }
            $fields['cover_image'] = $request->file('cover_image')->store('gigs', 'public');
        }

        if ($request->hasFile('gallery')) {
            foreach ($gig?->gallery ?? [] as $old) {
                Storage::disk('public')->delete($old);
            }
            $fields['gallery'] = collect($request->file('gallery'))
                ->map(fn ($f) => $f->store('gigs', 'public'))
                ->all();
        }

        return $fields;
    }

    private function syncPackages(Gig $gig, array $packages): void
    {
        foreach (self::TIERS as $tier) {
            if (! isset($packages[$tier])) {
                continue;
            }
            $gig->packages()->updateOrCreate(
                ['tier' => $tier],
                [
                    'price' => $packages[$tier]['price'],
                    'description' => $packages[$tier]['description'],
                ]
            );
        }
    }
}
