<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Car;
use App\Models\Category;
use App\Models\Coupon;

class ChatbotController extends Controller
{
    /**
     * Handle incoming chatbot message.
     */
    public function respond(Request $request)
    {
        try {
            $request->validate([
                'message' => 'required|string|max:1000',
            ]);

            $userMessage = trim($request->input('message'));
            $lowerMsg = mb_strtolower($userMessage);

            // Safely fetch live database context
            $categories = [];
            $locations = ['Mumbai', 'Delhi', 'Bangalore', 'Hyderabad', 'Chennai', 'Goa', 'Pune'];
            $coupons = collect();

            try {
                $categories = Category::pluck('name')->toArray();
            } catch (\Exception $e) {
                $categories = ['Economy', 'Sedan', 'SUV', 'Luxury'];
            }

            try {
                $dbLocs = Car::select('location')->distinct()->pluck('location')->filter()->values()->toArray();
                if (!empty($dbLocs)) {
                    $locations = $dbLocs;
                }
            } catch (\Exception $e) {
                // keep default locations
            }

            try {
                $coupons = Coupon::where('status', 'Active')->get();
            } catch (\Exception $e) {
                $coupons = collect();
            }

            // 1. Try Gemini API if key is available
            $geminiKey = config('services.gemini.key') ?? env('GEMINI_API_KEY');
            if (!empty($geminiKey)) {
                try {
                    $geminiResponse = $this->callGeminiApi($userMessage, $geminiKey, $categories, $locations, $coupons);
                    if ($geminiResponse) {
                        return response()->json([
                            'status' => 'success',
                            'reply' => $geminiResponse['reply'],
                            'quick_actions' => $geminiResponse['quick_actions'] ?? $this->getDefaultQuickActions(),
                            'cars' => $geminiResponse['cars'] ?? [],
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error('Gemini API Chatbot Error: ' . $e->getMessage());
                }
            }

            // 2. Intelligent Knowledge Base Engine (Fallback / Default)
            $botResult = $this->knowledgeEngineResponse($lowerMsg, $userMessage, $categories, $locations, $coupons);

            return response()->json([
                'status' => 'success',
                'reply' => $botResult['reply'],
                'quick_actions' => $botResult['quick_actions'] ?? $this->getDefaultQuickActions(),
                'cars' => $botResult['cars'] ?? [],
            ]);
        } catch (\Exception $ex) {
            Log::error('Chatbot Controller Error: ' . $ex->getMessage());
            return response()->json([
                'status' => 'success',
                'reply' => "I'm your DriveEase AI Assistant! I can help you search cars, check pricing, view active coupons, or contact customer care. What would you like to explore?",
                'quick_actions' => $this->getDefaultQuickActions(),
                'cars' => [],
            ]);
        }
    }

    /**
     * Call Gemini Generative AI API
     */
    private function callGeminiApi(string $userMessage, string $apiKey, array $categories, array $locations, $coupons)
    {
        $systemPrompt = "You are 'DriveEase AI', a helpful, polite, and enthusiastic virtual assistant for DriveEase - India's premier car rental platform.\n" .
            "Company context:\n" .
            "- We offer self-drive and chauffeur-driven car rentals across major Indian cities: " . implode(', ', $locations) . ".\n" .
            "- Available vehicle types: " . implode(', ', $categories) . " (Economy, Luxury, SUVs, Sedans, Electric).\n" .
            "- Features: Transparent pricing, zero hidden charges, 24/7 customer support, well-maintained sanitized cars, doorstep delivery.\n" .
            "- Contact info: Phone 1800-123-4567, Email support@driveease.in.\n" .
            "- Key website pages: /cars (Browse Cars), /booking (Book Now), /offers (Discounts), /contact (Support), /faq (FAQs), /locations (Cities).\n" .
            "Instructions: Answer concisely, warmly, and helpfully. Keep responses under 4 sentences unless listing details. Encourage users to browse cars or book on DriveEase. Use emoji sparingly for high engagement.";

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $apiKey;

        $response = Http::timeout(10)->post($url, [
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        ['text' => $systemPrompt . "\n\nUser asked: " . $userMessage]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.7,
                'maxOutputTokens' => 300,
            ]
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if (!empty($reply)) {
                return [
                    'reply' => trim($reply),
                    'quick_actions' => $this->getDefaultQuickActions(),
                ];
            }
        }

        return null;
    }

    /**
     * Intelligent Rule & DB Context Knowledge Engine
     */
    private function knowledgeEngineResponse(string $lower, string $originalMsg, array $categories, array $locations, $coupons)
    {
        // 1. Greetings
        if (preg_match('/\b(hi|hello|hey|namaste|greetings|good morning|good evening|good afternoon)\b/i', $lower)) {
            return [
                'reply' => "Hello! 👋 Welcome to **DriveEase**! I'm your AI assistant. How can I assist your journey today? You can search cars, check rental prices, view active offers, or ask about our rental policies.",
                'quick_actions' => [
                    ['label' => '🚗 Browse Cars', 'url' => '/cars'],
                    ['label' => '💰 Rental Pricing', 'action' => 'text', 'text' => 'What are your car rental rates?'],
                    ['label' => '🎉 Offers & Coupons', 'action' => 'text', 'text' => 'Show me active offers'],
                    ['label' => '📍 Locations', 'action' => 'text', 'text' => 'Which cities do you operate in?'],
                ]
            ];
        }

        // 2. How to Book / Booking Process / Requirements
        if (preg_match('/(how to book|booking process|how do i book|how can i book|how to rent|steps to book|requirement|documents|license|age limit)/i', $lower) || (str_contains($lower, 'book') && (str_contains($lower, 'how') || str_contains($lower, 'process') || str_contains($lower, 'step')))) {
            return [
                'reply' => "📋 **Booking a car with DriveEase is super easy in 4 simple steps:**\n\n" .
                    "1️⃣ **Choose your Car:** Browse available cars and select your dates & city.\n" .
                    "2️⃣ **Upload Documents:** Valid Driving License & Govt ID (Aadhaar/Passport).\n" .
                    "3️⃣ **Payment:** Pay securely online via Razorpay, UPI, Cards, or Cash on Pickup.\n" .
                    "4️⃣ **Drive Away:** Pick up your car or enjoy doorstep delivery!\n\n" .
                    "📌 *Requirement:* Driving license holder aged 21+ with at least 1 year driving experience.",
                'quick_actions' => [
                    ['label' => '⚡ Start Booking Now', 'url' => '/cars'],
                    ['label' => '📄 View Rental Policy', 'url' => '/rental-policy'],
                ]
            ];
        }

        // 3. Offers & Discounts
        if (preg_match('/\b(offer|offers|coupon|coupons|discount|discounts|promo|deal|deals|code|cheaper|discounted)\b/i', $lower)) {
            $couponText = "";
            if ($coupons->count() > 0) {
                foreach ($coupons as $cp) {
                    $valStr = ($cp->discount_type === 'percentage') ? $cp->discount_value . '% OFF' : '₹' . $cp->discount_value . ' OFF';
                    $couponText .= "• **Code:** `" . $cp->code . "` - " . $valStr . "\n";
                }
            } else {
                $couponText = "• **FIRSTDRIVE:** Get 15% flat OFF on your first booking!\n• **WEEKEND20:** Get 20% OFF on weekend rentals!";
            }

            return [
                'reply' => "🎁 **Active DriveEase Promo Codes & Offers:**\n\n" . $couponText . "\n\nApply these promo codes during checkout on the booking page to instantly claim your discount!",
                'quick_actions' => [
                    ['label' => '🏷️ View Offers Page', 'url' => '/offers'],
                    ['label' => '🚗 Book & Apply Coupon', 'url' => '/cars'],
                ]
            ];
        }

        // 4. Pricing & Rates
        if (preg_match('/\b(price|pricing|rate|rates|cost|rent|charges|deposit|tariff|cheap|expensive)\b/i', $lower)) {
            $minPrice = 1200;
            $maxPrice = 15000;
            try {
                $minPrice = Car::min('rate_per_day') ?? 1200;
                $maxPrice = Car::max('rate_per_day') ?? 15000;
            } catch (\Exception $e) {}

            return [
                'reply' => "DriveEase rental rates are transparent with **zero hidden charges**!\n\n" .
                    "• **Hatchback / Economy:** From ₹" . number_format($minPrice, 0) . "/day\n" .
                    "• **Sedan / Comfort:** From ₹2,200/day\n" .
                    "• **SUV / Family:** From ₹3,500/day\n" .
                    "• **Luxury Fleet:** From ₹" . number_format($maxPrice, 0) . "/day\n\n" .
                    "Rates include comprehensive insurance and 24/7 roadside assistance.",
                'quick_actions' => [
                    ['label' => '🚗 View All Cars & Prices', 'url' => '/cars'],
                    ['label' => '🎉 Discounts & Offers', 'url' => '/offers'],
                ]
            ];
        }

        // 5. Location / City queries
        if (preg_match('/\b(location|locations|city|cities|mumbai|delhi|bangalore|bengaluru|hyderabad|chennai|goa|pune|place|where)\b/i', $lower)) {
            return [
                'reply' => "DriveEase operates across major hubs in India including **" . implode(', ', $locations) . "**!\n\n" .
                    "We offer convenient pickup at airports, railway stations, central city points, or direct doorstep delivery.",
                'quick_actions' => [
                    ['label' => '📍 View All Locations', 'url' => '/locations'],
                    ['label' => '🚗 Search Cars by City', 'url' => '/cars'],
                ]
            ];
        }

        // 6. Cancellation / Refund Policy
        if (preg_match('/\b(cancel|cancellation|refund|refunds|money back|policy|change date)\b/i', $lower)) {
            return [
                'reply' => "🛡️ **DriveEase Cancellation & Refund Policy:**\n\n" .
                    "• **Free Cancellation:** Up to 24 hours before pickup time for 100% full refund.\n" .
                    "• **Late Cancellation:** 15% cancellation fee if cancelled within 24 hours of pickup.\n" .
                    "• **Refund Processing:** Refunds are automatically credited to your original payment method within 3-5 business days.",
                'quick_actions' => [
                    ['label' => '📜 Cancellation Policy', 'url' => '/cancellation-policy'],
                    ['label' => '💳 Refund Policy', 'url' => '/refund-policy'],
                    ['label' => '📅 My Bookings', 'url' => '/my-bookings'],
                ]
            ];
        }

        // 7. Contact Support / Help
        if (preg_match('/\b(contact|support|phone|email|number|help|customer care|issue|agent|call)\b/i', $lower)) {
            return [
                'reply' => "📞 **DriveEase 24/7 Customer Care:**\n\n" .
                    "• **Toll-Free Phone:** 1800-123-4567\n" .
                    "• **Email Support:** support@driveease.in\n" .
                    "• **Head Office:** BKC, Mumbai - 400051\n\n" .
                    "Our customer support specialists are available round-the-clock to help with your trip!",
                'quick_actions' => [
                    ['label' => '📩 Contact Us Page', 'url' => '/contact'],
                    ['label' => '❓ Read FAQs', 'url' => '/faq'],
                ]
            ];
        }

        // 8. Car search / SUV / Sedan / Luxury queries
        if (preg_match('/\b(car|cars|suv|sedan|hatchback|luxury|auto|vehicle|model|fleet|drive)\b/i', $lower)) {
            $carCards = [];
            try {
                $query = Car::with('category')->where('status', 'Available');

                if (str_contains($lower, 'suv')) {
                    $query->whereHas('category', function ($q) {
                        $q->where('name', 'like', '%SUV%');
                    });
                } elseif (str_contains($lower, 'sedan')) {
                    $query->whereHas('category', function ($q) {
                        $q->where('name', 'like', '%Sedan%');
                    });
                } elseif (str_contains($lower, 'luxury')) {
                    $query->whereHas('category', function ($q) {
                        $q->where('name', 'like', '%Luxury%');
                    });
                }

                $featuredCars = $query->limit(3)->get();
                foreach ($featuredCars as $car) {
                    $carCards[] = [
                        'id' => $car->id,
                        'name' => $car->brand_name . ' ' . $car->model_name,
                        'category' => $car->category->name ?? 'Car',
                        'price' => '₹' . number_format($car->rate_per_day, 0) . '/day',
                        'seats' => $car->seats . ' Seats',
                        'fuel' => $car->fuel_type,
                        'location' => $car->location,
                        'image' => $car->image ? asset('storage/' . $car->image) : asset('website/images/car-placeholder.jpg'),
                        'url' => url('/booking/' . $car->id),
                    ];
                }
            } catch (\Exception $e) {
                // ignore
            }

            $catList = !empty($categories) ? implode(', ', $categories) : 'Economy, Sedan, SUV, Luxury';
            $reply = "We have a wide range of verified cars ready for self-drive! Our fleet includes " . $catList . " with prices starting as low as ₹1,200/day.";

            return [
                'reply' => $reply,
                'cars' => $carCards,
                'quick_actions' => [
                    ['label' => '🔍 Filter & Browse Cars', 'url' => '/cars'],
                    ['label' => '⚡ Book A Car', 'url' => '/cars'],
                ]
            ];
        }

        // Default response for unmatched queries
        return [
            'reply' => "I'd be glad to assist you with that! At **DriveEase**, we provide hassle-free car rentals across major Indian cities. Would you like to check available cars, view pricing, explore discounts, or talk to customer support?",
            'quick_actions' => [
                ['label' => '🚗 Browse Available Cars', 'url' => '/cars'],
                ['label' => '💰 Check Rental Rates', 'action' => 'text', 'text' => 'What are your rental rates?'],
                ['label' => '🎉 Active Coupons', 'action' => 'text', 'text' => 'Show me active offers'],
                ['label' => '📞 Contact Support', 'url' => '/contact'],
            ]
        ];
    }

    /**
     * Default quick action options
     */
    private function getDefaultQuickActions()
    {
        return [
            ['label' => '🚗 Browse Cars', 'url' => '/cars'],
            ['label' => '💰 Check Prices', 'action' => 'text', 'text' => 'What are your rental prices?'],
            ['label' => '🎉 Active Offers', 'action' => 'text', 'text' => 'Show me active offers'],
            ['label' => '📞 Contact Support', 'url' => '/contact'],
        ];
    }
}
