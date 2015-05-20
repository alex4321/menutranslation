<?php

namespace Drupal\menutranslation\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Menu\MenuLinkInterface;

class MenuTranslationForm extends FormBase {
  private $link = NULL;

  /*
   * {@inheritdoc}
   */
  public function getFormId() {
    return "menutranslation_form";
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $menu_link=NULL) {
    $this->link = $menu_link;

    $form['original'] = [
      '#type' => 'fieldset',
      'item' => [
        '#markup' => sprintf(t('Original title is %s'), $menu_link->getTitle())
      ]
    ];
    $form['translations'] = [
      '#type' => 'fieldset',
      '#title' => t('Translations'),
    ];

    $language_manager = \Drupal::languageManager();
    $translation_config = \Drupal\menutranslation\Config\MenuTranslationConfig::get();
    foreach($language_manager->getLanguages() as $code => $language) {
      $form['translations'][$code] = [
        '#type' => 'textfield',
        '#title' => $language->getName(),
        '#value' => $translation_config->get_translated($menu_link, $language)
      ];
    }
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => t('Submit')
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    //kint($this->link);
    $language_manager = \Drupal::languageManager();
    $translation_config = \Drupal\menutranslation\Config\MenuTranslationConfig::get();
    foreach($language_manager->getLanguages() as $code => $language) {
      $value = $form_state->getValues()['translations'][$code];
      if($value == NULL) { //TODO: WTF?
        $value = $_POST[$code];
      }
      if($value != NULL) {
        $translation_config->translate($this->link, $language, $value);
      }
    }
  }
}