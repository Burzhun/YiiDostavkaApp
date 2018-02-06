/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{	
	
	     config.language = 'ru';
	     config.filebrowserImageBrowseUrl = '/ckeditor/plugins/filemanager/browser/default/browser.html?Type=Image&Connector=/ckeditor/plugins/filemanager/connectors/php/connector.php';
	     config.filebrowserFileBrowseUrl  = '/ckeditor/plugins/filemanager/browser/default/browser.html?Type=File&Connector=/ckeditor/plugins/filemanager/connectors/php/connector.php';
	     config.filebrowserFlashBrowseUrl = '/ckeditor/plugins/filemanager/browser/default/browser.html?Type=Flash&Connector=/ckeditor/plugins/filemanager/connectors/php/connector.php';
	     config.filebrowserBrowseUrl      = '/ckeditor/plugins/filemanager/browser/default/browser.html?Type=File&Connector=/ckeditor/plugins/filemanager/connectors/php/connector.php';
};
