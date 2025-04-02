<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            'Laravel', 'PHP', 'JavaScript', 'Vue', 'React',
            'Backend', 'Frontend', 'Database', 'API', 'Testing'
        ];

        foreach ($tags as $tag) {
            Tag::create(['name' => $tag]);
        }

        Tag::factory(5)->create();
    }
}
