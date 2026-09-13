<?php

namespace Database\Seeders;

use App\Models\AboutSection;
use Illuminate\Database\Seeder;

class AboutSectionSeeder extends Seeder
{
    public function run(): void
    {
        AboutSection::updateOrCreate(['id' => 1], [
            'title_en' => 'About US',
            'title_ar' => 'من نحن',
            'body_en'  => "AH GROUP is a leading group of companies established in Jordan with the purpose of uniting specialized companies under one umbrella, offering integrated solutions in marketing, technology, production, and business entrepreneurship.\n\nThe group includes successful companies such as Recovery Jo and Experts World for Marketing, and continuously seeks expansion by launching new companies in various sectors.\n\nWe believe the group's strength lies in its diversity, service integration, and teamwork that combines innovation, high performance, and strategic vision.",
            'body_ar'  => "مجموعة AH هي مجموعة رائدة من الشركات تأسست في الأردن بهدف توحيد الشركات المتخصصة تحت مظلة واحدة، لتقديم حلول متكاملة في التسويق والتقنية والإنتاج وريادة الأعمال.\n\nتضم المجموعة شركات ناجحة مثل ريكفري جو وإكسبرتس وورلد للتسويق، وتسعى باستمرار إلى التوسع من خلال إطلاق شركات جديدة في قطاعات متنوعة.\n\nنؤمن بأن قوة المجموعة تكمن في تنوعها وتكامل خدماتها والعمل الجماعي الذي يجمع بين الابتكار والأداء العالي والرؤية الاستراتيجية.",
            'image'    => null,
            'is_active' => true,
        ]);
    }
}
