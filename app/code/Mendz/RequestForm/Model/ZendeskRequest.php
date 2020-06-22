<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Mendz\RequestForm\Model;

class ZendeskRequest extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init(\Mendz\RequestForm\Model\ResourceModel\ZendeskRequest::class);
    }

    public function getId()
    {
        return $this->getRequestId();
    }

        public function setId($value)
    {
        return $this->setRequestId($value);
    }
}