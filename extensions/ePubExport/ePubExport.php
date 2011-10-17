<?php
if ( !defined( 'MEDIAWIKI' ) )
     die ();
 
$wgExtensionCredits['specialpage'][] = array(
        'name' => 'ePubExport',
        'author' =>'Nahshon Una-Tsameret',
        'version' => '0.5.4 (2010-05-16)',
        'description' => 'renders a page as ePub for reading in eBooks',
        'url' => 'http://epubexport.sourceforge.net/wiki/'
);

$wgePubExportProperties = Array();

$dir = dirname(__FILE__) . '/';

// ePubExport settings:
$wgeBookLibDir = $dir . 'epub/';
$wgeBookTempDir = $wgeBookLibDir . 'temp/';
require_once( $wgeBookLibDir . 'EPub.php');

# Internationalisation file
$wgExtensionMessagesFiles['ePubPrint'] = $dir . 'ePubExport.i18n.php';
$wgExtensionAliasesFiles['ePubPrint'] = $dir . 'ePubExport.i18n.alias.php';
$wgSpecialPageGroups['ePubPrint'] = 'pagetools';

# Add special page.
$wgSpecialPages['ePubPrint'] = 'SpecialePub';
$wgAutoloadClasses['SpecialePub'] = $dir . 'ePubExport_body.php';
 
$wgHooks['SkinTemplateBuildNavUrlsNav_urlsAfterPermalink'][] = 'wfSpecialePubNav';
$wgHooks['SkinTemplateToolboxEnd'][] = 'wfSpecialePubToolbox';



 
function wfSpecialePubNav( &$skintemplate, &$nav_urls, &$oldid, &$revid ) {
	wfLoadExtensionMessages( 'ePubPrint' );
        $nav_urls['ePubPrint'] = array(
                        'text' => wfMsg( 'ePub_print_link' ),
                        'href' => $skintemplate->makeSpecialUrl( 'ePubPrint', "page=" . wfUrlencode( "{$skintemplate->thispage}" )  )
                );
        return true;
}
 
function wfSpecialePubToolbox( &$monobook ) {
	wfLoadExtensionMessages( 'ePubPrint' );
        if ( isset( $monobook->data['nav_urls']['ePubPrint'] ) )
                if ( $monobook->data['nav_urls']['ePubPrint']['href'] == '' ) {
                        ?><li id="t-isePub"><?php echo $monobook->msg( 'ePub_print_link' ); ?></li><?php
                } else {
                        ?><li id="t-ePub">
<?php
                                ?><a href="<?php echo htmlspecialchars( $monobook->data['nav_urls']['ePubPrint']['href'] ) ?>"><?php
                                        echo $monobook->msg( 'ePub_print_link' );
                                ?></a><?php
                        ?></li><?php
                }
        return true;
}
?>
