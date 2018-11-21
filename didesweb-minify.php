<?php

  /* Didesweb Minify Html */
  
  if ( !is_admin() ) {
    if ( !( defined( 'WP_CLI' ) ) ) { add_action( 'init', ob_start('didesweb_optimize_output'), 1 ); }
  }
  function didesweb_optimize_output($output) {
    if ( substr( ltrim( $output ), 0, 5) == '<?xml' ) { return ( $output ); }
    if ( mb_detect_encoding($output, 'UTF-8', true) ) { $mod = '/u'; } else{ $mod = '/s'; }
    $output = str_replace(array (chr(13) . chr(10), chr(9)), array (chr(10), ''), $output);
    $output = str_replace(array ('<script', '/script>', '<pre', '/pre>', '<textarea', '/textarea>', '<style', '/style>'), array ('<script', '/script>', '<pre', '/pre>', '<textarea', '/textarea>', '<style', '/style>'), $output);
    $split = explode('optimize', $output);
    $output = ''; 
    for ($i=0; $i<count($split); $i++) {
      $code = $split[$i];
      $asis = '';
      $code = preg_replace(array ('/\>[^\S ]+' . $mod, '/[^\S ]+\<' . $mod, '/(\s)+' . $mod), array('>', '<', '\\1'), $code);
      $code = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->' . $mod, '', $code);
      $output .= $code.$asis;
    }
    if (strtolower( substr( ltrim( $output ), 0, 15 ) ) == '<!doctype html>' ) {
      $output = str_replace( ' />', '>', $output );
      $output = str_replace('woocommerce' , 'shop', $output );
      $output = str_replace('class="wp-' , 'class="', $output );
      $output = str_replace('> <' , '><', $output );
      $output = str_replace( array( 'http://', 'https://' ), '//', $output );
    }
    return ($output);
  }