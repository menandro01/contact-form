<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Mendz\RequestForm\Block;

use Magento\Framework\View\Element\Template\Context;
// use Mendz\RequestForm\Model\ZendRequestFactory as ZendRequestModelFactory;
// use Mendz\RequestForm\Model\ResourceModel\ZendRequest as ZendRequestResourceModelFactory;

class ZendRequest extends \Magento\Framework\View\Element\Template
{
    protected $subjectType;

    public function __construct(
        Context $context,
        \Mendz\RequestForm\Model\Source\SubjectType $subjectType,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->subjectType = $subjectType;
    }

    public function getFormActionUrl()
    {
        return $this->getUrl('requestform/zendesk/create', ['_secure' => true]);
    }

    public function getSubjectTypes(){
        return $this->subjectType->toOptionArray();
    }
}