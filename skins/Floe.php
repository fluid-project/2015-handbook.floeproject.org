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


define('INFUSION_LOC', "/extensions/infusion/");
define('FSS_LOC', INFUSION_LOC . "framework/fss/css/");
define('PREFS_CSS_LOC', INFUSION_LOC . "framework/preferences/css/");
define('JQ_CSS_LOC', INFUSION_LOC . "lib/jquery/ui/css/");

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

    <!-- div for the UI Options fat panel -->
    <div class="flc-prefsEditor-separatedPanel fl-prefsEditor-separatedPanel fl-clearfix">
        <!-- This is the div that will contain the Preference Editor component -->
        <div class="flc-slidingPanel-panel flc-prefsEditor-iframe"></div>

        <!-- This div is for the sliding panel that shows and hides the Preference Editor controls -->
        <div class="fl-panelBar">
            <span class="fl-prefsEditor-buttons">
                <button id="reset" class="flc-prefsEditor-reset fl-prefsEditor-reset"><span class="fl-icon-undo"></span> Reset</button>
                <button id="show-hide" class="flc-slidingPanel-toggleButton fl-prefsEditor-showHide"> Show/Hide</button>
            </span>
        </div>
    </div>

       <!-- MyInfusion.js was being included at the bottom of every generated page in
            MediaWiki 1.18.1 so addScriptFile in line #246 is not being used at the 
            moment, and the script is being included below.  Investigate this issue. -->
       <script src="/extensions/infusion/infusion-custom.js"></script>
       <script type="text/javascript">
            var floe = floe || {};

            (function ($, fluid) {
                floe.initUIOptions = function () {
	                fluid.uiOptions.prefsEditor(".flc-prefsEditor-separatedPanel", {
	                    "templatePrefix": "<?php global $wgScriptPath; echo $wgScriptPath; echo INFUSION_LOC?>/framework/preferences/html/",
	                    "messagePrefix": "<?php global $wgScriptPath; echo $wgScriptPath; echo INFUSION_LOC?>/framework/preferences/messages/",
	                    "tocTemplate": "<?php global $wgScriptPath; echo $wgScriptPath; echo INFUSION_LOC?>/components/tableOfContents/html/TableOfContents.html"
	                });
                };    
	            $(document).ready(function () {
	                floe.initUIOptions();
	            });
            })(jQuery, fluid);
 
        </script>

        <div class="fl-container-flex75 fl-centered">
            <div id="jump-links" class="fl-hidden-accessible">
                <?php if( $this->data['showjumplinks'] ) { ?><?php $this->msg('jumpto') ?> <a href="#site-toc">Table of Contents</a>, <a href="#tocontent">Content</a><?php } ?>
            </div>
            
        
            <!-- div for the table of contents, used by UI Options -->
            <div class="flc-toc-tocContainer toc"> </div>
        
            <div id="header" class="fl-col-flex2">
                <span class="links-header">User Links:</span>
                <ul id="user-links">
                <?php foreach($this->data['personal_urls'] as $key => $item) { ?>
                    <li id="<?php echo Sanitizer::escapeId( "pt-$key" ) ?>"<?php if ($item['active']) { ?> class="active"<?php } ?>>
                    <a href="<?php echo htmlspecialchars( $item['href'] ) ?>"<?php echo $skin->tooltipAndAccesskeyAttribs('pt-'.$key) ?>
                    <?php if( !empty( $item['class'] ) ) { ?> class="<?php echo htmlspecialchars( $item['class'] ) ?>"<?php } ?>><?php echo htmlspecialchars( $item['text'] ) ?></a></li>
                <?php } ?>
                </ul>
                <div class="fl-col-mixed-200">
                    <a href="<?php echo htmlspecialchars($this->data['nav_urls']['mainpage']['href']); ?>" id="logo"><img src="<?php echo htmlspecialchars($this->data['logopath']); ?>" alt="floe logo" /></a>
                    <h1><?php $this->msg('tagline') ?></h1>
                </div>
            </div>
        
            <div class="fl-col-mixed-250">
                <div id="site-toc" class="fl-col-fixed fl-force-left">
                    <h2>Table of Contents</h2>
                    <a name="site-toc"></a><ul>
                    <?php 
                    $pages = array("Home" => 
                                       array("is_link" => true),
                                   "Introduction" => 
                                       array("is_link" => false,
                                             "children" => array("Why is this important?" => array("is_link" => true),
                                                                 "What is the approach?" => array("is_link" => true)
                                                                )
                                            ),
                                   "Methods" => 
                                       array("is_link" => false,
                                             "children" => array("Inclusive learning" => array("is_link" => true),
                                                                 "Accessibility principles" => array("is_link" => true),
                                                                 "Techniques" => array("is_link" => true),
                                                                 "Learner needs and preferences" => array("is_link" => true),
                                                                 "Video content and learning" => array("is_link" => true),
                                                                 "Audio content and learning" => array("is_link" => true),
                                                                 "Authoring of content" => array("is_link" => true),
                                                                 "Cognitive considerations" => array("is_link" => true)
                                                                )
                                             ),
                                    "Inclusive EPUB 3" =>
                                       array("is_link" => true,
                                             "children" => array("Semantic markup - HTML 5 semantics and epub type" => array("is_link" => true),
                                                                 "WAI-ARIA - Beyond semantic tags" => array("is_link" => true),
                                                                 "Visual styling" => array("is_link" => true),
                                                                 "Handling graphics, video, and audio media" => array("is_link" => true),
                                                                 "Media overlays (narrations) and text-to-speech" => array("is_link" => true),
                                                                 "Accessibility metadata" => array("is_link" => true),
                                                                 "Resources" => array("is_link"=> true)
                                                                )
                                             )
                             );
                    
                    /**
                     * Print table of content recursively
                     * @param Array $pages  An array of the items that are listed on the table of content.
                     *        Example: (title => array("is_link" => [true|false], "children" => [array with the same example structure]))
                     * @param Integer $depth  The level to start with
                     */
                    function printTOC($pages, $depth) {
                        // Exit if the given menu is not an array with elements
                        if (!is_array($pages) || count($pages) == 0) return;
                        
                        foreach ($pages as $title => $attr) {
                            if ($attr["is_link"]) {
                                echo "<li class='site-toclevel-".$depth."'><a href='/index.php?title=".str_replace(' ','_',$title)."'>".$title."</a></span><br />";
                            } else {
                                echo "<li class='site-toclevel-".$depth."'>".$title."</span><br />";
                            }
                            
                            printTOC($attr["children"], $depth+1);
                        }
                    }
                    
                    printTOC($pages, 0);
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
                        echo '><a href="', htmlspecialchars($tab['href']), '"', $skin->tooltipAndAccesskeyAttribs('ca-'.$key), '>', htmlspecialchars($tab['text']), '</a></li>';
                    }?>
                    </ul>
                
                    <h2><?php $this->html('title'); ?></h2>
                    <?php $this->html('bodytext') ?>
                </div>    
            </div>        
            <div id="footer">         
              The Floe Inclusive Learning Design Handbook, part of the <a href="http://floeproject.org" class="external text" rel="nofollow">Floe Project</a>, is produced by the <a href="http://idrc.ocad.ca" class="external text" rel="nofollow">Inclusive Design Research Centre</a> at <a href="http://ocad.ca" class="external text" rel="nofollow">OCAD University</a>. Floe is funded by a grant from <a href="http://www.hewlett.org" class="external text" rel="nofollow">The William and Flora Hewlett Foundation</a>.
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
        
        /* fss */
        $out->addStyle(FSS_LOC.'fss-reset.css', 'screen');
        $out->addStyle(FSS_LOC.'fss-layout.css', 'screen');
        $out->addStyle(FSS_LOC.'fss-text.css', 'screen');

        /* UIO CSS files */
        $out->addStyle(PREFS_CSS_LOC.'fss/fss-theme-bw-prefsEditor.css', 'screen');
        $out->addStyle(PREFS_CSS_LOC.'fss/fss-theme-wb-prefsEditor.css', 'screen');
        $out->addStyle(PREFS_CSS_LOC.'fss/fss-theme-yb-prefsEditor.css', 'screen');
        $out->addStyle(PREFS_CSS_LOC.'fss/fss-theme-by-prefsEditor.css', 'screen');
        $out->addStyle(PREFS_CSS_LOC.'fss/fss-theme-lgdg-prefsEditor.css', 'screen');
        $out->addStyle(PREFS_CSS_LOC.'fss/fss-theme-dglg-prefsEditor.css', 'screen');
        $out->addStyle(PREFS_CSS_LOC.'fss/fss-text-prefsEditor.css', 'screen');
        $out->addStyle(JQ_CSS_LOC.'fl-theme-by/by.css', 'screen');
        $out->addStyle(JQ_CSS_LOC.'fl-theme-yb/yb.css', 'screen');
        $out->addStyle(JQ_CSS_LOC.'fl-theme-bw/bw.css', 'screen');
        $out->addStyle(JQ_CSS_LOC.'fl-theme-wb/wb.css', 'screen');
        $out->addStyle(JQ_CSS_LOC.'fl-theme-lgdg/lgdg.css', 'screen');
        $out->addStyle(JQ_CSS_LOC.'fl-theme-dglg/dglg.css', 'screen');
        $out->addStyle(PREFS_CSS_LOC.'PrefsEditor.css', 'screen');
        $out->addStyle(PREFS_CSS_LOC.'SeparatedPanelPrefsEditor.css', 'screen');

        $out->addStyle( 'floe/main.css', 'screen' );
        $out->addStyle( 'floe/rtl.css', '', '', 'rtl' );
    }

    function initPage( OutputPage $out ) {
        parent::initPage( $out );
        $this->skinname  = 'floe';
        $this->stylename = 'floe';
        $this->template  = 'FloeTemplate';

        /* UIO JS dependencies */
        // $out->addScriptFile(INFUSION_LOC.'MyInfusion.js');
    }

    function tocList($toc) {
        global $wgJsMimeType;

        return "<div id='toc'><h3>On this page</h3>".$toc."</ul></div><a name='tocontent'></a>";
    }
}
?>
