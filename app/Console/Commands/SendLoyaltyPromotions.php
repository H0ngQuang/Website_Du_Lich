<?php

namespace App\Console\Commands;

use App\Mail\LoyaltyPromotionEmail;
use App\Models\clients\CustomerLoyalty;
use App\Models\clients\Promotion;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendLoyaltyPromotions extends Command
{
    protected $signature = 'loyalty:send-promotions';
    protected $description = 'Gửi ưu đãi cho khách hàng cũ dựa trên tier (chạy hàng tuần)';

    public function handle()
    {
        $loyaltyModel = new CustomerLoyalty();
        $promotionModel = new Promotion();
        $customers = $loyaltyModel->getCustomersForPromotion();

        if ($customers->isEmpty()) {
            $this->info('Không có khách hàng nào cần gửi ưu đãi.');
            return;
        }

        $sentCount = 0;

        foreach ($customers as $customer) {
            // Tìm promotion phù hợp với tier
            $promotion = $promotionModel->getRandomPromotionForTier($customer->loyalty_tier);

            try {
                Mail::to($customer->email)->send(
                    new LoyaltyPromotionEmail(
                        $customer->username,
                        $customer->loyalty_tier,
                        $customer->points,
                        $promotion
                    )
                );

                $loyaltyModel->markPromotionSent($customer->userId);
                $sentCount++;

                $this->info("Đã gửi ưu đãi cho: {$customer->email} (Tier: {$customer->loyalty_tier})");
            } catch (\Exception $e) {
                $this->error("Lỗi gửi email cho {$customer->email}: {$e->getMessage()}");
            }
        }

        $this->info("Hoàn tất! Đã gửi {$sentCount} email ưu đãi loyalty.");
    }
}
