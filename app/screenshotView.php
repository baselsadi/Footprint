<?php

/**
 * Project:     Footprint - Business Collaboration for Web Designers
 * File:        screenshotView.php
 *
 * Please do not copy or distribute this code without prior consent
 * from Webstrong Ltd.
 *
 * This software is protected under Irish Copyright Law.
 *
 * @link http://www.footprintapp.com/
 * @copyright 2007-2009 Webstrong Ltd
 * @author Iarfhlaith Kelly <ik at webstrong dot ie>
 * @package Footprint
 * @version 1.0
 *
 * For questions, help, comments, discussion, etc., please visit the
 * Footprint Site Forums. http://www.footprintapp.com/forums
 *
 */

require_once ('lib/initialise.php');

// Defaults
$valid    = false;
$page	  = 'screenshotView';
$output   = $page;
$success  = false;
$id       = 0;
$size     = 'b';
$notify	  = false;

// Start Footprint
$fp = new footprint;
$fp->validator = new validator;
$fp->validator->setPage($page);
$fp->validator->setFormName('default');

// Authenticate User
$fp->authenticate(); 

// Clean ID Value
if(isset($_GET['id']))
{
	$id = cleanValue($_GET['id']);
}
elseif(isset($_POST['id']))
{
	$id = cleanValue($_POST['id']);
}

// Initialise Form Validators
if(empty($_POST))
{
	SmartyValidate::connect($smarty, true);
	SmartyValidate::register_validator('comment', 'comment', 'notEmpty');
	
	$smarty->assign('message'   , $fp->loadMessages());
}
else
{
	SmartyValidate::connect($smarty);
	
	if($valid=SmartyValidate::is_valid($_POST))
	{
		SmartyValidate::disconnect();
		
		// Clean Text
		$text = cleanValue($_POST['comment']);
		
		// Check whether to Notify
		if(isset($_POST['notify'])) $notify = true;
		
		// Create Comment Object
		$fp->comment = new comment;
		$fp->comment->setParent($id);
		$fp->comment->setAccID($fp->getAccID());
		$fp->comment->setType('screenshot');
		$fp->comment->setAuthor($_SESSION['user']['userID']);
		
		$newID = $fp->comment->create($text, $notify);
		
		// Update Activity Log
		$fp->updateActivityLog('screenshotCommentNew', $id);
		
		if(is_numeric($newID)) $success = true;
		
		// Reset Smarty Validate
		SmartyValidate::connect($smarty, true);
		SmartyValidate::register_validator('comment' , 'comment' , 'notEmpty');
	}
	
	// Process Results for Correct Response
	$res = $fp->validator->loadResponse($valid, $success);
	if($res['redirect'])
	{
		$_SESSION['message'] = $res;
		$res['redirect'] 	 = $res['redirect'].'?id='.$id;
	}
	
	if(isset($_POST['ajax']))
	{
		$output = 'json';
		$smarty->assign('response',  json_encode($res));
	}
	else
	{
		if($res['redirect'])
		{
			header('Location: '.$res['redirect']);
			exit();
		}
	}
}

// Create Screenshot Object
$fp->screenshot = new screenshot;
$fp->screenshot->select($id);

// Check Permissions
if($fp->checkPermission('all_objects'))
{
	$ss = $fp->screenshot->loadScreenshot();
}
elseif($fp->checkPermission('assigned_objects'))
{
	$ss = $fp->screenshot->loadScreenshot('', true);
}
else
{
	$ss = $fp->screenshot->loadScreenshot($_SESSION['user']['userID']);
}

// Check Project was Found
if(empty($ss['screenshotID']))
{
	$smarty->display('errorNotFound.tpl');
	exit();
}

// Mark Menu
$smarty->assign('belowScreenshots', true);
$smarty->assign('page'            , array('screenshotView' => true));
$smarty->assign('screenshot'      , $ss);

// Trim the Whitespace
$smarty->load_filter('output','trimwhitespace');

// Display in Template
$smarty->display($output.'.tpl');

?>