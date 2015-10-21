<?php
$serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
$serviceContainer->checkVersion('2.0.0-dev');
$serviceContainer->setAdapterClass('default', 'mysql');
$manager = new \Propel\Runtime\Connection\ConnectionManagerSingle();
$manager->setConfiguration([
	'dsn'       => 'mysql:host=127.0.0.1;dbname=gw',
	'user'      => 'root',
	'password'  => '',
	'settings'  =>
		[
			'charset' => 'utf8',
			'queries' =>
				[
				],
		],
	'classname' => '\\Propel\\Runtime\\Connection\\ConnectionWrapper',
]);
$manager->setName('default');
$serviceContainer->setConnectionManager('default', $manager);
$serviceContainer->setDefaultDatasource('default');