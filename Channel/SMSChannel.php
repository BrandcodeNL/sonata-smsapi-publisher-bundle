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

/**
 * @author Jeroen de Kok <jeroen.dekok@aveq.nl>
 */
class SMSChannel implements ChannelInterface
{

    private $smsApi;
    
    /**
     * @param MailChimp $mailchimp
     */
    public function __construct(SmsFactory $smsApi)
    {
        $this->smsApi = $smsApi;
    
    }


    /**
     * Publish object to Mailchimp
     * @param $object
     * TODO better error handling
     */
    public function publish($object)
    {       
       
        try {
            $actionSend = $this->smsApi->actionSend();

            $actionSend->setTo('0031650221054');
            $actionSend->setText('Hello World!!');
            $actionSend->setSender('Info');

            $response = $actionSend->execute();

            foreach ($response->getList() as $status) {
                echo $status->getNumber() . ' ' . $status->getPoints() . ' ' . $status->getStatus();
            }
        } catch (SmsapiException $exception) {
            echo 'ERROR: ' . $exception->getMessage();
        }
         
        // $this->settingsProvider->setObject($object);
        // $results = array();
        // //Loop through all the lists provided by the list provider
        // foreach($this->listProvider->getLists($object) as $list) 
        // {          
        //     if(!empty($list->getApiKey()))
        //     {
        //         $this->reinitializeMailchimp($list->getApiKey());
        //     }
        //     $campaign = $this->createCampaign($list, $object);
        //     $campaignId = isset($campaign['id']) ? $campaign['id'] : null;

        //     if($campaignId)
        //     {               
        //         $campaignResult = $this->insertContentInCampaign($campaignId, $list, $object);
        //         if(!isset($campaignResult['errors']) &&  $this->settingsProvider->getScheduleDateTime() != null)
        //         {
        //             //schedule campaign if date is provided by the settingsProvider
        //             $this->scheduleCampaign($campaignId, $this->settingsProvider->getScheduleDateTime());
        //         }
        //         $results[] = array_merge($campaign, $campaignResult);
        //     }
        //     else
        //     {
        //         $results = $campaign;
        //     }
        // }     
        
        // return $this->generateSuccessResponce($results);
        
    }

    /**
     * Create a new campaign
     */
    protected function createCampaign($list, $object)
    {
        if(!$list instanceof ListInterface)
        {
            Throw new \Exception(get_class($list)." is not an instance of ".ListInterface::class);
        }

        $this->settingsProvider->setList($list);
        
        $recipients = array(
            'list_id' => $list->getListId(),
        );

        //retrieve posible segment from list provider
        $segment = $this->listProvider->getSegment($list, $object);
        if(!empty($segment))
        {
            $recipients['segment_opts'] = $segment;
        }
       
        $result = $this->mailchimp->post("campaigns",
            array(
                "recipients" => $recipients,                
                'type' => 'regular',
                'settings' =>
                    array_merge(
                        array(
                            'subject_line' => $this->settingsProvider->getSubject(),
                            'template_id' => $this->settingsProvider->getTemplateId(),
                        ),
                        $this->settingsProvider->getFrom()
                    )   
            )
        );   
        
        return $result;
    }

    /**
     * Update an existing campaign and add content
     * TODO dont hardcode the section ID Here ? 
     * TODO Support multiple sections ? 
     */
    protected function insertContentInCampaign($campaignId, $list, $object)
    {
         //proceed with adding content
         $result = $this->mailchimp->put("campaigns/{$campaignId}/content",
            array(
                "template" => array(
                    'id' => $this->settingsProvider->getTemplateId(),
                    'sections' => array(
                        'content' => $this->formatter->generateHTML($object, $list)
                    )
                )
            )
        );

        if(!empty($result['errors']))
        {
            //handle errors ? 
            Throw new \Exception(json_encode($result['errors']));
        }

        return $result;
  
    }

    /**
     * Schedule a campaign
     * TODO error handling ? 
     */
    protected function scheduleCampaign($campaignId, $datetime)
    {       
        //convert to UTC
        $datetime->setTimezone(new \DateTimeZone("UTC"));       
        $result = $this->mailchimp->post("campaigns/{$campaignId}/actions/schedule",
            array(
                "schedule_time" => $datetime->format('Y-m-d H:i:s e')
            )
        );
    }

    private function reinitializeMailchimp($apiKey)
    {
        $this->mailchimp = new Mailchimp($apiKey);
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
        return "sonata.publish.mailchimp";
    }
}