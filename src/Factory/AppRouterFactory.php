<?php

namespace App\Factory;

use App\Blog\Archive\ArchiveController;
use App\Blog\BlogController;
use App\Blog\Post\PostController;
use App\Blog\Tag\TagController;
use App\Controller\ApiInfo;
use App\Controller\ApiUserController;
use App\Controller\AuthController;
use App\Controller\ContactController;
use App\Controller\SignupController;
use App\Controller\SiteController;
use App\Controller\UserController;
use App\Middleware\ApiDataWrapper;
use App\Todos\TodoController;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Yiisoft\Yii\Web\Data\Formatter\JsonDataResponseFormatter;
use Yiisoft\Yii\Web\Data\Middleware\FormatDataResponse;
use Yiisoft\Yii\Web\Data\Middleware\FormatDataResponseAsJson;
use Yiisoft\Yii\Web\Data\Middleware\FormatDataResponseAsXml;
use Psr\Container\ContainerInterface;
use Yiisoft\Http\Method;
use Yiisoft\Router\FastRoute\UrlMatcher;
use Yiisoft\Router\Group;
use Yiisoft\Router\Route;
use Yiisoft\Router\RouteCollection;
use Yiisoft\Router\RouteCollectorInterface;
use Yiisoft\Yii\Web\Data\DataResponseFactoryInterface;

class AppRouterFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $routes = [
            Route::get('/', [TodoController::class, TodoController::ACTION_INDEX])
                ->name(TodoController::getRouteName(TodoController::ACTION_INDEX)),


            Route::methods([Method::GET, Method::POST], '/login', [AuthController::class, 'login'])
                ->name('site/login'),
            Route::get('/logout', [AuthController::class, 'logout'])
                ->name('site/logout'),
            Route::methods([Method::GET, Method::POST], '/signup', [SignupController::class, 'signup'])
                ->name('site/signup'),


            // Blog routes
            Group::create('/todos', [
                Route::get('/create', [TodoController::class, TodoController::ACTION_CREATE])
                    ->name(TodoController::getRouteName(TodoController::ACTION_CREATE)),

                Route::post('/store', [TodoController::class, TodoController::ACTION_STORE])
                    ->name(TodoController::getRouteName(TodoController::ACTION_STORE)),

                Route::get('/show/{id}', [TodoController::class, TodoController::ACTION_SHOW])
                    ->name(TodoController::getRouteName(TodoController::ACTION_SHOW)),

                Route::get('/edit/{id}', [TodoController::class, TodoController::ACTION_EDIT])
                    ->name(TodoController::getRouteName(TodoController::ACTION_EDIT)),

                Route::post('/{id}', [TodoController::class, TodoController::ACTION_UPDATE])
                    ->name(TodoController::getRouteName(TodoController::ACTION_UPDATE)),

                Route::post('/delete/{id}', [TodoController::class, TodoController::ACTION_DESTROY])
                    ->name(TodoController::getRouteName(TodoController::ACTION_DESTROY)),


                // Post page
            ]),
        ];

        $collector = $container->get(RouteCollectorInterface::class);
        $collector->addGroup(
            Group::create(null, $routes)
                ->addMiddleware(FormatDataResponse::class)
        );

        return new UrlMatcher(new RouteCollection($collector));
    }
}
