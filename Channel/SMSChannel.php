<?php
/*
 * This file is part of the BrandcodeNL SonataMailchimpPublisherBundle.
 * (c) BrandcodeNL
 */
namespace BrandcodeNL\SonataSmsapiPublisherBundle\Channel;

use SMSApi\Api\SmsFactory;
use DrewM\MailChimp\MailChimp;
use BrandcodeNL\SonataPublisherBundle\Entity\PublishResponce;
use BrandcodeNL\SonataPublisherBundle\Channel\ChannelInterface;
use BrandcodeNL\SonataSmsapiPublisherBundle\Provider\ContentProviderInterface;
use BrandcodeNL\SonataSmsapiPublisherBundle\Provider\PhoneNumberProviderInterface;

/**
 * @author Jeroen de Kok <jeroen.dekok@aveq.nl>
 */
class SMSChannel implements ChannelInterface
{

    private $smsApi;
    private $phoneNumberProvider;
    private $contentProvider;
    
    /**
     * @param MailChimp $mailchimp
     */
    public function __construct(SmsFactory $smsApi, PhoneNumberProviderInterface $phoneNumberProvider, ContentProviderInterface $contentProvider)
    {
        $this->smsApi = $smsApi;    
        $this->phoneNumberProvider = $phoneNumberProvider;    
        $this->contentProvider = $contentProvider;    
    }

    /**
     * Publish object to Mailchimp
     * @param $object
     * TODO better error handling
     */
    public function publish($object)
    {       
        $phoneLists = $this->phoneNumberProvider->getPhoneLists($object);
        $result = array();

        foreach($phoneLists as $phoneList) {
            try {                
                $actionSend = $this->smsApi->actionSend();            
                $actionSend->setTo(implode(",",$phoneList->getPhoneNumbers()));
                $actionSend->setText($this->contentProvider->getText($object, $phoneList));
                $actionSend->setSender($phoneList->getSender());     
                $response = $actionSend->execute();
               
                foreach ($response->getList() as $status) {
                    $result[] = $status->getNumber() . ' ' . $status->getPoints() . ' ' . $status->getStatus();
                }
            } catch (SmsapiException $exception) {
                $result['errors'][]= 'ERROR: ' . $exception->getMessage();
            }
        }
        return $this->generateSuccessResponce($result);
        
    }

    public function generateSuccessResponce($result)
    {        
        if(!empty($result['errors']))
        {
            return  new PublishResponce("error", count($result), $result['errors'], strval($this));            
        }

        return  new PublishResponce("success", count($result), $result, strval($this));
    }
    
    public function __toString()
    {
        return "sonata.publish.smsapi";
    }
}