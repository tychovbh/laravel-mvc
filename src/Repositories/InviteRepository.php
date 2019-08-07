<?php
declare(strict_types=1);

namespace Tychovbh\Mvc\Repositories;

use Illuminate\Support\Facades\Mail;
use Tychovbh\Mvc\Invite;
use Illuminate\Support\Arr;
use Tychovbh\Mvc\Mail\UserInvite;

class InviteRepository extends AbstractRepository implements Repository
{
    /**
     * InviteRepository constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->model = new Invite();
        parent::__construct();
    }

    /**
     * @param array $data
     * @return Invite
     */
    public function save(array $data)
    {
        $email = Arr::get($data, 'email');
        $invite = parent::save([
            'reference' => random_string(),
            'token' => token($data)
        ]);

        $data['link'] = str_replace('{reference}', $invite->reference, config('mvc-auth.url'));
        Mail::to($email)->send(new UserInvite($data));

        return $invite;
    }
}
