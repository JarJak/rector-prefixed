<?php

namespace _PhpScoper26e51eeacccf\SoapTests;

use SoapFault;
class MySoapClient extends \SoapClient
{
}
class MySoapClient2 extends \SoapClient
{
    /**
     * @param string|null $wsdl
     * @param mixed[]|null $options
     */
    public function __construct($wsdl, array $options = null)
    {
        parent::__construct($wsdl, $options);
    }
}
class MySoapClient3 extends \SoapClient
{
    /**
     * @param string|null $wsdl
     * @param mixed[]|null $options
     */
    public function __construct($wsdl, array $options = null)
    {
        parent::SoapClient($wsdl, $options);
    }
}
function () {
    $soap = new \_PhpScoper26e51eeacccf\SoapTests\MySoapClient('some.wsdl', ['soap_version' => \SOAP_1_2]);
    $soap = new \_PhpScoper26e51eeacccf\SoapTests\MySoapClient2('some.wsdl', ['soap_version' => \SOAP_1_2]);
    $soap = new \_PhpScoper26e51eeacccf\SoapTests\MySoapClient3('some.wsdl', ['soap_version' => \SOAP_1_2]);
};
class MySoapHeader extends \SoapHeader
{
    public function __construct(string $username, string $password)
    {
        parent::SoapHeader($username, $password);
    }
}
function () {
    $header = new \_PhpScoper26e51eeacccf\SoapTests\MySoapHeader('user', 'passw0rd');
};
function (\SoapFault $fault) {
    echo $fault->faultcode;
    echo $fault->faultstring;
};
