<?php
// define maximum width of images
if(!defined('MAX_IMAGE_WIDTH'))
	define("MAX_IMAGE_WIDTH", 670);
 
if ( !defined( 'MEDIAWIKI' ) ) 
     die();
 
global $wgeBookLibDir;

$wgePubExportHttpsImages = false; // set to true if page is on a HTTPS server and contains images that are on the HTTPS server and also
                                 // reachable with HTTP
								 
class SpecialePub extends SpecialPage {
	var $title;
	var $article;
	var $html;
	var $parserOptions;
	var $bhtml;
	var $book;
	var $direction;
	var $lang;
	var $cssFile;
	var $cssFileName;

	private function lastModified($Article) {
		global $wgLang;
		$timestamp = $Article->getTimestamp();

		if ( $timestamp ) {
			$d = $wgLang->date( $timestamp, true );
			$t = $wgLang->time( $timestamp, true );
			$s = ' ' . wfMsg( 'lastmodifiedat', $d, $t );
		} else {
			$s = '';
		}
		if ( wfGetLB()->getLaggedSlaveMode() ) {
			$s .= ' <strong>' . wfMsg( 'laggedslavemode' ) . '</strong>';
		}
		return $s;
	}


	function SpecialePub() {
	
		global $wgContLang;
		global $wgLanguageCode;
		global $wgePubExportProperties;
		
		SpecialPage::SpecialPage( 'ePubPrint' );
		$os = getenv ("SERVER_SOFTWARE");
		$this->book = new EPub();
		$this->direction = ($wgContLang->isRTL() ? "rtl" : "ltr");
		$this->lang = $wgLanguageCode;
		if ( isset($wgePubExportProperties['css_file']) ) {
			$this->cssfile = $wgePubExportProperties['css_file'];
			$this->cssFileName = basename( $this->cssfile );
		} else {
			$this->cssfile = null;
			$this->cssFileName = "styles.css";
		}
	}

	public function save1page ( $page ) {
		global $wgUser;
		global $wgParser;
		global $wgScriptPath;
		global $wgServer;                       
		global $wgPdfExportHttpsImages;
		global $wgeBookLibDir;
		
		$title = Title::newFromText( $page );
		if( is_null( $title ) ) { 
			return null;
		}
		
		if( !$title->userCanRead() ){
			return null;
		}
		
		$chapterName = str_replace("_", " ", $page);
		
		$article = new Article ($title);
		$parserOptions = ParserOptions::newFromUser( $wgUser );
		$parserOptions->setEditSection( false );
		$parserOptions->setTidy(true);
		$wgParser->mShowToc = false;
		$parserOutput = $wgParser->parse( $article->preSaveTransform( $article->getContent() ) ."\n\n", $title, $parserOptions );
		
		$userSkin = $parserOptions->getSkin();
		$bhtml = $parserOutput->getText();
		// XXX Hack to thread the EUR sign correctly
		$bhtml = str_replace(chr(0xE2) . chr(0x82) . chr(0xAC), chr(0xA4), $bhtml);
		//$bhtml = utf8_decode($bhtml);
		
		$bhtml = str_replace ($wgScriptPath, $wgServer . $wgScriptPath, $bhtml);
		$bhtml = str_replace ('/w/',$wgServer . '/w/', $bhtml);
		
		// removed heights of images
		$bhtml = preg_replace ('/height="\d+"/', '', $bhtml);
		// set upper limit for width
		$bhtml = preg_replace ('/width="(\d+)"/e', '"width=\"".($1> MAX_IMAGE_WIDTH ?  MAX_IMAGE_WIDTH : $1)."\""', $bhtml);
		
		// remove scripts
		$bhtml = preg_replace('#(\n?<script[^>]*?>.*?</script[^>]*?>)|(\n?<script[^>]*?/>)#is', '', $bhtml);
		
		if ($wgPdfExportHttpsImages) {
			$bhtm = str_replace('img src=\"https:\/\/','img src=\"http:\/\/', $bhtml);
		}
		
		$bhtml = $this->handleImages($bhtml);
       
		$html = $this->getHtmlHeader( $page );
		$html .= "<h1>" . $chapterName . "</h1>"
		      . "<h4 class='warning'>" . $this->lastModified($article) . "</h4>\n" // Print version warning
		      . $bhtml . "</body></html>";

		return $html;
	}

