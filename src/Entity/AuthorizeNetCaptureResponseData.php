<?php

   namespace Grayl\Omnipay\AuthorizeNet\Entity;

   use Grayl\Omnipay\Common\Entity\OmnipayResponseDataAbstract;
   use Omnipay\AuthorizeNetApi\Message\Response;

   /**
    * Class AuthorizeNetCaptureResponseData
    * The class for working with a capture response from an AuthorizeNetGatewayData
    * @method void __construct( Response $api_response, string $gateway_name, string $action, float $amount )
    * @method void setAPIResponse( Response $api_response )
    * @method Response getAPIResponse()
    *
    * @package Grayl\Omnipay\AuthorizeNet
    */
   class AuthorizeNetCaptureResponseData extends OmnipayResponseDataAbstract
   {

      /**
       * The raw API response entity from the gateway
       *
       * @var Response
       */
      protected $api_response;

   }