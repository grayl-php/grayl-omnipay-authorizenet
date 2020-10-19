<?php

   namespace Grayl\Omnipay\AuthorizeNet\Controller;

   use Grayl\Gateway\Common\Entity\ResponseDataAbstract;
   use Grayl\Gateway\Common\Service\ResponseServiceInterface;
   use Grayl\Omnipay\AuthorizeNet\Entity\AuthorizeNetAuthorizeResponseData;
   use Grayl\Omnipay\AuthorizeNet\Service\AuthorizeNetAuthorizeResponseService;
   use Grayl\Omnipay\Common\Controller\OmnipayResponseControllerAbstract;

   /**
    * Class AuthorizeNetAuthorizeResponseController
    * The controller for working with AuthorizeNetAuthorizeResponseData entities
    *
    * @package Grayl\Omnipay\AuthorizeNet
    */
   class AuthorizeNetAuthorizeResponseController extends OmnipayResponseControllerAbstract
   {

      /**
       * The AuthorizeNetAuthorizeResponseData object that holds the gateway API response
       *
       * @var AuthorizeNetAuthorizeResponseData
       */
      protected ResponseDataAbstract $response_data;

      /**
       * The AuthorizeNetAuthorizeResponseService entity to use
       *
       * @var AuthorizeNetAuthorizeResponseService
       */
      protected ResponseServiceInterface $response_service;

   }