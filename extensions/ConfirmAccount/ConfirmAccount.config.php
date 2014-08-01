<?php

# ######## Configuration variables ########
# IMPORTANT: DO NOT EDIT THIS FILE
# When configuring globals, set them at LocalSettings.php instead

# Set the person's bio as their userpage?
$wgMakeUserPageFromBio = true;
# Text to add to bio pages if the above option is on
$wgAutoUserBioText = '';

$wgAutoWelcomeNewUsers = true;
# Make the username of the real name?
$wgUseRealNamesOnly = true;

# How long to store rejected requests
$wgRejectedAccountMaxAge = 7 * 24 * 3600; // One week
# How long after accounts have been requested/held before they count as 'rejected'
$wgConfirmAccountRejectAge = 30 * 24 * 3600; // 1 month

# How many requests can an IP make at once?
$wgAccountRequestThrottle = 1;
# Can blocked users request accounts?
$wgAccountRequestWhileBlocked = false;

# Minimum biography specs
$wgAccountRequestMinWords = 50;

# Show ToS checkbox
$wgAccountRequestToS = true;
# Show confirmation info fields (notes,url,files if enabled)
$wgAccountRequestExtraInfo = true;
# If $wgAccountRequestExtraInfo, also enables file attachments
$wgAllowAccountRequestFiles = true;
# If files can be attached, what types can be used? (MIME data is checked)
$wgAccountRequestExts = array( 'txt', 'pdf', 'doc', 'latex', 'rtf', 'text', 'wp', 'wpd', 'sxw' );

# Prospective account request types.
# Format is an array of (integer => (subpage param,user group,autotext)) pairs.
# The integer keys enumerate the request types. The key for a type should not change. 
# Each type has its own request queue at Special:ConfirmAccount/<subpage param>.
# When a request of a certain type is approved, the new user:
# (a) is placed in the <user group> group (if not User or *)
# (b) has <autotext> appended to his or her user page
$wgAccountRequestTypes = array(
	0 => array( 'authors', 'user', null )
);

# If set, will add {{DEFAULTSORT:sortkey}} to userpages for auto-categories.
# The sortkey will be made by replacing the first element of this array
# (regexp) with the second. Set this variable to false to avoid sortkey use.
$wgConfirmAccountSortkey = false;
// For example, the below will do {{DEFAULTSORT:firstname, lastname}}
# $wgConfirmAccountSortkey = array( '/^(.+) ([^ ]+)$/', '$2, $1' );

# IMPORTANT: do we store the user's notes and credentials
# for sucessful account request? This will be stored indefinetely
# and will be accessible to users with crediential lookup permissions
$wgConfirmAccountSaveInfo = true;

# Send an email to this address when account requestors confirm their email.
# Set to false to skip this
$wgConfirmAccountContact = false;

# If ConfirmEdit is installed and set to trigger for createaccount,
# inject catpchas for requests too?
$wgConfirmAccountCaptchas = true;

# Storage repos. Has B/C for when this used FileStore.
$wgConfirmAccountFSRepos = array(
	'accountreqs' => array( # Location of attached files for pending requests
		'name'       => 'accountreqs',
		'directory'  => isset($wgFileStore['accountreqs']) ?
			$wgFileStore['accountreqs']['directory'] : "{$IP}/images/accountreqs",
		'url'        => isset($wgFileStore['accountreqs']) ?
			$wgFileStore['accountreqs']['url'] : null,
		'hashLevels' => isset($wgFileStore['accountreqs']) ?
			$wgFileStore['accountreqs']['hash'] : 3
	),
	'accountcreds' => array( # Location of credential files
		'name'       => 'accountcreds',
		'directory'  => isset($wgFileStore['accountcreds']) ?
			$wgFileStore['accountcreds']['directory'] : "{$IP}/images/accountcreds",
		'url'        => isset($wgFileStore['accountcreds']) ?
			$wgFileStore['accountcreds']['url'] : null,
		'hashLevels' => isset($wgFileStore['accountcreds']) ?
			$wgFileStore['accountcreds']['hash'] : 3
	)
);

# Restrict account creation
$wgGroupPermissions['*']['createaccount'] = false;
$wgGroupPermissions['user']['createaccount'] = false;
# Grant account queue rights
$wgGroupPermissions['bureaucrat']['confirmaccount'] = true;
# This right has the request IP show when confirming accounts
$wgGroupPermissions['bureaucrat']['requestips'] = true;

# If credentials are stored, this right lets users look them up
$wgGroupPermissions['bureaucrat']['lookupcredentials'] = true;

# Show notice for open requests to admins?
# This is cached, but still can be expensive on sites with thousands of requests.
$wgConfirmAccountNotice = true;

# End of configuration variables.
# ########