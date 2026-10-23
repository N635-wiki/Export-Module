<?php
namespace MediaWiki\Extension\ExportModule;

use SpecialPage;
use HTMLForm;
use Title;
use WikiPage;
use MediaWiki\Revision\SlotRecord;

class SpecialExportModule extends SpecialPage {
	public function __construct() {
		// Registers Special:ExportModule
		parent::__construct( 'ExportModule' );
	}

	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();

		$formDescriptor = [
			'ModuleName' => [
				'type' => 'text',
				'label' => 'Lua Module Name (without "Module:" prefix):',
				'required' => true,
				'help' => 'Enter the name of the Lua script module you want to export.',
			],
		];

		$htmlForm = HTMLForm::factory( 'ooui', $formDescriptor, $this->getContext() );
		$htmlForm->setSubmitText( 'Export Lua Module' );
		$htmlForm->setSubmitCallback( [ $this, 'processForm' ] );

		$htmlForm->show();
	}

	public function processForm( array $formData ) {
		$moduleName = trim( $formData['ModuleName'] );
		$title = Title::makeTitleSafe( 828, $moduleName ); // Namespace 828 = Module:

		if ( !$title || !$title->exists() ) {
			return "Error: Module:{$moduleName} does not exist.";
		}

		// Retrieve the latest content of the wiki page
		$wikiPage = WikiPage::factory( $title );
		$content = $wikiPage->getContent();
		$text = '';
		
		if ( $content && $content->hasSlot( SlotRecord::MAIN ) ) {
			$text = $content->getSlot( SlotRecord::MAIN )->getText();
		}

		// Generate the XML structure
		$xml = new \SimpleXMLElement( '<?xml-stylesheet type="text/xsl" href="mwstub.xsl"?><mediawiki xmlns="http://www.mediawiki.org/xml/export-0.11/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.mediawiki.org/xml/export-0.11/ http://www.mediawiki.org/xml/export-0.11.xsd" version="0.11" xml:lang="en"></mediawiki>' );
		
		$page = $xml->addChild( 'page' );
		$page->addChild( 'title', 'Module:' . $moduleName );
		$page->addChild( 'ns', '828' );
		$page->addChild( 'id', $title->getArticleID() );

		$revision = $page->addChild( 'revision' );
		$revision->addChild( 'id', $title->getLatestRevID() );
		
		$model = $revision->addChild( 'model', 'Scribunto' );
		$format = $revision->addChild( 'format', 'text/plain' );
		
		$textNode = $revision->addChild( 'text', htmlspecialchars( $text ) );
		$textNode->addAttribute( 'xml:space', 'preserve' );

		// Set headers to trigger a file download in the browser
		$response = $this->getRequest()->response();
		$response->header( 'Content-Type: application/xml; charset=utf-8' );
		$response->header( 'Content-Disposition: attachment; filename="Module-' . $moduleName . '.xml"' );

		echo $xml->asXML();
		exit;
	}
}
