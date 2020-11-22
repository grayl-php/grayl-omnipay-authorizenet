<?php

   namespace Grayl\Test\Omnipay\AuthorizeNet;

   use Grayl\Gateway\PDO\PDOPorter;
   use Grayl\Omnipay\AuthorizeNet\AuthorizeNetPorter;
   use Grayl\Omnipay\AuthorizeNet\Controller\AuthorizeNetAuthorizeRequestController;
   use Grayl\Omnipay\AuthorizeNet\Controller\AuthorizeNetAuthorizeResponseController;
   use Grayl\Omnipay\AuthorizeNet\Controller\AuthorizeNetCaptureRequestController;
   use Grayl\Omnipay\AuthorizeNet\Controller\AuthorizeNetCaptureResponseController;
   use Grayl\Omnipay\AuthorizeNet\Entity\AuthorizeNetGatewayData;
   use Grayl\Omnipay\AuthorizeNet\Helper\AuthorizeNetOrderHelper;
   use Grayl\Omnipay\Common\Entity\OmnipayGatewayCreditCard;
   use Grayl\Store\Order\Controller\OrderController;
   use Grayl\Store\Order\OrderPorter;
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
       * Creates a test order object with good data
       * TODO: Change this test data
       *
       * @return OrderController
       * @throws \Exception
       */
      public function testCreateOrderController (): OrderController
      {

         // Create the test object
         $order = OrderPorter::getInstance()
                             ->newOrderController();

         // Check the type of object returned
         $this->assertInstanceOf( OrderController::class,
                                  $order );

         // Basic order data
         $data = $order->getOrderData();
         $data->setAmount( 100.00 );
         $data->setDescription( 'Authorizenet test order' );

         // Customer creation
         $order->setOrderCustomer( OrderPorter::getInstance()
                                              ->newOrderCustomer( $order->getOrderID(),
                                                                  'John',
                                                                  'Doe',
                                                                  'johndoe@fake.com',
                                                                  '1234 Fake Rd.',
                                                                  '#3307',
                                                                  'Las Vegas',
                                                                  '89131',
                                                                  'NV',
                                                                  'US',
                                                                  null ) );

         // Items
         $order->putOrderItem( OrderPorter::getInstance()
                                          ->newOrderItem( $order->getOrderID(),
                                                          'item1',
                                                          'Test Item',
                                                          '2',
                                                          65.58 ) );
         $order->putOrderItem( OrderPorter::getInstance()
                                          ->newOrderItem( $order->getOrderID(),
                                                          'item2',
                                                          'Test Item 2',
                                                          '1',
                                                          12.25 ) );

         // Save the order
         $order->saveOrder();

         // Return the object
         return $order;
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
       * @param OrderController          $order_controller A configured OrderController with order information
       * @param OmnipayGatewayCreditCard $credit_card      A configured OmnipayGatewayCreditCard entity with payment information
       *
       * @depends testCreateOrderController
       * @depends testCreateOmnipayGatewayCreditCard
       * @return AuthorizeNetAuthorizeRequestController
       * @throws \Exception
       */
      public function testCreateAuthorizeNetAuthorizeRequestController ( OrderController $order_controller,
                                                                         OmnipayGatewayCreditCard $credit_card ): AuthorizeNetAuthorizeRequestController
      {

         // Create the object
         $request = AuthorizeNetOrderHelper::getInstance()
                                           ->newAuthorizeNetAuthorizeRequestControllerFromOrder( $order_controller,
                                                                                                 $credit_card );

         // Check the type of object returned
         $this->assertInstanceOf( AuthorizeNetAuthorizeRequestController::class,
                                  $request );

         // Check the total
         $this->assertEquals( 143.41,
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
       * @depends      testCreateAuthorizeNetAuthorizeRequestController
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
       * @return string
       */
      public function testAuthorizeNetAuthorizeResponseController ( AuthorizeNetAuthorizeResponseController $response ): string
      {

         // Make sure it worked
         $this->assertTrue( $response->isSuccessful() );
         $this->assertFalse( $response->isPending() );
         $this->assertNotNull( $response->getReferenceID() );
         $this->assertNotNull( $response->getAVSCode() );
         $this->assertNotNull( $response->getCVVCode() );
         $this->assertNotNull( $response->getAmount() );

         // Return the reference ID
         return $response->getReferenceID();
      }


      /**
       * Creates an OrderPayment object from an AuthorizeNetAuthorizeResponseController and checks it for errors
       *
       * @param OrderController                         $order_controller A configured OrderController with order information
       * @param AuthorizeNetAuthorizeResponseController $response         An AuthorizeNetAuthorizeResponseController returned from the gateway
       *
       * @depends      testCreateOrderController
       * @depends      testSendAuthorizeNetAuthorizeRequestController
       * @return OrderController
       * @throws \Exception
       */
      public function testCreateOrderPaymentFromAuthorizeNetAuthorizeResponseController ( OrderController $order_controller,
                                                                                          AuthorizeNetAuthorizeResponseController $response ): OrderController
      {

         // Create a new OrderPayment record from the authorize response
         AuthorizeNetOrderHelper::getInstance()
                                ->newOrderPaymentFromOmnipayResponseController( $response,
                                                                                $order_controller,
                                                                                null );

         // Grab the created payment
         $payment = $order_controller->getOrderPayment();

         // Test the data
         $this->assertTrue( $payment->isSuccessful() );
         $this->assertNotNull( $payment->getReferenceID() );
         $this->assertEquals( $response->getReferenceID(),
                              $payment->getReferenceID() );
         $this->assertEquals( 'authorize',
                              $payment->getAction() );
         $this->assertEquals( 'authorizenet',
                              $payment->getProcessor() );
         $this->assertEquals( $response->getAmount(),
                              $payment->getAmount() );

         // Return the modified order
         return $order_controller;
      }


      /**
       * Tests the creation of an AuthorizeNetCaptureRequestController object
       *
       * @param OrderController $order_controller A configured OrderController with order information
       * @param string          $reference_id     The Omnipay reference ID from a previous authorization
       *
       * @depends testCreateOrderController
       * @depends testAuthorizeNetAuthorizeResponseController
       * @return AuthorizeNetCaptureRequestController
       * @throws \Exception
       */
      public function testCreateAuthorizeNetCaptureRequestController ( OrderController $order_controller,
                                                                       string $reference_id ): AuthorizeNetCaptureRequestController
      {

         // Create the object
         $request = AuthorizeNetOrderHelper::getInstance()
                                           ->newAuthorizeNetCaptureRequestControllerFromOrder( $order_controller,
                                                                                               $reference_id );

         // Check the type of object returned
         $this->assertInstanceOf( AuthorizeNetCaptureRequestController::class,
                                  $request );

         // Check the total
         $this->assertEquals( 143.41,
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


      /**
       * Creates an OrderPayment from an AuthorizeNetCaptureResponseController capture request and checks it for errors
       *
       * @param OrderController                       $order_controller A configured OrderController with order information
       * @param AuthorizeNetCaptureResponseController $response         An AuthorizeNetCaptureResponseController returned from the gateway
       *
       * @depends      testCreateOrderPaymentFromAuthorizeNetAuthorizeResponseController
       * @depends      testSendAuthorizeNetCaptureRequestController
       * @throws \Exception
       */
      public function testCreateOrderPaymentFromCaptureResponseController ( OrderController $order_controller,
                                                                            AuthorizeNetCaptureResponseController $response ): void
      {

         // Create a new OrderPayment record from the capture response
         AuthorizeNetOrderHelper::getInstance()
                                ->newOrderPaymentFromOmnipayResponseController( $response,
                                                                                $order_controller,
                                                                                null );

         // Make sure the order is paid
         $this->assertTrue( $order_controller->isOrderPaid() );

         // Grab the newly created payment
         $payment = $order_controller->getOrderPayment();

         // Test the data
         $this->assertTrue( $payment->isSuccessful() );
         $this->assertNotNull( $payment->getReferenceID() );
         $this->assertEquals( $response->getReferenceID(),
                              $payment->getReferenceID() );
         $this->assertEquals( 'capture',
                              $payment->getAction() );
         $this->assertEquals( 'authorizenet',
                              $payment->getProcessor() );
         $this->assertEquals( $response->getAmount(),
                              $payment->getAmount() );
      }

   }