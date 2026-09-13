<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'brand_name',      'value_en' => 'AH.GROUP',           'value_ar' => 'مجموعة AH'],
            ['key' => 'email',           'value_en' => 'info@ahgroup.com',    'value_ar' => 'info@ahgroup.com'],
            ['key' => 'phone',           'value_en' => '+962 771400000',      'value_ar' => '+962 771400000'],
            ['key' => 'facebook_url',    'value_en' => '#',                   'value_ar' => '#'],
            ['key' => 'instagram_url',   'value_en' => '#',                   'value_ar' => '#'],
            ['key' => 'website_url',     'value_en' => '#',                   'value_ar' => '#'],
            ['key' => 'clients_title',   'value_en' => 'Our Clients',         'value_ar' => 'عملاؤنا'],
            ['key' => 'clients_sub',     'value_en' => 'We are proud to have partnered with leading brands across various industries, delivering excellence and driving growth.', 'value_ar' => 'نفخر بشراكتنا مع كبرى العلامات التجارية في مختلف القطاعات، نقدم التميز ونحرك النمو.'],
            ['key' => 'cta_lead',        'value_en' => 'Ready to grow your brand? Let\'s build something great together.', 'value_ar' => 'مستعد لتنمية علامتك التجارية؟ دعنا نبني شيئاً عظيماً معاً.'],
            ['key' => 'cta_btn_text',    'value_en' => 'GET STARTED NOW',     'value_ar' => 'ابدأ الآن'],
            ['key' => 'cta_btn_link',    'value_en' => '#contact',            'value_ar' => '#contact'],
            ['key' => 'agencies_title',    'value_en' => 'Our Integrated Agencies', 'value_ar' => 'وكالاتنا المتكاملة'],
            // Decorative images (paths set by admin via upload)
            ['key' => 'logo_image',        'value_en' => '', 'value_ar' => ''],
            ['key' => 'hero_sign_image',   'value_en' => '', 'value_ar' => ''],
            ['key' => 'hero_octo_image',   'value_en' => '', 'value_ar' => ''],
            ['key' => 'agencies_octo_image','value_en' => '', 'value_ar' => ''],
            ['key' => 'about_octo_image',  'value_en' => '', 'value_ar' => ''],
            ['key' => 'showcase_octo_image','value_en' => '', 'value_ar' => ''],
            ['key' => 'cta_octo_image',    'value_en' => '', 'value_ar' => ''],
        ];

        foreach ($settings as $s) {
            SiteSetting::updateOrCreate(['key' => $s['key']], $s);
        }
    }
}
