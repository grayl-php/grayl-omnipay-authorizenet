<?php

   namespace Grayl\Omnipay\AuthorizeNet\Controller;

   use Grayl\Omnipay\AuthorizeNet\Entity\AuthorizeNetCaptureRequestData;
   use Grayl\Omnipay\AuthorizeNet\Entity\AuthorizeNetCaptureResponseData;
   use Grayl\Omnipay\Common\Controller\OmnipayRequestControllerAbstract;

   /**
    * Class AuthorizeNetCaptureRequestController
    * The controller for working with AuthorizeNetCaptureRequestData entities
    * @method AuthorizeNetCaptureRequestData getRequestData()
    * @method AuthorizeNetCaptureResponseController sendRequest()
    *
    * @package Grayl\Omnipay\AuthorizeNet
    */
   class AuthorizeNetCaptureRequestController extends OmnipayRequestControllerAbstract
   {

      /**
       * Creates a new AuthorizeNetCaptureResponseController to handle data returned from the gateway
       *
       * @param AuthorizeNetCaptureResponseData $response_data The AuthorizeNetCaptureResponseData entity received from the gateway
       *
       * @return AuthorizeNetCaptureResponseController
       */
      public function newResponseController ( $response_data ): object
      {

         // Return a new AuthorizeNetCaptureResponseController entity
         return new AuthorizeNetCaptureResponseController( $response_data,
                                                           $this->response_service );
      }

   }