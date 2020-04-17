<?php

use App\Command;
use Cycle\Schema\Generator;

return [
    'debugger.enabled' => true,
    'mailer' => [
        'host' => 'smtp.example.com',
        'port' => 25,
        'encryption' => null,
        'username' => 'admin@example.com',
        'password' => '',
    ],

    'supportEmail' => 'support@example.com',

    'aliases' => [
        '@root' => dirname(__DIR__),
        '@views' => '@root/views',
        '@resources' => '@root/resources',
        '@src' => '@root/src',
    ],

    'session' => [
        'options' => ['cookie_secure' => 0],
    ],

    'console' => [
        'commands' => [
            'user/create' => Command\User\CreateCommand::class,
            'fixture/add' => Command\Fixture\AddCommand::class,
        ],
    ],

    // cycle DBAL config
    'cycle.dbal'       => [
        'default'     => 'mysql',
        'aliases'     => [],
        'databases'   => [
            'default' => ['connection' => 'mysql'],
            'mysql'   => ['connection' => 'mysql'],
        ],
        'connections' => [
            'mysql' => [
                'driver'  => \Spiral\Database\Driver\MySQL\MySQLDriver::class,
                'options' => [
                    'connection' => 'mysql:host=127.0.0.1;dbname=todos',
                    'username'   => 'root',
                    'password'   => 'password',
                ]
            ],
        ],
    ],
    // cycle common config
    'cycle.common' => [
        'entityPaths' => [
            '@src/Entity',
            '@src/Todos/Entity',
        ],
        'cacheEnabled' => true,
        'cacheKey' => 'Cycle-ORM-Schema',
        'generators' => [
            // sync table changes to database
            Generator\SyncTables::class,
        ],
        // 'promiseFactory' => \Cycle\ORM\Promise\ProxyFactory::class,
        //'queryLogger' => \Yiisoft\Yii\Cycle\Logger\StdoutQueryLogger::class,
    ],
    // cycle migration config
    'cycle.migrations' => [
        'directory' => '@root/migrations',
        'namespace' => 'App\\Migration',
        'table' => 'migration',
        'safe' => false,
    ],
];
