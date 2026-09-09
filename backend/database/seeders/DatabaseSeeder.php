<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Faq;
use App\Models\FleetInquiry;
use App\Models\Lead;
use App\Models\Membership;
use App\Models\PpiRequest;
use App\Models\Review;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Vendor;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'QAC Admin',
            'email' => 'admin@qachouston.com',
            'password' => bcrypt('password'),
        ]);

        $this->call(ServiceCategorySeeder::class);

        // --- SERVICES (from live site) ---
        $services = [
            // Electrical
            ['name' => 'Electrical Diagnostics', 'description' => 'Advanced computer diagnostics to identify electrical system issues, check engine lights, and performance problems.', 'category' => 'electrical', 'price_range' => '$79 - $149', 'sort_order' => 1],
            ['name' => 'Alternator & Battery', 'description' => 'Battery testing, alternator repair and replacement, charging system diagnostics.', 'category' => 'electrical', 'price_range' => '$69 - $399', 'sort_order' => 2],
            ['name' => 'Calibrations', 'description' => 'Precision calibration for vehicle sensors and electronic control modules.', 'category' => 'electrical', 'price_range' => 'Call for quote', 'sort_order' => 3],
            ['name' => 'Electric Parking Brakes', 'description' => 'Diagnostics and repair of electric parking brake systems.', 'category' => 'electrical', 'price_range' => '$99 - $299', 'sort_order' => 4],
            ['name' => 'Key Fob Programming', 'description' => 'Key fob programming, initialization, calibration, and battery registration.', 'category' => 'electrical', 'price_range' => '$49 - $199', 'sort_order' => 5],
            ['name' => 'Ignition System', 'description' => 'Ignition system diagnostics, spark plug replacement, coil pack repair.', 'category' => 'electrical', 'price_range' => '$89 - $399', 'sort_order' => 6],
            ['name' => 'Interior Lights', 'description' => 'Interior lighting diagnostics and repair including dome lights, dash lights, and ambient lighting.', 'category' => 'electrical', 'price_range' => '$29 - $149', 'sort_order' => 7],
            ['name' => 'Head & Tail Lights', 'description' => 'Headlight, taillight, and signal light replacement and wiring repair.', 'category' => 'electrical', 'price_range' => '$29 - $199', 'sort_order' => 8],
            // Mechanical
            ['name' => 'Axle Shaft', 'description' => 'Axle shaft inspection, repair, and replacement for front and rear axles.', 'category' => 'mechanical', 'price_range' => '$199 - $699', 'sort_order' => 9],
            ['name' => 'Brakes', 'description' => 'Complete brake inspection, pad replacement, rotor resurfacing, and brake fluid flush.', 'category' => 'mechanical', 'price_range' => '$99 - $499', 'sort_order' => 10],
            ['name' => 'Catalytic Converters', 'description' => 'Catalytic converter diagnostics, repair, and replacement.', 'category' => 'mechanical', 'price_range' => '$299 - $1,299', 'sort_order' => 11],
            ['name' => 'CV Axles', 'description' => 'CV axle inspection and replacement, boot repair.', 'category' => 'mechanical', 'price_range' => '$149 - $499', 'sort_order' => 12],
            ['name' => 'Engine Swaps', 'description' => 'Complete engine removal and replacement for all makes and models.', 'category' => 'mechanical', 'price_range' => 'Call for quote', 'sort_order' => 13],
            ['name' => 'Filters', 'description' => 'Engine air filter, cabin air filter, fuel filter replacement.', 'category' => 'mechanical', 'price_range' => '$19 - $79', 'sort_order' => 14],
            ['name' => 'Fuel Injection Systems', 'description' => 'Fuel injector cleaning, repair, and replacement for optimal engine performance.', 'category' => 'mechanical', 'price_range' => '$89 - $499', 'sort_order' => 15],
            ['name' => 'Engine Tune-Ups', 'description' => 'Complete engine tune-up including spark plugs, filters, fluid checks, and performance inspection.', 'category' => 'mechanical', 'price_range' => '$149 - $399', 'sort_order' => 16],
            ['name' => 'Engine Mounts', 'description' => 'Engine mount inspection and replacement.', 'category' => 'mechanical', 'price_range' => '$99 - $399', 'sort_order' => 17],
            ['name' => 'Oil Changes', 'description' => 'Full synthetic and conventional oil changes with new filter and fluid top-off.', 'category' => 'mechanical', 'price_range' => '$39 - $89', 'sort_order' => 18],
            ['name' => 'Heater Core & Evaporator', 'description' => 'Heater core and evaporator diagnostics, repair, and replacement.', 'category' => 'mechanical', 'price_range' => '$299 - $999', 'sort_order' => 19],
            ['name' => 'Oxygen Sensors', 'description' => 'Oxygen sensor diagnostics and replacement.', 'category' => 'mechanical', 'price_range' => '$99 - $299', 'sort_order' => 20],
            ['name' => 'Radiator', 'description' => 'Radiator inspection, repair, and replacement. Cooling system service.', 'category' => 'mechanical', 'price_range' => '$149 - $599', 'sort_order' => 21],
            ['name' => 'Suspension', 'description' => 'Shock and strut replacement, ball joint inspection, control arm repair, and alignment.', 'category' => 'mechanical', 'price_range' => '$149 - $1,299', 'sort_order' => 22],
            ['name' => 'Timing Belt/Chain', 'description' => 'Timing belt and timing chain inspection and replacement with water pump service when applicable.', 'category' => 'mechanical', 'price_range' => '$499 - $1,199', 'sort_order' => 23],
            ['name' => 'U-Joints', 'description' => 'Universal joint inspection and replacement for drive shafts.', 'category' => 'mechanical', 'price_range' => '$79 - $299', 'sort_order' => 24],
            ['name' => 'Water Pump', 'description' => 'Water pump inspection and replacement. Cooling system diagnostics.', 'category' => 'mechanical', 'price_range' => '$199 - $699', 'sort_order' => 25],
            ['name' => 'Transfer Case Services & Swaps', 'description' => 'Transfer case fluid service, inspection, and replacement for 4WD/AWD vehicles.', 'category' => 'mechanical', 'price_range' => '$149 - $899', 'sort_order' => 26],
            ['name' => 'Transmission Services & Swaps', 'description' => 'Transmission fluid change, filter replacement, diagnostics, and complete transmission replacement.', 'category' => 'mechanical', 'price_range' => '$149 - $1,999', 'sort_order' => 27],
            ['name' => 'Differential Services & Swaps', 'description' => 'Differential fluid service, inspection, and replacement.', 'category' => 'mechanical', 'price_range' => '$99 - $599', 'sort_order' => 28],
            ['name' => 'A/C System Recharge', 'description' => 'A/C system inspection, refrigerant recharge including R134A and 1234YF, leak detection.', 'category' => 'mechanical', 'price_range' => '$89 - $299', 'sort_order' => 29],
            ['name' => 'Mechanical Diagnostics', 'description' => 'Comprehensive mechanical diagnostics to identify engine, transmission, and drivetrain issues.', 'category' => 'mechanical', 'price_range' => '$79 - $149', 'sort_order' => 30],
            // Special
            ['name' => 'Recalibration', 'description' => 'Advanced recalibration services for vehicle electronic systems and sensors.', 'category' => 'special', 'price_range' => 'Call for quote', 'sort_order' => 31],
            ['name' => 'Relearning', 'description' => 'Electronic system relearning procedures after component replacement.', 'category' => 'special', 'price_range' => '$49 - $149', 'sort_order' => 32],
            ['name' => 'Reprogramming', 'description' => 'ECU and module reprogramming for performance and updates.', 'category' => 'special', 'price_range' => '$99 - $299', 'sort_order' => 33],
            ['name' => 'Battery Registration', 'description' => 'Battery registration for European and luxury vehicles after replacement.', 'category' => 'special', 'price_range' => '$29 - $69', 'sort_order' => 34],
            ['name' => 'Transmission Adaptation', 'description' => 'Transmission adaptation and reset procedures for optimal shifting.', 'category' => 'special', 'price_range' => '$79 - $199', 'sort_order' => 35],
            ['name' => 'Wheel Alignments', 'description' => 'Precision wheel alignment service for all vehicles.', 'category' => 'special', 'price_range' => '$79 - $149', 'sort_order' => 36],
            ['name' => 'A/C & Heater Repairs & Services', 'description' => 'Complete A/C and heating system diagnostics, repair, and service for all vehicles.', 'category' => 'special', 'price_range' => '$89 - $599', 'sort_order' => 37],
            ['name' => 'Advanced Driver Assist Systems (ADAS)', 'description' => 'ADAS calibration and recalibration for modern safety systems including cameras, radar, and sensors.', 'category' => 'special', 'price_range' => 'Call for quote', 'sort_order' => 38],
            ['name' => 'Multi-Point Pre-Purchase Inspection', 'description' => 'Comprehensive pre-purchase inspection covering 25-100 points. Available in Basic ($99), Standard ($199), and Comprehensive ($299) tiers.', 'category' => 'special', 'price_range' => '$99 - $299', 'sort_order' => 39],
            ['name' => 'State Inspection including Emissions', 'description' => 'State vehicle inspection including emissions testing. (Coming soon)', 'category' => 'special', 'price_range' => 'Coming soon', 'sort_order' => 40],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }

        // --- CUSTOMERS ---
        $customers = Customer::factory(20)->create();

        foreach ($customers as $customer) {
            Vehicle::factory(rand(1, 3))->create(['customer_id' => $customer->id]);
        }

        $allVehicles = Vehicle::all();
        $allServices = Service::all();

        foreach ($customers as $customer) {
            $customerVehicles = $allVehicles->where('customer_id', $customer->id);
            $numAppointments = rand(1, 4);
            for ($i = 0; $i < $numAppointments; $i++) {
                Appointment::create([
                    'customer_id' => $customer->id,
                    'vehicle_id' => $customerVehicles->random()->id,
                    'service_id' => $allServices->random()->id,
                    'requested_date' => now()->addDays(rand(-30, 60))->format('Y-m-d'),
                    'requested_time' => ['08:00', '09:00', '10:00', '11:00', '13:00', '14:00', '15:00', '16:00'][rand(0, 7)],
                    'status' => ['pending', 'confirmed', 'completed', 'cancelled'][rand(0, 3)],
                    'notes' => fake()->optional(40)->sentence(),
                ]);
            }
        }

        foreach ($customers->take(8) as $customer) {
            Membership::create([
                'customer_id' => $customer->id,
                'tier' => ['preventive', 'discounts', 'fleet', 'vip'][rand(0, 3)],
                'start_date' => now()->subMonths(rand(1, 6))->format('Y-m-d'),
                'end_date' => now()->addMonths(rand(3, 12))->format('Y-m-d'),
                'status' => 'active',
            ]);
        }

        // --- FAQS (from live site) ---
        $faqs = [
            ['question' => 'Where are you located?', 'answer' => 'We are conveniently located in the Jersey Village area on Jones Road near HW 290. Address: 9934 Jones Road, Unit B5, Houston 77065.', 'category' => 'general', 'sort_order' => 1],
            ['question' => 'What are your operating hours?', 'answer' => 'Mon-Thu: 9am to 6pm, Fri-Sat: 9am to 1pm, Sun: Closed.', 'category' => 'general', 'sort_order' => 2],
            ['question' => 'Do you offer ADAS Recalibration?', 'answer' => 'Yes, we offer ADAS recalibration services. Contact us for details. ADAS recalibration is required if your vehicle has been in an accident or repairs have been made related to ADAS components.', 'category' => 'services', 'sort_order' => 3],
            ['question' => 'Do you service EV/Hybrid vehicles?', 'answer' => 'Yes! We offer services for Hybrid/EV vehicles, including A/C repair, battery replacement, brakes, lights, suspension, and more!', 'category' => 'services', 'sort_order' => 4],
            ['question' => 'What membership programs do you offer?', 'answer' => 'We offer 4 exclusive membership programs: 1. Preventative Maintenance Membership (Free*), 2. Discounts and Rewards Membership ($99/yr), 3. Fleet Maintenance Membership (Free*), 4. VIP Membership ($429/yr). Each program offers unique benefits including free diagnostics, loyalty points, priority service, and more.', 'category' => 'membership', 'sort_order' => 5],
            ['question' => 'Do you offer discounts?', 'answer' => 'Yes! We offer discounts for First Responders, Students, Senior Citizens, and Military Service Members. We also provide bundle and seasonal discounts!', 'category' => 'pricing', 'sort_order' => 6],
            ['question' => 'What is a Pre-Purchase Inspection (PPI)?', 'answer' => 'We offer multi-point inspections to ensure confidence when purchasing a used car: Basic Inspection ($99, 25 points), Standard Inspection ($199, 50 points), Comprehensive Inspection ($299, 100 points).', 'category' => 'services', 'sort_order' => 7],
            ['question' => 'What services do you offer?', 'answer' => 'Our expertise covers Electrical, Mechanical, and Special services for various vehicles, including fleet trucks, motorhomes, and food trucks. We specialize in diagnostics, repairs, maintenance, and performance modifications.', 'category' => 'services', 'sort_order' => 8],
            ['question' => 'Do you offer fleet services?', 'answer' => 'Yes! We serve fleet clients, including box trucks (refrigerated & non-refrigerated), fleet vans, and Turo rental vehicles. Our fleet maintenance program offers special rebates, priority service, and flexible appointments.', 'category' => 'services', 'sort_order' => 9],
            ['question' => 'Do you offer wheel alignment?', 'answer' => 'Contact us for details as we will soon be providing Wheel Alignment Services.', 'category' => 'services', 'sort_order' => 10],
            ['question' => 'What vehicles do you service?', 'answer' => 'We service all makes and models including domestic, Asian, and European vehicles. We specialize in luxury brands including Alfa Romeo, BMW, Ferrari, Maserati, Mercedes, Porsche, Range Rover, and Volvo.', 'category' => 'general', 'sort_order' => 11],
            ['question' => 'Do you provide towing services?', 'answer' => 'Yes, we provide towing assistance and offer towing expense reimbursement for qualifying repairs.', 'category' => 'general', 'sort_order' => 12],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }

        // --- REVIEWS (from live site Google reviews) ---
        $reviews = [
            ['customer_name' => 'Natalie Jones', 'rating' => 5, 'title' => 'Excellent Service', 'comment' => 'I brought my Honda Accord in for service. The customer experience was excellent from beginning to end. The environment is welcoming, and the service was great. The technician was honest and very trustworthy. No price gouging. I was even given tips on how to keep the condition of my car healthy to prevent major issues. I highly recommend this establishment especially if your vehicle needs major repairs. You won\'t regret it.', 'is_approved' => true],
            ['customer_name' => 'Saad Ali', 'rating' => 5, 'title' => 'Professional and Thorough', 'comment' => 'I have taken multiple of my cars to QAC for some smaller and some comprehensive services such as timing, oil pumps etc and only have good to say about them. They are very professional, thorough and communicative from the moment you drop it off till even after you pick it up to make sure everything is working good. If you are looking for people you can trust in the automotive repair space, QAC is your spot!', 'is_approved' => true],
            ['customer_name' => 'Bilal H', 'rating' => 5, 'title' => 'Highly Recommend', 'comment' => 'Highly recommend this place for automotive repairs. Everything from diagnostics, quote, explanation of questions and work was done professionally. Repair was completed on time.', 'is_approved' => true],
            ['customer_name' => 'Syed B', 'rating' => 5, 'title' => 'Very Professional', 'comment' => 'Very professional staff, and they are very helpful as well. They explained everything before they started working on my car. The repair was done in a very timely manner.', 'is_approved' => true],
            ['customer_name' => 'Osama Ali', 'rating' => 5, 'title' => 'True Automotive Veteran', 'comment' => 'I took my Acura TL Type-S for a timing belt service. I met Tabi and he is a true automotive veteran! His expertise along with his meticulous approach made the job look easy! I was kept in the loop at every step of the process! My car runs great and I have a peace of mind knowing that I can bring my other cars to QAC.', 'is_approved' => true],
            ['customer_name' => 'Osama M.', 'rating' => 5, 'title' => 'Great Experience', 'comment' => 'I had a great experience at QAC. Tabi was really helpful. He explained everything incredibly well. Helped me figure out all the essential repairs for my 17 year old clunker.', 'is_approved' => true],
            ['customer_name' => 'Hassan Ali Javed', 'rating' => 5, 'title' => 'Reliable and Trustworthy', 'comment' => 'I recently took my car in for brake, rotor, and engine oil service at Quality Automotive Center, and I couldn\'t be happier with the experience! The team was incredibly professional, from diagnosing the issues to explaining the necessary repairs. They provided a detailed breakdown of the costs upfront, so there were no surprises. Tabi at QAC is a reliable, efficient, and trustworthy mechanic.', 'is_approved' => true],
            ['customer_name' => 'Umer Farooq', 'rating' => 5, 'title' => 'Great Service', 'comment' => 'Great service and professional staff. Highly recommended!', 'is_approved' => true],
        ];

        foreach ($reviews as $review) {
            Review::create($review);
        }

        // --- TESTIMONIALS ---
        $testimonials = [
            ['customer_name' => 'Sarah M.', 'content' => 'Best auto shop in Houston! They diagnosed my BMW\'s electrical issue quickly and fixed it at a fair price.', 'rating' => 5, 'is_approved' => true],
            ['customer_name' => 'John D.', 'content' => 'I brought my fleet truck here for maintenance. Their fleet program is excellent - priority service and great pricing.', 'rating' => 5, 'is_approved' => true],
            ['customer_name' => 'Maria R.', 'content' => 'Tabi went above and beyond to explain what my car needed. I finally found a mechanic I can trust!', 'rating' => 5, 'is_approved' => true],
            ['customer_name' => 'David K.', 'content' => 'They saved me thousands compared to the dealership quote. Honest, skilled, and professional.', 'rating' => 5, 'is_approved' => true],
            ['customer_name' => 'Lisa T.', 'content' => 'I love their VIP membership program. Free pick and drop, premium appointments - worth every penny.', 'rating' => 5, 'is_approved' => true],
            ['customer_name' => 'Ahmed S.', 'content' => 'Pre-purchase inspection saved me from buying a lemon. Very thorough inspection with a detailed report.', 'rating' => 5, 'is_approved' => true],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }

        // --- VENDORS (from live site) ---
        $vendors = [
            ['name' => 'Worldpac', 'website' => 'https://www.worldpac.com', 'category' => 'luxury', 'description' => 'Premium parts supplier for luxury and foreign vehicles.', 'sort_order' => 1],
            ['name' => 'Parts Authority (IMC)', 'website' => 'https://www.partsauthority.com', 'category' => 'luxury', 'description' => 'Quality parts for import and luxury vehicles.', 'sort_order' => 2],
            ['name' => 'Crown Lift Trucks', 'website' => 'https://www.crown.com', 'category' => 'fleet', 'description' => 'Fleet truck parts and service equipment.', 'sort_order' => 3],
            ['name' => 'Rush Truck Parts', 'website' => 'https://www.rushtruckparts.com', 'category' => 'fleet', 'description' => 'Commercial truck parts and accessories.', 'sort_order' => 4],
            ['name' => 'Hayes Truck Group', 'website' => 'https://www.hayestruckgroup.com', 'category' => 'fleet', 'description' => 'Truck parts, sales, and service.', 'sort_order' => 5],
            ['name' => 'LKQ Corp', 'website' => 'https://www.lkqcorp.com', 'category' => 'remanufactured', 'description' => 'Pre-owned and remanufactured auto parts.', 'sort_order' => 6],
            ['name' => 'Jasper Engines & Transmissions', 'website' => 'https://www.jasperengines.com', 'category' => 'remanufactured', 'description' => 'Remanufactured engines, transmissions, and differentials.', 'sort_order' => 7],
            ['name' => 'Advance Auto Parts', 'website' => 'https://www.advanceautoparts.com', 'category' => 'standard', 'description' => 'Leading auto parts retailer for standard vehicles.', 'sort_order' => 8],
            ['name' => 'O\'Reilly Auto Parts', 'website' => 'https://www.oreillyauto.com', 'category' => 'standard', 'description' => 'Quality auto parts with free in-store pickup.', 'sort_order' => 9],
            ['name' => 'AutoZone', 'website' => 'https://www.autozone.com', 'category' => 'standard', 'description' => 'Auto parts, accessories, and advice.', 'sort_order' => 10],
            ['name' => 'NAPA Auto Parts', 'website' => 'https://www.napaonline.com', 'category' => 'standard', 'description' => 'Professional-grade auto parts and accessories.', 'sort_order' => 11],
            ['name' => 'Dodge/Jeep/Chrysler OEM', 'website' => null, 'category' => 'oem', 'description' => 'OEM dealership parts for Dodge, Jeep, and Chrysler vehicles.', 'sort_order' => 12],
            ['name' => 'Mercedes OEM', 'website' => null, 'category' => 'oem', 'description' => 'Genuine OEM parts for Mercedes vehicles.', 'sort_order' => 13],
            ['name' => 'Honda/Acura OEM', 'website' => null, 'category' => 'oem', 'description' => 'Genuine OEM parts for Honda and Acura vehicles.', 'sort_order' => 14],
            ['name' => 'BMW OEM', 'website' => null, 'category' => 'oem', 'description' => 'Genuine OEM parts for BMW vehicles.', 'sort_order' => 15],
            ['name' => 'Audi/Volkswagen OEM', 'website' => null, 'category' => 'oem', 'description' => 'Genuine OEM parts for Audi and Volkswagen vehicles.', 'sort_order' => 16],
            ['name' => 'Chevrolet OEM', 'website' => null, 'category' => 'oem', 'description' => 'Genuine OEM parts for Chevrolet vehicles.', 'sort_order' => 17],
            ['name' => 'Ford OEM', 'website' => null, 'category' => 'oem', 'description' => 'Genuine OEM parts for Ford vehicles.', 'sort_order' => 18],
            ['name' => 'Hyundai/Kia OEM', 'website' => null, 'category' => 'oem', 'description' => 'Genuine OEM parts for Hyundai and Kia vehicles.', 'sort_order' => 19],
            ['name' => 'Maserati/Alfa Romeo OEM', 'website' => null, 'category' => 'oem', 'description' => 'Genuine OEM parts for Maserati and Alfa Romeo vehicles.', 'sort_order' => 20],
            ['name' => 'Nissan/Infiniti OEM', 'website' => null, 'category' => 'oem', 'description' => 'Genuine OEM parts for Nissan and Infiniti vehicles.', 'sort_order' => 21],
            ['name' => 'Range Rover OEM', 'website' => null, 'category' => 'oem', 'description' => 'Genuine OEM parts for Range Rover vehicles.', 'sort_order' => 22],
        ];

        foreach ($vendors as $vendor) {
            Vendor::create($vendor);
        }

        FleetInquiry::factory(5)->create();
        PpiRequest::factory(5)->create();
        Lead::factory(10)->create();
    }
}
