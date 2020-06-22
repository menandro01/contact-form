<?php
namespace Mendz\RequestForm\Model\ResourceModel\ZendeskRequest;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	protected function _construct()
	{
		$this->_init(\Mendz\RequestForm\Model\ZendeskRequest::class, \Mendz\RequestForm\Model\ResourceModel\ZendeskRequest::class);
	}
}