<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function index()
    {
        return Schedule::orderBy('sort_order')->get(['id', 'day', 'title', 'time', 'type']);
    }

    // Admin mengirim seluruh daftar jadwal sekaligus (urutan array = urutan tampil)
    public function bulkUpdate(Request $request)
    {
        $data = $request->validate([
            'schedules' => 'required|array|max:31',
            'schedules.*.day' => 'required|string|max:10',
            'schedules.*.title' => 'required|string|max:255',
            'schedules.*.time' => 'nullable|string|max:100',
            'schedules.*.type' => 'nullable|string|max:100',
        ]);

        DB::transaction(function () use ($data) {
            Schedule::query()->delete();
            foreach ($data['schedules'] as $i => $row) {
                Schedule::create([
                    'day' => $row['day'],
                    'title' => $row['title'],
                    'time' => $row['time'] ?? null,
                    'type' => $row['type'] ?? 'LIVE STREAM',
                    'sort_order' => $i,
                ]);
            }
        });

        return $this->index();
    }
}
