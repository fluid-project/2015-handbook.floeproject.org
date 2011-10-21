<?php
/**
 * Floe
 *
 * @file
 * @ingroup Skins
 */

if( !defined( 'MEDIAWIKI' ) )
	die( -1 );

/** */
//require_once( dirname(__FILE__) . '/MonoBook.php' );



class FloeTemplate extends QuickTemplate {
	/**
	 * Template filter callback for this skin.
	 * Takes an associative array of data set from a SkinTemplate-based
	 * class, and a wrapper for MediaWiki's localization database, and
	 * outputs a formatted page.
	 */
	public function execute() {
		global $wgUser, $wgSitename;
        $skin = $wgUser->getSkin();

		// suppress warnings to prevent notices about missing indexes in $this->data
		wfSuppressWarnings();
 
 		$this->html( 'headelement' );
?>
	<!-- head>
	<title><?php echo $this->mHTMLtitle; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="generator" content="MediaWiki 1.16.0" />
	<link rel="alternate" type="application/x-wiki" title="Edit" href="/index.php?title=Techniques&amp;action=edit" />
	<link rel="edit" title="Edit" href="/index.php?title=Techniques&amp;action=edit" />
	<link rel="shortcut icon" href="/favicon.ico" />
	<link rel="search" type="application/opensearchdescription+xml" href="/opensearch_desc.php" title="Inclusive Learning Handbook (en)" />
	<link rel="alternate" type="application/atom+xml" title="Inclusive Learning Handbook Atom feed" href="/index.php?title=Special:RecentChanges&amp;feed=atom" />
	<link rel="stylesheet" href="/skins/common/shared.css?270" media="screen" />
	<link rel="stylesheet" href="/skins/common/commonPrint.css?270" media="print" />
	<link rel="stylesheet" href="/skins/floe/main.css?270" media="screen" />
	<link rel="stylesheet" href="/index.php?title=MediaWiki:Common.css&amp;usemsgcache=yes&amp;ctype=text%2Fcss&amp;smaxage=18000&amp;action=raw&amp;maxage=18000" />
	<link rel="stylesheet" href="/index.php?title=MediaWiki:Print.css&amp;usemsgcache=yes&amp;ctype=text%2Fcss&amp;smaxage=18000&amp;action=raw&amp;maxage=18000" media="print" />
	
	<link rel="stylesheet" href="/index.php?title=MediaWiki:Floe.css&amp;usemsgcache=yes&amp;ctype=text%2Fcss&amp;smaxage=18000&amp;action=raw&amp;maxage=18000" />
	<link rel="stylesheet" href="/index.php?title=-&amp;action=raw&amp;maxage=18000&amp;gen=css" />
	<script type="text/javascript">
	var skin="floe",
	stylepath="/skins",
	wgUrlProtocols="http\\:\\/\\/|https\\:\\/\\/|ftp\\:\\/\\/|irc\\:\\/\\/|gopher\\:\\/\\/|telnet\\:\\/\\/|nntp\\:\\/\\/|worldwind\\:\\/\\/|mailto\\:|news\\:|svn\\:\\/\\/",
	wgArticlePath="/index.php/$1",
	wgScriptPath="",
	wgScriptExtension=".php",
	wgScript="/index.php",
	wgVariantArticlePath=false,
	wgActionPaths={},
	wgServer="http://dev.handbook.floeproject.org",
	wgCanonicalNamespace="",
	wgCanonicalSpecialPageName=false,
	wgNamespaceNumber=0,
	wgPageName="Techniques",
	wgTitle="Techniques",
	wgAction="view",
	wgArticleId=3,
	wgIsArticle=true,
	wgUserName=null,
	wgUserGroups=null,
	wgUserLanguage="en",
	wgContentLanguage="en",
	wgBreakFrames=false,
	wgCurRevisionId=72,
	wgVersion="1.16.0",
	wgEnableAPI=true,
	wgEnableWriteAPI=true,
	wgSeparatorTransformTable=["", ""],
	wgDigitTransformTable=["", ""],
	wgMainPageTitle="Introduction",
	wgFormattedNamespaces={"-2": "Media", "-1": "Special", "0": "", "1": "Talk", "2": "User", "3": "User talk", "4": "Inclusive Learning Handbook", "5": "Inclusive Learning Handbook talk", "6": "File", "7": "File talk", "8": "MediaWiki", "9": "MediaWiki talk", "10": "Template", "11": "Template talk", "12": "Help", "13": "Help talk", "14": "Category", "15": "Category talk"},
	wgNamespaceIds={"media": -2, "special": -1, "": 0, "talk": 1, "user": 2, "user_talk": 3, "inclusive_learning_handbook": 4, "inclusive_learning_handbook_talk": 5, "file": 6, "file_talk": 7, "mediawiki": 8, "mediawiki_talk": 9, "template": 10, "template_talk": 11, "help": 12, "help_talk": 13, "category": 14, "category_talk": 15, "image": 6, "image_talk": 7},
	wgSiteName="Inclusive Learning Handbook",
	wgCategories=[],
	wgRestrictionEdit=[],
	wgRestrictionMove=[];
	</script>
	<script src="/skins/common/wikibits.js?270" type="text/javascript"></script>
	<script src="/skins/common/ajax.js?270" type="text/javascript"></script>
	<script src="/index.php?title=-&amp;action=raw&amp;gen=js&amp;useskin=floe&amp;270" type="text/javascript"></script>
	</head -->

    <!-- div for the UI Options fat panel -->
    <div class="flc-uiOptions-fatPanel fl-uiOptions-fatPanel fl-clearfix">
        <!-- This is the div that will contain the UI Options component -->
        <div id="myUIOptions" class="flc-slidingPanel-panel flc-uiOptions-iframe"></div>     
    
        <!-- This div is for the sliding panel that shows and hides the UI Options controls -->
        <div class="fl-panelBar fl-container-flex75 fl-centered">
            <button class="flc-slidingPanel-toggleButton fl-toggleButton">The show/hide button label will go here</button>
        </div>
    </div>  

       <script type="text/javascript">
            var demo = demo || {};

            // Define the functions that will be used by the demo
            (function ($, fluid) {
                demo.initPageEnhancer = function () {
                    fluid.pageEnhancer({
                        // Tell UIEnhancer where to find the table of contents' template URL
                        tocTemplate: "<?php  global $wgScriptPath; echo $wgScriptPath; ?>/extensions/infusion/components/tableOfContents/html/TableOfContents.html"
                    });
                };
                
                demo.initUIOptions = function () {
                    fluid.uiOptions.fatPanel(".flc-uiOptions-fatPanel", {
                        // Tell UIOptions where to find all the templates, relative to this file
                        prefix: "<?php global $wgScriptPath; echo $wgScriptPath; ?>/extensions/infusion/components/uiOptions/html/",
                        // Provide custom strings for slidingPanel button
                        slidingPanel: {
                            options: {
                                strings: {
                                    showText: "Display Preferences",
                                    hideText: "Display Preferences"
                                }
                            }
                        }
                        
                    });
                };    
            })(jQuery, fluid);
        </script>


        <?php $this->html('bottomscripts'); /* JS call to runBodyOnloadHook */ ?>

        <script type="text/javascript">
            // Initialize the page enhancer right away
            demo.initPageEnhancer();
            demo.initUIOptions();
        </script>

<div class="fl-container-flex75 fl-centered">
	<div id="jump-links" class="fl-hidden-accessible">
		<?php if( $this->data['showjumplinks'] ) { ?><?php $this->msg('jumpto') ?> <a href="#site-toc">Table of Contents</a>, <a href="#tocontent">Content</a><?php } ?>
	</div>
	

    <!-- div for the table of contents, used by UI Options -->
    <div class="flc-toc-tocContainer toc"> </div>

    <div id="header" class="fl-col-flex2">
        <div class="fl-fix">
            <span class="links-header">User Links:</span>
            <ul id="user-links">
            <?php foreach($this->data['personal_urls'] as $key => $item) { ?>
                <li id="<?php echo Sanitizer::escapeId( "pt-$key" ) ?>"<?php if ($item['active']) { ?> class="active"<?php } ?>>
                <a href="<?php echo htmlspecialchars( $item['href'] ) ?>"<?php echo $skin->tooltipAndAccesskey('pt-'.$key) ?>
                <?php if( !empty( $item['class'] ) ) { ?> class="<?php echo htmlspecialchars( $item['class'] ) ?>"<?php } ?>><?php echo htmlspecialchars( $item['text'] ) ?></a></li>
            <?php } ?>
            </ul>
        </div>  
        <div class="fl-col-mixed-200">
            <a href="<?php echo htmlspecialchars($this->data['nav_urls']['mainpage']['href']); ?>" id="logo"><img src="<?php echo htmlspecialchars($this->data['logopath']); ?>" alt="floe logo" /></a>
            <h1><?php $this->msg('tagline') ?></h1>
        </div>
    </div>



	<div class="fl-col-mixed-200">	
		<div id="site-toc" class="fl-col-fixed fl-force-left">
			<h2>Table of Contents</h2>
			<a name="site-toc"></a><ul>
			<?php 
			$pages = array("Introduction", "Why is this important?", "Who is this for?", "What is the approach?", "Techniques" );
			foreach($pages as $page) { 
				echo "<li><a href='/index.php?title=".str_replace(' ','_',$page)."'>".$page."</a></li>";
			} ?>
			</ul>
			
			<h2>Tools</h2>
			<ul>
			<?php
			wfRunHooks( 'MonoBookTemplateToolboxEnd', array( &$this ) );
			wfRunHooks( 'SkinTemplateToolboxEnd', array( &$this ) );
			echo "<li><a href='".htmlspecialchars($this->data['nav_urls']['mainpage']['href'])."&action=pdfbook'>Export PDF Book</a></li>";
			?>
			</ul>
		</div>
		
		<div class="fl-col-flex">					
			<span class="links-header">Page Links:</span>
			<ul id="page-links"><?php
			foreach( $this->data['content_actions'] as $key => $tab ) { 
				echo '<li id="', Sanitizer::escapeId( "ca-$key" ), '"';
				if ( $tab['class'] ) {
					echo ' class="', htmlspecialchars($tab['class']), '"';
				}
				echo '><a href="', htmlspecialchars($tab['href']), '"', $skin->tooltipAndAccesskey('ca-'.$key), '>', htmlspecialchars($tab['text']), '</a></li>';
			}?>
			</ul>
		
			<h2><?php $this->html('title'); ?></h2>
			<?php $this->html('bodytext') ?>
		</div>	
	</div>		
	<div id="footer">         
	  The FLOE Inclusive Learning Handbook, part of the <a href="http://floeproject.org" class="external text" rel="nofollow">FLOE Project</a>, is produced by the <a href="http://idrc.ocad.ca" class="external text" rel="nofollow">Inclusive Design Research Centre</a> at <a href="http://ocad.ca" class="external text" rel="nofollow">OCAD University</a>. FLOE is funded by a grant from <a href="http://www.hewlett.org" class="external text" rel="nofollow">The William and Flora Hewlett Foundation</a>.
	</div>
	
</div>
		</body>
		</html>
		<?php
		wfRestoreWarnings();
	} // end of execute() method
} // end of class


