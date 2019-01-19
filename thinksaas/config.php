<?php
//ГЛОБАЛЬНАЯ КОНФИГУРАЦИЯ
return array(

	//Memcache
	'memcache' => array(
		//'host' => '127.0.0.1',
		//'port' => 11211,
	),

	//отладка (debug) системы
	'debug' => true,

	//хуки модулей
	'hook' => false,

	//session БД
	'session' => false,

	//файлы session хранятся в каталоге  cache/sessions/
	//'sessionpath'=>'sessions',

	//ситемные логи в каталоге tslogs
	'logs' => false,

    //логи mysql в каталоге tslogs
    'slowsqllogs'=>0,   //по-умолчанию 0, для записи каждые 0.5 сек - 0.5, для более 1 сек - 1

	//поддержка поддоменов
	//не указывайте несколько групп для домена
	'subdomain' => array(
		//'domain'=>'domen.com', //домен
		//'app'=>array('group','user'), //область
	),

	//поддержка независимых доменных имен 
	'appdomain' => array(//'photo'=>'www.domen.com', //www.domen.com
	),

	//картинки и файлы на поддомене
	//'fileurl'=>'',
	'fileurl' => array(
		//'url'=>'cache.thinksaas.cn',
		//'dir'=>array('cache','uploadfile','public'),
	),


	//feed
	'feed'=>array(
		'user_register'=>'',
		'user_follow'=>'',

		'group_create'=>'',
		'group_topic_add'=>'',
		'group_topic_comment'=>'',

		'weibo_add'=>'',
		'weibo_comment'=>'',

		'article_add'=>'',
		'article_comment'=>'',
	),

    //блокировка доменов (через запятую). Например, вместо http://www.thinksaas.cn/ можно указать www.thinksaas.cn
    'urllock'=>'',


	/* Информация об авторских правах на программное обеспечение
	 * ThinkSAAS
	 * Уважайте информацию об авторских правах ThinkSAAS, если хотите её удалить, то купите лицензию
	 * Контакты: QQ:1078700473，thinksaas
	 */
	'info' => array(
		'name' => 'ThinkSAAS',
		'url' => 'https://www.thinksaas.cn/',
		'email' => 'qiujun@thinksaas.cn',
		'qq' => '1078700473',
		'weixin' => 'thinksaas',
		'copyright' => 'ThinkSAAS',
		'copyurl' => 'http://www.thinksaas.cn/',
		'year' => '2012',#год основания
		'author' => 'Цю Юнь',
	),

);
