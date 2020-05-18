<?php
/**
 * @file
 * Contains \Drupal\admin_user_path\Form\AdminUserSettingsForm
 */
namespace Drupal\admin_user_path\Form;

use Drupal\Component\Utility\UrlHelper;
use \Drupal\Core\Form\ConfigFormBase;
use \Symfony\Component\HttpFoundation\Request;
use \Drupal\Core\Form\FormStateInterface;

/**
 * Defines a form to configure Admin User Path settings
 */
class AdminUserSettingsForm extends ConfigFormBase
{

  /**
   * Gets the configuration names that will be editable.
   *
   * @return array
   *   An array of configuration object names that are editable if called in
   *   conjunction with the trait's config() method.
   */
  protected function getEditableConfigNames()
  {
    return [
      'admin_user_path.settings'
    ];
  }

  /**
   * Returns a unique string identifying the form.
   *
   * The returned ID should be a unique string that can be a valid PHP function
   * name, since it's used in hook implementation names such as
   * hook_form_FORM_ID_alter().
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId()
  {
    return 'admin_user_path_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, Request $request = NULL) {

    $config = $this->config('admin_user_path.settings');

    $form['admin_user_path'] = [
      '#type' => 'details',
      '#title' => 'Admin User Path Config',
      '#open' => TRUE,
    ];
    $form['admin_user_path']['user_url_pattern'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('The pattern of user pages'),
      '#default_value' => $config->get('user_url_pattern'),
    );
    $form['admin_user_path']['admin_theme'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Use admin theme'),
      '#default_value' => $config->get('admin_theme'),
    );
    return parent::buildForm($form,$form_state);
  }

  /**
   * {@inheritdoc}
   */
  function validateForm(array &$form, FormStateInterface $form_state)
  {
    $value = $form_state->getValue('user_url_pattern');
    if (!UrlHelper::isValid($value)) {
      $form_state->setErrorByName('user_url_pattern', t('The pattern of user pages is not valid.'));
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $this->config('admin_user_path.settings')
      ->set('user_url_pattern', $form_state->getValue('user_url_pattern'))
      ->set('admin_theme', $form_state->getValue('admin_theme'))
      ->save();
    drupal_flush_all_caches();
    parent::submitForm($form, $form_state);
  }
}
