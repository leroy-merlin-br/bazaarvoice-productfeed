<?php

namespace BazaarvoiceProductFeed\Elements;

class BrandElement extends ElementBase implements BrandElementInterface {

  public function __construct($external_id, $name) {
    $this->setExternalId($external_id);
    $this->setName($name);
    return $this;
  }

  public function generateXMLArray() {
    $element = parent::generateXMLArray();
    $element['#name'] = 'Brand';
    return $element;
  }

}