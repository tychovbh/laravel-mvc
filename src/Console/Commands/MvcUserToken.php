<?php

namespace Tychovbh\Mvc\Console\Commands;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Carbon;
use Tychovbh\Mvc\Repositories\UserRepository;
use Illuminate\Console\Command;
use Tychovbh\Mvc\Models\TokenType;
use Illuminate\Support\Arr;

class MvcUserToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mvc-user:token {--type=} {--expiration=} {--email=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate User Token';

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
     * @param UserRepository $repository
     */
    public function handle(UserRepository $repository)
    {
        $email = $this->option('email') ?? $this->ask('For which email do you want to generate a token?');
        try {
            $user = $repository->findBy('email', $email);
            $type = $this->option('type') ?? $this->choice('What type of token?', [
                    TokenType::API_KEY,
                    TokenType::USER_TOKEN
                ]);
            $expiration = null;

            if ($type !== TokenType::API_KEY) {
                $expiration = $this->option('expiration') ?? $this->ask('When should the token expire? YYYY-MM-DD HH:MM:SS');
                $expiration = Carbon::createFromFormat('Y-m-d H:i:s', $expiration)->timestamp;
            }

            $routes = ['all'];
            foreach (Route::getRoutes() as $route) {
                if (Arr::has($route, 'action.as')) {
                    $routes[] = $route['action']['as'];
                }
            }

            $params = [
                'id' => $user->id,
                'type' => $type,
            ];

            $routes = $this->choice('Allowed routes?', $routes, 0, null, true);
            if (!in_array('all', $routes)) {
                $params['routes'] = $routes;
            }

            $this->info('Token: ' . token($params, $expiration));
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
        }
    }
}
