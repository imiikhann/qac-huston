<?php

namespace Database\Seeders;

use App\Models\HeroBanner;
use App\Models\MembershipProgram;
use App\Models\NavigationMenu;
use App\Models\Page;
use App\Models\Section;
use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class CmsSeeder extends Seeder
{
    public function run(): void
    {
        SiteSetting::truncate();
        HeroBanner::truncate();
        NavigationMenu::truncate();
        Section::truncate();
        MembershipProgram::truncate();

        SiteSetting::create([
            'site_name' => 'QAC Houston',
            'contact_email' => 'info@qachouston.com',
            'contact_phone' => '(713) 123-4567',
            'address' => '1234 Auto Repair Blvd, Houston, TX 77001',
            'footer_text' => 'QAC Houston — Quality Auto Care you can trust.',
            'social_links' => [
                'facebook' => '#',
                'instagram' => '#',
                'twitter' => '#',
            ],
        ]);

        $heroImages = [
            'https://qachouston.com/wp-content/uploads/2025/02/456B13C3-C5BF-448D-BA70-2A633D929A0B.jpg',
            'https://qachouston.com/wp-content/uploads/2018/07/wordpress-auto-repair-slider_2.jpg',
            'https://qachouston.com/wp-content/uploads/2018/07/wordpress-auto-repair-slider_4.jpg',
            'https://qachouston.com/wp-content/uploads/2018/07/wordpress-auto-repair-slider_5.jpg',
            'https://qachouston.com/wp-content/uploads/2018/07/wordpress-auto-repair-slider_6.jpg',
        ];

        foreach ($heroImages as $i => $image) {
            HeroBanner::create([
                'title' => 'Over 10 Years Of Quality Auto Service',
                'subtitle' => 'Service',
                'image' => $image,
                'cta_text' => 'BOOK NOW',
                'cta_link' => '/book',
                'sort_order' => $i + 1,
                'is_active' => true,
            ]);
        }

        NavigationMenu::create(['label' => 'Home', 'url' => '/', 'location' => 'header', 'sort_order' => 1]);
        NavigationMenu::create(['label' => 'Services', 'url' => '/services', 'location' => 'header', 'sort_order' => 2]);
        NavigationMenu::create(['label' => 'About', 'url' => '/about', 'location' => 'header', 'sort_order' => 3]);
        NavigationMenu::create(['label' => 'Contact', 'url' => '/contact', 'location' => 'header', 'sort_order' => 4]);

        Section::create([
            'type' => 'stats',
            'title' => 'Our Impact',
            'content' => [
                'items' => [
                    ['value' => '15+', 'label' => 'Years Experience'],
                    ['value' => '5000+', 'label' => 'Vehicles Serviced'],
                    ['value' => '50+', 'label' => 'Expert Technicians'],
                    ['value' => '98%', 'label' => 'Customer Satisfaction'],
                ],
            ],
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Section::create([
            'type' => 'services',
            'title' => 'Our Services',
            'subtitle' => 'We specialize in electrical, mechanical, and special services on all vehicles.',
            'content' => ['badge' => 'What We Do'],
            'is_active' => true,
            'sort_order' => 2,
        ]);

        Section::create([
            'type' => 'image_break',
            'title' => 'Precision Service for Every Vehicle',
            'subtitle' => 'From luxury imports to heavy-duty fleet trucks, our certified technicians deliver exceptional care.',
            'image' => 'https://qachouston.com/wp-content/uploads/2018/07/wordpress-auto-repair-slider_6.jpg',
            'content' => [
                'overlay' => 'left',
                'align' => 'left',
                'button_text' => 'Explore Services',
                'button_link' => '/services',
            ],
            'is_active' => true,
            'sort_order' => 3,
        ]);

        Section::create([
            'type' => 'clientele',
            'title' => 'Clientele',
            'subtitle' => 'We serve a diverse range of customers with excellence.',
            'content' => [
                'badge' => 'Who We Serve',
                'tabs' => [
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
                ],
                'card_title' => 'Join Our Family',
                'card_text' => 'Over 70% of our customers are loyal regulars',
            ],
            'is_active' => true,
            'sort_order' => 4,
        ]);

        Section::create([
            'type' => 'membership',
            'title' => 'Membership Programs',
            'subtitle' => 'Exclusive benefits for our loyal customers.',
            'content' => ['badge' => 'Loyalty Rewards'],
            'is_active' => true,
            'sort_order' => 5,
        ]);

        Section::create([
            'type' => 'image_break',
            'title' => 'Your Car Deserves the Best',
            'subtitle' => 'Schedule your appointment today and experience the QAC difference.',
            'image' => 'https://qachouston.com/wp-content/uploads/2018/07/wordpress-auto-repair-slider_5.jpg',
            'content' => [
                'overlay' => 'right',
                'align' => 'right',
                'hide_content' => true,
                'hide_overlay' => true,
            ],
            'is_active' => true,
            'sort_order' => 6,
        ]);

        Section::create([
            'type' => 'reviews',
            'title' => 'What Our Customers Say',
            'content' => ['badge' => 'Testimonials'],
            'is_active' => true,
            'sort_order' => 7,
        ]);

        Section::create([
            'type' => 'cta',
            'title' => 'Ready to Service Your Vehicle?',
            'subtitle' => 'Book an appointment today and experience the QAC difference. Family-owned and trusted since 2015.',
            'content' => [
                'book_text' => 'Book Appointment',
                'book_link' => '/book',
                'phone' => '(832) 896-3865',
            ],
            'is_active' => true,
            'sort_order' => 8,
        ]);

        MembershipProgram::create([
            'name' => 'Preventive Maintenance',
            'price' => 'Free*',
            'features' => ['Buy 2, Get 1 Free Oil Change', 'Discounted Diagnostics', 'Earn Loyalty Points'],
            'is_popular' => false,
            'button_text' => 'Enroll Now',
            'button_link' => '/contact',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        MembershipProgram::create([
            'name' => 'Discounts + Rewards',
            'price' => '$99/yr',
            'features' => ['10-20% Off Labor and Parts', 'Free Diagnostics', 'Earn Loyalty Points'],
            'is_popular' => true,
            'button_text' => 'Enroll Now',
            'button_link' => '/contact',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        MembershipProgram::create([
            'name' => 'Fleet Maintenance',
            'price' => 'Free*',
            'features' => ['Special Rebates', 'Priority Service', 'Flexible Appointments'],
            'is_popular' => false,
            'button_text' => 'Enroll Now',
            'button_link' => '/contact',
            'is_active' => true,
            'sort_order' => 3,
        ]);

        MembershipProgram::create([
            'name' => 'VIP Program',
            'price' => '$429/yr',
            'features' => ['Complimentary Services', 'Premium Appointments', 'Free Diagnostics', 'Free Pick & Drop'],
            'is_popular' => false,
            'button_text' => 'Enroll Now',
            'button_link' => '/contact',
            'is_active' => true,
            'sort_order' => 4,
        ]);
    }
}