	// clean several iteration of BOM, if in the beginning of the output buffer
	private function CleanBOMs() 
	{
		$BOM =  chr(0xef). chr(0xbb) . chr(0xbf);
		
		$output = ob_get_contents();
		
		$toRemove = Array();
		// Characters that may be already written to the output buffer and must be clened.
		$toRemove[] = $BOM;
		$toRemove[] = chr(10);
		$toRemove[] = chr(13);
		
		if ( $output !== false ) {			

			if ( $output != "" ) {
				$output = str_replace($toRemove, "", $output); // delete all BOMs
				
				if ( $output != "" ) { 
					return false;
				}
			}
			
			// only BOM(s) in output, output buffer may be cleaned.
			ob_clean();
		}
		
		return true;
	}
	  
	private function getHtmlHeader($pageTitle)
	{
		$header = 
			"<?xml version=\"1.0\" encoding=\"utf-8\"?>\n"
			. "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\"\n"
			. "    \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">\n"
			. "<html xmlns=\"http://www.w3.org/1999/xhtml\"  xml:lang=\"". $this->lang ."\" lang=\"". $this->lang ."\" dir=\"". $this->direction ."\">\n"
			. "<head>\n"
			. "<meta http-equiv=\"Content-Type\" content=\"application/xhtml+xml; charset=utf-8\" />\n"
			. "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $this->cssFileName . "\" />\n"
			. "<title>" . $pageTitle ."</title>\n"
			. "</head>\n"
			. "<body>\n";
			
		return $header;
	}
	
	private function coverPage( $bookname )
	{
		global $wgLogo;
		global $wgLang;
		global $wgePubExportProperties;
		
		$content_start = $this->getHtmlHeader( $bookname );
		
		$timestamp = wfTimestampNow();

		$d = $wgLang->date( $timestamp, true );
		$t = $wgLang->time( $timestamp, false );
		
		/*
		I'm leaving it here for future development. Currentlly, it's not work for URLs.
		Getting this warnning and fata error:
		---------------------------------------
		Warning: file_get_contents(http://francisanderson.files.wordpress.com/2009/05/hp-prototype-e-reader-closeup.jpg) [function.file-get-contents]: failed to open stream: A connection attempt failed because the connected party did not properly respond after a period of time, or established connection failed because connected host has failed to respond. in C:\wamp\www\wiki\extensions\ePubExport\ePubExport_body.php on line 156

		Fatal error: Maximum execution time of 30 seconds exceeded in C:\wamp\www\wiki\extensions\ePubExport\ePubExport_body.php on line 156
		---------------------------------------
		*/
		/*$coverImage = isset($wgePubExportProperties['cover_image'])?$wgePubExportProperties['cover_image']:'..' . $wgLogo;
		$logoName = "images/" . basename($coverImage);
		$logoData = file_get_contents($coverImage);
		$imageSize = getimagesize($coverImage); 
		$mime = $imageSize['mime'];
		
		$this->book->addFile($logoName, uniqid(),  $logoData, $mime);
		*/
		
		$cover = $content_start . "<div class='cover'>\n<h1 class='cover'>". $bookname ."</h1>\n<h2 class='cover'>" . wfMsg('credit_text', $d, $t) ."</h2>\n"
		/*         . "<img class='cover' src='$logoName' />\n"*/
		         . "</div>\n</body>\n</html>\n";
		$this->book->addChapter("Cover", "Cover.html", $cover);
	}
	
	private function handleImages($html)
	{
		preg_match_all('/<img[^>]+>/i',$html, $results);
		
		$img = Array();
		foreach ($results as $result) {
			
			foreach ($result as $image) {
				preg_match('/src=("[^"]*")/', $image, $img[$image]);

			}
		}
		
		foreach ($img as $arr=>$image) {
			
			$fileName = $image[1];
			$fileName = str_replace('"', '', $fileName);
			
			$imageSize = getimagesize($fileName); 
			$mime = $imageSize['mime'];
		
			$fileData = file_get_contents($fileName);
			
			$fileName = "images/" . basename($fileName);
			
			$html = str_replace($image[0], 'src="'.$fileName . '"', $html);
		
			$this->book->addFile($fileName, uniqid(),  $fileData, $mime);
		}
		
		return $html;
	}
	
