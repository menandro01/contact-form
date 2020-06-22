<?php
namespace Mendz\RequestForm\Model\Source;

use Magento\Framework\Option\ArrayInterface;

class SubjectType implements ArrayInterface
{
    public function toOptionArray()
    {
        return [
            __('Cancel'),
            __('Where is my Order'),
            __('Something Went Wrong'),
        ];
    }
}
