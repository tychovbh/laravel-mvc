<?php

namespace Tychovbh\Mvc\Console\Commands;

use Tychovbh\Mvc\Repositories\RoleRepository;
use Tychovbh\Mvc\Repositories\UserRepository;
use Illuminate\Console\Command;

/**
 * @property UserRepository users
 * @property RoleRepository roles
 */
class MvcUserCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mvc-user:create {--name=} {--email=} {--role=} {--admin=} {--password=} {--type=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an user from command line';

    /**
     * Create a new command instance.
     *
     * @param UserRepository $users
     * @param RoleRepository $roles
     */
    public function __construct(UserRepository $users, RoleRepository $roles)
    {
        $this->users = $users;
        $this->roles = $roles;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param array $data
     */
    public function handle(array $data = [])
    {
        try {
            $data = array_merge([
                'name' => $this->option('name') ?? $this->ask('What is your name?'),
                'email' => $this->option('email') ?? $this->ask('We also need an email?'),
                'is_admin' => $this->option('admin') ?? $this->choice('Is the user an admin?', ['No', 'Yes']) === 'Yes',
                'password' => $this->option('password') ?? $this->secret('Finally give up a secure password?'),
                'role_id' => $this->role()
            ], $data);

            $user = $this->users->save($data);

            $this->info('User created!');

            foreach ($user->toArray() as $key => $value) {
                $this->info(ucfirst($key) . ': ' . $value);
            }
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
        }
    }

    /**
     * Ask Role
     * @return int
     */
    private function role(): int
    {
        $roles = $this->roles->all();
        $role = $this->option('role') ?? $this->choice('What Role has the User?', $roles->map(function ($role) {
                return $role['label'];
            })->toArray());
        return $roles->where('label', $role)->first()->id;
    }
}
