<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\PressRelease;

return new class extends Migration
{
    public function up(): void
    {
        $items = [
            [
                'title' => 'Loops Integrated Dominates SLIM DIGIS 2.4 with 14 Accolades Across Major Categories',
                'slug' => 'loops-integrated-dominates-slim-digis-2-4',
                'category' => 'Award Win',
                'published_date' => '2024-03-15',
                'author' => 'Loops Media Team',
                'publisher' => 'Daily FT',
                'external_link' => 'https://www.ft.lk',
                'excerpt' => 'Loops Integrated reaffirmed its dominance at SLIM DIGIS 2.4, securing an impressive haul of 14 trophies including multiple Golds and Silvers across integrated digital and creative disciplines.',
                'content' => "Loops Integrated reaffirmed its position as Sri Lanka's pioneering integrated communications agency at the recently concluded SLIM DIGIS 2.4. Securing an impressive tally of 14 accolades across diverse categories including Best Use of Branded Content, Banking & Finance, Retail, and Healthcare, the agency showcased the creative depth and analytical firepower that have become hallmarks of its craft.\n\nSpeaking on the achievement, the leadership highlighted that this milestone reflects the bold trust placed in Loops by ambitious brand partners, coupled with a relentless focus on tangible business outcomes rather than superficial metrics alone.",
                'image_url' => 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=1200&q=80',
                'is_featured' => true,
                'published' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Loops Expands Footprint Across APAC and Middle East Markets',
                'slug' => 'loops-expands-footprint-across-apac-middle-east',
                'category' => 'Achievement',
                'published_date' => '2024-06-20',
                'author' => 'Loops Corporate',
                'publisher' => 'Campaign Asia',
                'external_link' => 'https://www.campaignasia.com',
                'excerpt' => 'Expanding beyond Sri Lanka, Loops strengthens regional capabilities with active client engagements across Australia, the United Kingdom, United Arab Emirates, and Japan.',
                'content' => "Demonstrating the global scalability of Sri Lankan creative tech talent, Loops Integrated continues to accelerate its footprint in key international hubs. With active cross-border operations spanning Australia, the United Kingdom, the United Arab Emirates, and Japan, Loops delivers high-velocity performance marketing, bespoke MarTech solutions, and brand transformation programs on an international scale.\n\nBy uniting multidisciplinary squads under one roof, the agency offers global clients the agility and technological edge needed to stand out in crowded international landscapes.",
                'image_url' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1200&q=80',
                'is_featured' => true,
                'published' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Unveiling Loops AI Studio: Revolutionizing Generative Content Production',
                'slug' => 'unveiling-loops-ai-studio-generative-content',
                'category' => 'Press Release',
                'published_date' => '2024-09-10',
                'author' => 'Loops Tech Lab',
                'publisher' => 'Loops Newsroom',
                'external_link' => null,
                'excerpt' => 'Pioneering the intersection of artificial intelligence and brand storytelling, Loops launches its proprietary AI Content Engine, cutting campaign production cycles by 60%.',
                'content' => "In an industry first for Sri Lanka, Loops Integrated officially unveils its dedicated AI Content and MarTech engine. Built specifically to empower modern brand marketers, the platform blends cutting-edge visual synthesis, hyper-localized copy models, and dynamic asset generation to deliver enterprise-grade campaign production at unprecedented velocity.\n\nFrom predictive creative testing to personalized video workflows at scale, the newly established lab positions Loops at the absolute forefront of creative technology in the region.",
                'image_url' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=1200&q=80',
                'is_featured' => true,
                'published' => true,
                'sort_order' => 3,
            ],
            [
                'title' => 'Softlogic Life "Health Quiz" Wins Best Interactive Campaign Recognition',
                'slug' => 'softlogic-life-health-quiz-interactive-campaign',
                'category' => 'Media Coverage',
                'published_date' => '2024-11-05',
                'author' => 'Editorial Staff',
                'publisher' => 'Ada Derana',
                'external_link' => 'https://www.adaderana.lk',
                'excerpt' => 'How Loops Integrated engineered a viral gamified financial literacy experience that captivated over half a million unique users in under 3 weeks.',
                'content' => "Gamification met meaningful brand utility in the recent campaign developed by Loops Integrated for Softlogic Life. By turning complex insurance policies into an engaging, rewarded interactive experience, the campaign demonstrated the power of creative digital integration in driving high-intent customer acquisition.\n\nThe initiative recorded exceptional engagement rates and set a new benchmark for fintech and insurtech consumer education in Sri Lanka.",
                'image_url' => 'https://images.unsplash.com/photo-1551836022-d5d88e9218df?auto=format&fit=crop&w=1200&q=80',
                'is_featured' => false,
                'published' => true,
                'sort_order' => 4,
            ],
            [
                'title' => 'Loops Celebrates 15 Years of Boundary-Pushing Integrated Marketing',
                'slug' => 'loops-celebrates-15-years-of-integrated-marketing',
                'category' => 'Milestone',
                'published_date' => '2025-01-18',
                'author' => 'Executive Office',
                'publisher' => 'Loops Newsroom',
                'external_link' => null,
                'excerpt' => 'From a pioneering digital agency to a 100+ member integrated powerhouse, Loops marks a decade and a half of creative courage and digital leadership.',
                'content' => "Marking 15 transformative years in the industry, Loops Integrated looks back on a journey that started as one of the island's earliest digital agencies and evolved into a multi-disciplinary juggernaut handling Creative, Tech, Digital, Video, Events, and AI. With hundreds of campaign awards and dozens of premier corporate partnerships, Loops looks ahead to the next frontier of advertising.",
                'image_url' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=1200&q=80',
                'is_featured' => false,
                'published' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($items as $item) {
            PressRelease::updateOrCreate(['slug' => $item['slug']], $item);
        }
    }

    public function down(): void
    {
        PressRelease::truncate();
    }
};
