<?php

/*************************************************************************
* Requirements
*************************************************************************/
require_once(ROOT_DIR . 'vendor/silex/silex.phar');
require_once(ROOT_DIR . 'vendor/markdown/markdown.php');
require_once(ROOT_DIR . 'vendor/spyc/spyc.php');

/*************************************************************************
* Setup debug flag if running on localhost
*************************************************************************/
if ($_SERVER['SERVER_ADDR'] == '127.0.0.1') {
	define('PRODUCTION_ENV', false);
} else {
	define('PRODUCTION_ENV', true);
}

/*************************************************************************
* Usages
*************************************************************************/
use Symfony\Component\HttpFoundation\Response;

/*************************************************************************
* Create our application
*************************************************************************/
$app = new Silex\Application();

/*************************************************************************
* Auto loader settings
*************************************************************************/
$app['autoloader']->registerNamespace('Cubicle', ROOT_DIR . 'src');

/*************************************************************************
* Extensions
*************************************************************************/
$app->register(new Silex\Extension\TwigExtension(), array(
    'twig.path'       => ROOT_DIR . 'layouts',
    'twig.class_path' => ROOT_DIR . 'vendor/twig/lib',
	// 'twig.options' => array('cache' => ROOT_DIR . 'cache')
));
$app['twig']->addFilter('nl2br', new Twig_Filter_Function('nl2br'));

$app->register(new Silex\Extension\SessionExtension(), array(
	'session.storage.options' => array(
		'lifetime' => 3600*24*7,
		'httponly' => true
	)
));

/*************************************************************************
* Services
*************************************************************************/
$app['article_service'] = $app->share(function($app) {
	return new Cubicle\ArticleService($app);
});

$app['gallery_service'] = $app->share(function() {
	return new Cubicle\GalleryService();
});

$app['rss_service'] = $app->share(function($app) {
	return new Cubicle\RssService($app);
});

/*************************************************************************
* Error handling
*************************************************************************/
if (PRODUCTION_ENV) {
	$app->error(function (\Exception $e) use($app) {
		error_log($e->getMessage());
		error_log($e->getTraceAsString());
	    return new Response($app['twig']->render('error.html.twig', array()), 404);
	});
}

/*************************************************************************
* Routes
*************************************************************************/
$app->get('/galleries', function() use($app) {
	return $app['twig']->render('galleries.html.twig', array('title' => 'Galleries', 'galleries' => $app['gallery_service']->getGalleries()));
});

$app->get('/contact', function() use($app) {
	return $app['twig']->render('contact.html.twig', array('title' => 'Contact me'));
});

$app->get('/article/{name}', function($name) use ($app) {
	return $app['twig']->render('article.html.twig', $app['article_service']->getRenderData($name));
});

$app->get('/rss', function() use($app) {
	return $app['rss_service']->render();
});

$app->post('/openidtoken', function() use($app) {
	if (!$app['openid_service']->handleCallback()) {
		return $app['twig']->render('openiderror.html.twig');
	}
	if ($app['session']->has('openid_launch_page')) {
		return $app->redirect($app['session']->get('openid_launch_page'));
	} else {
		return $app->redirect('/');
	}
});

$app->get('/{page}', function ($page) use($app) {
	return $app['twig']->render('main.html.twig', $app['article_service']->getPageList($page));
})->convert('page', function($page) { return (int) $page; });

$app->get('/', function() use ($app) {
	return $app['twig']->render('main.html.twig', $app['article_service']->getPageList());
});

/*************************************************************************
* Process the request
*************************************************************************/
$app->run();
