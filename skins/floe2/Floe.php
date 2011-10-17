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

	<div id="jump-links">
		<?php if( $this->data['showjumplinks'] ) { ?><?php $this->msg('jumpto') ?> <a href="#site-toc">Table of Contents</a>, <a href="#tocontent">Content</a><?php } ?>
	</div>
	<div id="header">
		<h1><?php $this->msg('tagline') ?></h1>
		<div id="top-links">
			<a href="<?php echo htmlspecialchars($this->data['nav_urls']['mainpage']['href']); ?>" id="logo"><img src="<?php echo htmlspecialchars($this->data['logopath']); ?>" alt="floe logo" /></a>
			
			<span class="links-header">User Links:</span>
			<ul id="user-links">
			<?php foreach($this->data['personal_urls'] as $key => $item) { ?>
				<li id="<?php echo Sanitizer::escapeId( "pt-$key" ) ?>"<?php if ($item['active']) { ?> class="active"<?php } ?>>
				<a href="<?php echo htmlspecialchars( $item['href'] ) ?>"<?php echo $skin->tooltipAndAccesskey('pt-'.$key) ?>
				<?php if( !empty( $item['class'] ) ) { ?> class="<?php echo htmlspecialchars( $item['class'] ) ?>"<?php } ?>><?php echo htmlspecialchars( $item['text'] ) ?></a></li>
			<?php } ?>
			</ul>
		</div>	
	</div>
		
	<div id="site-toc">
		<h2>Table of Contents</h2>
		<a name="site-toc"></a><ul>
		<?php 
		$pages = array("Introduction", "Why is this important?", "Who is this for?", "What is the approach?", "Techniques", "Your answers and input", "Evolving research" );
		foreach($pages as $page) { 
			echo "<li><a href='/index.php?title=".str_replace(' ','_',$page)."'>".$page."</a></li>";
		} ?>
		</ul>
	</div>
	<div id="container">		
		
		<div id="content">
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
		
		<div id="footer">         
          The FLOE Inclusive Learning Handbook, part of the <a href="http://floeproject.org" class="external text" rel="nofollow">FLOE Project</a>, is produced by the <a href="http://idrc.ocad.ca" class="external text" rel="nofollow">Inclusive Design Research Centre</a> at <a href="http://ocad.ca" class="external text" rel="nofollow">OCAD University</a>. FLOE is funded by a grant from <a href="http://www.hewlett.org" class="external text" rel="nofollow">The William and Flora Hewlett Foundation</a>.
        </div>
	</div>
	
		<?php $this->html('bottomscripts'); /* JS call to runBodyOnloadHook */ ?>

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
		$out->addStyle( 'floe/main.css', 'screen' );
		$out->addStyle( 'floe/rtl.css', '', '', 'rtl' );
	}

	function initPage( OutputPage $out ) {
		parent::initPage( $out );
		$this->skinname  = 'floe';
		$this->stylename = 'floe';
		$this->template  = 'FloeTemplate';
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