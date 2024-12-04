<?php

define('PLUGIN_BOTOES_VERSION', '1.0.0');

function plugin_version_botoes() {
   return [
      'name'           => 'Botoes',
      'version'        => PLUGIN_BOTOES_VERSION,
      'author'         => 'Adriano Marinho',
      'license'        => 'GPLv2+',
      'homepage'       => 'https://github.com/malakaygames',
      'requirements'   => [
         'glpi' => [
            'min' => '10.0.0',
            'max' => '10.0.99',
         ]
      ]
   ];
}

function plugin_init_botoes() {
    global $PLUGIN_HOOKS;
    
    $PLUGIN_HOOKS['csrf_compliant']['botoes'] = true;
    $PLUGIN_HOOKS['post_item_form']['botoes'] = 'plugin_botoes_post_item_form';
}