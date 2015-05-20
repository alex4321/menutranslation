<?php

namespace Drupal\menutranslation\Form;
use Drupal\Core\Form\FormStateInterface;
use Drupal\menu_link_content\Entity\MenuLinkContent;

class MenuEntityTranslationForm extends MenuTranslationForm {
  public function buildForm(array $form, FormStateInterface $form_state, $id=NULL) {
    $item = entity_load("menu_link_content", $id);
    return parent::buildForm($form, $form_state, $item);
  }
}