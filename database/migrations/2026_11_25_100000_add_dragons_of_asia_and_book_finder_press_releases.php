<?php

use App\Models\PressRelease;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    private const SLUGS = [
        'loops-integrated-with-sampath-bank-solves-the-colombo-book-fairs-biggest-problem',
        'loops-integrated-wins-gold-at-dragons-of-asia-2026',
    ];

    public function up(): void
    {
        // Inserted oldest-first: equal sort_order/date fall back to id desc, so Dragons lists first
        PressRelease::updateOrCreate(['slug' => self::SLUGS[0]], [
            'title' => 'Loops Integrated with Sampath Bank Solves the Colombo Book Fair’s Biggest Problem',
            'category' => 'Press Release',
            'published_date' => '2026-11-25',
            'excerpt' => 'For years, the Colombo International Book Fair had one problem no map could solve: finding the book you came for.',
            'content' => "For years, the Colombo International Book Fair had one problem no map could solve: finding the book you came for.\n\nA title could be waiting somewhere across the fair’s many halls and stalls, but visitors had no live way to know where. Cataloguing every book at every stall would be almost impossible to build and keep current. Loops Integrated found a smarter solution. The people walking the fair were already seeing the books. What if they could show everyone else?\n\nFor Sampath Bank, Loops brought Book Finder to life: a live, community powered platform built around one simple idea: See it. Share it. Find it.\n\nWhen visitors spotted a book, they could post a photo and its stall location. Someone searching for that title could find the post and go straight there. If nobody had shared it yet, they could post a request. Each contribution made the platform more useful for the next visitor. An admin dashboard supported post moderation, analytics, a BMICH stall directory and live fair notices.\n\nBook Finder turned visitors into the fair’s live information network. It addressed a longstanding problem through a simple human insight and technology that could respond as the fair unfolded. Sampath Bank did not need to build a book by stall database.\n\nAt the Colombo International Book Fair, held from 25 September to 4 October at the BMICH, Sampath Bank’s more than 20 year partnership took on a new role: helping visitors spend less time searching and more time discovering.",
            'image_url' => '/images/press/sampath-book-finder.jpg',
            'is_featured' => false,
            'published' => true,
            'sort_order' => 0,
        ]);

        PressRelease::updateOrCreate(['slug' => self::SLUGS[1]], [
            'title' => 'Loops Integrated Wins Gold at Dragons of Asia 2026',
            'category' => 'Award Win',
            'published_date' => '2026-11-25',
            'excerpt' => 'Loops Integrated has proudly taken home a Gold Dragon at the Dragons of Asia 2026 Awards for “You Are A Bad Example”, a bold campaign created for Softlogic Life Insurance.',
            'content' => "Loops Integrated has proudly taken home a Gold Dragon at the Dragons of Asia 2026 Awards for “You Are A Bad Example”, a bold campaign created for Softlogic Life Insurance. This prestigious recognition celebrates the brilliant creative thinking behind a campaign that challenges how adults speak to children and highlights the lasting impact their words can have.\n\nAt the heart of “You Are A Bad Example” lies a familiar phrase that carries far more weight than many adults realise. Labels such as “troublemaker”, “hopeless” and “bad example” can shape how children see themselves, affecting their confidence and how they understand their place in the world.\n\nThe campaign encourages adults to reconsider the language they use around children and to recognise how everyday comments can influence a young person’s self-image. By turning a familiar expression into a conversation about the power of words, it shines a spotlight on an issue that often goes unnoticed.\n\nFor Loops Integrated and Softlogic Life, the campaign is a shining example of how a meaningful human insight can become a creative idea that truly connects with people. Rather than treating language as a minor part of everyday interaction, it highlights how words can influence a child’s confidence and development.\n\nThe Gold Dragon at Dragons of Asia 2026 celebrates the work behind the campaign and the team that brought this idea to life. It also strengthens Loops Integrated’s growing portfolio of award-winning work, showcasing the agency’s passion for developing ideas that blend creativity with meaningful messages.\n\nFor Loops Integrated, this achievement reinforces a firm commitment to creating work that challenges perspectives, sparks conversations and forges meaningful connections with audiences.",
            'image_url' => '/images/press/dragons-of-asia-2026-gold-bad-example.jpg',
            'is_featured' => false,
            'published' => true,
            'sort_order' => 0,
        ]);
    }

    public function down(): void
    {
        PressRelease::whereIn('slug', self::SLUGS)->delete();
    }
};
