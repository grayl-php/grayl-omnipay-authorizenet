<?php

   namespace Grayl\Omnipay\AuthorizeNet;

   use Grayl\Mixin\Common\Traits\StaticTrait;
   use Grayl\Omnipay\AuthorizeNet\Config\AuthorizeNetAPIEndpoint;
   use Grayl\Omnipay\AuthorizeNet\Config\AuthorizeNetConfig;
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
    * @method AuthorizeNetGatewayData getSavedGatewayDataEntity ( string $api_endpoint_id )
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
      protected string $config_file = 'omnipay-authorizenet.php';

      /**
       * The AuthorizeNetConfig instance for this gateway
       *
       * @var AuthorizeNetConfig
       */
      protected $config;


      /**
       * Creates a new Omnipay ApiGateway object for use in a AuthorizeNetGatewayData entity
       *
       * @param AuthorizeNetAPIEndpoint $api_endpoint A AuthorizeNetAPIEndpoint with credentials needed to create a gateway API object
       *
       * @return ApiGateway
       * @throws \Exception
       */
      public function newGatewayAPI ( $api_endpoint ): object
      {

         // Create the Omnipay AuthorizeNetGatewayData api entity
         /* @var $api ApiGateway */
         $api = Omnipay::create( 'AuthorizeNetApi_Api' );

         // Set the credentials into the API
         $api->setAuthName( $api_endpoint->getLoginID() );
         $api->setTransactionKey( $api_endpoint->getTransactionKey() );

         // Return the new instance
         return $api;
      }


      /**
       * Creates a new AuthorizeNetGatewayData entity
       *
       * @param string $api_endpoint_id The API endpoint ID to use (typically "default" if there is only one API gateway)
       *
       * @return AuthorizeNetGatewayData
       * @throws \Exception
       */
      public function newGatewayDataEntity ( string $api_endpoint_id ): object
      {

         // Grab the gateway service
         $service = new AuthorizeNetGatewayService();

         // Get a new API
         $api = $this->newGatewayAPI( $service->getAPIEndpoint( $this->config,
                                                                $this->environment,
                                                                $api_endpoint_id ) );

         // Configure the API as needed using the service
         $service->configureAPI( $api,
                                 $this->environment );

         // Return the gateway
         return new AuthorizeNetGatewayData( $api,
                                             $this->config->getGatewayName(),
                                             $this->environment );
      }


      /**
       * Creates a new AuthorizeNetAuthorizeRequestController entity
       *
       * @param string  $transaction_id     The internal transaction ID
       * @param float   $amount             The amount to charge
       * @param string  $currency           The base currency of the amount
       * @param string  $ip_address         The IP of the client
       * @param string  $first_name         The  first name
       * @param string  $last_name          The  last name
       * @param string  $email_address      The email address
       * @param string  $address_1          The address part 1
       * @param ?string $address_2          The address part 2
       * @param string  $city               The city
       * @param string  $state              The state
       * @param string  $postcode           The postcode
       * @param string  $country            The country
       * @param ?string $phone              The phone
       * @param string  $credit_card_number The credit card number
       * @param string  $expiry_month       The credit card expiry month (2 digit)
       * @param string  $expiry_year        The credit card expiry year (4 digit)
       * @param string  $cvv                The credit card CVV
       *
       * @return AuthorizeNetAuthorizeRequestController
       * @throws \Exception
       */
      public function newAuthorizeNetAuthorizeRequestController ( string $transaction_id,
                                                                  float $amount,
                                                                  string $currency,
                                                                  string $ip_address,
                                                                  string $first_name,
                                                                  string $last_name,
                                                                  string $email_address,
                                                                  string $address_1,
                                                                  ?string $address_2,
                                                                  string $city,
                                                                  string $state,
                                                                  string $postcode,
                                                                  string $country,
                                                                  ?string $phone,
                                                                  string $credit_card_number,
                                                                  string $expiry_month,
                                                                  string $expiry_year,
                                                                  string $cvv ): AuthorizeNetAuthorizeRequestController
      {

         // Create the AuthorizeNetQueryRequestData entity
         $request_data = new AuthorizeNetAuthorizeRequestData( 'authorize',
                                                               $this->getOffsiteURLs() );

         // Set the order parameters
         $request_data->setTransactionID( $transaction_id );
         $request_data->setAmount( $amount );
         $request_data->setCurrency( $currency );
         $request_data->setClientIP( $ip_address );

         // Set the customer parameters
         $request_data->setFirstName( $first_name );
         $request_data->setLastName( $last_name );
         $request_data->setEmail( $email_address );
         $request_data->setAddress1( $address_1 );
         $request_data->setAddress2( $address_2 );
         $request_data->setCity( $city );
         $request_data->setState( $state );
         $request_data->setPostcode( $postcode );
         $request_data->setCountry( $country );
         $request_data->setPhone( $phone );

         // Set the credit card parameters
         $request_data->setCreditCardNumber( $credit_card_number );
         $request_data->setCreditCardExpiryMonth( $expiry_month );
         $request_data->setCreditCardExpiryYear( $expiry_year );
         $request_data->setCreditCardCVV( $cvv );

         // Return a new AuthorizeNetQueryRequestController entity
         return new AuthorizeNetAuthorizeRequestController( $this->getSavedGatewayDataEntity( 'default' ),
                                                            $request_data,
                                                            new AuthorizeNetAuthorizeRequestService(),
                                                            new AuthorizeNetAuthorizeResponseService() );
      }


      /**
       * Creates a new AuthorizeNetCaptureRequestController entity
       *
       * @param string $transaction_id The internal transaction ID
       * @param float  $amount         The amount to charge
       * @param string $currency       The base currency of the amount
       * @param string $ip_address     The IP of the client
       * @param string $reference_id   A reference ID from a previously submitted PayflowAuthorizeRequestController
       *
       * @return AuthorizeNetCaptureRequestController
       * @throws \Exception
       */
      public function newAuthorizeNetCaptureRequestController ( string $transaction_id,
                                                                float $amount,
                                                                string $currency,
                                                                string $ip_address,
                                                                string $reference_id ): AuthorizeNetCaptureRequestController
      {

         // Create the AuthorizeNetQueryRequestData entity
         $request_data = new AuthorizeNetCaptureRequestData( 'capture',
                                                             $this->getOffsiteURLs() );

         // Set the order parameters
         $request_data->setTransactionID( $transaction_id );
         $request_data->setAmount( $amount );
         $request_data->setCurrency( $currency );
         $request_data->setClientIP( $ip_address );
         $request_data->setTransactionReference( $reference_id );

         // Return a new AuthorizeNetQueryRequestController entity
         return new AuthorizeNetCaptureRequestController( $this->getSavedGatewayDataEntity( 'default' ),
                                                          $request_data,
                                                          new AuthorizeNetCaptureRequestService(),
                                                          new AuthorizeNetCaptureResponseService() );
      }

   }