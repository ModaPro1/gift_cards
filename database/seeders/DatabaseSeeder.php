<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Card;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(50)->create();
        // Create First User
        \App\Models\User::create([
          'name' => 'Mahmoud Ahmed',
          'email' => 'moda@x',
          'password' => Hash::make('12345678')
        ]);

        // Create First Admin
        \App\Models\Admin::create([
          'name' => 'Mahmoud Ahmed',
          'email' => 'admin@gg.com',
          'password' => Hash::make('12345678')
        ]);

        // Create Categories
        $catsData = [
          [
            'name' => 'Pubg',
            'url' => '/pubg',
            'image' => 'pubg.webp'
          ],
          [
            'name' => 'Roblox',
            'url' => '/roblox',
            'image' => 'roblox.webp'
          ],
          [
            'name' => 'Razer',
            'url' => '/razer',
            'image' => 'razer.webp'
          ],
          [
            'name' => 'Google Play',
            'url' => '/googleplay',
            'image' => 'google-play.webp'
          ],
          [
            'name' => 'Steam',
            'url' => '/steam',
            'image' => 'steam.webp'
          ],
          [
            'name' => 'Amazon',
            'url' => '/amazon',
            'image' => 'amazon.webp'
          ],
        ];
        foreach($catsData as $cat) {
          Category::create([
            'name' => $cat['name'],
            'image' => $cat['image'],
            'url' => $cat['url']
          ]);
        }

        // Create Cards
        $cardsData = [
          [
            'name' => 'Google Play',
            'type' => 'googleplay',
            'image' => 'google-play.webp',
            'price' => 5
          ],
          [
            'name' => 'Google Play',
            'type' => 'googleplay',
            'image' => 'google-play.webp',
            'price' => 10
          ],
          [
            'name' => 'Google Play',
            'type' => 'googleplay',
            'image' => 'google-play.webp',
            'price' => 15
          ],
          [
            'name' => 'Google Play',
            'type' => 'googleplay',
            'image' => 'google-play.webp',
            'price' => 20
          ],
          [
            'name' => 'Pubg Mobile',
            'type' => 'pubg',
            'image' => 'pubg.webp',
            'price' => 5
          ],
          [
            'name' => 'Pubg Mobile',
            'type' => 'pubg',
            'image' => 'pubg.webp',
            'price' => 10
          ],
          [
            'name' => 'Pubg Mobile',
            'type' => 'pubg',
            'image' => 'pubg.webp',
            'price' => 15
          ],
          [
            'name' => 'Pubg Mobile',
            'type' => 'pubg',
            'image' => 'pubg.webp',
            'price' => 20
          ],
          [
            'name' => 'Roblox',
            'type' => 'roblox',
            'image' => 'roblox.webp',
            'price' => 1
          ],
          [
            'name' => 'Roblox',
            'type' => 'roblox',
            'image' => 'roblox.webp',
            'price' => 5
          ],
          [
            'name' => 'Roblox',
            'type' => 'roblox',
            'image' => 'roblox.webp',
            'price' => 10
          ],
          [
            'name' => 'Roblox',
            'type' => 'roblox',
            'image' => 'roblox.webp',
            'price' => 15
          ],
          [
            'name' => 'Roblox',
            'type' => 'roblox',
            'image' => 'roblox.webp',
            'price' => 20
          ],
        ];
        foreach($cardsData as $card) {
          Card::create([
            'name' => $card['name'],
            'type' => $card['type'],
            'image' => $card['image'],
            'price' => $card['price']
          ]);
        }
    }
}
