<?php
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
require_once ('lib/initialise.php');

// Defaults
$valid    = false;
$page	  = 'taskView';
$output   = $page;
$success  = false;
$id       = 0;
$size     = 'd';
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
		$fp->comment->setType('task');
		$fp->comment->setAuthor($_SESSION['user']['userID']);
		
		$newID = $fp->comment->create($text, $notify);
		
		// Update Activity Log
		$fp->updateActivityLog('taskCommentNew', $id);
		
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

// Create Request Object
$fp->task = new task;
$fp->task->select($id);
	
// Check Access Rights and Load Task
if($fp->checkPermission('all_objects'))
{
	$task = $fp->task->loadTask();
	$imgs = $fp->loadScreenshots($id , '', $size, false, false);
	$docs = $fp->loadAllDocuments($id, '', false);
}
elseif($fp->checkPermission('assigned_objects'))
{
	$task = $fp->task->loadAssignedTask();
	$imgs = $fp->loadScreenshots($id, '', $size, true, false);
	$docs = $fp->loadAssignedDocuments($id, '', false);	
}
else
{
	$task = $fp->task->loadTask($_SESSION['user']['userID']);
	$imgs = $fp->loadScreenshots($id, $_SESSION['user']['userID'], $size, false, false);
	$docs = $fp->loadAllDocuments($id, $_SESSION['user']['userID'], false);
}

// Check Project was Found
if(empty($task['taskID']))
{
	$smarty->display('errorNotFound.tpl');
	exit();
}

// Mark Menu
$smarty->assign('belowTasks', true);
$smarty->assign('text'      , $lang[$page]);
$smarty->assign('page'      , array('taskView' => true));
$smarty->assign('task'      , $task);
$smarty->assign('imgs'      , $imgs);
$smarty->assign('docs'      , $docs);

// Trim the Whitespace
$smarty->load_filter('output','trimwhitespace');

// Display in Template
$smarty->display($output.'.tpl');

?>