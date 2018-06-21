<?php
/*
 * This file is part of the BrandcodeNL SonataSmsapiPublisherBundle.
 * (c) BrandcodeNL
 */
namespace BrandcodeNL\SonataSmsapiPublisherBundle\Model;

/**
 * @author Jeroen de Kok <jeroen.dekok@aveq.nl>
 */
class PhoneList implements PhoneListInterface
{

    private $phoneNumbers;
    private $sender;

    public function __construct($phoneNumbers = array(), $sender = "")
    {
        $this->phoneNumbers = $phoneNumbers;
        $this->sender = $sender;
    }

    public function getPhoneNumbers()
    {
        return $this->phoneNumbers;
    }

    public function addPhoneNumber($phoneNumber)
    {
        $this->phoneNumbers[] = $phoneNumber;
    }

    public function getSender()
    {
        return $this->sender;
    }
    
}
