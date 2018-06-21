<?php
/*
 * This file is part of the BrandcodeNL SonataSmsapiPublisherBundle.
 * (c) BrandcodeNL
 */
namespace BrandcodeNL\SonataSmsapiPublisherBundle\Model;


/**
 * @author Jeroen de Kok <jeroen.dekok@aveq.nl>
 */
interface PhoneListInterface 
{  
   
    public function getPhoneNumbers();   

    public function getSender();
    
}