	private function EmbedFonts()
	{
		global $wgePubExportProperties;

		if ( isset( $wgePubExportProperties['fonts'] ) ) {
			foreach ( $wgePubExportProperties['fonts'] as $fontSrc ) {
				$fileData = file_get_contents($fontSrc);
				$fontName = basename($fontSrc);
				$this->book->addFile("Fonts/" . $fontName, uniqid(), $fileData, "application/x-font-" .  substr($fontName, strrpos($fontName, '.') + 1));
			}				
			
			if ( isset( $wgePubExportProperties['font_license'] ) ) {
				$fileData = file_get_contents($wgePubExportProperties['font_license']);
				$fileName = basename($wgePubExportProperties['font_license']);
				$this->book->addFile("Fonts/" . $fileName, uniqid(), $fileData, "text/plain");
			}
			
		} else { // Default fonts
			/*
			Supported Languages: 
			Afar, Abkhazia, Afrikaans, Akan, Aragonese, Arabic, Asturian/Bable/Leonese/Asturleonese, Avaric, Aymara, Azerbaijani in Azerbaijan, Azerbaijani in Iran, 
			Bashkir, Byelorussian, Berber in Algeria, Berber in Morocco, Bulgarian, Bislama, Edo or Bini, Bambara, Breton, Bosnian, Buriat (Buryat), Catalan, Chechen, 
			Chamorro, Mari (Lower Cheremis / Upper Cheremis), Corsican, Crimean Tatar/Crimean Turkish, Czech, Kashubian, Old Church Slavonic, Chuvash, Welsh, Danish, 
			German, Ewe, Greek, English, Esperanto, Spanish, Estonian, Basque, Persian, Fanti, Fulah (Fula), Finnish, Filipino, Fijian, Faroese, French, Friulian, 
			Frisian, Irish, Scots Gaelic, Galician, Guarani, Manx Gaelic, Hausa, Hawaiian, Hebrew, Hiri Motu, Croatian, Upper Sorbian, Haitian/Haitian Creole, Hungarian, 
			Armenian, Herero, Interlingua, Indonesian, Interlingue, Igbo, Inupiaq (Inupiak, Eskimo), Ido, Icelandic, Italian, Inuktitut, Javanese, Georgian, 
			Kara-Kalpak (Karakalpak), Kabyle, Kikuyu, Kuanyama/Kwanyama, Kazakh, Greenlandic, Kanuri, Kurdish in Armenia, Kurdish in Iraq, Kurdish in Iran, Kurdish in Turkey, 
			Kumyk, Komi (Komi-Permyak/Komi-Siryan), Cornish, Kwambi, Kirgiz, Latin, Luxembourgish (Letzeburgesch), Lezghian (Lezgian), Ganda, Limburgan/Limburger/Limburgish, 
			Lingala, Lao, Lithuanian, Latvian, Malagasy, Marshallese, Maori, Macedonian, Mongolian in Mongolia, Moldavian, Malay, Maltese, Nauru, Norwegian Bokmal, Low Saxon, 
			Ndonga, Dutch, Norwegian Nynorsk, Norwegian (Bokmal), Ndebele, South, Northern Sotho, Navajo/Navaho, Chichewa, Occitan, Oromo or Galla, Ossetic, 
			Papiamento in Netherlands Antilles, Papiamento in Aruba, Polish, Portuguese, Quechua, Rhaeto-Romance (Romansch), Rundi, Romanian, Russian, Kinyarwanda, Yakut, 
			Sardinian, Scots, North Sami, Selkup (Ostyak-Samoyed), Sango, Serbo-Croatian, Secwepemctsin, Slovak, Slovenian, Samoan, South Sami, Lule Sami, Inari Sami, 
			Skolt Sami, Shona, Somali, Albanian, Serbian, Swati, Sotho, Southern, Sundanese, Swedish, Swahili, Tajik, Turkmen, Tagalog, Tswana, Tonga, Turkish, Tsonga, Tatar, 
			Twi, Tahitian, Tuvinian, Uighur, Ukrainian, Uzbek, Venda, Vietnamese, Volapuk, Votic, Walloon, Sorbian languages (lower and upper), Wolof, Xhosa, Yapese, Yiddish, 
			Yoruba, Zhuang/Chuang and Zulu
			*/
			$dir = dirname(__FILE__) . '/fonts/';
			$fileData = file_get_contents($dir . "DejaVuSans.ttf");
			$this->book->addFile("Fonts/DejaVuSans.ttf", uniqid(),  $fileData, "application/x-font-ttf");
			$fileData = file_get_contents($dir . "DejaVuSans-Bold.ttf");
			$this->book->addFile("Fonts/DejaVuSans-Bold.ttf", uniqid(),  $fileData, "application/x-font-ttf");
			$fileData = file_get_contents($dir . "DejaVuSans-BoldOblique.ttf");
			$this->book->addFile("Fonts/DejaVuSans-BoldOblique.ttf", uniqid(),  $fileData, "application/x-font-ttf");
			$fileData = file_get_contents($dir . "DejaVuSans-Oblique.ttf");
			$this->book->addFile("Fonts/DejaVuSans-Oblique.ttf", uniqid(),  $fileData, "application/x-font-ttf");
			// add font license
			$fileData = file_get_contents($dir . "LICENSE");
			$this->book->addFile("Fonts/LICENSE", uniqid(),  $fileData, "text/plain");
		}
	}
	
