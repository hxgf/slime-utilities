<?php

/**
 * @package    SLIME Render
 * @version    1.2.0
 * @author     Jonathan Youngblood <jy@hxgf.io>
 * @license    https://github.com/jyoungblood/slime-render/blob/master/LICENSE.md (MIT License)
 * @source     https://github.com/jyoungblood/slime-render
 */

namespace Slime;

use LightnCandy\LightnCandy;
use Slim\Views\Twig;

class render {

	// render data as json string
  public static function json($req, $res, $args){
    $data = [];
    if (isset($args['data'])){
      $data = $args['data'];
    }
    $status = 200;
    if (isset($args['status'])){
      $status = $args['status'];
    }
    $res->getBody()->write(json_encode($data));
    return $res->withHeader('content-type', 'application/json')->withStatus($status);
  }

  // return a rendered Twig template
  public static function twig($req, $res, $args){
    $data = [];
    $data['locals'] = $GLOBALS['locals'];
    if (isset($args['data'])){
      $data = array_merge($data, $args['data']);
    }
    if (isset($args['title'])){
      $data['title'] = $args['title'];
    }
    return Twig::fromRequest($req)->render($res, $args['template'] . '.html', $data);
  }

  // define custom helpers
  public static function initialize_handlebars_helpers(){

    $GLOBALS['hbars_helpers']['date'] = function ($arg1, $arg2) {
      if ($arg1 == "now"){
        return date($arg2);
      }else{
        return date($arg2, $arg1);
      }
    };

    $GLOBALS['hbars_helpers']['is'] = function ($l, $operator, $r, $options) {
      if ($operator == '=='){
        $condition = ($l == $r);
      }
      if ($operator == '==='){
        $condition = ($l === $r);
      }
      if ($operator == 'not' || $operator == '!='){
        $condition = ($l != $r);
      }	
      if ($operator == '<'){
        $condition = ($l < $r);
      }
      if ($operator == '>'){
        $condition = ($l > $r);
      }
      if ($operator == '<='){
        $condition = ($l <= $r);
      }
      if ($operator == '>='){
        $condition = ($l >= $r);
      }
      if ($operator == 'in'){
        if (gettype($r) == 'array'){
          $condition = (in_array($l, $r));
        }else{
          // expects a csv string
          $condition = (in_array($l, str_getcsv($r)));
        }
      }
      if ($operator == 'typeof'){
        $condition = (gettype($l) == gettype($r));
      }
      if ($condition){
        return $options['fn']();
      }else{
        return $options['inverse']();
      }
    }; 
            
  }

  // render a LightnCandy template, compiled with HBS settings
  public static function lightncandy_html($args){
    $template_path = isset($GLOBALS['settings']['templates']['path']) ? $GLOBALS['settings']['templates']['path'] : 'templates';
    $template_extension = isset($GLOBALS['settings']['templates']['extension']) ? $GLOBALS['settings']['templates']['extension'] : 'html';
    $template = file_get_contents( './' . $template_path .'/'. $args['template'] . '.' . $template_extension );
    if (isset($args['layout'])){
      $layout = explode('{{outlet}}', file_get_contents( './' . $template_path .'/'. $args['layout'] . '.' . $template_extension ));
      $template = $layout[0] . $template . $layout[1];
    }
    preg_match_all('/{{> ([^}}]+)/', $template, $partial_handles);
    $partials = [];
    foreach ($partial_handles[1] as $handle){
      $partials[$handle] = file_get_contents( './' . $template_path .'/'. $handle . '.' . $template_extension );        
    }
    render::initialize_handlebars_helpers();
    return LightnCandy::prepare(
      LightnCandy::compile($template, array(
        "flags" => LightnCandy::FLAG_ELSE | LightnCandy::FLAG_PARENT,
        "partials" => $partials,
        "helpers" => $GLOBALS['hbars_helpers']
      ))
    );
  }

  // return a rendered LightnCandy/HBS template
  public static function hbs($req, $res, $args){
    $data = [];
    if (isset($GLOBALS['locals'])){
      $data['locals'] = $GLOBALS['locals'];
    }
    if (isset($args['data'])){
      $data = array_merge($data, $args['data']);
    }
    if (isset($args['title'])){
      $data['title'] = $args['title'];
    }
    $body = $res->getBody();
    $body->write(render::lightncandy_html($args)($data));
    return $res->withStatus(isset($args['status']) ? $args['status'] : 200);
  }

  // return a url redirect
  public static function redirect($req, $res, $args){
    $args['status'] = isset($args['status']) ? $args['status'] : 301;
    return $res->withHeader('Location', $args['location'])->withStatus($args['status']);
  }

}

?>