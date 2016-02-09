<?php

return CMap::mergeArray(
	require(dirname(__FILE__).'/main.php'),
	array(
		'components'=>array(
			'fixture'=>array(
				'class'=>'system.test.CDbFixtureManager',
			),			
			// uncomment the following to use a MySQL database
			
			'db'=>array(
				'connectionString' => 'mysql:host=localhost;dbname=tet',
				'emulatePrepare' => true,
				'enableProfiling' => true,
				'username' => 'tester',
				'password' => 'eh3Majyd',
				'charset' => 'utf8',
			),			
		),
	)
);
