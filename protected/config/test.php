<?php

return CMap::mergeArray(
	require(dirname(__FILE__).'/main.php'),
	array(
		'components'=>array(
			'fixture'=>array(
				'class'=>'system.test.CDbFixtureManager',
			),

			'db'=>array(
                'connectionString' => 'mysql:host=localhost;dbname=wiki_test',
                'emulatePrepare' => true,
                'username' => 'root',
                'password' => '1234',
                'charset' => 'utf8',
			),

		),
	)
);
