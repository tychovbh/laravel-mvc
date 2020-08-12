<?php

namespace Tychovbh\Mvc\Console\Commands;

use Illuminate\Console\Command;
use Tychovbh\Mvc\Payment;
use Tychovbh\Mvc\Repositories\PaymentRepository;

class MvcPaymentsCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check payments statuses';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param PaymentRepository $payments
     * @return mixed
     * @throws \Exception
     */
    public function handle(PaymentRepository $payments)
    {
        $open = $payments::withParams(['status' => Payment::STATUS_OPEN])->get();

        foreach ($open as $payment) {
            $payment->check();
        }
    }
}