	private function AddCss()
	{
		global $wgePubExportProperties;
		
		if ( $this->cssfile == null ) {
			$direction_str = "direction: " . $this->direction . ";\n";
			$fontFamily = "'Times New Roman', Times, serif, 'sans serif'";
		
			$cssData = "";
			// use default fonts:
			if ( isset($wgePubExportProperties['embed_fonts']) && (!isset($wgePubExportProperties['fonts'])) ) {
				$cssData .=  "@font-face {\n  font-family: 'DejaVu Sans';\n  font-style: normal;\n  font-weight: normal;\n  src:url(Fonts/DejaVuSans.ttf);\n}\n\n";
				$cssData .= "@font-face {\n  font-family: 'DejaVu Sans';\n  font-style: italic;\n  font-weight: normal;\n  src:url(Fonts/DejaVuSans-Oblique.ttf);\n}\n\n";
				$cssData .= "@font-face {\n  font-family: 'DejaVu Sans';\n  font-style: normal;\n  font-weight: bold;\n  src:url(Fonts/DejaVuSans-Bold.ttf);\n}\n\n";
				$cssData .= "@font-face {\n  font-family: 'DejaVu Sans';\n  font-style: italic;\n  font-weight: bold;\n  src:url(Fonts/DejaVuSans-BoldOblique.ttf);\n}\n\n";
				
				$fontFamily .= ", 'DejaVu Sans'";
			}
			
			$cssData .= "html {\n  ". $direction_str ."}\n\n";
			$cssData .= "text {\n  ". $direction_str ."}\n\n";
			$cssData .= "body {\n  margin-left: .5em;\n  margin-right: .5em;\n  text-align: justify;\n  ". $direction_str ."\n}\n\n";
			$cssData .= ".toctext, .tocnumber, p {\n  font-family: " . $fontFamily . ";\n  text-align: justify;\n  text-indent: 1em;\n  margin-top: 0px;\n  margin-bottom: 1ex;\n  " . $direction_str . "\n}\n\n";
			$cssData .= "h1, h2, .mw-headline {\n  font-family: " . $fontFamily . ";\n  font-style: italic;\n}\n\n";
			$cssData .= "h1.cover, h2.cover {\n  font-family: " . $fontFamily . ";\n  font-style: italic;\n  text-align: center;\n}\n\n";
			$cssData .= "h1.cover {\n  font-size:3.5em;\n  margin-down:40px\n}\n\n";
			$cssData .= "h2.cover {\n  font-size:1.5em;\n}\n\n";
			$cssData .= "h1 {\n  margin-bottom: 2px;\n  " . $direction_str . "\n}\n\n";
			$cssData .= "h2, .mw-headline {\n  margin-top: -2px;\n  margin-bottom: 2px;\n  " . $direction_str . "\n}\n\n";
			$cssData .= "h4.warning {\n  font-family: " . $fontFamily . ";\n  font-style: italic;\n  " . $direction_str ."\n}\n\n";
			$cssData .= "div.cover {\n  text-align: center;\n}\n\n";
			$cssData .= "img.cover {\n  margin-left:auto;\n  margin-right:auto;\n  margin-top:20px;\n  text-align: center;\n}\n\n";
		} else {
			$cssData =  file_get_contents( $this->cssfile );			
		}
		$this->book->addCSSFile($this->cssFileName, "css1", $cssData);
	}
	
