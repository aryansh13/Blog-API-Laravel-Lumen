<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;

class PassportServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(AuthorizationServer::class, function ($app) {
            $clientRepository = $app->make(ClientRepositoryInterface::class);
            $accessTokenRepository = $app->make(AccessTokenRepositoryInterface::class);
            $scopeRepository = $app->make(ScopeRepositoryInterface::class);
            $userRepository = $app->make(UserRepositoryInterface::class);
            $refreshTokenRepository = $app->make(RefreshTokenRepositoryInterface::class);

            $server = new AuthorizationServer(
                $clientRepository,
                $accessTokenRepository,
                $scopeRepository,
                'file://' . storage_path('oauth-private.key'),
                'file://' . storage_path('oauth-public.key')
            );

            $grant = new PasswordGrant(
                $userRepository,
                $refreshTokenRepository
            );

            $grant->setRefreshTokenTTL(Passport::refreshTokensExpireIn());

            $server->enableGrantType(
                $grant,
                Passport::tokensExpireIn()
            );

            return $server;
        });
    }
} 