/**
 * Inherit main code from SkinTemplate, set the CSS and template filter.
 * @ingroup Skins
 */
class SkinFloe extends SkinTemplate {
	var $skinname = 'floe', $stylename = 'floe',
	$template = 'FloeTemplate', $useHeadElement = true;

	function setupSkinUserCss( OutputPage $out ){
		parent::setupSkinUserCss( $out );
		
		/* fss */
		$fss_loc = "/extensions/infusion/framework/fss/css/";
		$out->addStyle($fss_loc.'fss-reset.css', 'screen');
		$out->addStyle($fss_loc.'fss-layout.css', 'screen');
		$out->addStyle($fss_loc.'fss-text.css', 'screen');
		/*$out->addStyle($fss_loc.'fss-theme-coal.css', 'screen');*/

		/* UIO CSS files */
        $uio_loc = "/extensions/infusion/components/uiOptions/css/";
        $out->addStyle($uio_loc.'fss/fss-theme-bw-uio.css', 'screen');
        $out->addStyle($uio_loc.'fss/fss-theme-wb-uio.css', 'screen');
        $out->addStyle($uio_loc.'fss/fss-theme-yb-uio.css', 'screen');
        $out->addStyle($uio_loc.'fss/fss-theme-by-uio.css', 'screen');
        $out->addStyle($uio_loc.'fss/fss-text-uio.css', 'screen');
        $out->addStyle($uio_loc.'FatPanelUIOptions.css', 'screen');

		$out->addStyle( 'floe/main.css', 'screen' );
		$out->addStyle( 'floe/rtl.css', '', '', 'rtl' );
	}

