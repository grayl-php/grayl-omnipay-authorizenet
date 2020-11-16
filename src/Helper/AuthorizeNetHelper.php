<?php

   namespace Grayl\Omnipay\AuthorizeNet\Helper;

   use Grayl\Mixin\Common\Traits\StaticTrait;
   use Grayl\Omnipay\Common\Helper\OmnipayHelperCreditCardAbstract;

   /**
    * A package of functions for working with various AuthorizeNet objects
    * These are kept isolated to maintain separation between the main library and specific user functionality
    *
    * @package Grayl\Omnipay\AuthorizeNet
    */
   class AuthorizeNetHelper extends OmnipayHelperCreditCardAbstract
   {

      // Use the static instance trait
      use StaticTrait;

      // No overrides to the abstract class

   }