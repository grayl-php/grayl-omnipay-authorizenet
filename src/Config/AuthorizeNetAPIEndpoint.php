<?php

   namespace Grayl\Omnipay\AuthorizeNet\Config;

   use Grayl\Omnipay\Common\Config\OmnipayAPIEndpointAbstract;

   /**
    * Class AuthorizeNetAPIEndpoint
    * The class of a single AuthorizeNet API endpoint
    *
    * @package Grayl\Omnipay\AuthorizeNet
    */
   class AuthorizeNetAPIEndpoint extends OmnipayAPIEndpointAbstract
   {

      /**
       * The login ID
       *
       * @var string
       */
      protected string $login_id;

      /**
       * The transaction key
       *
       * @var string
       */
      protected string $transaction_key;


      /**
       * Class constructor
       *
       * @param string $api_endpoint_id The ID of this API endpoint (default, provision, etc.)
       * @param string $login_id        The login ID
       * @param string $transaction_key The transaction key
       */
      public function __construct ( string $api_endpoint_id,
                                    string $login_id,
                                    string $transaction_key )
      {

         // Call the parent constructor
         parent::__construct( $api_endpoint_id );

         // Set the class data
         $this->setLoginID( $login_id );
         $this->setTransactionKey( $transaction_key );
      }


      /**
       * Gets the login ID
       *
       * @return string
       */
      public function getLoginID (): string
      {

         // Return it
         return $this->login_id;
      }


      /**
       * Sets the login ID
       *
       * @param string $login_id The login ID to set
       */
      public function setLoginID ( string $login_id ): void
      {

         // Set the login ID
         $this->login_id = $login_id;
      }


      /**
       * Gets the transaction key
       *
       * @return string
       */
      public function getTransactionKey (): string
      {

         // Return it
         return $this->transaction_key;
      }


      /**
       * Sets the transaction key
       *
       * @param string $transaction_key The transaction key to set
       */
      public function setTransactionKey ( string $transaction_key ): void
      {

         // Set the transaction key
         $this->transaction_key = $transaction_key;
      }

   }