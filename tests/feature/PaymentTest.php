<?php
declare(strict_types=1);

namespace Tychovbh\Tests\Mvc\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Tychovbh\Mvc\Events\PaymentUpdated;
use Tychovbh\Mvc\Http\Resources\PaymentResource;
use Tychovbh\Mvc\Payment;
use Tychovbh\Mvc\User;
use Tychovbh\Tests\Mvc\TestCase;
use Illuminate\Support\Facades\Mail;


class PaymentTest extends TestCase
{
    /**
     * @test
     */
    public function itCanIndex()
    {
//        $users = factory(User::class, 3)->create();
//        $this->index('users.index', UserResource::collection($users));
    }

    /**
     * @test
     */
    public function itCanShow()
    {
//        $user = factory(User::class)->create();
//        $this->show('users.show', UserResource::make($user));
    }


    /**
     * @test
     */
    public function itCanStore()
    {
//        Mail::fake();

        $payment = factory(Payment::class)->make();

        $data = $payment->toArray();
        $payment->status = Payment::STATUS_OPEN;
        $response = $this->store('payments.store', PaymentResource::make($payment), $data);

        $payment = json_decode($response->getContent(), true)['data'];
//        Mail::assertQueued(UserCreated::class, function (UserCreated $mail) use ($user) {
//            return $mail->email = $user['data']['email'];
//        });
//
//        Mail::assertQueued(UserVerify::class, function (UserVerify $mail) use ($user) {
//            return $mail->mail['email'] = $user['data']['email'];
//        });

        return $payment;
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
        Event::fake();

        $this->insert($payment);

        $response = $this->get(route('payments.success', [$payment['id']]));
        $response->assertRedirect(
            str_replace('{id}', $payment['id'] ?? 0, config('mvc-payments.return'))
        );

        Event::assertDispatched(PaymentUpdated::class, function (PaymentUpdated $event) use ($payment) {
            return (int)$event->payment->id === $payment['id'];
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
            'user_id' => $payment['user']['id']
        ]);
    }
}


