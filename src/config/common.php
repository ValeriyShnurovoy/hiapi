<?php
/**
 * HiAPI Yii2 base project for building API
 *
 * @link      https://github.com/hiqdev/hiapi
 * @package   hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 */

return [
    'id' => 'hiapi',
    'name' => 'HiAPI',
    'basePath' => dirname(__DIR__),

    /// aliases must be set before their use
    'aliases' => [],
    'viewPath' => '@hiapi/views',
    'vendorPath' => '@root/vendor',
    'runtimePath' => '@root/runtime',

    'container' => [
        'definitions' => [
            \hiapi\filters\ContentNegotiator::class => [
                'class' => \yii\filters\ContentNegotiator::class,
                'formats' => [
                    'application/json' => \yii\web\Response::FORMAT_JSON,
                    // XXX disabled because browsers accept XML
//                     'application/xml'  => \yii\web\Response::FORMAT_XML,
                ],
            ],
        ],
    ],
];
