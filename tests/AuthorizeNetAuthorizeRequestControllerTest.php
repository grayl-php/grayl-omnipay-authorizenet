<?php

   namespace Grayl\Test\Omnipay\AuthorizeNet;

   use Grayl\Gateway\PDO\PDOPorter;
   use Grayl\Omnipay\AuthorizeNet\AuthorizeNetPorter;
   use Grayl\Omnipay\AuthorizeNet\Controller\AuthorizeNetAuthorizeRequestController;
   use Grayl\Omnipay\AuthorizeNet\Controller\AuthorizeNetAuthorizeResponseController;
   use Grayl\Omnipay\AuthorizeNet\Controller\AuthorizeNetCaptureRequestController;
   use Grayl\Omnipay\AuthorizeNet\Controller\AuthorizeNetCaptureResponseController;
   use Grayl\Omnipay\AuthorizeNet\Entity\AuthorizeNetGatewayData;
   use Grayl\Omnipay\Common\Entity\OmnipayGatewayCreditCard;
   use PHPUnit\Framework\TestCase;

   /**
    * Test class for the AuthorizeNet package
    *
    * @package Grayl\Omnipay\AuthorizeNet
    */
   class AuthorizeNetAuthorizeRequestControllerTest extends TestCase
   {

      /**
       * Test setup for sandbox environment
       */
      public static function setUpBeforeClass (): void
      {

         // Change the PDO API to sandbox mode
         PDOPorter::getInstance()
                  ->setEnvironment( 'sandbox' );

         // Change the API environment to sandbox mode
         AuthorizeNetPorter::getInstance()
                           ->setEnvironment( 'sandbox' );
      }


      /**
       * Tests the creation of a AuthorizeNetGatewayData
       *
       * @throws \Exception
       */
      public function testCreateAuthorizeNetGatewayData (): void
      {

         // Create the object
         $gateway = AuthorizeNetPorter::getInstance()
                                      ->getSavedGatewayDataEntity( 'default' );

         // Check the type of object returned
         $this->assertInstanceOf( AuthorizeNetGatewayData::class,
                                  $gateway );
      }


      /**
       * Creates a test OmnipayGatewayCreditCard object to be used in a test
       *
       * @return OmnipayGatewayCreditCard
       */
      public function testCreateOmnipayGatewayCreditCard (): OmnipayGatewayCreditCard
      {

         // Create the OmnipayGatewayCreditCard
         $credit_card = AuthorizeNetPorter::getInstance()
                                          ->newOmnipayGatewayCreditCard( '4111111111111111',
                                                                         12,
                                                                         2025,
                                                                         '869' );

         // Check the type of object returned
         $this->assertInstanceOf( OmnipayGatewayCreditCard::class,
                                  $credit_card );

         // Return the object
         return $credit_card;
      }


      /**
       * Tests the creation of an AuthorizeNetAuthorizeRequestController object
       *
       * @param OmnipayGatewayCreditCard $credit_card A configured OmnipayGatewayCreditCard entity with payment information
       *
       * @depends testCreateOmnipayGatewayCreditCard
       * @return AuthorizeNetAuthorizeRequestController
       * @throws \Exception
       */
      public function testCreateAuthorizeNetAuthorizeRequestController ( OmnipayGatewayCreditCard $credit_card ): AuthorizeNetAuthorizeRequestController
      {

         // Create the object
         $request = AuthorizeNetPorter::getInstance()
                                      ->newAuthorizeNetAuthorizeRequestController( 'test-' . time(),
                                                                                   100.00,
                                                                                   'USD',
                                                                                   $_SERVER[ 'REMOTE_ADDR' ],
                                                                                   'John',
                                                                                   'Doe',
                                                                                   'johndoe@fake.com',
                                                                                   '1234 Fake Rd.',
                                                                                   '#3307',
                                                                                   'Las Vegas',
                                                                                   '89131',
                                                                                   'NV',
                                                                                   'US',
                                                                                   null,
                                                                                   $credit_card->getNumber(),
                                                                                   $credit_card->getExpiryMonth(),
                                                                                   $credit_card->getExpiryYear(),
                                                                                   $credit_card->getCVV() );

         // Check the type of object returned
         $this->assertInstanceOf( AuthorizeNetAuthorizeRequestController::class,
                                  $request );

         // Check the total
         $this->assertEquals( 100.00,
                              $request->getRequestData()
                                      ->getAmount() );

         // Return the object
         return $request;
      }


      /**
       * Performs an authorization using a request
       *
       * @param AuthorizeNetAuthorizeRequestController $request A configured AuthorizeNetAuthorizeRequestController to send
       *
       * @depends testCreateAuthorizeNetAuthorizeRequestController
       * @return AuthorizeNetAuthorizeResponseController
       * @throws \Exception
       */
      public function testSendAuthorizeNetAuthorizeRequestController ( AuthorizeNetAuthorizeRequestController $request ): AuthorizeNetAuthorizeResponseController
      {

         // Authorize the payment
         $response = $request->sendRequest();

         // Check the type of object returned
         $this->assertInstanceOf( AuthorizeNetAuthorizeResponseController::class,
                                  $response );

         // Return the response
         return $response;
      }


      /**
       * Checks an AuthorizeNetAuthorizeResponseController for data and errors
       *
       * @param AuthorizeNetAuthorizeResponseController $response An AuthorizeNetAuthorizeResponseController returned from the gateway
       *
       * @depends testSendAuthorizeNetAuthorizeRequestController
       */
      public function testAuthorizeNetAuthorizeResponseController ( AuthorizeNetAuthorizeResponseController $response ): void
      {

         // Make sure it worked
         $this->assertTrue( $response->isSuccessful() );
         $this->assertFalse( $response->isPending() );
         $this->assertNotNull( $response->getReferenceID() );
         $this->assertNotNull( $response->getAVSCode() );
         $this->assertNotNull( $response->getCVVCode() );
         $this->assertNotNull( $response->getAmount() );

      }


      /**
       * Tests the creation of an AuthorizeNetCaptureRequestController object
       *
       * @param AuthorizeNetAuthorizeResponseController $auth_response A PayflowAuthorizeResponseController returned from the gateway
       *
       * @depends testSendAuthorizeNetAuthorizeRequestController
       * @return AuthorizeNetCaptureRequestController
       * @throws \Exception
       */
      public function testCreateAuthorizeNetCaptureRequestController ( AuthorizeNetAuthorizeResponseController $auth_response ): AuthorizeNetCaptureRequestController
      {

         // Create the object
         $request = AuthorizeNetPorter::getInstance()
                                      ->newAuthorizeNetCaptureRequestController( 'test-' . time(),
                                                                                 100.00,
                                                                                 'USD',
                                                                                 $_SERVER[ 'REMOTE_ADDR' ],
                                                                                 $auth_response->getReferenceID() );

         // Check the type of object returned
         $this->assertInstanceOf( AuthorizeNetCaptureRequestController::class,
                                  $request );

         // Check the total
         $this->assertEquals( 100.00,
                              $request->getRequestData()
                                      ->getAmount() );

         // Return the object
         return $request;
      }


      /**
       * Performs a capture using a request
       *
       * @param AuthorizeNetCaptureRequestController $request A configured AuthorizeNetAuthorizeRequestData to send
       *
       * @depends      testCreateAuthorizeNetCaptureRequestController
       * @return AuthorizeNetCaptureResponseController
       * @throws \Exception
       */
      public function testSendAuthorizeNetCaptureRequestController ( AuthorizeNetCaptureRequestController $request ): AuthorizeNetCaptureResponseController
      {

         // Capture the payment
         $response = $request->sendRequest();

         // Check the type of object returned
         $this->assertInstanceOf( AuthorizeNetCaptureResponseController::class,
                                  $response );

         // Return the response
         return $response;
      }


      /**
       * Checks an AuthorizeNetCaptureResponseController for data and errors
       *
       * @param AuthorizeNetCaptureResponseController $response An AuthorizeNetCaptureResponseController returned from the gateway
       *
       * @depends testSendAuthorizeNetCaptureRequestController
       */
      public function testAuthorizeNetCaptureResponseController ( AuthorizeNetCaptureResponseController $response ): void
      {

         // Make sure it worked
         $this->assertTrue( $response->isSuccessful() );
         $this->assertFalse( $response->isPending() );
         $this->assertNotNull( $response->getReferenceID() );
      }

   }