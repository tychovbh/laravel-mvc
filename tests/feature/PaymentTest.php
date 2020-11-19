<?php
declare(strict_types=1);

namespace Tychovbh\Tests\Mvc\Feature;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Tychovbh\Mvc\Events\PaymentUpdated;
use Tychovbh\Mvc\Mail\PaymentUpdated as PaymentUpdatedMail;
use Tychovbh\Mvc\Http\Resources\PaymentResource;
use Tychovbh\Mvc\Payment;
use Tychovbh\Mvc\Product;
use Tychovbh\Tests\Mvc\TestCase;

class PaymentTest extends TestCase
{
    /**
     * @test
     */
    public function itCanStore()
    {
        $payment = factory(Payment::class)->make();
        $products = factory(Product::class, 2)->create()->map(function (Product $product) {
            return [
                'id' => $product->id,
                'test_extra_options' => uniqid()
            ];
        })->toArray();

        $data = $payment->toArray();
        $data['products'] = $products;
        $payment->products = $products;
        $payment->status = Payment::STATUS_OPEN;
        $response = $this->store('payments.store', PaymentResource::make($payment), $data, 201, [
            'data' => ['status' => 'open']
        ]);

        return json_decode($response->getContent(), true)['data'];
    }

    /**
     * @test
     */
    public function itCanIgnoreStoreAndReturnExisting()
    {
        $products = factory(Product::class, 2)->create()->map(function (Product $product) {
            return ['id' => $product->id];
        })->toArray();

        $payment = factory(Payment::class)->create([
            'user_id' => null,
            'products' => $products
        ]);

        $data = $payment->toArray();
        $data['products'] = $products;
        Arr::forget($data, ['id', 'user_id', 'created_at', 'updated_at', 'external_id']);
        $this->store('payments.store', PaymentResource::make($payment), $data, 200, [], $payment->user_id);
    }

    /**
     * @test
     * @depends itCanStore
     * @param array $payment
     */
    public function itCanCheckStatus(array $payment)
    {
        $this->markTestSkipped('TO MAKE THIS TEST WORK YOU WILL HAVE TO GO TO THE URL IN $payment["url"], and pay mollie');
        Event::fake();

        $this->insert($payment);

        $this->artisan('payments:check');

        Event::assertDispatched(PaymentUpdated::class, function (PaymentUpdated $event) use ($payment) {
            return (int)$event->payment->id === $payment['id'];
        });
    }

    /**
     * @test
     * @depends itCanStore
     * @param array $payment
     */
    public function itCanSuccess(array $payment)
    {
        $this->markTestSkipped('TO MAKE THIS TEST WORK YOU WILL HAVE TO GO TO THE URL IN $payment["url"], and pay mollie');
        Mail::fake();
        Event::fake();

        $this->insert($payment);

        $response = $this->get(route('payments.success', [$payment['id']]));
        $response->assertRedirect(
            str_replace('{id}', $payment['id'] ?? 0, config('mvc-payments.return'))
        );

        Event::assertDispatched(PaymentUpdated::class, function (PaymentUpdated $event) use ($payment) {
            return (int)$event->payment->id === $payment['id'];
        });

        Mail::assertQueued(PaymentUpdatedMail::class, function (PaymentUpdatedMail $mail) use ($payment) {
            return $payment['id'] === $mail->payment->id;
        });
    }

    /**
     * Insert Payment
     * @param array $payment
     */
    private function insert(array $payment)
    {
        DB::table('payments')->insert([
            'id' => $payment['id'],
            'amount' => $payment['amount'],
            'status' => $payment['status'],
            'description' => $payment['description'],
            'external_id' => $payment['external_id'],
//            'user_id' => $payment['user']['id']
        ]);
    }
}


