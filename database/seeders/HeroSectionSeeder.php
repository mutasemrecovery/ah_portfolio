<?php

namespace Database\Seeders;

use App\Models\HeroSection;
use Illuminate\Database\Seeder;

class HeroSectionSeeder extends Seeder
{
    public function run(): void
    {
        HeroSection::updateOrCreate(['id' => 1], [
            'title_en'    => 'House Of Brands',
            'title_ar'    => 'بيت العلامات التجارية',
            'subtitle_en' => 'We Empower Innovation, Marketing & Tech',
            'subtitle_ar' => 'نُمكّن الابتكار والتسويق والتقنية',
            'cta_text_en' => 'EXPLORE OUR ECOSYSTEM',
            'cta_text_ar' => 'استكشف منظومتنا',
            'cta_link'    => '#agencies',
            'is_active'   => true,
        ]);
    }
}
