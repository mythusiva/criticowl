/**
 * @license Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.startupOutlineBlocks = true;
	
	config.toolbar = 'Custom';

  config.toolbar_Custom =
    [
        { name: 'document', items : [ 'NewPage','Preview','Templates','Source' ] },
        { name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','-','Undo','Redo' ] },
        { name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','Scayt' ] },
        { name: 'insert', items : [ 'Image','Table','HorizontalRule','Smiley','SpecialChar','PageBreak' ] },
                '/',
        { name: 'styles', items : [ 'Styles','Format','FontSize' ] },
        { name: 'basicstyles', items : [ 'Bold','Superscript','Subscript','Italic','Strike','-','RemoveFormat' ] },
        { name: 'colors',      items : [ 'TextColor','BGColor' ] },
                '/',
        { name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'] },
        { name: 'links', items : [ 'Link','Unlink','Anchor' ] },
        
        { name: 'tools', items : [ 'Maximize','-','About' ] }
    ];
    
};
