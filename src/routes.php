<?php

use Slim\Http\Request;
use Slim\Http\Response;

$app->get('/', function (Request $request, Response $response) {
    return $this->view->render($response, 'home.phtml', ['active' => 'home']);
});

$app->get('/contact', function (Request $request, Response $response) {
    return $this->view->render($response, 'contact.phtml', ['active' => 'contact']);
});

$app->group('/api', function () use ($app) {
    $app->group('/v1', function () use ($app) {
        $app->post('/create', function (Request $request, Response $response) {
            $body = $request->getParsedBody();            
            $free_link = $this->db->query("select `id`, `short` from `links` where `url` = '' limit 1")->fetch();            
            $this->db->query("UPDATE `test`.`links` SET `url` = '" . $body['link']. "' WHERE `id` = " . $free_link['id']);
            echo json_encode([
                'message' => 'Successfuly created.',
                'data' => $free_link['short']
            ]);
        });
    });
});

$app->get('/{short}', function (Request $request, Response $response, array $args) {
    $res = $this->db->query("SELECT `short`,`url` FROM `links` WHERE `short` = '" . $args['short'] . "' LIMIT 1;")->fetch();
    if ( ! empty($res)) {
        header("Location: " . $res['url']);        
        exit();
    }    
    throw new \Slim\Exception\NotFoundException($request, $response);    
});