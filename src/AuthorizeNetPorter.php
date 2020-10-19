<?php

   namespace Grayl\Omnipay\AuthorizeNet;

   use Grayl\Mixin\Common\Traits\StaticTrait;
   use Grayl\Omnipay\AuthorizeNet\Controller\AuthorizeNetAuthorizeRequestController;
   use Grayl\Omnipay\AuthorizeNet\Controller\AuthorizeNetCaptureRequestController;
   use Grayl\Omnipay\AuthorizeNet\Entity\AuthorizeNetAuthorizeRequestData;
   use Grayl\Omnipay\AuthorizeNet\Entity\AuthorizeNetCaptureRequestData;
   use Grayl\Omnipay\AuthorizeNet\Entity\AuthorizeNetGatewayData;
   use Grayl\Omnipay\AuthorizeNet\Service\AuthorizeNetAuthorizeRequestService;
   use Grayl\Omnipay\AuthorizeNet\Service\AuthorizeNetAuthorizeResponseService;
   use Grayl\Omnipay\AuthorizeNet\Service\AuthorizeNetCaptureRequestService;
   use Grayl\Omnipay\AuthorizeNet\Service\AuthorizeNetCaptureResponseService;
   use Grayl\Omnipay\AuthorizeNet\Service\AuthorizeNetGatewayService;
   use Grayl\Omnipay\Common\OmnipayPorterAbstract;
   use Omnipay\AuthorizeNetApi\ApiGateway;
   use Omnipay\Omnipay;

   /**
    * Front-end for the AuthorizeNet Omnipay package
    * @method AuthorizeNetGatewayData getSavedGatewayDataEntity ( string $endpoint_id )
    *
    * @package Grayl\Omnipay\AuthorizeNet
    */
   class AuthorizeNetPorter extends OmnipayPorterAbstract
   {

      // Use the static instance trait
      use StaticTrait;

      /**
       * The name of the config file for the AuthorizeNet package
       *
       * @var string
       */
      protected string $config_file = 'gateway.omnipay.authorizenet.php';


      /**
       * Creates a new Omnipay ApiGateway object for use in a AuthorizeNetGatewayData entity
       *
       * @param array $credentials An array containing all of the credentials needed to create the gateway API
       *
       * @return ApiGateway
       * @throws \Exception
       */
      public function newGatewayAPI ( array $credentials ): object
      {

         // Create the Omnipay AuthorizeNetGatewayData api entity
         /* @var $api ApiGateway */
         $api = Omnipay::create( 'AuthorizeNetApi_Api' );

         // Set the credentials into the API
         $api->setAuthName( $credentials[ 'login_id' ] );
         $api->setTransactionKey( $credentials[ 'transaction_key' ] );

         // Return the new instance
         return $api;
      }


      /**
       * Creates a new AuthorizeNetGatewayData entity
       *
       * @param string $endpoint_id The API endpoint ID to use (typically "default" is there is only one API gateway)
       *
       * @return AuthorizeNetGatewayData
       * @throws \Exception
       */
      public function newGatewayDataEntity ( string $endpoint_id ): object
      {

         // Grab the gateway service
         $service = new AuthorizeNetGatewayService();

         // Get an API
         $api = $this->newGatewayAPI( $service->getAPICredentials( $this->config,
                                                                   $this->environment,
                                                                   $endpoint_id ) );

         // Configure the API as needed using the service
         $service->configureAPI( $api,
                                 $this->environment );

         // Return the gateway
         return new AuthorizeNetGatewayData( $api,
                                             $this->config->getConfig( 'name' ),
                                             $this->environment );
      }


      /**
       * Creates a new AuthorizeNetAuthorizeRequestController entity
       *
       * @return AuthorizeNetAuthorizeRequestController
       * @throws \Exception
       */
      public function newAuthorizeNetAuthorizeRequestController (): AuthorizeNetAuthorizeRequestController
      {

         // Create the AuthorizeNetQueryRequestData entity
         $request_data = new AuthorizeNetAuthorizeRequestData( 'authorize',
                                                               $this->getOffsiteURLs() );

         // Return a new AuthorizeNetQueryRequestController entity
         return new AuthorizeNetAuthorizeRequestController( $this->getSavedGatewayDataEntity( 'default' ),
                                                            $request_data,
                                                            new AuthorizeNetAuthorizeRequestService(),
                                                            new AuthorizeNetAuthorizeResponseService() );
      }


      /**
       * Creates a new AuthorizeNetCaptureRequestController entity
       *
       * @return AuthorizeNetCaptureRequestController
       * @throws \Exception
       */
      public function newAuthorizeNetCaptureRequestController (): AuthorizeNetCaptureRequestController
      {

         // Create the AuthorizeNetQueryRequestData entity
         $request_data = new AuthorizeNetCaptureRequestData( 'capture',
                                                             $this->getOffsiteURLs() );

         // Return a new AuthorizeNetQueryRequestController entity
         return new AuthorizeNetCaptureRequestController( $this->getSavedGatewayDataEntity( 'default' ),
                                                          $request_data,
                                                          new AuthorizeNetCaptureRequestService(),
                                                          new AuthorizeNetCaptureResponseService() );
      }

   }