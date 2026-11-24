<?php

use App\Models\Section;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $section = Section::where('type', 'clientele')->first();

        if (! $section) {
            return;
        }

        $content = $section->content ?? [];

        $content['tabs'] = [
            [
                'id' => 'individual',
                'label' => 'Individual',
                'content' => 'We perform all types of scheduled maintenance including oil changes, brake checks, A/C services and more. Emergency repairs, such as alternator replacement or troubleshooting engine overheating are also available. We also provide multi-point Pre-Purchase Inspection service.',
            ],
            [
                'id' => 'fleet',
                'label' => 'Fleet',
                'content' => 'We provide regular services to fleet companies involved in delivery services, logistics, and health services. Our high standards of quality and attention to detail help prevent breakdowns during deliveries.',
            ],
            [
                'id' => 'commercial',
                'label' => 'Commercial',
                'content' => 'We service a variety of commercial clients, including buses, box trucks, food trucks, forklifts, and work vans (including 15-seaters).',
            ],
            [
                'id' => 'luxury',
                'label' => 'Luxury',
                'content' => 'We offer specialized repairs for premium brands, including Alfa Romeo, BMW, Ferrari, Maserati, Mercedes, Porsche, Range Rover, and Volvo.',
            ],
            [
                'id' => 'walkin',
                'label' => 'Walk-In',
                'content' => 'Although we primarily work by appointment, we do welcome walk-in customers. We aim to do whatever we can to help.',
            ],
        ];

        $content['card_title'] = 'Join Our Family';
        $content['card_text'] = 'Over 70% of our customers are loyal regulars';

        $section->update(['content' => $content]);
    }

    public function down(): void
    {
        $section = Section::where('type', 'clientele')->first();

        if (! $section) {
            return;
        }

        $content = $section->content ?? [];

        unset($content['tabs'], $content['card_title'], $content['card_text']);

        $section->update(['content' => $content]);
    }
};