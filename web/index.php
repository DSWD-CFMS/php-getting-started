<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

require('../vendor/autoload.php');

$app = new Silex\Application();
$app['debug'] = true;

// Register the monolog logging service
$app->register(new Silex\Provider\MonologServiceProvider(), array(
  'monolog.logfile' => 'php://stderr',
));

// Register view rendering
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

// Our web handlers

$app->get('/', function() use($app) {
  $app['monolog']->addDebug('logging output.');
  return $app['twig']->render('index.twig');
});

$app->get('/cowsay', function() use($app) {
  $app['monolog']->addDebug('cowsay');
  return "<pre>".\Cowsayphp\Cow::say("Cool beans")."</pre>";
});

$app->get('/version', function() use($app) {

  if ($handle = opendir('uploads')) {
      $version = "";
      while (false !== ($entry = readdir($handle))) { $version = $entry; }
      closedir($handle);

      return json_encode($version);
  }
});
$app->run();
