<?php
namespace Mendz\RequestForm\Model\ResourceModel;

class ZendeskRequest extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
	protected function _construct()
	{
		$this->_init('zendesk_request', 'request_id');
	}
}