<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.3.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App;

use App\PasswordHasher\ExPasswordHasher;
use Cake\Core\Configure;
use Cake\Core\Exception\MissingPluginException;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;
use OperationLogs\Middleware\OperationLogsMiddleware;
use Authentication\AuthenticationService;
use Authentication\AuthenticationServiceInterface;
use Authentication\AuthenticationServiceProviderInterface;
use Authentication\Middleware\AuthenticationMiddleware;
use Cake\Http\Middleware\BodyParserMiddleware;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Application setup class.
 *
 * This defines the bootstrapping logic and middleware layers you
 * want to use in your application.
 */
class Application extends BaseApplication implements AuthenticationServiceProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function bootstrap(): void
    {
        // プラグインのロード

        $this->addPlugin('Authentication');

        $this->addPlugin('OperationLogs', ['bootstrap' => true]);

        $this->addPlugin('CsvView');

        // Call parent to load bootstrap from files.
        parent::bootstrap();

        if (PHP_SAPI === 'cli') {
            try {
                $this->addPlugin('Bake');
            } catch (MissingPluginException $e) {
                // Do not halt if the plugin is missing
            }

            $this->addPlugin('Migrations');
        }

        /*
         * Only try to load DebugKit in development mode
         * Debug Kit should not be installed on a production system
         */
        if (Configure::read('debug')) {
            $this->addPlugin(\DebugKit\Plugin::class);

            // 開発用プラグインのロード
            try {
                $this->addPlugin('Cake/TwigView');
                $this->addPlugin('Cake3AdminBaker', ['bootstrap' => true, 'routes' => true]);
                $this->addPlugin('Cake3ApiGenerator', ['routes' => true]);
            } catch (MissingPluginException $e) {
            }
        }
    }

    /**
     * Setup the middleware queue your application will use.
     *
     * @param \Cake\Http\MiddlewareQueue $middlewareQueue The middleware queue to setup.
     * @return \Cake\Http\MiddlewareQueue The updated middleware queue.
     */
    public function middleware($middlewareQueue): \Cake\Http\MiddlewareQueue
    {
        $middlewareQueue
            // Catch any exceptions in the lower layers,
            // and make an error page/response
            ->add(new ErrorHandlerMiddleware(Configure::read('Error')))

            // Handle plugin/theme assets like CakePHP normally does.
            ->add(new AssetMiddleware([
                'cacheTime' => Configure::read('Asset.cacheTime')
            ]))

            // Add routing middleware.
            // If you have a large number of routes connected, turning on routes
            // caching in production could improve performance. For that when
            // creating the middleware instance specify the cache config name by
            // using it's second constructor argument:
            // `new RoutingMiddleware($this, '_cake_routes_')`
            ->add(new RoutingMiddleware($this, '_cake_routes_'))

            // 認証のプラグインを使用する。
            ->add(new AuthenticationMiddleware($this))

            // Parse various types of encoded request bodies so that they are
            // available as array through $request->getData()
            // https://book.cakephp.org/4/en/controllers/middleware.html#body-parser-middleware
            ->add(new BodyParserMiddleware())

            // Add csrf middleware.
            ->add(new CsrfProtectionMiddleware([
                'httponly' => true
            ]))

            // Add operation_logs middleware.
            ->add(new OperationLogsMiddleware([
                'mode' => 'exclude',
                'exclude_urls' => [
                    '/debug-kit',
                    '/cake3-admin-baker',
                    '/api',
                    '/admin'
                ],
                'exclude_ips' => [
                    '192.168',
                    '::'
                ]
            ]));

        return $middlewareQueue;
    }

    /**
     * Bootrapping for CLI application.
     *
     * That is when running commands.
     *
     * @return void
     */
    protected function bootstrapCli(): void
    {
        try {
            $this->addPlugin('Bake');
        } catch (MissingPluginException $e) {
            // Do not halt if the plugin is missing
        }

        $this->addPlugin('Migrations');

        // Load more plugins here
    }
    /**
     * Gets the successful authenticator instance if one was successful after calling authenticate
     *
     * @param ServerRequestInterface $request リクエストインタフェース
     * @return \Authentication\Authenticator\AuthenticatorInterface|null
     */
    public function getAuthenticationService(ServerRequestInterface $request): AuthenticationServiceInterface
    {
        $authenticationService = new AuthenticationService([
            'unauthenticatedRedirect' => '/admin/auth/login',
            'queryParam' => 'redirect',
        ]);
        $authenticationService->loadIdentifier('Authentication.Password', [
            'resolver'=>[
                'className' => 'Authentication.Orm',
                'userModel' => 'Admins',
                'finder' => 'auth',
            ],
            'fields' => [
                'username' => 'mail',
                'password' => 'password',
            ],
            'passwordHasher' => ExPasswordHasher::class,
        ]);
        $authenticationService->loadAuthenticator('Authentication.Session', [
            'sessionKey' => 'Auth.Admin'
        ]);
        $authenticationService->loadAuthenticator('Authentication.Form', [
            'fields' => [
                'username' => 'mail',
                'password' => 'password',
            ],
            'loginUrl' => '/admin/auth/login',
        ]);

        return $authenticationService;
    }
}
