<?php
namespace GDO\TCPDF;

use GDO\Core\GDO_Module;
use GDO\File\GDT_ImageFile;
use GDO\File\GDO_File;
use GDO\Util\FileUtil;
use GDO\Core\GDT_UInt;

/**
 * Includes and helpers for the TCPDF Library.
 * Uses GDOTCPDF tp create files in the gdo6/temp/tcpdf directory.
 * Generators (inheriting from GDOTCPDF) shall return a GDO_File.
 * 
 * @author gizmore
 * @version 7.0.1
 * @since 6.10.0
 * @see GDOTCPDF
 */
final class Module_TCPDF extends GDO_Module
{
	public int $priority = 8;
	
	public function onInstall(): void { FileUtil::createDir($this->tempPath()); }

	public function includeTCPDF(): void { require_once $this->filePath('TCPDF/tcpdf.php'); }
	
	public function onLoadLanguage(): void { $this->loadLanguage('lang/tcpdf'); }
	
	public function thirdPartyFolders(): array { return ['TCPDF/']; }
	
	##############
	### Config ###
	##############
	public function getConfig(): array
	{
		return [
			GDT_ImageFile::make('pdf_top_logo')->previewHREF(href('TCPDF', 'Logo', "&id={id}")),
			GDT_UInt::make('pdf_top_logo_height')->notNull()->initial('0'),
		];
	} 
	
	public function cfgLogo(): ?GDO_File { return $this->getConfigValue('pdf_top_logo'); }
	public function cfgLogoId(): ?string { return $this->getConfigVar('pdf_top_logo'); }
	public function cfgLogoHeight() { return $this->getConfigVar('pdf_top_logo_height'); }

}
