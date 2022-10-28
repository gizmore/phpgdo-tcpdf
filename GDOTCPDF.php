<?php
namespace GDO\TCPDF;

use GDO\Address\GDO_Address;
use GDO\Session\GDO_Session;
use GDO\Address\GDT_Address;

Module_TCPDF::instance()->includeTCPDF();

class GDOTCPDF extends \TCPDF
{
	protected $marginHeader = 20;
	protected $marginFooter = 22;
	
	protected $marginTop = 5;
	protected $marginLeft = 5;
	protected $marginRight = 5;
	protected $marginBottom = 10;
	
	public function __construct($orientation='P', $unit='mm', $format='A4', $unicode=true, $encoding='UTF-8', $diskcache=false, $pdfa=false)
	{
		parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache=false, $pdfa=false);

		$this->SetHeaderMargin($this->marginHeader);
		$this->SetFooterMargin($this->marginFooter);
		$this->SetMargins($this->marginLeft, $this->marginTop, $this->marginRight);
		$this->SetAutoPageBreak(true, $this->marginBottom);

// 		$this->setImageScale(PDF_IMAGE_SCALE_RATIO);

		$this->setTextShadow(array(
			'enabled' => true,
			'depth_w' => 0.2,
			'depth_h' => 0.2,
			'color' => array(196,196,196),
			'opacity' => 1,
			'blend_mode' => 'Normal',
		));
	}
	
	/**
	 * @return \GDO\TCPDF\Module_TCPDF
	 */
	public function getModule() { return Module_TCPDF::instance(); }
	
	public function tempPath() { return $this->getModule()->tempPath(GDO_Session::instance()->getID() . '.pdf'); }
	
	#############
	### Fonts ###
	#############
	public function fontHeader() { $this->SetFont('dejavusans', '', 13, '', true); }
	public function fontFooter() { $this->SetFont('dejavusans', '', 9, '', true); }
	public function fontLarge() { $this->SetFont('dejavusans', '', 14, '', true); }
	public function fontDefault() { $this->SetFont('dejavusans', '', 12, '', true); }
	public function fontSmall() { $this->SetFont('dejavusans', '', 9, '', true); }
	
	##############
	### Header ###
	##############
	public $pdftitle = GDO_SITENAME;
	public function title($title) { $this->pdftitle = $title; }
	
	public $subtitle = '';
	public function subtitle($subtitle) { $this->subtitle = $subtitle; }
	
	public function Header()
	{
// 		$y = $this->GetY();
		if ($img = $this->getModule()->cfgLogo())
		{
			$w = $img->getWidth();
// 			$h = $img->getHeight();
			$wu = $this->pixelsToUnits($w);
			$x = $this->getPageWidth() - $wu; #- $this->marginRight;
			if (!($hu = $this->getModule()->cfgLogoHeight()))
			{
				$hu = $this->marginHeader;
			}
			$this->Image($img->getPath(), $x, 0, $wu, $hu, '', '', '', true);
		}
		
		$this->fontHeader();

		$this->y = $this->marginTop;
		$this->x = $this->marginLeft;
		$this->HTML($this->pdftitle);
		$this->HTML($this->subtitle);
		$this->Ln(5.0);
		$this->HR();
	}
	
	##############
	### Footer ###
	##############
	public $footerHTML = '';
	public function footerHTML($footerHTML) { $this->footerHTML = $footerHTML; }
	public function Footer()
	{
		$y = $this->GetY() + 2.5;
		$this->HR();
		$this->fontFooter();
		$pagenumtxt = t('page') . ' ' . $this->getAliasNumPage().' / '.$this->getAliasNbPages();
		$this->y += $this->marginFooter - 10;
		$this->Cell(0, 0, $this->getAliasRightShift().$pagenumtxt, 'T', 0, 'R');
// 		$this->Ln();
		$this->SetY($y);
		$this->writeHTMLCell($this->getPageWidth(), 20, $this->marginLeft, $this->y, $this->footerHTML);
	}
	
	############
	### Util ###
	############
	public function MoveToOrigin()
	{
		$this->x = $this->marginLeft;
		$this->y = $this->marginTop + $this->marginHeader;
	}
	
	public function HR()
	{
		$this->HTML('<hr/>');
	}
	
	public function smallParagraph($text, $align='L')
	{
		$this->fontSmall();
		$this->HTML($text, $align);
		$this->fontDefault();
	}
	
	public function paragraph($text, $align='L')
	{
		$this->HTML($text, $align);
	}
	
	public function largeParagraph($text, $align='L')
	{
		$this->fontLarge();
		$this->HTML($text, $align);
		$this->fontDefault();
	}
	
	public function Address(GDO_Address $address, $align='L', $small=false)
	{
		$this->fontDefault();
		$gdt = GDT_Address::make()->gdo($address)->small($small);
		$this->HTML($gdt->renderPDF(), $align);
	}
	
	public function HTML($html, $align='L')
	{
		$ln = 1;
		$border = 0;
		$resetH = true;
		$autopad = true;
		$this->writeHTMLCell(0, 0, $this->x, $this->y, $html, $border, $ln, false, $resetH, $align, $autopad);
	}
	
}
