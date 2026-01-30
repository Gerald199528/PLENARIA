<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [


            /* Settings de los logos */

            [
                'key' => 'logo',
                'value' => 'images/1_logo.png',
                'type' => 'string',
                'group' => 'general',
                'name' => 'Logo',
                'description' => 'Logo de la empresa',
                'is_public' => true,
                'is_tenant_editable' => true,
            ],
            [
                'key' => 'logo_background_solid',
                'value' => 'images/2_logo_background_solid.png',
                'type' => 'string',
                'group' => 'general',
                'name' => 'Logo Background Solid',
                'description' => 'Logo de la empresa',
                'is_public' => true,
                'is_tenant_editable' => true,
            ],
            [
                'key' => 'logo_grey',
                'value' => 'images/3_logo_grey.png',
                'type' => 'string',
                'group' => 'general',
                'name' => 'Logo Grey',
                'description' => 'Logo de la empresa',
                'is_public' => true,
                'is_tenant_editable' => true,
            ],
            [
                'key' => 'logo_horizontal',
                'value' => 'images/4_logo_horizontal.png',
                'type' => 'string',
                'group' => 'general',
                'name' => 'Logo Horizontal',
                'description' => 'Logo de la empresa',
                'is_public' => true,
                'is_tenant_editable' => true,
            ],
            [
                'key' => 'logo_horizontal_background_solid',
                'value' => 'images/5_logo_horizontal_background_solid.png',
                'type' => 'string',
                'group' => 'general',
                'name' => 'Logo Horizontal Background Solid',
                'description' => 'Logo de la empresa',
                'is_public' => true,
                'is_tenant_editable' => true,
            ],
            [
                'key' => 'logo_icon',
                'value' => 'images/6_logo_icon.png',
                'type' => 'string',
                'group' => 'general',
                'name' => 'Logo Icon',
                'description' => 'Logo de la empresa',
                'is_public' => true,
                'is_tenant_editable' => true,
            ],
            [
                'key' => 'logo_icon_grey',
                'value' => 'images/7_logo_icon_grey.png',
                'type' => 'string',
                'group' => 'general',
                'name' => 'Logo Icon Grey',
                'description' => 'Logo de la empresa',
                'is_public' => true,
                'is_tenant_editable' => true,
            ],

        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
