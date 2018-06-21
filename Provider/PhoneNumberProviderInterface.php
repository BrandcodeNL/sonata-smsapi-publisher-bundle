<?php
/*
 * This file is part of the BrandcodeNL SonataMailchimpPublisherBundle.
 * (c) BrandcodeNL
 */
namespace BrandcodeNL\SonataSmsapiPublisherBundle\Provider;


/**
 * @author Jeroen de Kok <jeroen.dekok@aveq.nl>
 */
interface PhoneNumberProviderInterface 
{
   
    /**
     * Return array with phonenumbers
     * @param $object object that gets published 
     * @return Array<string>     
     */
    public function getPhoneLists($object);

}