<?
/**
 * The main GUI for the ImageManager.
 * @author $Author: Wei Zhuo $
 * @version $Id: manager.php 26 2004-03-31 02:35:21Z Wei Zhuo $
 * @package ImageManager
 */

	require_once('config.inc.php');
	require_once('Classes/ImageManager.php');
	
	$manager = new ImageManager($IMConfig);
	$dirs = $manager->getDirs();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
	<title>Insert Image</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <link href="assets/manager.css" rel="stylesheet" type="text/css" />	
<script type="text/javascript" src="assets/popup.js"></script>
<script type="text/javascript" src="assets/dialog.js"></script>
<script type="text/javascript">
/*<![CDATA[*/
	window.resizeTo(600, 520);

	if(window.opener)
		I18N = window.opener.ImageManager.I18N;

	var thumbdir = "<? echo $IMConfig['thumbnail_dir']; ?>";
	var base_url = "<? echo $manager->getBaseURL(); ?>";
/*]]>*/
</script>
<script type="text/javascript" src="assets/manager.js"></script>
</head>
<body>
<div class="title">Insert Image</div>
<form action="images.php" id="uploadForm" method="post" enctype="multipart/form-data">
<fieldset><legend>Image Manager</legend>
<div class="dirs">
	<label for="dirPath">Directory</label>
	<select name="dir" class="dirWidth" id="dirPath" onchange="updateDir(this)">
	<option value="/">/</option>
<? foreach($dirs as $relative=>$fullpath) { ?>
		<option value="<? echo rawurlencode($relative); ?>"><? echo $relative; ?></option>
<? } ?>
	</select>
	<a href="#" onclick="javascript: goUpDir();" title="Directory Up"><img src="img/btnFolderUp.gif" height="15" width="15" alt="Directory Up" /></a>
<? if($IMConfig['safe_mode'] == false && $IMConfig['allow_new_dir']) { ?>
	<a href="#" onclick="newFolder();" title="New Folder"><img src="img/btnFolderNew.gif" height="15" width="15" alt="New Folder" /></a>
<? } ?>
	<div id="messages" style="display: none;"><span id="message"></span><img SRC="img/dots.gif" width="22" height="12" alt="..." /></div>
	<iframe src="images.php" name="imgManager" id="imgManager" class="imageFrame" scrolling="auto" title="Image Selection" frameborder="0"></iframe>
</div>
</fieldset>
<!-- image properties -->
	<table class="inputTable">
		<tr>
			<td align="right"><label for="f_url">Image File</label></td>
			<td><input type="text" id="f_url" class="largelWidth" value="" /></td>
			<td rowspan="3" align="right">&nbsp;</td>
			<td align="right"></td>
			<td><input type="hidden" id="f_width" value=""/></td>
			<td rowspan="2" align="right"></td>
			<td rowspan="3" align="right">&nbsp;</td>
			<td align="right"></td>
			<td><input type="hidden" id="f_vert" class="smallWidth" value="" /></td>
		</tr>		
		<tr>
			<td align="right"></td>
			<td><input type="hidden" id="f_alt" value="" /></td>
			<td align="right"></td>
			<td><input type="hidden" id="f_height" value="" /></td>
			<td align="right"></td>
			<td><input type="hidden" id="f_horiz" class="smallWidth" value="" /></td>
		</tr>
		<tr>
<? if($IMConfig['allow_upload'] == true) { ?>
			<td align="right"><label for="upload">Upload</label></td>
			<td>
				<table cellpadding="0" cellspacing="0" border="0">
                  <tr>
                    <td><input type="file" name="upload" id="upload"/></td>
                    <td>&nbsp;<button type="submit" name="submit" onclick="doUpload();"/>Upload</button></td>
                  </tr>
                </table>
			</td>
<? } else { ?>
			<td colspan="2"></td>
<? } ?>
			<td align="right"></td>
			<td colspan="2"><input type="hidden" id="f_align" value="" />
			</td>
			<td align="right"></td>
			<td><input type="hidden" id="f_border" value="" /></td>
		</tr>
		<tr> 
         <td colspan="4" align="right">
				<input type="hidden" id="orginal_width" />
				<input type="hidden" id="orginal_height" />
            <input type="hidden" id="constrain_prop" />
          </td>
          <td colspan="5"></td>
      </tr>
	</table>
<!--// image properties -->	
	<div style="text-align: right;"> 
          <hr />
		  <button type="button" class="buttons" onclick="return refresh();">Refresh</button>
          <button type="button" class="buttons" onclick="return onOK();">OK</button>
          <button type="button" class="buttons" onclick="return onCancel();">Cancel</button>
    </div>
	<input type="hidden" id="f_file" name="f_file" />
</form>
</body>
</html>
