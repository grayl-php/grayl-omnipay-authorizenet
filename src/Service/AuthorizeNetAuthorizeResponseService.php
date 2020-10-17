<?php

namespace Grayl\Omnipay\AuthorizeNet\Service;

use Grayl\Omnipay\AuthorizeNet\Entity\AuthorizeNetAuthorizeResponseData;
use Grayl\Omnipay\Common\Service\OmnipayResponseServiceAbstract;

/**
 * Class AuthorizeNetAuthorizeResponseService
 * The service for working with AuthorizeNet authorize responses
 *
 * @package Grayl\Omnipay\AuthorizeNet
 */
class AuthorizeNetAuthorizeResponseService extends
    OmnipayResponseServiceAbstract
{

    /**
     * Returns the AVS result code from a gateway API response
     *
     * @param AuthorizeNetAuthorizeResponseData $response_data The response object to pull the AVS code from
     *
     * @return string
     */
    public function getAVSCode($response_data): ?string
    {

        // Get the API response object
        $api_response = $response_data->getAPIResponse();

        // Return the AVS code field
        return $api_response->getValue('transactionResponse.avsResultCode');
    }


    /**
     * Returns the CVV result code from a gateway API response
     *
     * @param AuthorizeNetAuthorizeResponseData $response_data The response object to pull the CVV code from
     *
     * @return string
     */
    public function getCVVCode($response_data): ?string
    {

        // Get the API response object
        $api_response = $response_data->getAPIResponse();

        // Return the CVV code field
        return $api_response->getValue('transactionResponse.cvvResultCode');
    }

}