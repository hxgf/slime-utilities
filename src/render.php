<?php

namespace Slime;

class render {

	// render data as json string
  public static function json($req, $res, $args){
    $data = [];
    if ($args['data']){
      $data = $args['data'];
    }
    $status = 200;
    if ($args['status']){
      $status = $args['status'];
    }
    $res->getBody()->write(json_encode($data));
    return $res->withHeader('content-type', 'application/json')->withStatus($status);
  }

  // render a twig template w/ a given data array (and include global 'locals' array)
  public static function twig($req, $res, $args){
    $template = $args['template'] . '.html';
    $data = [];
    $data['locals'] = $GLOBALS['locals'];
    if ($args['data']){
      $data = array_merge($data, $args['data']);
    }
    if ($args['title']){
      $data['title'] = $args['title'];
    }
    return Twig::fromRequest($req)->render($res, $template, $data);
  }

}

?>