	function outputePub($pages, $filename = 'wikiebook', $description = '')
	{
		global $wgRequest;
		global $wgeBookLibDir;
		global $wgSitename;
		global $wgServer;
		global $wgContLang;
		global $wgePubExportProperties;

		
		$fileTime = date("D, d M Y H:i:s T");

		$filename = str_replace(" ", "_", $filename);
		$bookname = str_replace("_", " ", $filename);

		// Title and Identifier are mandatory!
		$this->book->setTitle($bookname);
		$this->book->setIdentifier( uniqid() , "UUID" );
		$this->book->setLanguage($this->lang); // Not needed, but included for the example, Language is mandatory, but EPub defaults to "en". Use RFC3066 Language codes, such as "en", "da", "fr" etc.
		$this->book->setDescription($description);
		$this->book->setAuthor($wgSitename . ' contributors', $wgSitename . ' contributors'); 
		$this->book->setPublisher($wgSitename, $wgServer);
		$this->book->setDate(time()); // Strictly not needed as the book date defaults to time().
		$this->book->setRights("See here:" . $wgServer . '/index.php/' . $wgSitename . ':About');
		$this->book->setSourceURL($wgServer);
		
		$this->AddCss();
		
		if ( isset($wgePubExportProperties['embed_fonts']) && ($wgePubExportProperties['embed_fonts']) ) {
			$this->EmbedFonts();
		}

		$this->coverPage( $bookname );

		$i = 1;
		foreach ($pages as $pg) {
			$content = $this->save1page($pg);
			if ( $content !== null ) {
				$this->book->addChapter("article ". $i .": " . $filename = str_replace("_", " ", $pg), "wikipage" . $i . ".html", $content, true);
				$i++;
			}
		}

		$this->book->setIgnoreEmptyBuffer(true);
		$this->book->finalize(); // Finalize the book, and build the archive.

		if ( $this->CleanBOMs() or die ("Output buffer is not empty. Now contins" . ob_get_contents()) ) {
			$zipData = $this->book->sendBook($filename);
		}
	}
	  

	function execute( $par ) {
		global $wgRequest;
		global $wgOut; 
		global $wgSitename;

		wfLoadExtensionMessages ('ePubPrint');
		$dopdf = false;
		if ($wgRequest->wasPosted()) {
			$pagel = $wgRequest->getText ('pagel');
			$pages = array_filter( explode( "\n", $pagel ), 'wfFilterPageePub' );
			$filename = $wgRequest->getText ('filename');
			$description = $wgRequest->getText ('description');
			
			$dopdf = true;
		
		} else {
			$page = isset( $par ) ? $par : $wgRequest->getText( 'page' );
			if ($page != "") {
				$dopdf = true; 
			}
			$pages = array ($page);
			$filename = str_replace(" ", "_", $page);
		}
		
		if ( $dopdf ) {
			$wgOut->setPrintable();
			$wgOut->disable();

			$this->outputePub ($pages, $filename);
			
			return;
		}

		$self = SpecialPage::getTitleFor( 'ePubPrint' );

		$wgOut->addHtml(wfMsgExt( 'ePub_special_page_title', 'parse'));
		$wgOut->addHtml( wfMsgExt( 'ePub_print_text', 'parse' ));
		$form = Xml::openElement( 'form', array( 'method' => 'post',
							 'action' => $self->getLocalUrl( 'action=submit' ) ) ); 
		$form .= "<p>";
		$form .= Xml::openElement( 'textarea', array( 'name' => 'pagel', 'cols' => 40, 'rows' => 10 ) );
		$form .= Xml::closeElement( 'textarea' );
		$form .= "</p>\n";
		$form .=  wfMsgExt( 'ePub_enter_description', 'parse' );
		$form .= "<p>";
		$form .= Xml::openElement( 'textarea', array( 'name' => 'description', 'cols' => 40, 'rows' => 10 ) );
		$form .= wfMsg( 'default_description' );
		$form .= Xml::closeElement( 'textarea' );
		$form .= "</p>\n";

		$form .= "<p>";
		$form .= wfMsg ('ePub_filename').":";
		$form .= Xml::openElement( 'input', array( 'type'=>'text', 'name' => 'filename', 'value' => $wgSitename . '_export' ) );
		$form .= Xml::closeElement( 'input' );
		$form .= "</p>";
		$form .= "<p>";
		$form .= Xml::submitButton( wfMsg( 'ePub_submit' ) );
		$form .= "</p>";
		$form .= Xml::closeElement( 'form' );

		$wgOut->addHtml( $form );

	}
}
 
	function wfFilterPageePub( $page ) {
		return $page !== '' && $page !== null;
	}
?>
