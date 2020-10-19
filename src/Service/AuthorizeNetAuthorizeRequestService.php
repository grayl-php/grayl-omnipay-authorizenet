<?php

   namespace Grayl\Omnipay\AuthorizeNet\Service;

   use Grayl\Omnipay\AuthorizeNet\Entity\AuthorizeNetAuthorizeRequestData;
   use Grayl\Omnipay\AuthorizeNet\Entity\AuthorizeNetAuthorizeResponseData;
   use Grayl\Omnipay\AuthorizeNet\Entity\AuthorizeNetGatewayData;
   use Grayl\Omnipay\Common\Service\OmnipayRequestServiceAbstract;
   use Omnipay\AuthorizeNetApi\Message\Response;

   /**
    * Class AuthorizeNetAuthorizeRequestService
    * The service for working with the AuthorizeNet authorize requests
    *
    * @package Grayl\Omnipay\AuthorizeNet
    */
   class AuthorizeNetAuthorizeRequestService extends OmnipayRequestServiceAbstract
   {

      /**
       * Sends a AuthorizeNetAuthorizeRequestData object to the AuthorizeNet gateway and returns a response
       *
       * @param AuthorizeNetGatewayData          $gateway_data A configured AuthorizeNetGatewayData entity to send the request through
       * @param AuthorizeNetAuthorizeRequestData $request_data The AuthorizeNetAuthorizeRequestData entity to send
       *
       * @return AuthorizeNetAuthorizeResponseData
       * @throws \Exception
       */
      public function sendRequestDataEntity ( $gateway_data,
                                              $request_data ): object
      {

         // Use the abstract class function to send the authorize request and return a response
         return $this->sendAuthorizeRequestData( $gateway_data,
                                                 $request_data );
      }


      /**
       * Creates a new AuthorizeNetAuthorizeResponseData object to handle data returned from the gateway
       *
       * @param Response $api_response The response entity received from a gateway
       * @param string   $gateway_name The name of the gateway
       * @param string   $action       The action performed in this response (authorize, capture, etc.)
       * @param string[] $metadata     Extra data associated with this response
       *
       * @return AuthorizeNetAuthorizeResponseData
       */
      public function newResponseDataEntity ( $api_response,
                                              string $gateway_name,
                                              string $action,
                                              array $metadata ): object
      {

         // Return a new AuthorizeNetAuthorizeResponseData entity
         return new AuthorizeNetAuthorizeResponseData( $api_response,
                                                       $gateway_name,
                                                       $action,
                                                       $metadata[ 'amount' ] );
      }

   }