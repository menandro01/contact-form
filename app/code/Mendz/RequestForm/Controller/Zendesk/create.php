<?php
namespace Mendz\RequestForm\Controller\Zendesk;

use Magento\Framework\Exception\LocalizedException;

class Create extends \Magento\Framework\App\Action\Action implements \Magento\Framework\App\Action\HttpPostActionInterface
{
	protected $zendRequestRepository;
	protected $orderRepository;
    protected $mediaDirectory;
    protected $fileUploaderFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Mendz\RequestForm\Model\ZendRequestRepository $zendRequestRepository,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory
    ) {
        $this->zendRequestRepository = $zendRequestRepository;
        $this->orderRepository = $orderRepository;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        $this->fileUploaderFactory = $fileUploaderFactory;
        parent::__construct(
            $context
        );
    }

    protected function validateOrderNumber($orderNumber){
    	if(!$this->orderRepository->get($orderNumber)){
            throw new LocalizedException(
                __('Order Number does not exits.')
            );
    	}
    }

    protected function uploadFile() {
        try{
            $target = $this->mediaDirectory->getAbsolutePath('contactForm/');
            $uploader = $this->fileUploaderFactory->create(['fileId' => 'image']); //input name
            $uploader->setAllowedExtensions(['jpg', 'png']);
            $uploader->setAllowRenameFiles(true);
            $result = $uploader->save($target);

            return $result['path'] . $result['file'];
        } catch (\Exception $e) {
            throw new LocalizedException(
                __('Error uploading the file.')
            );
        }
    }

    public function execute()
    {
        if ($this->getRequest()->isPost()) {
            $data = array(
            	'subject' => (string)$this->getRequest()->getPost('subject'),
            	'name' => (string)$this->getRequest()->getPost('name'),
            	'email' => (string)$this->getRequest()->getPost('email'),
            	'order_number' => (string)$this->getRequest()->getPost('order_number'),
            	'description' => (string)$this->getRequest()->getPost('description')
            );

            try {
            	$this->validateOrderNumber($data['order_number']);

                $image_dir = $this->uploadFile();
                $data['image'] = $image_dir;
            	$this->zendRequestRepository->save($data);
            	$this->messageManager->addSuccessMessage('Request was sent.');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong.'));
            }
        }

        $redirect = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
        $redirectUrl = $this->_redirect->getRedirectUrl();
        return $redirect->setUrl($redirectUrl);
    }
}