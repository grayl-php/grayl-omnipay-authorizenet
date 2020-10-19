<?php

   namespace Grayl\Omnipay\AuthorizeNet\Entity;

   use Grayl\Omnipay\Common\Entity\OmnipayGatewayDataAbstract;
   use Omnipay\AuthorizeNetApi\ApiGateway;

   /**
    * Class AuthorizeNetGatewayData
    * This entity for the AuthorizeNet API
    * @method void __construct( ApiGateway $api, string $gateway_name, string $environment )
    * @method void setAPI( ApiGateway $api )
    * @method ApiGateway getAPI()
    *
    * @package Grayl\Omnipay\AuthorizeNet
    */
   class AuthorizeNetGatewayData extends OmnipayGatewayDataAbstract
   {

      /**
       * Fully configured Omnipay AuthorizeNet gateway entity
       *
       * @var ApiGateway
       */
      protected $api;

   }