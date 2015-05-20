<?php

namespace Drupal\menutranslation\Config;
use Drupal\Core\Menu\MenuLinkInterface;
use Drupal\Core\Language;

class MenuTranslationConfig {
  private static $instance = NULL;
  private $config = NULL;
  private $items = NULL;

  private function __construct() {
    $this->config = \Drupal::configFactory()->getEditable('menutranslation.config');
  }

  private function __clone() {}

  public static function get() {
    if(self::$instance == NULL) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  public function get_translated($link, $language) {
    $title = gettype($link) == "string" ? $link : $link->getTitle();
    $language_code = gettype($language) == "string" ? $language : $language->getId();

    return $this->get_translated_string($title, $language_code);
  }

  private function get_translated_string($link, $language) {
    $translations_json = $this->config->get($link);
    if($translations_json!=NULL) {
      $translations = (array)json_decode($translations_json);
      if($translations[$language]!=NULL) {
        return $translations[$language];
      }
      else {
        return NULL;
      }
    }
    else {
      return NULL;
    }
  }

  public function translate($link, $language, $title) {
    $link_title = gettype($link)=="string" ? $link : $link->getTitle();
    $language_code = gettype($language) == "string" ? $language : $language->getId();

    return $this->translate_string($link_title, $language_code, $title);
  }

  private function translate_string($link, $language, $title) {
    $translations_json = $this->config->get($link);
    if($translations_json == NULL) {
      $translations_json = "{}";
    }
    $translations = (array)json_decode($translations_json);
    $translations[$language] = $title;
    $translations_json = json_encode((object)$translations);
    $this->config->set($link, $translations_json);
    $this->config->save();
  }
}