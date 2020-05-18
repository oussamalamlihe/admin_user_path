<?php
/**
 * @file
 * Contains \Drupal\admin_user_path\Routing\UserAlterRouting
 */

namespace Drupal\admin_user_path\Routing;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events.
 */
class UserAlterRouting extends RouteSubscriberBase
{
  /**
   * @var ConfigFactoryInterface $config_factory.
   */
  protected $config_factory;

  /**
   * UserAlterRouting constructor.
   * @param ConfigFactoryInterface $config_factory
   */
  function __construct(ConfigFactoryInterface $config_factory)
  {
    $this->config_factory = $config_factory;
  }


  /**
   * Alters existing routes for a specific collection.
   *
   * @param \Symfony\Component\Routing\RouteCollection $collection
   *   The route collection for adding routes.
   */
  protected function alterRoutes(RouteCollection $collection)
  {
    // The routes we want alter.
    $user_routes = [
      'login' => 'user.login',
      'logout' => 'user.logout',
      'register' => 'user.register',
      'pass' => 'user.pass',
      '{user}' => 'entity.user.canonical',
      '{user}/shortcuts' => 'shortcut.set_switch',
      '{user}/edit' => 'entity.user.edit_form',
    ];
    $config = $this->config_factory->get('admin_user_path.settings');

    foreach ($user_routes as $name => $route) {
      $tmp_route = $collection->get($route);
      if ($config->get('user_url_pattern') != '') {
      $tmp_route->setPath($config->get('user_url_pattern') . $name);
      }
      if ($config->get('admin_theme')) {
        $tmp_route->hasRequirement()->addRequirements()->addDefaults()->getRequirement()->setOption('_admin_route', TRUE);
      }
    }
  }
}
