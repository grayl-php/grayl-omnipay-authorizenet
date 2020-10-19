<?php

   namespace Grayl\Omnipay\AuthorizeNet\Service;

   use Grayl\Omnipay\AuthorizeNet\Entity\AuthorizeNetCaptureRequestData;
   use Grayl\Omnipay\AuthorizeNet\Entity\AuthorizeNetCaptureResponseData;
   use Grayl\Omnipay\AuthorizeNet\Entity\AuthorizeNetGatewayData;
   use Grayl\Omnipay\Common\Service\OmnipayRequestServiceAbstract;
   use Omnipay\AuthorizeNetApi\Message\Response;

   /**
    * Class AuthorizeNetCaptureRequestService
    * The service for working with the AuthorizeNet capture requests
    *
    * @package Grayl\Omnipay\AuthorizeNet
    */
   class AuthorizeNetCaptureRequestService extends OmnipayRequestServiceAbstract
   {

      /**
       * Sends a AuthorizeNetCaptureRequestData object to the AuthorizeNet gateway and returns a response
       *
       * @param AuthorizeNetGatewayData        $gateway_data A configured AuthorizeNetGatewayData entity to send the request through
       * @param AuthorizeNetCaptureRequestData $request_data The AuthorizeNetCaptureRequestData entity to send
       *
       * @return AuthorizeNetCaptureResponseData
       * @throws \Exception
       */
      public function sendRequestDataEntity ( $gateway_data,
                                              $request_data ): object
      {

         // Use the abstract class function to send the capture request and return a response
         return $this->sendCaptureRequestData( $gateway_data,
                                               $request_data );
      }


      /**
       * Creates a new AuthorizeNetCaptureResponseData object to handle data returned from the gateway
       *
       * @param Response $api_response The response entity received from a gateway
       * @param string   $gateway_name The name of the gateway
       * @param string   $action       The action performed in this response (authorize, capture, etc.)
       * @param string[] $metadata     Extra data associated with this response
       *
       * @return AuthorizeNetCaptureResponseData
       */
      public function newResponseDataEntity ( $api_response,
                                              string $gateway_name,
                                              string $action,
                                              array $metadata ): object
      {

         // Return a new AuthorizeNetCaptureResponseData entity
         return new AuthorizeNetCaptureResponseData( $api_response,
                                                     $gateway_name,
                                                     $action,
                                                     $metadata[ 'amount' ] );
      }

   }