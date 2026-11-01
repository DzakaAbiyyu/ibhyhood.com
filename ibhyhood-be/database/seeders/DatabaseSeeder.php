<?php

namespace Database\Seeders;

use App\Models\Gig;
use App\Models\MerchFilter;
use App\Models\MerchItem;
use App\Models\Promo;
use App\Models\Schedule;
use App\Models\User;
use App\Models\VumeProduct;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin: ganti email/password lewat .env (ADMIN_EMAIL, ADMIN_PASSWORD)
        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@ibhyhood.com')],
            [
                'name' => 'Admin Ibhyhood',
                'password' => Hash::make(env('ADMIN_PASSWORD', 'ganti-password-ini')),
            ]
        );

        // Jadwal
        $schedules = [
            ['31', 'Mobile Legends'],
            ['1', 'REANIMAL'],
            ['2', 'Mobile Legends'],
            ['3', 'A SPACE FOR THE UNBOUND'],
            ['4', 'Mobile Legends'],
            ['5', 'Mobile Legends'],
        ];
        foreach ($schedules as $i => [$day, $title]) {
            Schedule::create([
                'day' => $day,
                'title' => $title,
                'time' => '[21.00/21.30 WIB]',
                'type' => 'LIVE STREAM',
                'sort_order' => $i,
            ]);
        }

        // Filter merch
        foreach (['Mousepads', 'Clothing', 'Posters', 'Pins', 'Acrylics'] as $name) {
            MerchFilter::create(['group' => 'type', 'name' => $name]);
        }
        foreach (['Cyberpunk', 'Cute', 'Idol'] as $name) {
            MerchFilter::create(['group' => 'theme', 'name' => $name]);
        }

        // Promo
        Promo::create([
            'title' => 'MIDNIGHT STARLIGHT SIGNATURE DROP',
            'subtitle' => 'STARLIGHT MOUSEPAD - $30',
            'ends_at' => now()->addDays(7),
        ]);

        // Merch
        $merch = [
            ['Kira Signature Mousepad', 'Mousepads', 'Cyberpunk', 250000, 'NEW'],
            ['Midnight Neon Jacket', 'Clothing', 'Cyberpunk', 650000, 'FEATURED'],
            ['Kira Art Print Poster', 'Posters', 'Idol', 120000, null],
            ['Midnight Neon Hoodie', 'Clothing', 'Cyberpunk', 450000, null],
            ['Chibi Pin Set (3 Pcs)', 'Pins', 'Cute', 95000, null],
            ['Acrylic Stand Figure', 'Acrylics', 'Cute', 180000, null],
        ];
        foreach ($merch as [$title, $type, $theme, $price, $badge]) {
            MerchItem::create(compact('title', 'type', 'theme', 'price', 'badge'));
        }

        // Vume
        VumeProduct::create([
            'slug' => 'voici',
            'title' => 'VOICI',
            'subtitle' => 'Eau de Parfum',
            'top' => 'Orange Mandarin',
            'middle' => 'Honey, Orange Blossom, Jasmine',
            'base' => 'Caramel, Patchouli',
            'size' => '30ml',
        ]);
        VumeProduct::create([
            'slug' => 'phantovi',
            'title' => 'PHANTOVI',
            'subtitle' => 'Wood & Candy',
            'top' => 'Saffron, Jasmine',
            'middle' => 'Ambergris, Sugar',
            'base' => 'Fir Resin, Amber Wood',
            'size' => '30ml',
        ]);

        // Gigs
        $gigs = [
            ['Anime Illustrations', 100000, 'Full colored anime illustration custom tailored to your request.', [
                'basic' => [100000, 'Bust-up / Lineart only, 1x Revision'],
                'standart' => [250000, 'Half Body + Color, 2x Revision, HD File'],
                'premium' => [500000, 'Full Body + Detailed BG + Source File, Unlimited Revision'],
            ]],
            ['Custom Emote & Badges or Sticker Youtube/Twitch', 150000, 'Cute custom emotes for your channel stream.', [
                'basic' => [150000, '3 Custom Emotes'],
                'standart' => [300000, '6 Custom Emotes'],
                'premium' => [500000, '10 Custom Emotes + Badges'],
            ]],
            ['Custom Mascot Logo', 200000, null, []],
            ['Custom Thumbnail Youtube or Twitch', 75000, null, []],
            ['Custom Draw Chibi Art in Cute Anime Style', 80000, null, []],
            ['Custom T Shirt & Clothing Design', 120000, null, []],
            ['Video Editing YT Short, Instagram, Tiktok, Meme, Gaming, etc', 100000, null, []],
            ['Website Design & Development', 500000, null, []],
        ];
        foreach ($gigs as [$title, $start, $desc, $packages]) {
            $gig = Gig::create([
                'title' => $title,
                'starting_price' => $start,
                'description' => $desc,
            ]);
            foreach ($packages as $tier => [$price, $d]) {
                $gig->packages()->create(['tier' => $tier, 'price' => $price, 'description' => $d]);
            }
        }
    }
}
