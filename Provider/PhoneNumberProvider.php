<?php
/*
 * This file is part of the BrandcodeNL SonataMailchimpPublisherBundle.
 * (c) BrandcodeNL
 */
namespace BrandcodeNL\SonataSmsapiPublisherBundle\Provider;

use Symfony\Component\Intl\Exception\NotImplementedException;


/**
 * @author Jeroen de Kok <jeroen.dekok@aveq.nl>
 */
class PhoneNumberProvider implements PhoneNumberProviderInterface 
{
   
    /**
     * Return array with phonenumbers
     * @param $object object that gets published 
     * @return Array<string>     
     */
    public function getPhoneLists($object)
    {
        Throw new NotImplementedException();
    }

}