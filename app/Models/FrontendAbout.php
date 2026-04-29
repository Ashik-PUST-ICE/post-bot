<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FrontendAbout extends Model
{
    protected $fillable = [
        'title', 'description',
        'image_1', 'image_2', 'image_3', 'image_4',
        'statistic_title_1', 'statistic_description_1',
        'statistic_title_2', 'statistic_description_2',
        'statistic_title_3', 'statistic_description_3',
        'mission_title', 'mission_description', 'mission_image',
        'vision_title', 'vision_description', 'vision_image',
        'team_section_title', 'team_section_description', 'team_members',
        'core_value_section_title', 'core_value_section_description', 'core_values',
    ];

    protected $casts = [
        'team_members' => 'array',
        'core_values'  => 'array',
    ];

    public static function instance(): self
    {
        return static::firstOrCreate(['id' => 1], [
            'title'                       => 'About Us',
            'description'                 => 'We are a leading SaaS platform.',
            'statistic_title_1'           => '10K+',
            'statistic_description_1'     => 'Happy Customers',
            'statistic_title_2'           => '99%',
            'statistic_description_2'     => 'Uptime Guaranteed',
            'statistic_title_3'           => '24/7',
            'statistic_description_3'     => 'Support Available',
            'mission_title'               => 'Our Mission',
            'mission_description'         => 'To empower businesses with AI-powered automation.',
            'vision_title'                => 'Our Vision',
            'vision_description'          => 'To be the #1 social messaging automation platform.',
            'team_section_title'          => 'Meet Our Team',
            'team_section_description'    => 'The people behind the platform.',
            'team_members'                => [],
            'core_value_section_title'    => 'Our Core Values',
            'core_value_section_description' => 'What drives us every day.',
            'core_values'                 => [],
        ]);
    }
}
