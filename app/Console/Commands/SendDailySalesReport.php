<?php

namespace App\Console\Commands;

use App\Services\OrderService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDailySalesReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:daily-sales';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily sales report to admin';

    public function __construct(
        private readonly OrderService $orderService
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $date = now()->toDateString();
        $orders = $this->orderService->getOrdersByDate($date);

        if ($orders->isEmpty()) {
            $this->info('No sales for today');
            return Command::SUCCESS;
        }

        $totalSales = $orders->sum('total_amount');
        $totalOrders = $orders->count();

        $reportContent = "Daily Sales Report for {$date}\n\n";
        $reportContent .= "Total Orders: {$totalOrders}\n";
        $reportContent .= "Total Sales: $" . number_format($totalSales, 2) . "\n\n";
        $reportContent .= "Orders:\n";
        $reportContent .= str_repeat('-', 50) . "\n";

        foreach ($orders as $order) {
            $reportContent .= "Order #{$order->id}\n";
            $reportContent .= "User: {$order->user->name} ({$order->user->email})\n";
            $reportContent .= "Amount: $" . number_format($order->total_amount, 2) . "\n";
            $reportContent .= "Items:\n";

            foreach ($order->orderItems as $item) {
                $reportContent .= "  - {$item->product->name} x {$item->quantity} @ $" .
                    number_format($item->price, 2) . "\n";
            }

            $reportContent .= "\n";
        }

        $adminEmail = config('mail.admin_email', 'admin@example.com');

        Mail::raw($reportContent, function ($message) use ($adminEmail, $date) {
            $message->to($adminEmail)
                ->subject("Daily Sales Report - {$date}");
        });

        $this->info("Daily sales report sent to {$adminEmail}");

        return Command::SUCCESS;
    }
}
