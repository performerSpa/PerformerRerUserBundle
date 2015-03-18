<?php

namespace Performer\RerUserBundle\Test;

use Performer\RerUserBundle\Security\Soap\Service;

/**
 * Stub del servizio soap, per testare il login
 * È necessario avere gli utenti usati in questo test nelle fixtures
 * Inserire in config_test.yml:
 *   performer_rer_user:
 *       soap:
 *          service_class: Performer\RerUserBundle\Test\SoapServiceStub
 */
class SoapServiceStub extends Service
{
    /**
     * @inheritdoc
     */
    public function login($username, $password, $dominio)
    {
        if(in_array($username, ['sambri.daniele', 'fornaciari_n', 'cova.massimo'])){
            return 'Riuscito';
        }
        if($username == 'tirer_lettura' && $password == 'tirer_lettura' && $dominio == 'prova'){
            return 'Riuscito';
        }
        if($username == 'tirer_scrittura' && $password == 'tirer_scrittura' && $dominio == 'prova'){
            return 'Riuscito';
        }
        if($username == 'tirer_power_user' && $password == 'tirer_power_user' && $dominio == 'prova'){
            return 'Riuscito';
        }
        if ($username == 'pluto' && $password == 'paperopoli' && $dominio == 'prova') {
            return 'Riuscito';
        }
        if ($username == 'scaduto') {
            return 'PasswordScaduta';
        }
        if ($username == 'eccezione') {
            return 'Ahia!';
        }

        return 'LoginFallito';
    }
}
