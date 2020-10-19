<?php

   namespace Grayl\Omnipay\AuthorizeNet\Controller;

   use Grayl\Gateway\Common\Entity\ResponseDataAbstract;
   use Grayl\Gateway\Common\Service\ResponseServiceInterface;
   use Grayl\Omnipay\AuthorizeNet\Entity\AuthorizeNetCaptureResponseData;
   use Grayl\Omnipay\AuthorizeNet\Service\AuthorizeNetCaptureResponseService;
   use Grayl\Omnipay\Common\Controller\OmnipayResponseControllerAbstract;

   /**
    * Class AuthorizeNetCaptureResponseController
    * The controller for working with AuthorizeNetCaptureResponseData entities
    *
    * @package Grayl\Omnipay\AuthorizeNet
    */
   class AuthorizeNetCaptureResponseController extends OmnipayResponseControllerAbstract
   {

      /**
       * The AuthorizeNetCaptureResponseData object that holds the gateway API response
       *
       * @var AuthorizeNetCaptureResponseData
       */
      protected ResponseDataAbstract $response_data;

      /**
       * The AuthorizeNetCaptureResponseService entity to use
       *
       * @var AuthorizeNetCaptureResponseService
       */
      protected ResponseServiceInterface $response_service;

   }