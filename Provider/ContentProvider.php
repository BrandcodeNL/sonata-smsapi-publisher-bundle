<?php
/*
 * This file is part of the BrandcodeNL SonataMailchimpPublisherBundle.
 * (c) BrandcodeNL
 */
namespace BrandcodeNL\SonataSmsapiPublisherBundle\Provider;

use BrandcodeNL\SonataSmsapiPublisherBundle\Model\PhoneList;


/**
 * @author Jeroen de Kok <jeroen.dekok@aveq.nl>
 */
class ContentProvider implements ContentProviderInterface
{
   
    /**
     * Return text for the sms
     * @param $object object that gets published 
     * @return Array<string>     
     */
    public function getText($object, PhoneList $phoneList)
    {
        Throw new NotImplementedException();        
    }
 
}