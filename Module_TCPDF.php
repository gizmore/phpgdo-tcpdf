<?php
namespace GDO\TCPDF;

use GDO\Core\GDO_Module;
use GDO\Core\GDT_UInt;
use GDO\File\GDO_File;
use GDO\File\GDT_ImageFile;
use GDO\Util\FileUtil;

/**
 * Includes and helpers for the TCPDF Library.
 * Uses GDOTCPDF tp create files in the gdo6/temp/tcpdf directory.
 * Generators (inheriting from GDOTCPDF) shall return a GDO_File.
 *
 * @version 7.0.1
 * @since 6.10.0
 * @author gizmore
 * @see GDOTCPDF
 */
final class Module_TCPDF extends GDO_Module
{

	public int $priority = 8;

	public function onInstall(): void { FileUtil::createDir($this->tempPath()); }

	public function onLoadLanguage(): void { $this->loadLanguage('lang/tcpdf'); }

	public function thirdPartyFolders(): array { return ['TCPDF/']; }

	public function getConfig(): array
	{
		return [
			GDT_ImageFile::make('pdf_top_logo')->previewHREF(href('TCPDF', 'Logo', '&id={id}')),
			GDT_UInt::make('pdf_top_logo_height')->notNull()->initial('0'),
		];
	}

	##############
	### Config ###
	##############

	public function includeTCPDF(): void
    {
        $old = error_reporting();
        error_reporting(0);
        require_once $this->filePath('TCPDF/tcpdf.php');
        error_reporting($old);
    }

	public function cfgLogo(): ?GDO_File { return $this->getConfigValue('pdf_top_logo'); }

	public function cfgLogoId(): ?string { return $this->getConfigVar('pdf_top_logo'); }

	public function cfgLogoHeight() { return $this->getConfigVar('pdf_top_logo_height'); }

}
