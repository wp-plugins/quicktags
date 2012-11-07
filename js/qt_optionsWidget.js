/**
 * JS Handles front end for quick-tags
 * widget.
 */
(function($) {

	var quickTags = {
		
		/**
		 * Adds a tag to the tag set if one doesn't exist, 
		 * otherwise adds it to the tag set.
		 */
		addTag: function(tag, postID){
			var thisObj = this;
			if(postID == undefined){
				postID = $('#qt_postID').val();
			}
			$.ajax({
					url     : qtJS.ajaxurl,
					dataType: 'json',
					type    : 'POST',
					data    : {action: 'addNewTagAJAX', nonce: qtJS.qtNonce, tag: tag, postID: postID},
					success : function( data, status, xhr ) {
							console.log(data);
							if( $('#tag-' + data.tagID).length > 0){
								alert('"' + tag + '" already used!');
							}else{
								$('.qt_postTags').append('<span class="qt_postTag" id="tag-' + data.tagID + '">' + 
																																			'<a href="' + data.tagLink + '">' + data.tag + '</a> ' +
																																			'<img src="' + qtJS.IMAGE_PATH + 'no-nohover.png" class="removeTag" data-tagid="' + data.tagID + '" alt="Remove tag" title="Remove tag" />' +
																																	'</span> ');
								var removeButton = $('#tag-' + data.tagID).find('img');
								thisObj.removeButtonClick(removeButton);
								thisObj.showRemove( $('#tag-' + data.tagID) );
							}
					}
			});		
		},
		
		/**
		 * Returns false if tag is < 2 chars or is empty.
		 * Empty entails the following strings:
		 * "", '', "\"\"", '\'\''
		 */
		validateTag: function(tag){
			if( tag.length < 2 ){
				return false;
			}else if( tag == "" ){
				return false;
			}else	if( tag == '' ){
				return false;
			}else if( tag == "\"\"" ){
				return false;
			}else if( tag == '\'\''){
				return false;
			}else{
				return true;
			}
		},
		/**
		 * Initialize autocomplete on searchElem where searchElem
		 * is an HTML DOMElement.
		 */
		initAutoComplete: function(searchElem){
			var cache = {}, lastXHR, thisObj = this;
			searchElem.autocomplete({
				create: function( event, ui ){
					$(this).keypress(function (e) {
					  if (e.which == 13) {
								var tag  = $(this).val();					  
					  	if ( thisObj.validateTag( tag ) ){
					  	 thisObj.addTag( tag );
						 		$(this).val("");					  	 
					  	}else{
									alert("Please type in a tag that is at least 2 characters long!");
					  	}
					  }
					});
				},
				minLength: 2,
				select: function( event, ui ){
					var tag = ui.item.label;
					thisObj.addTag(tag);
					$(this).val("");
				},
				source: function( request, response ) {
					var term = request.term;
					if ( term in cache ) {
						response( cache[ term ] );
						return;
					}
					lastXhr = 
						$.ajax({ 
							url     : qtJS.ajaxurl,
							dataType: 'json',
							type    : 'POST',
							data    : {action: 'searchTagsAJAX', nonce: qtJS.qtNonce, tagRequest: request},
							success : function( data, status, xhr ) {
								cache[ term ] = data;
								if ( xhr === lastXhr ) {
									response( data );
								}
							}
						});
				}
			});
		},
		
		/**
		 * Remove a tag
		 */
		removeTag: function(postID, tagID){
				if(tagID == undefined || tagID == 0){
					return;
				}
				$.ajax({ 
					url     : qtJS.ajaxurl,
					dataType: 'json',
					type    : 'POST',
					data    : {action: 'removeTagAJAX', nonce: qtJS.qtNonce, tagID: tagID, postID: postID},
					success : function( data, status, xhr ) {
						if(data.success){
							$('#tag-' + tagID).remove();
						}
					}
				});			
		},
		
		/**
		 * removeButton click event - calls removeTag()
		 */
		removeButtonClick: function(removeButton){
			var thisObj = this;
			removeButton.click(function(){
				var tagID = $(this).attr('data-tagid');
				var postID = $('#qt_postID').val();
				thisObj.removeTag(postID, tagID);
			})
		},
		
		/**
		 * Show tag removal button on tag hover
		 */
	 showRemove: function(tagElem){
		 $(tagElem).hover(
		 	function(){ $(this).find('img').attr('src', qtJS.IMAGE_PATH + 'no.png') }, 
		 	function(){ $(this).find('img').attr('src', qtJS.IMAGE_PATH + 'no-nohover.png') }
		 );
		},
		
		/**
		 * Initialize quickTags JS
		 */
		init: function(){
			this.initAutoComplete($('#qt_addTags'));
			this.showRemove( $('.qt_postTag') );
			this.removeButtonClick( $('.removeTag') );
		}
	}
	 
	$(document).ready(function(){
		quickTags.init();
	});
   
})(jQuery);


