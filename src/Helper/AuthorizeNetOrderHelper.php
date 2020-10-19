<?php

   namespace Grayl\Omnipay\AuthorizeNet\Helper;

   use Grayl\Mixin\Common\Traits\StaticTrait;
   use Grayl\Omnipay\AuthorizeNet\AuthorizeNetPorter;
   use Grayl\Omnipay\AuthorizeNet\Controller\AuthorizeNetAuthorizeRequestController;
   use Grayl\Omnipay\AuthorizeNet\Controller\AuthorizeNetCaptureRequestController;
   use Grayl\Omnipay\Common\Entity\OmnipayGatewayCreditCard;
   use Grayl\Omnipay\Common\Helper\OmnipayOrderHelperAbstract;
   use Grayl\Store\Order\Controller\OrderController;

   /**
    * A package of functions for working with AuthorizeNet and orders
    * These are kept isolated to maintain separation between the main library and specific user functionality
    *
    * @package Grayl\Omnipay\AuthorizeNet
    */
   class AuthorizeNetOrderHelper extends OmnipayOrderHelperAbstract
   {

      // Use the static instance trait
      use StaticTrait;

      /**
       * Creates a new AuthorizeNetAuthorizeRequestController for authorizing a payment using data from an order
       *
       * @param OrderController          $order_controller An OrderController entity to translate from
       * @param OmnipayGatewayCreditCard $credit_card      An Omnipay OmnipayGatewayCreditCard entity to use
       *
       * @return AuthorizeNetAuthorizeRequestController
       * @throws \Exception
       */
      public function newAuthorizeNetAuthorizeRequestControllerFromOrder ( OrderController $order_controller,
                                                                           OmnipayGatewayCreditCard $credit_card ): AuthorizeNetAuthorizeRequestController
      {

         // Create a new AuthorizeNetAuthorizeRequestController for authorizing a payment
         $request = AuthorizeNetPorter::getInstance()
                                      ->newAuthorizeNetAuthorizeRequestController();

         // Translate the OrderController and its sub classes
         $this->translateOrderController( $request->getRequestData(),
                                          $order_controller );

         // Translate the credit card information
         AuthorizeNetHelper::getInstance()
                           ->translateOmnipayGatewayCreditCard( $request->getRequestData(),
                                                                $credit_card );

         // Return the created entity
         return $request;
      }


      /**
       * Creates a new AuthorizeNetCaptureRequestController for capturing a payment using data from an order
       *
       * @param OrderController $order_controller An OrderController entity to translate from
       * @param string          $reference_id     The Omnipay reference ID from a previous authorization
       *
       * @return AuthorizeNetCaptureRequestController
       * @throws \Exception
       */
      public function newAuthorizeNetCaptureRequestControllerFromOrder ( OrderController $order_controller,
                                                                         string $reference_id ): AuthorizeNetCaptureRequestController
      {

         // Create a new AuthorizeNetCaptureRequestController for capturing payment
         $request = AuthorizeNetPorter::getInstance()
                                      ->newAuthorizeNetCaptureRequestController();

         // Translate only the OrderData entity into the capture request, the rest was sent with the authorization
         $this->translateOrderData( $request->getRequestData(),
                                    $order_controller->getOrderData() );

         // Translate the reference ID from the previous response
         AuthorizeNetHelper::getInstance()
                           ->translateOmnipayReferenceID( $request->getRequestData(),
                                                          $reference_id );

         // Return the created entity
         return $request;
      }

   }