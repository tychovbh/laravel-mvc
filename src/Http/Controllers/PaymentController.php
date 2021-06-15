<?php
declare(strict_types=1);

namespace Tychovbh\Mvc\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Tychovbh\Mvc\Models\Payment;

class PaymentController extends AbstractController
{
    /**
     * Payment success
     * @param int $id
     * @return RedirectResponse
     */
    public function success(int $id): RedirectResponse
    {
        $payment = new Payment;

        try {
            $payment = $this->repository->find($id);

            /* @var Payment $payment*/
            $payment->check();
        } catch (ModelNotFoundException $exception) {
            error(message('model.notfound', ucfirst($this->controller), 'ID', $id));
        }

        return redirect()->to(
            str_replace('{id}', (string)($payment->id ?? 0), config('mvc-payments.return'))
        );
    }
}
