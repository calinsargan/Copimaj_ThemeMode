<?php
class Copimaj_ThemeMode_MainController extends Mage_Core_Controller_Front_Action 
{
	public function setAction()
	{
		if (isset($_GET['mode'])) {
			$mode = $_GET['mode'];
			$available = $this->_getModes();

			try {
				
				$name = 'thememode';
				$value = $available[$mode];
				Mage::getModel('core/cookie')
					->set(
						$name, 
						$value, 
						$period, 
						$path, 
						$domain, 
						$secure, 
						$httponly
					);

	            $this->_redirectReferer();
            } 
            catch (Exception $e) {
            	// echo $e;
            }

		} else {
			// do nothing
		}
	}

	protected function _getModes() {
		$theme = array();
		$theme['desktop'] = 'larosa';
		$theme['mobile'] = 'larosa-mobile';
		return $theme;
	}

	/**
	 * Set referer url for redirect in responce
	 *
	 * @param   string $defaultUrl
	 * @return  Mage_Core_Controller_Varien_Action
	 */
	protected function _redirectReferer($defaultUrl=null)
	{

	    $refererUrl = $this->_getRefererUrl();
	    if (empty($refererUrl)) {
	        $refererUrl = empty($defaultUrl) ? Mage::getBaseUrl() : $defaultUrl;
	    }

	    $this->getResponse()->setRedirect($refererUrl);
	    return $this;
	}

	/**
	 * Identify referer url via all accepted methods (HTTP_REFERER, regular or base64-encoded request param)
	 *
	 * @return string
	 */
	protected function _getRefererUrl()
	{
	    $refererUrl = $this->getRequest()->getServer('HTTP_REFERER');
	    if ($url = $this->getRequest()->getParam(self::PARAM_NAME_REFERER_URL)) {
	        $refererUrl = $url;
	    }
	    if ($url = $this->getRequest()->getParam(self::PARAM_NAME_BASE64_URL)) {
	        $refererUrl = Mage::helper('core')->urlDecode($url);
	    }
	    if ($url = $this->getRequest()->getParam(self::PARAM_NAME_URL_ENCODED)) {
	        $refererUrl = Mage::helper('core')->urlDecode($url);
	    }

	    if (!$this->_isUrlInternal($refererUrl)) {
	        $refererUrl = Mage::app()->getStore()->getBaseUrl();
	    }
	    return $refererUrl;
	}
}