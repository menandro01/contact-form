<?php
namespace Mendz\RequestForm\Model;

class ZendRequestRepository
{

    protected $zendeskRequestModel;
    protected $zendeskRequestResourceModel;
    protected $zendeskRequestCollection;
    protected $httpClient;
    protected $scopeConfig;

    public function __construct(
        \Mendz\RequestForm\Model\ZendeskRequestFactory $zendeskRequestModel,
        \Mendz\RequestForm\Model\ResourceModel\ZendeskRequestFactory $zendeskRequestResourceModel,
        \Mendz\RequestForm\Model\ResourceModel\ZendeskRequest\CollectionFactory $zendeskRequestCollection,
        \Zendesk\Zendesk\ZendeskApi\HttpClient $httpClient,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->zendeskRequestModel = $zendeskRequestModel;
        $this->zendeskRequestResourceModel = $zendeskRequestResourceModel;
        $this->zendeskRequestCollection = $zendeskRequestCollection;
        // $this->httpClient = $httpClient;
        $this->scopeConfig = $scopeConfig;
    }
	
    public function save($data)
    {
        $zendeskRequestResourceModel = $this->zendeskRequestResourceModel->create();
        $zendeskRequestModel = $this->zendeskRequestModel->create();

        $zendeskRequestModel->setSubject($data['subject']);
        $zendeskRequestModel->setName($data['name']);
        $zendeskRequestModel->setEmail($data['email']);
        $zendeskRequestModel->setOrderNumber($data['order_number']);
        $zendeskRequestModel->setImage($data['image']);
        $zendeskRequestModel->setDescription($data['description']);
        
        $this->createTicket($zendeskRequestModel);

        return $zendeskRequestResourceModel->save($zendeskRequestModel);
    }

    public function createTicket($model){
        $subject = $this->scopeConfig->getValue('request_form/general/subject') ?: 'subject';
        $name = $this->scopeConfig->getValue('request_form/general/name') ?: 'name';
        $email = $this->scopeConfig->getValue('request_form/general/email') ?: 'email';
        $order_number = $this->scopeConfig->getValue('request_form/general/order_number') ?: 'attribute_value_ids';
        $description = $this->scopeConfig->getValue('request_form/general/description') ?: 'comment';

        $params = [
            'external_id' => $model->getId(),
            'type' => 'request',
            $subject  => $model->getSubject(),
            $description  => array(
                $model->getDescription()
            ),
            'requester' => array(
                'locale_id' => '1',
                $name => $model->getName(),
                $email => $model->getEmail(),
            ),
            'priority' => 'normal',
            $order_number => array($model->getOrderNumber())
        ];

        $this->httpClient->post('/api/v2/tickets.json', $params);
    }
}