	function initPage( OutputPage $out ) {
		parent::initPage( $out );
		$this->skinname  = 'floe';
		$this->stylename = 'floe';
		$this->template  = 'FloeTemplate';

        /* UIO JS dependencies */
        $infusion_loc = "/extensions/infusion/";
        $out->addScriptFile($infusion_loc.'MyInfusion.js');
	}

	function tocList($toc) {
		global $wgJsMimeType;

		return "<div id='toc'><h3>Contents</h3>".$toc."</ul></div><a name='tocontent'></a>";
	}
	
	function prevNextLink($dir) {
		$title = $this->mTitle;
		
		$pages = array("Introduction", "Why is this important?", "Who is this for?", "What is the approach?", "Techniques" );
		
		/* get list of high level pages from db */
		$dbr = &wfGetDB(DB_SLAVE);
        $pageTable = $dbr->tableName('page');
                
       //	$res = $dbr->query("SELECT * FROM page WHERE page_namespace=0 ORDER BY page_id DESC");				
		
		$loc = array_search($title, $pages);
		
		if ($title=="Table of Contents") {
			if ($dir=="previous")
				return false;
			else
				return "<a href='/index.php?title=Introduction'>Introduction</a>";
				
		} else if ($dir == "previous" && $loc>0) {
			return "<a href='/index.php?title=".str_replace(' ','_',$pages[$loc-1])."'>".$pages[$loc-1]."</a>";
			
		} else if ($dir == "next" && $loc+1<count($pages)) {
			return "<a href='/index.php?title=".str_replace(' ','_',$pages[$loc+1])."'>".$pages[$loc+1]."</a>";
			
		} else if ($dir == "current") {
			return "<a href='/index.php?title=".str_replace(' ','_',$pages[$loc])."'>".$pages[$loc]."</a>";
			
		} else {
			return false;
		}
	}
	
}
?>