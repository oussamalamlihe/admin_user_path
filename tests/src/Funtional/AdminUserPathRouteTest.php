<?php
/**
 * Created by PhpStorm.
 * User: Oussama Lamlihe
 * Date: 16/05/2020
 * Time: 15:51
 */

namespace Drupal\Tests\admin_user_path\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Tests the functionality of the Admin User Path route subscriber.
 *
 * @group admin_user_path
 */

class AdminUserPathRouteTest extends BrowserTestBase {
  /**
   * Modules to install.
   *
   * @var string[]
   */
  public static $modules = ['admin_user_path'];

  /**
   * Asserts that login routes are correctly marked as admin routes.
   */
  public function testAdminRoute() {
    $login_routes = ['user.login', 'user.register', 'user.pass'];

    foreach ($login_routes as $route_name) {
      $route = \Drupal::service('router.route_provider')->getRouteByName($route_name);
      $is_admin = \Drupal::service('router.admin_context')->isAdminRoute($route);
      $this->assertTrue($is_admin, t('Admin route correctly marked for "@title" page.', ['@title' => $route->getDefault('_title')]));
    }
  }
}
