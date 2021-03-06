[~*
/**
 * Footprint
 *
 * A project management tool for web designers.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the Open Software License version 3.0
 *
 * This source file is subject to the Open Software License (OSL 3.0) that is
 * bundled with this package in the files license.txt / license.rst. It is
 * also available through the world wide web at this URL:
 * http://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to obtain it
 * through the world wide web, please send an email to
 * licensing@ellislab.com so we can send you a copy immediately.
 *
 * @package Footprint
 * @author Iarfhlaith Kelly
 * @copyright Copyright (c) 2007 - 2012, Iarfhlaith Kelly. (http://iarfhlaith.com/)
 * @license http://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * @link http://footprintapp.com
 * @since Version 1.0
 * @filesource
 */
*~]
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title>API - [~$user.organisation~] - Footprint</title>
	
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="cache-control" value="no-cache, no store, must-revalidate">
	<meta http-equiv="content-language" content="en-us" />
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	
	<link rel="shortcut icon" href="/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="/app/css/screen.css" />
	
	<script type="text/javascript" src="/app/jscript/plugins/jQuery.js"></script>
	<script type="text/javascript" src="/app/jscript/base.scripts.js"></script>
	<script type="text/javascript" src="/app/jscript/lang.js"></script>
	
	<script type="text/javascript">

	$(document).ready(function()
	{
		$('#settings4').submit(function ()
		{
			runFormVisualsSync();
    	});
  	});
	
	</script>
	
	<style type="text/css">
		[~include file='inc.css.tpl'~]
	</style>
	
</head>

<body>

[~include file='inc.nav.tpl'~]

<div id='content'>

	<h1 id='headerSettings'>Settings</h1>
	
	[~include file='inc.tab.tpl'~]
	
	<div class='settingOptions'>
	
		<h2>API Settings</h2>
		
		<p>
		The Footprint API lets you create your own ways to access information on the system. And, developers
		can use it to create new tools. Check out some of the <a href='#'>existing tools</a> that have already been built with
		the API.
		</p>
		
		<p>
		The <a href='/api' target='_blank'>Official API Documentation</a> is also a great place to help you get started writing your own apps.
		</p>
		
		<p></p>

		<div class='submit'>
		<form name='settings4' id='settings4' action='/app/settings4.php' method='post'>
			[~if $apiStatus~]
				<img src='/app/media/images/loaders/progress.gif' />
				<h3>API is Currently Running...</h3>
				<input type='submit' name='submit' id='submit' value='Turn Off' name='api' id='api' />
				<img src='/media/images/loaders/loading.gif' alt='Loading...' class='loader' id='loader' />
				<input type='hidden' name='action' value='off' />
			[~else~]
				<img src='/app/media/images/icons/redLarge.gif' />
				<h3>API Access to your account is currently Turned Off</h3>
				<input type='submit' id='submit' name='submit' value='Turn On API Access' name='api' id='api' />
				<img src='/media/images/loaders/loading.gif' alt='Loading...' class='loader' id='loader' />
				<input type='hidden' name='action' value='on' />
			[~/if~]
		</form>
		</div>
		
		<div>
			<br /><br />
		</div>
	
	</div>
	
</div>

[~include file='inc.end.tpl'~]

</body>
</html>
