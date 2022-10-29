<?php
namespace GDO\TCPDF\Method;

use GDO\Core\Method;
use GDO\File\Method\GetFile;
use GDO\TCPDF\Module_TCPDF;

final class Logo extends Method
{
    public function isTrivial(): bool { return false; }
    
	public function isShownInSitemap(): bool { return false; }
	
	public function execute()
	{
		if ($fileId = Module_TCPDF::instance()->cfgLogoId())
		{
			return GetFile::make()->executeWithId($fileId);
		}
		else
		{
			return $this->error('err_unknown_file');
		}
	}
	
}
