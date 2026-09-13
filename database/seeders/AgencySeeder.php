<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\AgencyService;
use Illuminate\Database\Seeder;

class AgencySeeder extends Seeder
{
    public function run(): void
    {
        // ── Recovery Jo ────────────────────────────────────────────────────
        $recovery = Agency::updateOrCreate(['slug' => 'recovery'], [
            'name_en'        => 'Recovery Jo',
            'name_ar'        => 'ريكفري جو',
            'logo'           => null,
            'description_en' => 'A SPECIALIZED COMPANY OFFERING COMPREHENSIVE DIGITAL SERVICES',
            'description_ar' => 'شركة متخصصة تقدم خدمات رقمية متكاملة',
            'heading_en'     => 'Recovery Jo – Digital & Tech Marketing',
            'heading_ar'     => 'ريكفري جو – التسويق الرقمي والتقني',
            'sort_order'     => 1,
            'is_active'      => true,
        ]);

        $recoveryServices = [
            ['en' => 'Digital marketing and social media management',     'ar' => 'التسويق الرقمي وإدارة وسائل التواصل الاجتماعي',   'order' => 1],
            ['en' => 'Website design and development',                    'ar' => 'تصميم وتطوير مواقع الويب',                          'order' => 2],
            ['en' => 'Application programming',                           'ar' => 'برمجة التطبيقات',                                   'order' => 3],
            ['en' => 'Visual identity design and promotional videos',     'ar' => 'تصميم الهوية البصرية والفيديوهات الترويجية',        'order' => 4],
            ['en' => 'Technical consulting and user interface design',    'ar' => 'الاستشارات التقنية وتصميم واجهات المستخدم',          'order' => 5],
        ];

        AgencyService::where('agency_id', $recovery->id)->delete();
        foreach ($recoveryServices as $s) {
            AgencyService::create([
                'agency_id'  => $recovery->id,
                'title_en'   => $s['en'],
                'title_ar'   => $s['ar'],
                'sort_order' => $s['order'],
                'is_active'  => true,
            ]);
        }

        // ── Experts World ───────────────────────────────────────────────────
        $experts = Agency::updateOrCreate(['slug' => 'experts'], [
            'name_en'        => 'Experts World',
            'name_ar'        => 'إكسبرتس وورلد',
            'logo'           => null,
            'description_en' => 'A MARKETING COMPANY PROVIDING BOTH ONLINE AND OFFLINE SERVICES',
            'description_ar' => 'شركة تسويقية تقدم خدمات عبر الإنترنت وخارجها',
            'heading_en'     => 'Experts World for Marketing – Comprehensive Marketing',
            'heading_ar'     => 'إكسبرتس وورلد للتسويق – تسويق متكامل',
            'sort_order'     => 2,
            'is_active'      => true,
        ]);

        $expertsServices = [
            ['en' => 'Digital and field marketing',                       'ar' => 'التسويق الرقمي والميداني',                           'order' => 1],
            ['en' => 'Event and exhibition organization',                 'ar' => 'تنظيم الفعاليات والمعارض',                           'order' => 2],
            ['en' => 'Brand identity and branding',                       'ar' => 'هوية العلامة التجارية والبراندينج',                    'order' => 3],
            ['en' => 'Marketing and advertising campaign planning',       'ar' => 'تخطيط الحملات التسويقية والإعلانية',                  'order' => 4],
            ['en' => 'Influencer and creative marketing',                 'ar' => 'التسويق عبر المؤثرين والتسويق الإبداعي',              'order' => 5],
        ];

        AgencyService::where('agency_id', $experts->id)->delete();
        foreach ($expertsServices as $s) {
            AgencyService::create([
                'agency_id'  => $experts->id,
                'title_en'   => $s['en'],
                'title_ar'   => $s['ar'],
                'sort_order' => $s['order'],
                'is_active'  => true,
            ]);
        }
    }
}
