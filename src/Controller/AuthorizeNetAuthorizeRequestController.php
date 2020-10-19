<?php

   namespace Grayl\Omnipay\AuthorizeNet\Controller;

   use Grayl\Omnipay\AuthorizeNet\Entity\AuthorizeNetAuthorizeRequestData;
   use Grayl\Omnipay\AuthorizeNet\Entity\AuthorizeNetAuthorizeResponseData;
   use Grayl\Omnipay\Common\Controller\OmnipayRequestControllerAbstract;

   /**
    * Class AuthorizeNetAuthorizeRequestController
    * The controller for working with AuthorizeNetAuthorizeRequestData entities
    * @method AuthorizeNetAuthorizeRequestData getRequestData()
    * @method AuthorizeNetAuthorizeResponseController sendRequest()
    *
    * @package Grayl\Omnipay\AuthorizeNet
    */
   class AuthorizeNetAuthorizeRequestController extends OmnipayRequestControllerAbstract
   {

      /**
       * Creates a new AuthorizeNetAuthorizeResponseController to handle data returned from the gateway
       *
       * @param AuthorizeNetAuthorizeResponseData $response_data The AuthorizeNetAuthorizeResponseData entity received from the gateway
       *
       * @return AuthorizeNetAuthorizeResponseController
       */
      public function newResponseController ( $response_data ): object
      {

         // Return a new AuthorizeNetAuthorizeResponseController entity
         return new AuthorizeNetAuthorizeResponseController( $response_data,
                                                             $this->response_service );
      }

   }