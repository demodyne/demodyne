/**
 * These are the global variables
 */
var partnerDashboardSearchOpportunitiesFormCoveredDepartments = [];
var partnerDashboardSearchOpportunitiesFormCoveredCategories = [];
var partnerDashboardSearchOpportunitiesFormCoveredKeywords = [];
var inboxListTimer;
var newsListTimer;
/**
 * These are the helper functions
 */
$(document).ajaxError(function(event, request, settings) {
    if (request.status==401) {
       window.location = location.pathname.substring(location.pathname.indexOf("/"));
    }
});

	function updateContextualHelp($hash) {
		$('#contextual-help').attr('href', $('#contextual-help').data('base-url')+$hash);
	}
	
	(function ( $ ) {
		 
	    $.fn.reload = function(link) {
	        this.parent().load(link);
	        return this;
	    };
	 
	}( jQuery ));
/**
 * Tab navigation history
 */
	$(function(){
		var myTabs = [ "#inbox", "#mycontacts", "#favorites", "#myprop2", "#myscn", "#impl", "#hours", "#fundings", "#draftmeasures", "#banner", "#newsletter", "#events" ];
		if (location.hash !== '') {
			$('a[href="' + location.hash + '"]').tab('show');
			updateContextualHelp(location.hash);
		    if ($.inArray(location.hash, myTabs) > -1) {
		    	$('[href=#myspace]').tab('show');
		    }
		}
			  // add a hash to the URL when the user clicks on a tab
			  $('a[data-toggle="tab"]').on('click', function(e) {
				  var href;
				  if (typeof $(this).data('tabs') === 'undefined') {
					  href = $(this).attr('href');
					  history.pushState(null, null, href);	  
				  }
				  else {
					  href = $('.'+$(this).data('tabs')).find('.active > a').attr('href');
					  history.pushState(null, null, href);
				  }
			    
			    updateContextualHelp(href);
			  });
			  // navigate to a tab when the history changes
			  window.addEventListener("popstate", function(e) {
			    var activeTab;
			    if (location.hash !== '') {
			    	activeTab = $('[href=' + location.hash + ']');
				    if (activeTab.length) {
				        activeTab.tab('show');
				        updateContextualHelp(location.hash);
				    }
				    //console.log(found);
				    if ($.inArray(location.hash, myTabs) > -1) {
				    	$('[href=#myspace]').tab('show');
				    }
			    }
			    else {
				      $('.nav-tabsj a:first').tab('show');
				      updateContextualHelp($('.nav-tabsj a:first').attr('href'));
				    }
			  });
	});
	/**
	 * Layout management & history management
	 */
	 $(function(){
		 
		 
		 $('a#layout-browse').popover({ 
         	trigger: "manual" , 
         	html: true, 
         	placement: 'auto',
         	container: 'body',
         	animation:false, 
         	content: function(){
         		
         		if (!$('#layout-browse-content').length) {
    	    		$('<div id="layout-browse-content" style="display:none">')
    			        .html('Loading...')
    			        .appendTo('#pageContent'); 
    	    		$.ajax({
    		            url: $('#layout-browse').attr('href'),
    		            success: function(response){
    		                $('#layout-browse-content').html(response);
    		                //$('.popover-content').html(response);
    		                $('#layout-browse').popover("show");
    		            }
    		        });
    	    	}
    	    	return $('#layout-browse-content').html();
    	    	
         	}
         })
 		.on("click", function () {
 			console.log('pop');
     	    var _this = this;
     	    $('.popover').popover('hide');
     	    $(this).popover("show");
     	    return false;
     	})
     	;
	    	
	    	$('body').on('click', function (e) {
	    	    //only buttons
	    	    if (!$(e.target).parent().hasClass('mini-profile')
	    	        && $(e.target).parents('.popover.in').length === 0) { 
	    	    	$('#layout-browse').popover('hide');
	    	    }
	    	});
		 
		 
		 
		 $('a#history-back').click(function() {
			 parent.history.back();
			return false;
		 });
		 
		 $('button#history-back').click(function() {
			 parent.history.back();
		 });
		 
		 $('button#history-home').click(function() {
			 window.location.href = $(this).attr('href');
		 });
		 
		 $('#layout-goto-inbox').on('click', function() {
			 if ($('#inbox').length) {
				 $('[href=#myspace]').tab('show');
				 $('[href=#inbox]').tab('show');
				 return false;
			 }
			 window.location.href = $(this).attr('href');
		 });
		 
		 $('#submit-bug').click(function() {
			 modalDialog('submit-bug-dialog', $(this).data("dialog-title"), $(this).attr("href"), false);
			 return false;
		 });
		 
		 $('button#please-register-button, button#viewing-mode').click(function() {
			 modalDialog('guest-dialog', $(this).data("dialog-title"), $(this).data("url"), false);
			 return false;
		 });
		 
	 });
	  $(function(){
            $(document).tooltip({
                position: {
                    my: "center top",
                    at: "center bottom+5",
                },
                content: function () {
                    return $(this).prop('title');
                },
                show: {
                    duration: 0,
              	  delay: 0,
              	effect: "fold"
                  },
                  hide: {
                	  duration: 0,
                	  delay: 0,
                    	effect: "fold"
                  }
            });
		// IconSelectMenu initialization
	    $.widget( "custom.iconselectmenu", $.ui.selectmenu, {
	        _renderItem: function( ul, item ) {
	          var li = $( "<li>", { text: item.label } );
	          if ( item.disabled ) {
	            li.addClass( "ui-state-disabled" );
	          }
	          $( "<span>", {
	            style: item.element.attr( "data-style" ),
	            "class": "ui-icon " + item.element.attr( "data-class" )
	          })
	            .appendTo( li );
	          return li.appendTo( ul );
	        }
	      });
	});
	  /****************************************************************
	   *															  *
	   * 			 Proposal execution: partner find  				  *
	   * 							BEGIN						      *
	   * 															  *
	   ****************************************************************/
	    $(function() {
	  	  $('#menuFindPartners').click(function() {
	  		 $('#findPartners').slideDown();
	  		$('#selectedPartners').slideUp();
	  		$('#helpSelectPartners').slideUp();
	  		 return false;
	  	  });
	  	  $('#menuListPartners').click(function() {
	  			 $('#findPartners').slideUp();
	  			 $('#selectedPartners').slideDown();
	  			$('#helpSelectPartners').slideUp();
		  		return false;
	  		  });
	  	$('#menuHelpSelectPartners').click(function() {
 			 $('#findPartners').slideUp();
 			 $('#selectedPartners').slideUp();
 			$('#helpSelectPartners').slideDown();
	  		return false;
 		  });
	  	 $('#findPartnersForm').submit(function() { // catch the form's submit event
	  		    // verify if at least a category or a keyword is given
	  		    if ($('#partnerCategoryList span').length==0 && $('#partnerKeywordList span').length==0) {
	  		    	$.msg('You must choose at least a category or write a keyword.', 'error');
	  		    	return false;
	  		    }
	  		    $("#findPartnersIcon").removeClass("fa-search").addClass('fa-spinner').addClass('fa-pulse');
	  		    $.ajax({ // create an AJAX call...
	  		        data: $(this).serialize(), // get the form data
	  		        type: $(this).attr('method'), // GET or POST
	  		        url: $(this).attr('action'), // the file to call
	  		        success: function(response) { // on success..
	  		        	//alert(response);
	  		        	$('#findPartnersDiv').html(response);
//	  		        	$.getScript("/js/demodyne.js");
	  			 	  // update the DIV
	  		        },
	      	        complete: function() {
	      	        	$("#findPartnersIcon").removeClass("fa-spinner").removeClass('fa-pulse').addClass('fa-search');
	      	        }
	  		    });
	  		    return false; // cancel original event to prevent form submitting
	  		});
	      $( "#mainPartnerCategories" )
	          .iconselectmenu()
	          .iconselectmenu( "menuWidget")
	          .addClass( "ui-menu-icons avatar" );
	      $( "#subPartnerCategories" )
	          .selectmenu();
	      //updatePartnerSubCategories($("#mainPartnerCategories").val());
	      $("#mainPartnerCategories").iconselectmenu({
	          select: function (event, ui) {
	              //alert("the select event has fired!");
	              updatePartnerSubCategories($(this).val());
	          }
	      });
	      function updatePartnerSubCategories(main_category) {
	    	  $.post('/category/get-subcategories', // this is the actual link, update if necessary
	              { 'main_category' : main_category },
	               function(itemJson){
	               console.log(itemJson);
	               $('#subPartnerCategories').find('option').remove().end();
	               $('#subPartnerCategories').selectmenu('destroy').selectmenu({ style: 'dropdown' });
	            	  if (typeof itemJson.name != 'undefined') { // check if category has subcategories
	              		$("#subPartnerCategories").append('<option value="'+(-main_category)+'">All</option>');
	                  	for(var i=0;i<itemJson.name.length;i++) {
	                		  $("#subPartnerCategories").append('<option value="'+itemJson.id[i]+'">'+itemJson.name[i]+'</option>');
	                      }
	                  	// refresh jquery-ui selectmenu
	                  	$('#subPartnerCategories').selectmenu('refresh', true);
	            	  }
	        }, 'json');
	        return false;
	     }
	      $('#btnPartnerAddCategory').on('click', function() {
	          if ($('#partnerCategoryList span[id='+$('#subPartnerCategories').val()+']').length==0) {
	              $('#partnerCategoryList').append('<span class="badge" id="'+$('#subPartnerCategories').val()+'">'+$('#mainPartnerCategories option:selected').text()+' / '+$('#subPartnerCategories option:selected').text()+' <input name="partnerCategories[]" type="hidden" value="'+$('#subPartnerCategories').val()+'"><a><i class="fa fa-times-circle"></i></a></span>');
	              $('#partnerCategoryList span a').click(function() {
	                  $(this).parent().remove();
	              });
	          }
	      });
	      $('#partnerKeywords').on('keypress', function (e) {
	          if (e.which==13) {
	          	$('#btnPartnerAddKeyword').click();
	          	return false;
	          }
	  	});
	      $('#btnPartnerAddKeyword').on('click', function() {
	          var keyword = $.trim($('#partnerKeywords').val());
	          if (keyword.length>0 && $('#partnerKeywordList span[id="'+keyword+'"]').length==0) {
	              $('#partnerKeywordList').append('<span class="badge" id="'+keyword+'">'+keyword+' <input name="partnerKeywords[]" type="hidden" value="'+keyword+'"><a><i class="fa fa-times-circle"></i></a></span>');
	              $('#partnerKeywordList span a').click(function() {
	                  $(this).parent().remove();
	              });
	              $('#partnerKeywords').val('');
	          }
	      });
	   // calling AJAX for add and remove partner to proposal
		  $("#ckbAddRemovePartner").click(function(){
			    // Load the content of the page referenced in the a-tags href
			    var ref = $(this);
			    var user = $(this).attr('user');
			    var proposal = $(this).attr('proposal');
			    var url = '/city/proposal/implementation/add-remove-user/'+proposal+'/user/'+user;
			    $.ajax({ // create an AJAX call...
			        type: 'POST', // GET or POST
			        url: url, // the file to call
			        complete: function() {
	    	        	if (ref.is(":checked")) {
	    					   $.msg(ref.parent().parent().find('#partnerName').text()+' has been added to Partners list.', 'success');
	    				   }
	    	        	else {
	    	        		$.msg(ref.parent().parent().find('#partnerName').text()+' has been removed from Partners list.', 'success');
	    	        	}
	    	        	$('#partnerListSection').load('/city/proposal/implementation/get-partners/'+proposal, function() {
	    			 	  });
	    	        }
			    });
			});
	    });
	  /****************************************************************
	   *															  *
	   *							END							  	  *
	   * 			 Proposal execution: partner find  				  *
	   * 															  *
	   ****************************************************************/
//	    function showInside(href) {
//	    	$('#view-proposal-div').load(href, function () {
//                $('#user-dashboard-div').hide();
//                $('#view-proposal-div').show();
//            });
//	    }
//
//	    function goDashboard() {
//	    	$('#user-dashboard-div').show();
//			$('#view-proposal-div').hide();
//	    }
	    /**************************************************************************************************************************************************/
	    /**************************************************************************************************************************************************/
	    /*************           **    **  ********                                                                                         ***************************/
	    /*************           **    **  **    **                                                                                     ***************************/
	    /*************           **    **  **    **                                                                                         ***************************/
	    /*************           ********  ********                                                                                         ***************************/
	    /*************           ********  ********                                                                                             ***************************/
	    /*************           **    **  **    **                                                                                         ***************************/
	    /*************           **    **  **    **                                                                                     ***************************/
	    /*************           **    **  **    **                                                                                     ***************************/
	    /**************************************************************************************************************************************************/
	    /**************************************************************************************************************************************************/
	    /**
	     *  Handlers for the AdministrationDashboardController -> citizenProposalsAction
	     */
	    function administrationDashboardCitizenProposalsHandlers() {
	    	userMiniProfilePopover($('a#administration-dashboard-citizen-proposals-view-profile'));
            // calling AJAX for add and remove favorites
	    	proposalAddRemoveFavoriteHandler($("a#administration-dashboard-add-remove-favorite"));
	    	proposalAddProposalToProgramHandler($('a#administration-dashboard-citizen-proposals-add-proposal-to-scenario'), $('#administration-dashboard-citizen-proposals').data('add-proposal-to-scenario'));
            $("a#page-citizen-proposals").click(function(){
    		    $("#administration-dashboard-citizen-proposals").parent().load($(this).attr("href"));
    		    return false;
    		});
	    }
	    
	    function bannerMyBannersHandlers() {
	    	$('#banner-my-banners-add-banner').click(function(){
		  	    modalDialog('new-banner-dialog', $(this).data("dialog-title"), $(this).data("href"), true);
		  	    return false;
		  	});
	    }
	    
	    function bannerAddBannerHandlers() {
//	    	console.log("add banners");
	    	$("#bannerImage").on("change", function() {
				imageUploadPreview(this, $("#banner-add-banner-preview"));
		    });
	    	$('#banner-add-banner-save-button').click(function() {
	    		$(this).prop('disabled', 'disabled');
	    		$(this).find('i').removeClass("fa-floppy-o").addClass('fa-spinner').addClass('fa-pulse');
	    		$('#banner-add-banner-form').submit();
	    	});
	    	$('#banner-add-banner-publish-button').click(function () {
				$(this).prop('disabled', 'disabled');
				$(this).find('i').removeClass("fa-share-square-o").addClass('fa-spinner').addClass('fa-pulse');
				var action = $('#banner-add-banner-form').attr('action');
				if (action.indexOf("publish") < 0) {
					$('#banner-add-banner-form').attr('action', action+'/publish/true');
				}
				$('#banner-add-banner-form').submit();
			});
			$('#banner-add-banner-form').submit(function() { // catch the form's submit event
				console.log('submit');
				var fd = new FormData($('#banner-add-banner-form')[0]);
	  		    $.ajax({ // create an AJAX call...
	  		        data: fd, // get the form data
	  		        type: $(this).attr('method'), // GET or POST
	  		        url: $(this).attr('action'), // the file to call
	  		        processData: false,
			        contentType: false,
	  		        success: function(response) { // on success..
//	  		        	console.log(response);
	  		        	if (typeof response ==  'object') {
	  		        		updatePages(['#banner-active-banners', '#banner-inactive-banners', '#banner-carousel-banners']);
			        		$('.modal').modal('hide');
			        	    //$('#add-new-proposal-dialog').remove();
			        	}
			        	else {
			        	    // its not json
			        		$('#banner-add-banner').parent().html(response); // update the DIV
			        	}
	  		        },
	  		    });
	  		    return false; // cancel original event to prevent form submitting
			});
	    }
	    
	    function bannerInactiveBannersHandlers() {
	    	$('a#banner-inactive-banners-publish').click(function(){
		  	    modalDialog('publish-banner-dialog', $(this).data("dialog-title"), $(this).attr("href"), true);
		  	    return false;
		  	});
	    	$('a#banner-inactive-banners-edit').click(function(){
		  	    modalDialog('edit-banner-dialog', $(this).data("dialog-title"), $(this).attr("href"), true);
		  	    return false;
		  	});
	    	
	    	$('a#banner-inactive-banners-delete').click(function(){
		  	    modalDialog('delete-banner-dialog', $(this).data("dialog-title"), $(this).attr("href"), true);
		  	    return false;
		  	});
	    	
	    	$("a#page-inactive-banners").click(function(){
    		    $("#banner-inactive-banners").parent().load($(this).attr("href"));
    		    return false;
    		});
	    }
	    
	    function bannerActiveBannersHandlers() {
	    	
	    	$('a#banner-active-banners-edit').click(function(){
		  	    modalDialog('edit-banner-dialog', $(this).data("dialog-title"), $(this).attr("href"), true);
		  	    return false;
		  	});
	    	
	    	$('a#banner-active-banners-publish').click(function(){
		  	    modalDialog('publish-banner-dialog', $(this).data("dialog-title"), $(this).attr("href"), true);
		  	    return false;
		  	});
	    	
	    	$('a#banner-active-banners-delete').click(function(){
		  	    modalDialog('delete-banner-dialog', $(this).data("dialog-title"), $(this).attr("href"), true);
		  	    return false;
		  	});
	    	
	    	renumber_table('#banner-active-banners-table');
	    	
	      $("#banner-active-banners-table tbody").sortable({
	            helper: fixHelperModified,
	            stop: function(event,ui) {
	                renumber_table('#banner-active-banners-table');
	                $('#banner-active-banners-sort-form').submit();
	            }
	        }).disableSelection();
	      
	      $('#sort-proposals-save-button').click(function () {
	    		$('#sort-proposals-save-button').prop('disabled', 'disabled');
	    		$('#sort-proposals-save-button-icon').removeClass("fa-floppy-o").addClass('fa-spinner').addClass('fa-pulse');
	    		$('#sort-proposals-form').submit();
	    	});
	      
	    	$('#banner-active-banners-sort-form').submit(function() { // catch the form's submit event
	    		  //console.log('Calling submit...');
	    		  $.ajax({ // create an AJAX call...
	    		        data: $(this).serialize(), // get the form data
	    		        type: $(this).attr('method'), // GET or POST
	    		        url: $(this).attr('action'), // the file to call
	    		        success: function(response) { // on success..
		  		        	
		  		        	if (typeof response ==  'object') {
		  		        		updatePages(['#banner-carousel-banners']);
				        		
				        	}
		  		        },
	    		    });
	    		    return false; // cancel original event to prevent form submitting
	    		});
	    }
	    
	   
	    
	    function bannerPublishBannerHandlers() {
	    	
    		$('#publish-banner-yes-button').click(function () {
				$(this).prop('disabled', 'disabled');
				$(this).find('i').removeClass("fa-floppy-o").addClass('fa-spinner').addClass('fa-pulse');
				$.ajax({ // create an AJAX call...
			        type: 'POST', // GET or POST
			        url: $('#banner-publish-banner').data('url'),
			        success: function(response) { // on success..
//			        	console.log(response);
			        	if (typeof response ==  'object') {
			        		updatePages(['#banner-active-banners', '#banner-inactive-banners', '#banner-carousel-banners']);
//			        		if (typeof $('#banner-active-banners').data('url') !== 'undefined') {
//			        	    	$('#banner-active-banners').parent().load($('#banner-active-banners').data('url'));
//			        		}
//			        		if (typeof $('#banner-inactive-banners').data('url') !== 'undefined') {
//			        	    	$('#banner-inactive-banners').parent().load($('#banner-inactive-banners').data('url'));
//			        		}
//			        		if (typeof $('#banner-carousel-banners').data('url') !== 'undefined') {
//			        	    	$('#banner-carousel-banners').parent().load($('#banner-carousel-banners').data('url'));
//			        		}

			        	    $('.modal').modal('hide');
			        	}
			        	else {
			        	    // its not json
			        		$('#banner-publish-banner').parent().html(response); // update the DIV
			        	}
			        },
			    });
			    return false; // cancel original event to prevent form submitting
			});
    		
	    }
	    /**
	     *  Handlers for the BannerController -> deleteBannerAction
	     */
	    function bannerDeleteBannerHandlers() {
			$('#banner-delete-banner-yes').click(function () {
				$(this).prop('disabled', 'disabled');
				$(this).find('i').removeClass("fa-trash").addClass('fa-spinner').addClass('fa-pulse');
				$.ajax({ // create an AJAX call...
			        type: 'POST', // GET or POST
			        url: $(this).data('href'),
			        success: function(response) { // on success..
			        	if (typeof response ==  'object') {
			        		 $('.modal').modal('hide');
			        		 updatePages(['#banner-active-banners', '#banner-inactive-banners', '#banner-carousel-banners']);
			        	}
			        	else {
			        	    // its not json
			        		$('#banner-delete-banner').parent().html(response); // update the DIV
			        	}
			        },
			    });
			    return false; // cancel original event to prevent form submitting
			});
	    }
	    
	    function commentAddCommentHandlers() {
	    	
	    	$('#comment-add-comment-textbox').on('change propertychange click keyup input paste focus', function () {
				if ($('#comment-add-comment-textbox').html() == '' ) {
					$('#comment-add-comment-submit').prop('disabled', true);
				} else {
					$('#comment-add-comment-submit').prop('disabled', false);
				}
			});
	    	
	    	$('#comment-form').submit(function() { // catch the form's submit event
	        	$( "#addCommentIcon" ).removeClass("fa-comment-o").addClass('fa-spinner').addClass('fa-pulse');
	       	 	$('#comText').html($('#comment-add-comment-textbox').html());
	        	//var data = $('#commentForm').find('.nicEdit-main').text();
	        	//$('#comment').val(data);
	            $.ajax({ // create an AJAX call...
	                data: $(this).serialize(), // get the form data
	                type: $(this).attr('method'), // GET or POST
	                url: $(this).attr('action'), // the file to call
	                success: function(response) { // on success..
	                	//alert(response);
	                	//console.log(response);
	                	$('#comment-add-comment').html(response); // update the DIV
	                	//else alert('not OK');
	                    
	                }
	            });
	            return false; // cancel original event to prevent form submitting
	        });
	    	
	    }
	    
	    function commentListHandlers() {
	    	
	    	userMiniProfilePopover($('a#comment-list-profile'));
	    	
	    	// loading city proposition pages in div 
	  	  $('a#page-com').click(function(){
	  		    $('#comment-list').load($(this).attr("href"));
	  		    return false;
	  		});
	  	  // calling AJAX for add and remove favorites 
	  	  $('a#thumb').click(function(){
	  		    var ref = $(this);
	  		    $.post(ref.attr("href"),
	  	              { }, 
	  	              function(success){
	      	               if (success) {
	      	            	   var value = parseInt(ref.prev().text());
	      	            	   ref.prev().text(value+1);
	      	            	   ref.parent().children('#thumb').removeAttr('href');
	      	               }
	  		        }, 'json');
	  		    // Prevent browsers default behavior to follow the link when clicked
	  		    return false;
	  		});
	  	  
		  	$('a#comment-list-report').click(function(){
		  	    modalDialog('report-dialog', $('#comment-list').data('report-comment'), $(this).attr("href"), false);
		  	    return false;
		  	});
		  	
		  	$('a#comment-list-reply').click(function(){
		  	    var ref = $(this).parent().prev();
		  	    var mes = $('#comment-list').data('reply-text');
		  	    mes = mes.replace('%date%', ref.find('#comment-list-datetime').html())
		  	    mes = mes.replace('%user%', ref.find('#comment-list-username').html())
		  	    mes = mes.replace('%text%', ref.find('#comment-list-text').html());
		  	    
		  	    $('#comment-add-comment-textbox').html(mes).trigger('focus');
		  	    $("#comment-add-comment")[0].scrollIntoView({
		  	    	behavior: "smooth", // or "auto" or "instant"
		  	    	block: "start" // or "end"
		  	    });
//		  	    console.log(mes);
		  	    return false;
		  	});
	    	
	    }
	    
	    function commentListNoActionsHandlers() {
	    	
	    	
	    	// loading city proposition pages in div 
	  	  $('a#page-com').click(function(){
	  		  	console.log('page-com');
	  		    $('#comment-list-no-actions').load($(this).attr("href"));
	  		    return false;
	  		});
	  	  
	    	
	    }
	    
	    
	    function eventAddEventHandlers() {
	    	 $('#eventStartDateGroup').datetimepicker({
	             format: 'DD/MM/YYYY HH:mm',
//            	 autoclose: true,
	         });
	         $('#eventEndDateGroup').datetimepicker({
	             useCurrent: false, //Important! See issue #1075
//	             autoclose: true,
	             format: 'DD/MM/YYYY HH:mm'	
	         });
	         $("#eventStartDateGroup").on("dp.change", function (e) {
	             $('#eventEndDateGroup').data("DateTimePicker").minDate(e.date);
	         });
	         $("#eventEndDateGroup").on("dp.change", function (e) {
//	             $('#eventStartDateGroup').data("DateTimePicker").maxDate(e.date);
	         });
	    	$("#eventImage").on("change", function() {
				imageUploadPreview(this, $("#event-add-event-preview"));
		    });
	    	$('#event-add-event-save-button').click(function() {
	    		$(this).prop('disabled', 'disabled');
	    		$(this).find('i').removeClass("fa-floppy-o").addClass('fa-spinner').addClass('fa-pulse');
	    		$('#event-add-event-form').submit();
	    	});
	    	$('#event-add-event-publish-button').click(function () {
				$(this).prop('disabled', 'disabled');
				$(this).find('i').removeClass("fa-share-square-o").addClass('fa-spinner').addClass('fa-pulse');
				var action = $('#event-add-event-form').attr('action');
				if (action.indexOf("publish") < 0) {
					$('#event-add-event-form').attr('action', action+'/publish/true');
				}
				$('#event-add-event-form').submit();
			});
			$('#event-add-event-form').submit(function() { // catch the form's submit event
//				console.log('submit');
				$('#eventDescription').html($('#event-add-event-textbox').html());
				var fd = new FormData($('#event-add-event-form')[0]);
//				$('#newsletter-add-newsletter-save-button > i').removeClass("fa-floppy-o").addClass('fa-spinner').addClass('fa-pulse');
				  //console.log('Calling submit...');
	  		    $.ajax({ // create an AJAX call...
	  		        data: fd, // get the form data
	  		        type: $(this).attr('method'), // GET or POST
	  		        url: $(this).attr('action'), // the file to call
	  		        processData: false,
			        contentType: false,
	  		        success: function(response) { // on success..
	  		        	if (typeof response ==  'object') {
	  		        		updatePages(['#event-my-events', '#event-all-events', '#event-upcoming-events']);
			        		$('.modal').modal('hide');
			        	    //$('#add-new-proposal-dialog').remove();
			        	}
			        	else {
			        	    // its not json
			        		$('#event-add-event').parent().html(response); // update the DIV
			        	}
	  		        },
	  		    });
	  		    return false; // cancel original event to prevent form submitting
			});
	    }
	    
	    /**
	     *  Handlers for the BannerController -> deleteBannerAction
	     */
	    function eventDeleteEventHandlers() {
			$('#event-delete-event-yes').click(function () {
				$(this).prop('disabled', 'disabled');
				$(this).find('i').removeClass("fa-trash").addClass('fa-spinner').addClass('fa-pulse');
				$.ajax({ // create an AJAX call...
			        type: 'POST', // GET or POST
			        url: $(this).data('href'),
			        success: function(response) { // on success..
			        	if (typeof response ==  'object') {
			        		 updatePages(['#event-my-events', '#event-all-events', '#event-upcoming-events']);
			        		 $('.modal').modal('hide');
			        	}
			        	else {
			        	    // its not json
			        		$('#event-delete-event').parent().html(response); // update the DIV
			        	}
			        },
			    });
			    return false; // cancel original event to prevent form submitting
			});
	    }
	    
	    function eventMyEventsHandlers(lang) {
	    	
	    	$('#event-my-events-month').datepicker({
	            autoclose: true,
	            format: "MM yyyy",
	    	    viewMode: "months", 
	    	    minViewMode: "months",
    	    	 language: lang
	        }).
    	    	 on('changeDate', function (e) {
//    	                console.log(e.date);
    	                var date= e.date;
//    	                console.log((date.getMonth()+1)+'/'+date.getFullYear());
    	                $('#event-my-events').parent().load($('#event-my-events-see-all').data('url')+'/date/'+(date.getMonth()+1)+'/'+date.getFullYear());
    				});
	    	
	    	$('#event-my-events-add-new-event').click(function(){
		  	    modalDialog('new-event-dialog', $('#event-my-events').data('add-event'), $(this).data("href"), true);
		  	    return false;
		  	});
	    	
	    	$('a#event-my-events-delete').click(function(){
		  	    modalDialog('delete-event-dialog', $('#event-my-events').data('delete-event'), $(this).attr("href"), true);
		  	    return false;
		  	});
	    	
	    	$('a#event-my-events-duplicate').click(function(){
		  	    modalDialog('duplicate-event-dialog', $('#event-my-events').data('duplicate-event'), $(this).attr("href"), true);
		  	    return false;
		  	});
	    	
	    	$('#event-my-events-see-all').click(function(){
	    		$('#event-my-events').parent().load($(this).data('url'));
		  	    return false;
		  	});
	    	
	    	$('#event-my-events-see-drafts').click(function(){
	    		$('#event-my-events').parent().load($(this).data('url'));
		  	    return false;
		  	});
	    	
	    	$('#event-my-events-see-month').click(function(){
	    		$('#event-my-events').parent().load($(this).data('url'));
		  	    return false;
		  	});
	    	
	    	$('#event-my-events-search-form').submit(function() { // catch the form's submit event
	    		$(this).find('i').removeClass("fa-search").addClass('fa-spinner').addClass('fa-pulse');
	    		  $.ajax({ // create an AJAX call...
				        data: $(this).serialize(), // get the form data
				        type: $(this).attr('method'), // GET or POST
				        url: $(this).attr('action'), // the file to call
				        success: function(html) { // on success..
				        	$('#event-my-events').replaceWith(html);
				        },
				    });
				    return false; // cancel original event to prevent form submitting
				});
	    	
	    	
	    	$('a#event-my-events-edit').click(function(){
		  	    modalDialog('edit-event-dialog', $('#event-my-events').data('edit-event'), $(this).attr("href"), true);
		  	    return false;
		  	});
//	    	$('a#event-my-events-view-attendees').click(function(){
//		  	    modalDialog('view-attendees-dialog', $('#event-my-events').data('view-attendees'), $(this).attr("href"), true);
//		  	    return false;
//		  	});
	    	$('a#event-my-events-cancel').click(function(){
		  	    modalDialog('cancel-event-dialog', $('#event-my-events').data('cancel-event'), $(this).attr("href"), true);
		  	    return false;
		  	});
	    	$('a#event-my-events-publish').click(function(){
		  	    modalDialog('publish-event-dialog', $('#event-my-events').data('publish-event'), $(this).attr("href"), true);
		  	    return false;
		  	});
	    	$("a#page-my-events").click(function(){
    		    $("#event-my-events").parent().load($(this).attr("href"));
    		    return false;
    		});
	    }
    	function eventAllEventsHandlers(lang) {
    		
    		 $('#event-all-events-month').datepicker({
    	            autoclose: true,
    	            format: "MM yyyy",
		    	    viewMode: "months", 
		    	    minViewMode: "months",
	    	    	 language: lang
    	        }).
	    	    	 on('changeDate', function (e) {
//	    	                console.log(e.date);
	    	                var date= e.date;
//	    	                console.log((date.getMonth()+1)+'/'+date.getFullYear());
	    	                $('#event-all-events').parent().load($('#event-all-events-see-all').data('url')+'/date/'+(date.getMonth()+1)+'/'+date.getFullYear());
	    				});
    		 
	    	$('#event-all-events-add-new-event').click(function(){
		  	    modalDialog('new-event-dialog', $('#event-all-events').data('add-event'), $(this).data("href"), true);
		  	    return false;
		  	});
	    	
	    	$('#event-all-events-see-all').click(function(){
	    		$('#event-all-events').parent().load($(this).data('url'));
		  	    return false;
		  	});
	    	
	    	$('#event-all-events-see-month').click(function(){
	    		$('#event-all-events').parent().load($(this).data('url'));
		  	    return false;
		  	});
	    	
	    	$('#event-all-events-search-form').submit(function() { // catch the form's submit event
	    		$(this).find('i').removeClass("fa-search").addClass('fa-spinner').addClass('fa-pulse');
	    		  $.ajax({ // create an AJAX call...
				        data: $(this).serialize(), // get the form data
				        type: $(this).attr('method'), // GET or POST
				        url: $(this).attr('action'), // the file to call
				        success: function(html) { // on success..
				        	$('#event-all-events').replaceWith(html);
				        },
				    });
				    return false; // cancel original event to prevent form submitting
				});
	    	
	    	$('a#event-all-events-edit').click(function(){
		  	    modalDialog('edit-event-dialog', $('#event-all-events').data('edit-event'), $(this).attr("href"), true);
		  	    return false;
		  	});
//	    	$('a#event-all-events-view-attendees').click(function(){
//		  	    modalDialog('view-attendees-dialog', $('#event-all-events').data('view-attendees'), $(this).attr("href"), true);
//		  	    return false;
//		  	});
	    	$('a#event-all-events-cancel').click(function(){
		  	    modalDialog('cancel-event-dialog', $('#event-all-events').data('cancel-event'), $(this).attr("href"), true);
		  	    return false;
		  	});
//	    	$('a#event-all-events-publish').click(function(){
//		  	    modalDialog('publish-event-dialog', $('#event-all-events').data('publish-event'), $(this).attr("href"), true);
//		  	    return false;
//		  	});
	    	$("a#page-all-events").click(function(){
    		    $("#event-all-events").parent().load($(this).attr("href"));
    		    return false;
    		});
	    	$('a#event-all-events-attend').click(function () {
                var ref = $(this);
//                var id = ref.data("favorite");
                ref.find('i').removeClass("fa-check-circle-o").removeClass("fa-times-circle-o").addClass('fa-spinner').addClass('fa-pulse');
                $.post(ref.attr("href"), {},
                    function (itemJson) {
                        if (itemJson.success) {
//                        	if (typeof $('#proposal-my-favorites').data('url') !== 'undefined') {
//                        		$('#proposal-my-favorites').parent().load($('#proposal-my-favorites').data('url'));
//                        	}
                        	var cnt = ref.parent().find('#event-all-events-attendees-count');
                        	var attendeesCount = parseInt(cnt.text())
                        	if (itemJson.success == 1) {
                        		ref.html('<i class="fa fa-check-circle-o"></i> '+ $("#event-all-events").data('attend'));
                        		cnt.text(attendeesCount-1);
                        	}
                        	else {
                        		ref.html('<i class="fa fa-times-circle-o"></i> '+$("#event-all-events").data('decline'));
                        		cnt.text(attendeesCount+1);
                        	}
                        }
                    }, 'json');
                return false;
            });
	    }
    	function eventPublishEventHandlers() {
    		$('#publish-event-yes-button').click(function () {
				$(this).prop('disabled', 'disabled');
				$(this).find('i').removeClass("fa-floppy-o").addClass('fa-spinner').addClass('fa-pulse');
				$.ajax({ // create an AJAX call...
			        type: 'POST', // GET or POST
			        url: $('#event-publish-event').data('url'),
			        success: function(response) { // on success..
			        	//console.log(response);
			        	if (typeof response ==  'object') {
			        		if (typeof $('#event-my-events').data('url') !== 'undefined') {
			        	    	$('#event-my-events').parent().load($('#event-my-events').data('url'));
			        		}
			        		if (typeof $('#event-all-events').data('url') !== 'undefined') {
			        	    	$('#event-all-events').parent().load($('#event-all-events').data('url'));
			        		}
			        		if (typeof $('#event-upcoming-events').data('url') !== 'undefined') {
			        	    	$('#event-upcoming-events').parent().load($('#event-upcoming-events').data('url'));
			        		}
			        	    $('.modal').modal('hide');
			        	    //$('#add-new-proposal-dialog').remove();
			        	}
			        	else {
			        	    // its not json
			        		$('#event-publish-event').parent().html(response); // update the DIV
			        	}
			        },
			    });
			    return false; // cancel original event to prevent form submitting
			});
	    }
    	function eventCancelEventHandlers() {
    		$('#cancel-event-yes-button').click(function () {
				$(this).prop('disabled', 'disabled');
				$(this).find('i').removeClass("fa-floppy-o").addClass('fa-spinner').addClass('fa-pulse');
				$.ajax({ // create an AJAX call...
			        type: 'POST', // GET or POST
			        url: $('#event-cancel-event').data('url'),
			        success: function(response) { // on success..
//			        	console.log(response);
			        	if (typeof response ==  'object') {
			        		if (typeof $('#event-my-events').data('url') !== 'undefined') {
			        	    	$('#event-my-events').parent().load($('#event-my-events').data('url'));
			        		}
			        		if (typeof $('#event-all-events').data('url') !== 'undefined') {
			        	    	$('#event-all-events').parent().load($('#event-all-events').data('url'));
			        		}
			        		if (typeof $('#event-upcoming-events').data('url') !== 'undefined') {
			        	    	$('#event-upcoming-events').parent().load($('#event-upcoming-events').data('url'));
			        		}
			        	    $('.modal').modal('hide');
			        	    //$('#add-new-proposal-dialog').remove();
			        	}
			        	else {
			        	    // its not json
			        		$('#event-publish-event').parent().html(response); // update the DIV
			        	}
			        },
			    });
			    return false; // cancel original event to prevent form submitting
			});
	    }
		function eventUpcomingEventsHandlers() {
	    	$('#event-upcoming-events-see-all').click(function(){
	    		$('[href=#all-events]').tab('show');
		  	    return false;
		  	});
	    }
    	function eventViewEventHandlers() {
//	    	$('#event-view-event-see-attendees').click(function(){
//		  	    modalDialog('view-attendees-dialog', 'View Event Attendees', $(this).data("href"), true);
//		  	    return false;
//		  	});
    		updateContextualHelp('#event-view-event');
	    }
    	 /**
	     *  Handlers for the EventController -> viewAttendeesAction
	     */
	    function eventViewAttendeesHandlers() {
	    	userMiniProfilePopover($('a#event-view-attendees-view-profile'));
	    	$("a#page-view-attendees").click(function(){
    		    $("#event-view-attendees").parent().load($(this).attr("href"));
    		    return false;
    		});
	    	$('#event-view-attendees-attend').click(function () {
                var ref = $(this);
//                var id = ref.data("favorite");
                ref.find('i').removeClass("fa-check-circle-o").removeClass("fa-times-circle-o").addClass('fa-spinner').addClass('fa-pulse');
                $.post(ref.data("url"), {},
                    function (itemJson) {
                        if (itemJson.success) {
//                        	if (typeof $('#proposal-my-favorites').data('url') !== 'undefined') {
//                        		$('#proposal-my-favorites').parent().load($('#proposal-my-favorites').data('url'));
//                        	}
                            //ref.html((itemJson.success == 1) ? '<i class="fa fa-check-circle-o"></i> '+ $("#event-view-attendees").data('attend'): '<i class="fa fa-times-circle-o"></i> '+$("#event-view-attendees").data('decline'));
                            updatePages(['#event-view-attendees']);
                        }
                    }, 'json');
                return false;
            });
	    }
	    function executionAddStepDialogHandlers() {
	    	$( "#stepStartDate" ).datepicker({
	  	      defaultDate: "+1d",
	  	      minDate: 0,
	  	      dateFormat: "dd/mm/yy",
	  	      changeMonth: true,
	  	      numberOfMonths: 3,
	  	      onClose: function( selectedDate ) {
	  	        $( "#stepEndDate" ).datepicker( "option", "minDate", selectedDate );
	  	      }
	  	    });
	  	    $( "#stepEndDate" ).datepicker({
	  	      defaultDate: "+1d",
	  	      dateFormat: "dd/mm/yy",
	  	      changeMonth: true,
	  	      numberOfMonths: 3,
	  	      onClose: function( selectedDate ) {
	  	        $( "#stepStartDate" ).datepicker( "option", "maxDate", selectedDate );
	  	      }
	  	    });
	    }
	    function executionTimelineHandlers() {
	    	//renumber_table('#timelineTable');
	        //Helper function to keep table row from collapsing when being sorted
	        var fixHelperModified = function(e, tr) {
	            var $originals =finddren();
	            var $helper = tr.clone();
	            $helper.children().each(function(index)
	            {
	              $(this).width($originals.eq(index).width())
	            });
	            return $helper;
	        };
	        //Make diagnosis table sortable
	      $("#timelineTable tbody").sortable({
	            helper: fixHelperModified,
	            stop: function(event,ui) {
	                //renumber_table('#timelineTable')
	            }
	        }).disableSelection();
	      /*  $( "#dialogAddNewStep" ).dialog({
	        	autoOpen: false,
	            resizable: true,
	            height:325,
	            width:950,
	            modal: true,
	            buttons: {
	              "Add step": function() {
	            	  $(":button:contains('Add step')").attr("disabled", true);
	            	  $( "#addStepForm" ).submit();
	                //$( this ).dialog( "close" );
	              },
	              Cancel: function() {
	                $( this ).dialog( "close" );
	              }
	            }
	        });*/
	    	  $('#addStepForm').submit(function() { // catch the form's submit event
	    		  console.log('Calling submit...');
	    		  $.ajax({ // create an AJAX call...
	    		        data: $(this).serialize(), // get the form data
	    		        type: $(this).attr('method'), // GET or POST
	    		        url: $(this).attr('action'), // the file to call
	    		        success: function(response) { // on success..
	    		        	//alert(response);
	    		        	$('#dialogAddNewStep').html(response); // update the DIV
	    		        	if ($('.text-danger').length==0) { // if no errors
	    		        		//alert('Set timeout!');
	    		        		setTimeout(function(){
	    		        				$('#dialogAddNewStep').dialog("close");
	    		        			}, 2000);
	    		        		$('#timeline').load("/city/proposal/implementation/timeline/"+$('#propUUID').val(), function() {
	    		        		});
	    		        	}
	    		        	else {
	    		        		$(":button:contains('Add step')").attr("disabled", false);
	    		        	}
	    		        	//$.getScript("/js/demodyne.js");
	    		        }
	    		    });
	    		    return false; // cancel original event to prevent form submitting
	    		});
	    	  $("a#execution-timeline-add-new-step").on("click",function(event){
	    		  modalDialog('execution-timeline-add-new-step-dialog', 'Add new Step to Proposal', $(this).attr("href"));
	    		  return false;
	    		});
	    	  $('a[title="Edit Step"]').on("click", function(){
	    		  var link = $(this).attr("href");
	    		  $('#editStepName').text($(this).text());
	    	      $('#dialogEditStep .modal-body').html('Loading step <strong>'+$(this).text()+'</strong>. Please wait... <i class="fa fa-spinner fa-pulse "></i> ').load(link );
	    	      $('#dialogEditStep').modal({
	    	            backdrop: 'static',
	    	            keyboard: false,
	    	            show: true,
	    	        });
	    	      //$('#dialogEditStep').modal("show");
	    		 return false;
	    	  });
	    	  $('.modal-dialog').draggable({
	              handle: ".modal-header"
	          });
	    }
	    
	    /**
	     *  Handlers for the IndexController -> indexAction
	     */
	    function homeLoggedinHandlers() {
	    	
	    	$('div.product-chooser').not('.disabled').find('div.product-chooser-item').on('click', function(){

	    		$(this).parent().parent().find('div.product-chooser-item').removeClass('selected');

	    		$(this).addClass('selected');

	    		$(this).find('input[type="radio"]').prop("checked", true);

	    		

	    	});
	    	
	    	$('#home-step-user-0-next').click(function() { 
	    		if ($('input[name="level-radio"]:checked').length) {
	    			if ($('input[name="level-radio"]:checked').val() == 'city') {
	    				$('#home-step-user-1').show();
		    			$('#home-step-user-0').hide();
		    		}
		    		else if ($('input[name="level-radio"]:checked').val() == 'region') {
		    			window.location.href = $('input[name="level-radio"]:checked').data('url');
		    		}
		    		else if ($('input[name="level-radio"]:checked').val() == 'country') {
		    			window.location.href = $('input[name="level-radio"]:checked').data('url');
		    		}
		    		
	    		}
		  	  }); 
	    	
	    	$('#home-step-user-1-next').click(function() { 
	    		if ($('input[name="user-radio"]:checked').length) {
		    		if ($('input[name="user-radio"]:checked').val() == 'proposal') {
		    			$('#home-step-user-proposal').show();
		    		}
		    		else if ($('input[name="user-radio"]:checked').val() == 'program') {
		    			$('#home-step-user-measure').show();
		    		}
		    		else if ($('input[name="user-radio"]:checked').val() == 'scenario') {
		    			$('#home-step-user-scenario').show();
		    		}
		    		$('#home-step-user-1').hide();
	    		}
		  	  }); 
	    	
	    	$('#home-step-admin-1-next').click(function() { 
	    		if ($('input[name="admin-radio"]:checked').length) {
		    		if ($('input[name="admin-radio"]:checked').val() == 'bne') { // banners, newsletters and events
		    			$('#home-step-admin-bne').show();
		    		}
		    		else if ($('input[name="admin-radio"]:checked').val() == 'program') {
		    			$('#home-step-admin-program').show();
		    		}
		    		else if ($('input[name="admin-radio"]:checked').val() == 'scnprop') { // scenarios and proposals
		    			$('#home-step-admin-scenario').show();
		    		}
		    		$('#home-step-admin-1').hide();
	    		}
		  	  }); 
	    	
	    	$('button#home-step-goto').click(function() { 
	    		$($(this).data('show')).show();
	    		$($(this).data('hide')).hide();
	    		
		  	}); 
		  	  
	    }
	    
	    
	    /**
	     *  Handlers for the InboxController -> myContactsAction
	     */
	    function inboxDeleteSelectedHandlers() {
	    	$('#inbox-list-delete-selected-form').submit(function() { // catch the form's submit event
	    		$('#inbox-delete-selected-yes-button').prop('disabled', 'disabled');
				$('#inbox-delete-selected-yes-button-icon').removeClass("fa-trash").addClass('fa-spinner').addClass('fa-pulse');
				  //console.log('Calling submit...');
				  $.ajax({ // create an AJAX call...
				        data: $(this).serialize(), // get the form data
				        type: $(this).attr('method'), // GET or POST
				        url: $(this).attr('action'), // the file to call
				        success: function(response) { // on success..
				        		$('#inbox-delete-selected').parent().html(response); // update the DIV
				        		
				        },
				    });
				    return false; // cancel original event to prevent form submitting
				});
	    }
	    /**
	     *  Handlers for the InboxController -> myContactsAction
	     */
	    function inboxMyContactsHandlers() {
	      /**
	  	   * Pagination for user contacts
	  	   */
	  	  $("a#page-contact").click(function(){
	  		  $('#inbox-my-contacts').parent().load($(this).attr("href"));
	  		    return false;
	  		});
	  	  
	  	  $('a#inbox-my-contacts-send-message').click(function() { 
	  		modalDialog('new-message-dialog', $('#inbox-my-contacts').data('new-message'), $(this).attr("href"), true);
		    return false; // cancel original event to prevent form submitting
	  	  }); 
	  	  
	  	  $('#inbox-my-contacts-new-message-selected').click(function() { 
	  		  if ($('#inbox-my-contacts input:checked').length==0) {
	  			  return false;
	  		  }
	  		  var to = '';
	  		  $('#inbox-my-contacts input:checked').each(function() {
	  			  to = to + $(this).val() + '%2C%20';
	  		  });
	  		
	  		  modalDialog('new-message-dialog', $('#inbox-my-contacts').data('new-message'), $(this).data("href") + '/to/' + to, true);
	  		  return false; // cancel original event to prevent form submitting
	  	  }); 
	  	  
	  	  $("a#inbox-my-contacts-add-remove-contact").click(function () {
	            var ref = $(this);
	            ref.find('i').removeClass("fa-times-circle").addClass('fa-spinner').addClass('fa-pulse');
	            $.get(ref.attr("href"), {},
	                function (response) {
	            		if (!response.success) {
	  						alert('An error occured. Please retry later.');
	            		}
	            		else {
	            			$('a#user-profile-mini-profile-add-remove-contact[data-id="'+ref.data('id')+'"]').addClass('btn-orange').removeClass('btn-primary').html('<i class="fa fa-plus-circle"></i> '+$('#user-profile-mini-profile').data('add-contact'));
							if (typeof $('#inbox-my-contacts').data('url') !== 'undefined') {
	                			$('#inbox-my-contacts').parent().load($('#inbox-my-contacts').data('url'));
	                		}
	            		}
	                }, 'json');
	            // Prevent browsers default behavior to follow the link when clicked
	            return false;
	        });
	    }
	   /* function inboxListsPartial(partialId) {
	    	$("#"+partialId+"-view-received").click(function(){
				  console.log('Show received messages!');
				  $('#inbox-list').show();
			      $('#inbox-list-sent').hide();
			      $('#inbox-list-trash').hide();
				  return false;
			  });
	    	$("#"+partialId+"-view-sent").click(function(){
				  console.log('Show sent messages!');
				  if ($('#inbox-list-sent').length==0) {// first load
					  $.ajax({
					        url: $(this).attr('href'),
					        success: function(html) {
					        	$('#inbox-list').hide();
					        	$('#inbox-list-trash').hide();
				        		$('#inbox-list').parent().append(html);
					        },
					    });
				  }
				  else {
					  $('#inbox-list').hide();
				      $('#inbox-list-sent').show();
				      $('#inbox-list-trash').hide();
				  }
				  return false;
			  });
	    	$("#"+partialId+"-view-trash").click(function(){
				  console.log('Show trash messages!');
				  if ($('#inbox-list-trash').length==0) {// first load
					  $.ajax({
					        url: $(this).attr('href'),
					        success: function(html) {
					        	$('#inbox-list').hide();
					        	$('#inbox-list-sent').hide();
				        		$('#inbox-list').parent().append(html);
					        },
					    });
				  }
				  else {
					  $('#inbox-list').hide();
				      $('#inbox-list-sent').hide();
				      $('#inbox-list-trash').show();
				  }
				  return false;
			  });
	    }*/
	    function inboxListHandlersSelectToView() {
	    	return '<div class="panel panel-default" id="inbox-view-message">'+
			'<div class="panel-body" style="min-height: 450px !important; text-align: center; vertical-align: middle; line-height: 450px;">'+
				'<div class="row"><div class="col-md-12 col-xs-12">'+$('#inbox-list-received').data('select-message')+'</div></div>'+
			'</div></div>';
	    }
	    /**
	     *  Handlers for the InboxController -> listActions
	     */
	    function inboxListHandlers(type) {
//	    	if (type=='sent') inboxListSentUrl = inboxListURL;
//	    	if (type=='trash') inboxListTrashUrl = inboxListURL;
	    	userMiniProfilePopover($('a#inbox-list-profile-'+type));
	    	$("a#page-ibx-"+type).click(function(){
			    // Load the content of the page referenced in the a-tags href
	    		$.get($(this).attr("href"), function(data) {
	    		     $("#inbox-list-"+type).replaceWith(data);
	    		});
			    //$("#inbox-list").parent().load($(this).attr("href"));
			    // Prevent browsers default behavior to follow the link when clicked
			    return false;
			});
		    //$('#allprop').load('/proposition/list');
	    	$('input#checkbox-'+type).change(function(){
	    		//console.log('checkbox changed');
	    		if ($('input#checkbox-'+type+':checked').length) {
	    			$('#inbox-delete-selected-'+type).show();
	    		}
	    		else {
	    			$('#inbox-delete-selected-'+type).hide();
	    		}
			    return false;
			});
	    	$('#inbox-list-delete-more-form-'+type).submit(function() { // catch the form's submit event
	    		//console.log('form submit');
	    		modalDialog('inbox-delete-more-dialog', $(this).data('dialog-title'), $(this).attr('action')+'?'+$(this).serialize(), false);
				    return false; // cancel original event to prevent form submitting
				});
		  $("a#inbox-list-message-item-"+type).click(function(){
			  $('#inbox-list-'+type).find('.list-group-item').removeClass('active');
			  $(this).addClass('active').removeClass('notview');
			    // Load the content of the page referenced in the a-tags href
			    $("#inbox-view-div").load($(this).attr("href"));
			    $('#inbox-list-'+type).data('view-url', $(this).attr("href"));
			    // Prevent browsers default behavior to follow the link when clicked
			    return false;
			});
		    //$('#allprop').load('/proposition/list');
		   // console.log($('#inbox-view-message').data('message-id'));
		  //$('#inbox-list-message-item-'+type+'[data-message-id="'+$('#inbox-view-message').data('message-id')+'"]').addClass('active');
		  clearInterval(inboxListTimer);
		  inboxListTimer = setInterval(function() {
										  $.get($('#inbox-list-received').data('url'), function(data) {
									    		var display = $('#inbox-list-received').css('display');
									    		 $('#inbox-list-received').replaceWith(data);
								    		     $('#inbox-list-received').css('display', display);
								    		});
//										console.log('refresh inbox');
										}, 600000);
		  //console.log(inboxListTimer);
//		  inboxListsPartial('inbox-list');
		  $("#inbox-list-view-received-"+type).click(function(){
//			  console.log('Show received messages!');
			  if ($('#inbox-list-received').length==0) {// first load
				  $.ajax({
				        url: $(this).attr('href'),
				        success: function(html) {
				        	$('#inbox-list-sent').hide();
				        	$('#inbox-list-trash').hide();
			        		$('#inbox-my-inbox-list').append(html);
			        		if (typeof $('#inbox-list-received').data('view-url')!== "undefined") {
			          			$("#inbox-view-div").load($('#inbox-list-received').data('view-url'));
			          		  }
			        		else {
			        			$("#inbox-view-div").html(inboxListHandlersSelectToView());
			        		}
				        },
				    });
			  }
			  else {
				  $('#inbox-list-received').show();
			      $('#inbox-list-sent').hide();
			      $('#inbox-list-trash').hide();
			      if (typeof $('#inbox-list-received').data('view-url')!== "undefined") {
		      			$("#inbox-view-div").load($('#inbox-list-received').data('view-url'));
		      		  }
			      else {
	        			$("#inbox-view-div").html(inboxListHandlersSelectToView());
	        		}
			  }
		      $('#inbox-list-search').hide();
			  return false;
		  });
    	$("#inbox-list-view-sent-"+type).click(function(){
//			  console.log('Show sent messages!');
			  if ($('#inbox-list-sent').length==0) {// first load
				  $.ajax({
				        url: $(this).attr('href'),
				        success: function(html) {
				        	$('#inbox-list-received').hide();
				        	$('#inbox-list-trash').hide();
			        		$('#inbox-my-inbox-list').append(html);
			        		if (typeof $('#inbox-list-sent').data('view-url')!== "undefined") {
			          			$("#inbox-view-div").load($('#inbox-list-sent').data('view-url'));
			          		  }
			        		else {
			        			$("#inbox-view-div").html(inboxListHandlersSelectToView());
			        		}
				        },
				    });
			  }
			  else {
				  $('#inbox-list-received').hide();
			      $('#inbox-list-sent').show();
			      $('#inbox-list-trash').hide();
			      if (typeof $('#inbox-list-sent').data('view-url')!== "undefined") {
		      			$("#inbox-view-div").load($('#inbox-list-sent').data('view-url'));
		      		  }
			      else {
	        			$("#inbox-view-div").html(inboxListHandlersSelectToView());
	        		}
			  }
			  $('#inbox-list-search').hide();
			  return false;
		  });
    	$("#inbox-list-view-trash-"+type).click(function(){
//			  console.log('Show trash messages!');
			  if ($('#inbox-list-trash').length==0) {// first load
				  $.ajax({
				        url: $(this).attr('href'),
				        success: function(html) {
				        	$('#inbox-list-received').hide();
				        	$('#inbox-list-sent').hide();
			        		$('#inbox-my-inbox-list').append(html);
			        		if (typeof $('#inbox-list-trash').data('view-url')!== "undefined") {
				      			$("#inbox-view-div").load($('#inbox-list-trash').data('view-url'));
				      		  }
			        		else {
			        			$("#inbox-view-div").html(inboxListHandlersSelectToView());
			        		}
				        },
				    });
			  }
			  else {
				  $('#inbox-list-received').hide();
			      $('#inbox-list-sent').hide();
			      $('#inbox-list-trash').show();
			      if (typeof $('#inbox-list-trash').data('view-url')!== "undefined") {
		      			$("#inbox-view-div").load($('#inbox-list-trash').data('view-url'));
		      		  }
			      else {
	        			$("#inbox-view-div").html(inboxListHandlersSelectToView());
	        		}
			  }
			  $('#inbox-list-search').hide();
			  return false;
		  });
	    }
	    /**
	     *  Handlers for the InboxController -> newMessageAction
	     */
	    function inboxNewMessageHandlers(getContactsURL) {
	    	/**
	    	 * Start Autocomplete functions
	    	 * */
	    	function split( val ) {
	    		return val.split( /,\s*/ );
	    	}
	    	function extractLast( term ) {
	    		return split( term ).pop();
	    	}
	    	$( "#msgTo" )
	    	// don't navigate away from the field on tab when selecting an item
	    	.bind( "keydown", function( event ) {
	    		if ( event.keyCode === $.ui.keyCode.TAB &&
	    				$( this ).autocomplete( "instance" ).menu.active ) {
	    			event.preventDefault();
	    		}
	    	})
	    	.autocomplete({
	    		source: function( request, response ) {
	    			$.getJSON( getContactsURL, {
	    				term: extractLast( request.term )
	    			}, response );
	    		},
	    		search: function() {
	    			// custom minLength
	    			var term = extractLast( this.value );
	    			if ( term.length < 2 ) {
	    				return false;
	    			}
	    		},
	    		focus: function() {
	    			// prevent value inserted on focus
	    			return false;
	    		},
	    		select: function( event, ui ) {
	    			var terms = split( this.value );
	    			// remove the current input
	    			terms.pop();
	    			// add the selected item
	    			terms.push( ui.item.value );
	    			// add placeholder to get the comma-and-space at the end
	    			terms.push( "" );
	    			this.value = terms.join( ", " );
	    			return false;
	    		}
	    	});
	    	/**
	    	 * Stop Autocomplete functions
	    	 * */
	    	 $('#newMessageForm').submit(function() { // catch the form's submit event
	    		 $('#inbox-new-message-send-message-icon').removeClass("fa-envelope-o").addClass('fa-spinner').addClass('fa-pulse');
	    		 $('#msgText').html($('#inbox-new-message-textbox').html());
			    	$.ajax({ // create an AJAX call...
			    		data: $(this).serialize(), // get the form data
			    		type: $(this).attr('method'), // GET or POST
			    		url: $(this).attr('action'), // the file to call
			    		success: function(response) { // on success..
			    			//alert(response);
			    			$('#inbox-new-message').parent().html(response); // update the DIV
			    			updateInboxLists('all');
			    		}
			    	});
			    	return false; // cancel original event to prevent form submitting
			    });
	    }
	    /**
	     *  Handlers for the InboxController -> inbox-partial-menu.phtml
	     */
	    function inboxPartialMenuHandlers() {
	    	$('#inbox-partial-menu-new-message').click(function(){
		  	    modalDialog('new-message-dialog', $(this).data('dialog-title'), $(this).data('url'), false);
//		  	    console.log('mic');
		  	    return false;
		  	});
		  $("a#page-ibx_filter").click(function(){
			    // Load the content of the page referenced in the a-tags href
			    $('#inbox-partia-menu-filter').removeClass('open');
			    $('#inbox-partia-menu-filter-check').prependTo($(this));
			    $('#inbox-list').parent().load($(this).attr("href"));
			    // Prevent browsers default behavior to follow the link when clicked
			    return false;
			});
		  $("a#inbox-partial-menu-view-sent").click(function(){
			    // Load the content of the page referenced in the a-tags href
			    var _this = this;
			    $(this).find('i').removeClass("fa-eye").addClass('fa-spinner').addClass('fa-pulse');
			    $('#inbox-list').parent().load($(this).attr("href"), function() {
			    	$(_this).find('i').removeClass('fa-spinner').removeClass('fa-pulse').addClass("fa-eye");
			    	if ($(_this).data('sent')==$('#inbox-partial-menu-view-sent-text').html()) {
		    			$('#inbox-partial-menu-view-sent-text').html($(_this).data('received'));
		    			$(_this).attr('href', $(_this).attr('href').replace('-sent', ''));
		    		}
		    		else {
		    			$('#inbox-partial-menu-view-sent-text').html($(_this).data('sent'));
		    			$(_this).attr('href', $(_this).attr('href') + '-sent');
		    		}
		    	});
			    // Prevent browsers default behavior to follow the link when clicked
			    return false;
			});
		  $("a#inbox-partial-menu-refresh").click(function(){
			    // Load the content of the page referenced in the a-tags href
			    var _this = this;
			    $('#inbox-partial-menu-refresh-icon').addClass('fa-spin');
			    if (typeof $('#inbox-list-received').data('url') !== 'undefined') {
			    	$.get($('#inbox-list-received').data('url'), function(data) {
			    		var display = $('#inbox-list-received').css('display');
			    		 $('#inbox-list-received').replaceWith(data);
		    		     $('#inbox-list-received').css('display', display);
		    		     $('#inbox-partial-menu-refresh-icon').removeClass('fa-spin');
		    		});
			    }
			    // Prevent browsers default behavior to follow the link when clicked
			    return false;
			});
		  /*****
		   * Search handlers
		   *****/
		  	$("a#inbox-partial-menu-search-receiver").click(function(){
				if ($(this).find('i').hasClass('fa-check')){
		  			$(this).find('i').removeClass('fa-check').addClass('fa-times');
		  			$('#inbox-partial-menu-search-receiver-input').attr('value', '0');
		  		}
		  		else {
		  			$(this).find('i').removeClass('fa-times').addClass('fa-check');
		  			$('#inbox-partial-menu-search-receiver-input').attr('value', '1');
		  		}
		  	    return false;
			});
		  	$("a#inbox-partial-menu-search-sender").click(function(){
				if ($(this).find('i').hasClass('fa-check')){
		  			$(this).find('i').removeClass('fa-check').addClass('fa-times');
		  			$('#inbox-partial-menu-search-sender-input').attr('value', '0');
		  		}
		  		else {
		  			$(this).find('i').removeClass('fa-times').addClass('fa-check');
		  			$('#inbox-partial-menu-search-sender-input').attr('value', '1');
		  		}
		  	    return false;
			});
		  	$("a#inbox-partial-menu-search-subject").click(function(){
				if ($(this).find('i').hasClass('fa-check')){
		  			$(this).find('i').removeClass('fa-check').addClass('fa-times');
		  			$('#inbox-partial-menu-search-subject-input').attr('value', '0');
		  		}
		  		else {
		  			$(this).find('i').removeClass('fa-times').addClass('fa-check');
		  			$('#inbox-partial-menu-search-subject-input').attr('value', '1');
		  		}
		  	    return false;
			});
		  	$("a#inbox-partial-menu-search-message").click(function(){
				if ($(this).find('i').hasClass('fa-check')){
		  			$(this).find('i').removeClass('fa-check').addClass('fa-times');
		  			$('#inbox-partial-menu-search-message-input').attr('value', '0');
		  		}
		  		else {
		  			$(this).find('i').removeClass('fa-times').addClass('fa-check');
		  			$('#inbox-partial-menu-search-message-input').attr('value', '1');
		  		}
		  	    return false;
			});
		  	$("a#inbox-partial-menu-search-all").click(function(){
		  		if ($('#inbox-partial-menu-search-receiver').find('i').hasClass('fa-times')) {
		  			$('#inbox-partial-menu-search-receiver').find('i').removeClass('fa-times').addClass('fa-check');
		  			$('#inbox-partial-menu-search-receiver-input').attr('value', '1');
		  		}
		  		if ($('#inbox-partial-menu-search-sender').find('i').hasClass('fa-times')) {
		  			$('#inbox-partial-menu-search-sender').find('i').removeClass('fa-times').addClass('fa-check');
		  			$('#inbox-partial-menu-search-sender-input').attr('value', '1');
		  		}
		  		if ($('#inbox-partial-menu-search-subject').find('i').hasClass('fa-times')) {
		  			$('#inbox-partial-menu-search-subject').find('i').removeClass('fa-times').addClass('fa-check');
		  			$('#inbox-partial-menu-search-subject-input').attr('value', '1');
		  		}
		  		if ($('#inbox-partial-menu-search-message').find('i').hasClass('fa-times')) {
		  			$('#inbox-partial-menu-search-message').find('i').removeClass('fa-times').addClass('fa-check');
		  			$('#inbox-partial-menu-search-message-input').attr('value', '1');
		  		}
		  		return false;
			});
		  	$('#inbox-partial-menu-search-form').submit(function() { // catch the form's submit event
	    		$('#inbox-partial-menu-search-submit').find('i').removeClass("fa-search").addClass('fa-spinner').addClass('fa-pulse');
	    		var url = $(this).attr('action') + '/sk/' + $('#inbox-partial-menu-search-keywords').val().trim()/*.replace(/\s+/g, '|')*/ +
	    							'/sr/' + $('#inbox-partial-menu-search-receiver-input').attr('value') +
	    							'/ss/' + $('#inbox-partial-menu-search-sender-input').attr('value') +
	    							'/st/' + $('#inbox-partial-menu-search-subject-input').attr('value') +
	    							'/sm/' + $('#inbox-partial-menu-search-message-input').attr('value');
				  //console.log('Calling submit...');
				  $.ajax({ // create an AJAX call...
				        data: $(this).serialize(), // get the form data
				        type: $(this).attr('method'), // GET or POST
				        url: url, // the file to call
				        success: function(html) { // on success..
				        	$('#inbox-list-received').hide();
				        	$('#inbox-list-sent').hide();
				        	$('#inbox-list-trash').hide();
				        	if ($('#inbox-list-search').length) {// list exists
				        		$('#inbox-list-search').replaceWith(html).show();
				        	}
				        	else {
				        		$('#inbox-list-received').parent().append(html);
				        	}
				        	$('#inbox-partial-menu-search-submit').find('i').addClass("fa-search").removeClass('fa-spinner').removeClass('fa-pulse');
				        },
				    });
				    return false; // cancel original event to prevent form submitting
				});
	    }
	    /**
	     *  Handlers for the InboxController -> viewAction
	     */
	    function inboxViewHandlers(unreadMessage) {
	    	userMiniProfilePopover($('a#inbox-view-message-view-profile'));
	    	if (unreadMessage) {
	    		$('span#inbox-unread-messages-count').html($('#inbox-unread-messages-count').html()-1);
	    	}
	    	$('#inbox-view-message-delete-one').click(function(){
	    		var _this = $(this);
	    		$.get($(this).data('url'), function(data) {
	   		     	$('#inbox-view-div').replaceWith('<div class="col-md-6" id="inbox-view-div">'+
	   		     		'<div class="panel panel-default" id="inbox-view-message" >'+
	   		     	'<div class="panel-body" style="min-height:450px !important;text-align: center;vertical-align: middle;line-height: 450px; " >'+
	   		     	'<div class="row"><div class="col-md-12 col-xs-12">'+_this.data('message')+'</div></div>'+
	   		     	'</div></div></div>');
	   		     	updateInboxLists(data.type);
	   		     	updateInboxLists('trash');
	    		});
	    		return false;
	    	});
	    	$('#inbox-view-message-reply').click(function() { 
	    		modalDialog('inbox-reply-dialog', $(this).data('dialog-title')+ ' ' + $(this).data('type'),  $(this).data("url"), true);
			});
	    	$('#inbox-view-message-reply-all').click(function() { 
	    		modalDialog('inbox-reply-all-dialog', $(this).data('dialog-title'),  $(this).data("url"), true);
			});
	    	$('#inbox-view-message-forward').click(function() { 
	    		modalDialog('inbox-forward-dialog', $(this).data('dialog-title'),  $(this).data("url"), true);
			});
	    	$('#inbox-view-message-report').click(function() { 
	    		modalDialog('add-report', $(this).data('dialog-title'),  $(this).data("url"), false);
			});
	    	$('#inbox-view-message-print').click(function() {
	    		$("#inbox-view-message-print-zone").print();
			});
	    }
	    function measureAddMesureRemoveLinkHandler(div, admin) {
	    	div.find('a').click(function() {
	     		   $(this).parents('#measure-add-measure-link-row').remove();
	     		   if (!admin && !$('#measure-add-measure-links').find('a').length) {
	     			  $('#measure-add-measure-publish').prop('disabled', 'disabled');
	     		   }
	               return false;
	           });
	    	if ($('#measure-add-measure-links').find('a').length) {
   			  $('#measure-add-measure-publish').prop('disabled', false);
//   			  console.log('enabled');
   		   	}
	    }
	    /**
	     *  Handlers for the ProposalController -> addAction
	     */
	    function measureAddMeasureHandlers(getSubCategoriesURL, admin) {
//	    	getSubCategoriesUrl = getSubCategoriesURL;
	    	// configuration options
    		$('.input-daterange').datepicker({
			    format: "dd/mm/yyyy",
			    calendarWeeks: true,
			    autoclose: true,
			    todayHighlight: true
			});
//    		$('a#measure-add-measure-remove-link').click(function() {
//	     		   $(this).parent().remove();
//	               return false;
//	           });
    		measureAddMesureRemoveLinkHandler($('#measure-add-measure-links'), admin);
    		$('#measure-add-measure-new-link').click(function () {
//    			var urlInput=$('<span><input type="url" name="links[]" class="form-control text-change"> <a href="" id="measure-add-measure-remove-link"><i class="fa fa-times-circle darkgray"></i></a></span>');
    			var urlInput=$('<div class="bot5" id="measure-add-measure-link-row">'+
    							'<div style="width: 250px; float: left;" class="bot5">'+
    							'<input type="url" name="links[]" class="form-control col-md-11 text-change" value="">'+
    							'</div>'+
    							'<div style="width: 20px; float: left; padding: 10px 0 0 5px;">'+
    							'<a href="" id="measure-add-measure-remove-link"><i class="fa fa-times-circle darkgray"></i></a>'+
    							'</div></div>');
    			urlInput.appendTo($('#measure-add-measure-links'));
    			measureAddMesureRemoveLinkHandler(urlInput, admin);
			});
//	    	$('#add-new-proposal-save-button').click(function () {
//
//				$('#add-new-proposal-save-button').prop('disabled', 'disabled');
//				$('#add-new-proposal-save-button-icon').removeClass("fa-floppy-o").addClass('fa-spinner').addClass('fa-pulse');
//
//				$('#add-new-proposal-form').submit();
//			});
			$('#measure-add-measure-publish').click(function () {
				$('#measure-add-measure-publish').prop('disabled', 'disabled').find('i').removeClass("fa-floppy-o").addClass('fa-spinner').addClass('fa-pulse');
				var action = $('#measure-add-measure-form').attr('action');
				if (action.indexOf("publish") < 0) {
					$('#measure-add-measure-form').attr('action', action+'/publish/true');
				}
				$('#measure-add-measure-form').submit();
			});
			$('#measure-add-measure-form').submit(function() { // catch the form's submit event
				$('#propDescription').html($('#measure-add-measure-textbox').html());
				$('#measure-add-measure-save').prop('disabled', 'disabled');
				$('#measure-add-measure-save').find('i').removeClass("fa-floppy-o").addClass('fa-spinner').addClass('fa-pulse');
				  //console.log('Calling submit...');
				  $.ajax({ // create an AJAX call...
				        data: $(this).serialize(), // get the form data
				        type: $(this).attr('method'), // GET or POST
				        url: $(this).attr('action'), // the file to call
				        success: function(response) { // on success..
				        	//console.log(response);
				        	if (typeof response ==  'object') {
				        		updatePages(['#measure-view-measure', '#administration-dashboard-draft-measures', '#measure-all-measures', '#city-news-list']);

				        	    $('.modal').modal('hide');
				        	}
				        	else {
				        	    // its not json
				        		$('#measure-add-measure').parent().html(response); // update the DIV
				        		
				        	}
				        },
				    });
//				    return false; // cancel original event to prevent form submitting
				});
			/*$('#add-new-proposal-dialog').on('hidden.bs.modal',function(e){
			    $(this).remove();
				//$(this).data('modal', null);
			    console.log('removed');
			});*/
//			$('#propSavedName, #propDescription').on('change propertychange click keyup input paste', function () {
//
//				if (($('#propDescription').val() == '' || $('#propSavedName').val() == '')) {
//					$('#add-new-proposal-save-button').prop('disabled', true);
//					$('#add-new-proposal-publish-button').prop('disabled', true);
//				} else {
//					$('#add-new-proposal-save-button').prop('disabled', false);
//					$('#add-new-proposal-publish-button').prop('disabled', false);
//				}
//			});
			$("#main_category").on("change", function () {
				//console.log(data);
				updateSubCategories($(this).val(), getSubCategoriesURL);
			});
	    }
	    /**
	     *  Handlers for the MesureController -> cityMeasuresAction
	     */
	    function mesureAllMeasuresHandlers() {
	    	
	    	var parent = $('#measure-all-measures');
	    	
	    	proposalAddProposalToProgramHandler(parent.find('a#add-to-program'));
	    	proposalRemoveProposalFromProgramHandler(parent.find('a#remove-from-program'));
	    	
	    	proposalAddRemoveFavoriteHandler($('a#measure-all-measures-favorite'));
	    	userMiniProfilePopover(parent.find('a#view-profile'));
	    	$('#measure-all-measures-add-new-measure').click(function(){
		  	    modalDialog('new-measure-dialog', $(this).data('dialog-title').replace('$level$', parent.find('#level-name').html()), $(this).data('url'), true);
		  	});
	    	$("a#page-all-measures").click(function(){
	    		console.log('page');
    		    parent.parent().load($(this).attr("href"));
    		    return false;
    		});
	    }
	    
	    /**
	     *  Handlers for the MesureController -> cityMeasuresAction
	     */
//	    function measureCountryMeasuresHandlers() {
//	    	proposalAddRemoveFavoriteHandler($("a#measure-country-measures-favorite"), $("#measure-country-measures"));
////	    	proposalAddProposalToProgramHandler('a#country-measures-add-proposal-to-scenario', $('#measure-city-measures').data('add-measure-to-scenario'));
//	    	userMiniProfilePopover($('a#country-measures-view-profile'));
//	    	$('#measure-country-measures-add-new-measure').click(function(){
//		  	    modalDialog('new-measure-dialog', $(this).data('dialog-title'), $(this).data('url'), true);
//		  	    return false;
//		  	});
//	    	$("a#page-country-measures").click(function(){
//    		    $("#measure-country-measures").parent().load($(this).attr("href"));
//    		    return false;
//    		});
//	    }
	    /**
	     *  Handlers for the MeasureController -> addAction
	     */
	    function measureClaimOwnershipHandlers() {
	    	$('#measure-claim-ownership-form').submit(function() { // catch the form's submit event7
				$('#measure-claim-ownership-submit').prop('disabled', 'disabled').find('i').removeClass("fa-certificate").addClass('fa-spinner').addClass('fa-pulse');
				  //console.log('Calling submit...');
				  $.ajax({ // create an AJAX call...
				        type: $(this).attr('method'), // GET or POST
				        url: $(this).attr('action'), // the file to call
				        success: function(response) { // on success..
				        	//console.log(response);
				        	if (typeof response ==  'object') {
				        		if (typeof $("#measure-view-measure").data('url') !== 'undefined') {
				        	    	$("#measure-view-measure").parent().load($("#measure-view-measure").data('url'));
				        		}
				        		$('.modal').modal('hide');
				        	    //$('#add-new-proposal-dialog').remove();
				        	}
				        	else {
				        	    // its not json
				        		$('#measure-claim-ownership').parent().html(response); // update the DIV
				        	}
				        },
				    });
				    return false; // cancel original event to prevent form submitting
				});
	    }
	    /**
	     *  Handlers for the MesureController -> draftMeasuresAction
	     */
	    function mesureDraftMeasuresHandlers() {
	    	$('#administration-dashboard-draft-measures-add-new-measure').click(function(){
		  	    modalDialog('new-measure-dialog', $(this).data('dialog-title'), $(this).data('url'), true);
		  	    return false;
		  	});
	    	$('a#administration-dashboard-draft-measures-edit-draft').click(function(){
		  	    modalDialog('edit-draft-measure-dialog', $('#administration-dashboard-draft-measures').data('edit-measure'), $(this).attr("href"), true);
		  	    return false;
		  	});
	    	$("a#page-draft-proposals").click(function(){
    		    $("#administration-dashboard-draft-measures").parent().load($(this).attr("href"));
    		    return false;
    		});
	    }
	    /**
	     *  Handlers for the ProposalController -> viewAction
	     */
	    function measureViewMeasureHandlers() {
	    	
	    	var parent = $('#measure-view-measure');
            
            proposalAddProposalToProgramHandler(parent.find('a#add-to-program'));
	    	proposalRemoveProposalFromProgramHandler(parent.find("a#remove-from-program"));
	    	
	    	updateContextualHelp('#measure-view-measure');
	    	proposalAddRemoveFavoriteHandler($('#measure-view-measure-favorite'));
	    	userMiniProfilePopover($('a#measure-view-measure-view-profile'));
	    	$('#measure-view-measure-edit').click(function(){
		  	    modalDialog('edit-measure-dialog', $(this).data('dialog-title'), $(this).data('url'), true);
		  	    return false;
		  	});
	    	$('#measure-view-measure-view-history').click(function(){
		  	    modalDialog('view-history-dialog', $(this).data('dialog-title'), $(this).data('url'), true);
		  	    return false;
		  	});
	    	$('#measure-view-measure-claim-ownership').click(function(){
		  	    modalDialog('claim-ownership-dialog', $(this).data('dialog-title'), $(this).data("url"), true);
		  	    return false;
		  	});
	    	$('#measure-view-measure-report').click(function(){
		  	    modalDialog('report-dialog', $(this).data('dialog-title'), $(this).attr("href"), false);
		  	    return false;
		  	});
	    }
	    /**
	     *  Handlers for the MeasureController -> viewHistoryAction
	     */
	    function measureViewHistoryHandlers() {
	    	userMiniProfilePopover($('a#measure-view-history-view-profile'));
	    }
	    /**
	     *  Handlers for the NewsController -> allNewsAction
	     */
	    function newsAllNewsHandlers() {
	    	userMiniProfilePopover($('a#news-all-news-view-profile'));
	    	$("a#page-all-news").click(function(){
			    $("#news-all-news").parent().load($(this).attr("href"));
			    return false;
			});
//			$("a#city-news-list-item").click(function () {
//	            var self = $(this);
//	            var proposalId = self.data('id');
//	            console.log('View proposal : ' + proposalId);
//	            if (proposalId == $('#view-proposal-div').data('id')) {
//	                $('#user-dashboard-div').hide();
//	                $('#view-proposal-div').show();
//	            } else {
//	                $('#view-proposal-div').data('id', proposalId);
//	                $('#view-proposal-div').load(self.attr("href"), function () {
//	                    $('#user-dashboard-div').hide();
//	                    $('#view-proposal-div').show();
//	                });
//	            }
//	            return false;
//	        });
	    	clearInterval(newsListTimer);
	    	newsListTimer = setInterval(function() {
	    		updatePages(['#news-all-news']);
		    		
				}, 600000);
	    }
	    function newsletterAddNewsletterHandlers() {
//	    	console.log("add banners");
		    var selectedCategoryValue = $('#mainCategories option:first').val();
	    	var imageFile = $('#mainCategories option:first').data('style');
	    	var selectedCategoryImage = imageFile.substring(23, imageFile.indexOf(';')-2);
	    	var selectedCategoryName = $('#mainCategories option:first').text();
	    	
	    	$('#mainCategories').TFOiconSelectImg({
	    		create: function (event, ui) {
	                  var widget = $(this).TFOiconSelectImg("widget");
	                  var $span = $('<span id="' + this.id + 'ImgSelected" class="TFOSizeImgSelected"> ').html("&nbsp;").appendTo(widget);
	                  $span.attr("style", $(this).children(":first").data("style"));
	//	                  console.log(selectedCategoryImage);
	              },
	              change: function (event, ui) {
	                  $("#" + this.id + 'ImgSelected').attr("style", ui.item.element.data("style"));
	                  selectedCategoryValue = ui.item.value;
	                  selectedCategoryImage = ui.item.element.data("style");
	                  selectedCategoryImage = selectedCategoryImage.substring(23, selectedCategoryImage.indexOf(';')-2);
	                  selectedCategoryName = ui.item.label;
	//	                  console.log(ui.item);
	              },
	              open: function(event, ui)
	              {
	              $('.ui-selectmenu-open').zIndex($('.modal').zIndex()+1);
	              }
	          }).TFOiconSelectImg("menuWidget").addClass("ui-menu-icons customicons");
	    	
	    	$('#picture-file').on("change", function() {
				imageUploadPreview(this, $("#newsletter-add-newsletter-preview"));
		    });
	    	
	    	function newsletterPreview() {
	    		$('#newsletter-add-newsletter-title').html($('#nlSubject').val());
	    		$('#newsletter-add-newsletter-message').html(nl2br($('#newsletter-add-newsletter-textbox').html()));
	    		if ($('#nlContact').val()!='') {
	    			$('#newsletter-add-newsletter-contact').html($('#nlContact').val());
	    		}
	    		else {
	    			$('#newsletter-add-newsletter-contact').html($('#newsletter-add-newsletter').data('no-contact'));
	    		}
	    		if ($('#nlUrl').val()!='') {
	    			$('#newsletter-add-newsletter-url').html($('<a target="_blank">').attr('href',$('#nlUrl').val()).text($('#nlUrl').val()));
	    		}
	    		else {
	    			$('#newsletter-add-newsletter-url').html($('#newsletter-add-newsletter').data('no-url'));
	    		}
	    	};
	    	
	    	if ($('#newsletter-add-newsletter').find('.text-danger').length) {
	    		newsletterPreview();
	    		updateCategoriesPreview();
	    	}
	    	
	    	$('#nlSubject, #newsletter-add-newsletter-textbox, #nlContact, #nlUrl').on("change propertychange click keyup input paste", newsletterPreview);
	    	
	    	$('a#newsletter-add-newsletter-remove-category').click(function() {
    			$(this).parent().remove();
    			updateCategoriesPreview();
    			return false;
    		});
	    	
	    	function updateCategoriesPreview() {
	    		$('#newsletter-add-newsletter-category-preview').empty();
	    		if (!$('#newsletter-add-newsletter-category-list > span').length) {
	    			$('#newsletter-add-newsletter-category-preview').html($('#newsletter-add-newsletter').data('no-categories-selected'));
	    		}
	    		else {
	    			$('#newsletter-add-newsletter-category-list > span').each(function() {
		    			var span = $(this).clone();
		    			span.find('span').remove();
		    			span.find('input').remove();
		    			span.find('a').remove();
		    			$('<span>&nbsp;</span>').appendTo(span);
		    			span.appendTo($('#newsletter-add-newsletter-category-preview'));
	    			});
	    		}
	    	}
	    	
	    	$('#newsletter-add-newsletter-add-category').click(function() {
	    		if (!$('#category-'+selectedCategoryValue).length && $('#newsletter-add-newsletter-category-list').find('input').length<3) {
		    		var span = $('<span id="category-'+selectedCategoryValue+'">');
		    		$('<span>&nbsp;</span>').appendTo(span);
		    		$('<img width="32" height="32" title="'+selectedCategoryName+'"	src="'+selectedCategoryImage+'">&nbsp; ').appendTo(span);
//		    		span.clone().appendTo($('#newsletter-add-newsletter-category-preview'));
		    		$('<input type="hidden" name="categories[]" value="'+selectedCategoryValue+'">').appendTo(span);
		    		$('<span>&nbsp;</span>').appendTo(span);
		    		$('<a href=""><i class="fa fa-times-circle darkgray"></i></a>')
			    		.click(function() {
			    			$(this).parent().remove();
			    			updateCategoriesPreview();
			    			return false;
			    		})
			    		.appendTo(span);
		    		span.appendTo($('#newsletter-add-newsletter-category-list'));
		    		updateCategoriesPreview();
//		    		$('#category-'+selectedCategoryValue).find('a').click(function() {
//		    			$(this).parent().remove();
//		    			return false;
//		    		});
	    		}
	    	});
	    	
	    	$('#newsletter-add-newsletter-citizens').click(function() {
	    		if ($(this).prop('checked')) {
	    			$('#newsletter-add-newsletter-champions').prop('checked', false);
	    		}
	    		newsletterAddNewsletterUpdateSendTo();
	    	});
	    	
	    	$('#newsletter-add-newsletter-champions').click(function() {
	    		if ($(this).prop('checked')) {
	    			$('#newsletter-add-newsletter-citizens').prop('checked', false);
	    		}
	    		newsletterAddNewsletterUpdateSendTo();
	    	});
	    	
			$('#newsletter-add-newsletter-partners').click(function() {
				newsletterAddNewsletterUpdateSendTo();
			});
			
			$('#newsletter-add-newsletter-contacts').click(function() {
				newsletterAddNewsletterUpdateSendTo();
			});
			
			$('#newsletter-add-newsletter-publish-button').click(function () {
				$(this).prop('disabled', 'disabled');
				$(this).find('i').removeClass("fa-share-square-o").addClass('fa-spinner').addClass('fa-pulse');
				var action = $('#newsletter-add-newsletter-form').attr('action');
				if (action.indexOf("publish") < 0) {
					$('#newsletter-add-newsletter-form').attr('action', action+'/publish/true');
				}
				$('#newsletter-add-newsletter-form').submit();
			});
			
			$('#newsletter-add-newsletter-form').submit(function() { // catch the form's submit event
				console.log('submit');
				$('#nlMessage').html($('#newsletter-add-newsletter-textbox').html());
				var fd = new FormData($('#newsletter-add-newsletter-form')[0]);
				$('#newsletter-add-newsletter-save-button > i').removeClass("fa-floppy-o").addClass('fa-spinner').addClass('fa-pulse');
				  //console.log('Calling submit...');
	  		    $.ajax({ // create an AJAX call...
	  		        data: fd, // get the form data
	  		        type: $(this).attr('method'), // GET or POST
	  		        url: $(this).attr('action'), // the file to call
	  		        processData: false,
			        contentType: false,
	  		        success: function(response) { // on success..
	  		        	if (typeof response ==  'object') {
			        		if (typeof $('#newsletter-my-newsletters').data('url') !== 'undefined') {
			        	    	$('#newsletter-my-newsletters').parent().load($('#newsletter-my-newsletters').data('url'));
			        		}
			        		$('.modal').modal('hide');
			        	    //$('#add-new-proposal-dialog').remove();
			        	}
			        	else {
			        	    // its not json
			        		$('#newsletter-add-newsletter').parent().html(response); // update the DIV
			        	}
	  		        },
	  		    });
	  		    return false; // cancel original event to prevent form submitting
			});
			
	    }
	    
	    function newsletterMyNewslettersHandlers() {
	    	newsletterAddNewsletterUpdateSendTo();
	    	$('#newsletter-my-newsletters-add-new-newsletter').click(function(){
		  	    modalDialog('new-newsletter-dialog', $(this).data('dialog-title'), $(this).data('url'), true);
		  	    return false;
		  	});
	    	$("a#page-my-newsletters").click(function(){
    		    $("#newsletter-my-newsletters").parent().load($(this).attr("href"));
    		    return false;
    		});
	    	$("a#newsletter-my-newsletters-edit").click(function(){
	    		modalDialog('edit-newsletter-dialog',  $('#newsletter-my-newsletters').data('edit-newsletter'), $(this).attr("href"), true);
		  	    return false;
    		});
	    }
	    
	    function resizeImages(fileInput, canvas,  complete, error) {
	    	var file = fileInput.prop('files')[0];
	        // read file as dataUrl
	        ////////  2. Read the file as a data Url
	    	if (file.type.match('image.*')) {
//	    	    console.log("is an image");
	    	    var reader = new FileReader();
		          // file read
		          reader.onload = function(e) {
		              // create img to store data url
		              ////// 3 - 1 Create image object for canvas to use
		              var img = new Image();
		              img.onload = function() {
		               /////////// 3-2 send image object to function for manipulation
		                complete(resizeInCanvas(img, canvas));
		              };
		              img.src = e.target.result;
		            };
		            // read file
		          reader.readAsDataURL(file);
	    	}
	    	else {
	    		error();
	    		
	    	}
	        
	       
	      }

	    

	    function resizeInCanvas(img, canvas){
	      /////////  3-3 manipulate image
	    	var perferedWidth = 500;
	      var ratio = perferedWidth / img.width;
	      
	      canvas?canvas = canvas[0]:$('<canvas>')[0];
	      canvas.width = img.width * ratio;
	      canvas.height = img.height * ratio;
	      canvas.style.width  = '100%';
//	      canvas.style.height = '00px';
	      var ctx = canvas.getContext("2d");
	      ctx.drawImage(img, 0,0,canvas.width, canvas.height);
	      //////////4. export as dataUrl
	      return canvas.toDataURL("image/jpeg");
	    }
	    
//	    function resizeImageToInput(from, to, canvas) {
//	    	$('.modal-body').block({ message: 'Resizing image...' }); 
//   		 	if (from.hasExtension(['jpg', 'png', 'gif', 'jpeg', 'bmp', 'tiff'])) {
//	    		from.siblings('.red').html("");
//	    		resizeImages(from, canvas, function(dataUrl) {
//					to.val(dataUrl);
//					canvas.parent().show();
//					$('.modal-body').unblock(); 
//			    }, function() {
//
//			    });
//	    	}
//	    	else {
//	    		from.siblings('.red').html("Please upload an image (png, jpg, jpeg, gif, bmp, tiff)");
//	    		canvas.parent().hide();
//	    		$('.modal-body').unblock(); 
//	    	}
//	    }
	    
	    
	    /**
	     *  Handlers for the ProposalController -> addProposalAction + editProposalAction
	     */
	    function proposalAddEditProposalHandlers() {
	    	
	    	$('canvas').each(function() {
	    		if (typeof $(this).data('image') !== 'undefined') {
	    			var canvas = $(this)[0];
//	    		      var context = canvas.getContext('2d');
	    		      var imageObj = new Image();

	    		      imageObj.onload = function() {
//	    		    	  canvas.height = img.height * ratio;
	    		    	  canvas.width = imageObj.width;
	    			      canvas.height = imageObj.height;
	    			      canvas.style.width  = '100%';
//	    			      canvas.style.height = '00px';
	    			      var ctx = canvas.getContext("2d");
	    			      ctx.drawImage(imageObj, 0,0,canvas.width, canvas.height);
//	    		    	  context.drawImage(imageObj, imageObj.width, imageObj.height);
	    		      };
	    		      imageObj.src = $(this).data('image');
	    		}
	    	});
	    	
	    	 $('#imageFile').change(function() {
//	    		 console.log('File changed!');
//	    		 resizeImageToInput($(this), $('#propImage1Resized'), $('#propImage1Canvas'));
	    		 
	    		 $('.modal-body').block({ message: 'Resizing image...' }); 
		 		if ($(this).hasExtension(['jpg', 'png', 'gif', 'jpeg', 'bmp', 'tiff'])) {
    		 		$(this).siblings('.red').html("");
    		 		var div = null;
    		 		if (!$('#proposal-add-edit-proposal-image-1').is(":visible")) {
    		 			div = $('#proposal-add-edit-proposal-image-1');
    		 		}
    		 		else if (!$('#proposal-add-edit-proposal-image-2').is(":visible")) {
    		 			div = $('#proposal-add-edit-proposal-image-2');
    		 		}
    		 		else if (!$('#proposal-add-edit-proposal-image-3').is(":visible")) {
    		 			div = $('#proposal-add-edit-proposal-image-3');
    		 		}
    		 		else {
    		 			$(this).siblings('.red').html($('#proposal-add-edit-proposal').data('error-image-size'));
//		 	    		canvas.parent().hide();
    		 			$('#imageFile').wrap('<form>').parent('form').trigger('reset');
	 					$('#imageFile').unwrap();
    		 			$('.modal-body').unblock(); 
    		 		}
    		 		if (div) {
		 	    		resizeImages($(this), div.find('canvas'), function(dataUrl) {
		 					div.find('input').val(dataUrl);
	//	 					div.find('canvas').attr('width', '100%').attr('height', '100%');
		 					div.show();
		 					$('#imageFile').wrap('<form>').parent('form').trigger('reset');
		 					$('#imageFile').unwrap();
		 					$('.modal-body').unblock(); 
		 			    }, function() {
	
		 			    });
    		 		}
	 	    	}
	 	    	else {
	 	    		$(this).siblings('.red').html($('#proposal-add-edit-proposal').data('error-image-type'));
//		 	    		canvas.parent().hide();
	 	    		$('.modal-body').unblock(); 
	 	    	}
		 	    
	    		 
	    		 
	    	 });
	    	 
	    	 
	    	 $('a#proposal-add-edit-proposal-remove-image').click(function() {
	    		 $(this).siblings('input').val('');
	    		 $(this).parent().hide();
	    		 $('#imageFile').prop('disabled', false);
	    		 return false;
	    	 });
	    	
	    	 
	    	$('#proposal-add-edit-proposal-save-button').click(function () {
	    		$('#proposal-add-edit-proposal-save-button').prop('disabled', 'disabled');
				$('#proposal-add-edit-proposal-save-button').find('i').removeClass("fa-floppy-o").addClass('fa-spinner').addClass('fa-pulse');
				
				$('#proposal-add-edit-proposal-form').submit();
				
			});
	    	
			$('#proposal-add-edit-proposal-publish-button').click(function () {
				$('#proposal-add-edit-proposal-publish-button').prop('disabled', 'disabled');
				$('#proposal-add-edit-proposal-publish-button-icon').removeClass("fa-floppy-o").addClass('fa-spinner').addClass('fa-pulse');
				var action = $('#proposal-add-edit-proposal-form').attr('action');
				if (action.indexOf("publish") < 0) {
					$('#proposal-add-edit-proposal-form').attr('action', action+'/publish/true');
				}
				$('#proposal-add-edit-proposal-form').submit();
			});
			
			$('#proposal-add-edit-proposal-form').submit(function() { // catch the form's submit event
				$('#propDescription').html($('#proposal-add-edit-proposal-textbox').html());
				
				$.ajax({ // create an AJAX call...
				        data: $(this).serialize(), // get the form data
				        type: $(this).attr('method'), // GET or POST
				        url: $(this).attr('action'), // the file to call
				        success: function(response) { // on success..
				        	//console.log(response);
				        	if (typeof response ==  'object') {
				        		if (response.level=='edit') {
				        			updatePages(["#proposal-view"]);
				        		}
				        		else {
				        			updatePages(["#proposal-my-proposals", "#proposal-all-proposals", "#news-all-news"]);
				        		}
				        		$('.modal').modal('hide');
				        	    //$('#proposal-add-edit-proposal-dialog').remove();
				        	}
				        	else {
				        	    // its not json
				        		$('#proposal-add-edit-proposal').parent().html(response); // update the DIV
				        	}
				        },
				    });
				  
				    return false; // cancel original event to prevent form submitting
				});

//			$('#propSavedName, #propDescription').on('change propertychange click keyup input paste', function () {
//				if (($('#propDescription').val() == '' || $('#propSavedName').val() == '')) {
//					$('#proposal-add-edit-proposal-save-button').prop('disabled', true);
//					$('#proposal-add-edit-proposal-publish-button').prop('disabled', true);
//				} else {
//					$('#proposal-add-edit-proposal-save-button').prop('disabled', false);
//					$('#proposal-add-edit-proposal-publish-button').prop('disabled', false);
//				}
//			});
			
//			$('#propSavedName').on('change propertychange click keyup input paste', function () {
//				if ($('#propSavedName').val() == '') {
//					$('#proposal-add-edit-proposal-save-button').prop('disabled', true);
//					$('#proposal-add-edit-proposal-publish-button').prop('disabled', true);
//				} else {
//					$('#proposal-add-edit-proposal-save-button').prop('disabled', false);
//					$('#proposal-add-edit-proposal-publish-button').prop('disabled', false);
//				}
//			});
			
			$('input[name=level]').change(function() {
				$('#add-proposal-category-image').html('<i class="fa fa-spinner fa-pulse fa-2x"></i>');
				
				$.post($('#proposal-add-edit-proposal').data('get-categories-url')+'/level/'+$('input[name="level"]:checked').val(), 
					function (itemJson) {
//							console.log(itemJson);
						$("#main_category option").remove();
						// $("#sub_category").append('<option value">Select a subcategory</option>');

						if (typeof itemJson.name != 'undefined') { // check if category has subcategories
							for (var i = 0; i < itemJson.name.length; i++) {
								$("#main_category").append('<option value="' + itemJson.id[i] + '" style="background: url('+itemJson.image[i]+') no-repeat; background-size: 32px 32px;  padding-left: 35px; padding-top:10px; height:35px;">' + itemJson.name[i] + '</option>');
							}
						}
						$("#main_category").val($("#main_category option:first").val());
						var imageFile = $("#main_category").find('option:selected').css('background-image');
						imageFile = imageFile.substring(4, imageFile.length-1);
//							console.log(imageFile);
						$('#add-proposal-category-image').html('<img width="32px" height="32px" src='+imageFile+'>');
						updateSubCategories($("#main_category").find('option:selected').val(), $('#proposal-add-edit-proposal').data('get-subcategories-url')+'/level/'+$('input[name="level"]:checked').val());
					}, 'json');
				return false;
		    });
			
			$("#main_category").on("change", function () {
				//console.log(data);
				var level = $('input[name="level"]:checked').length?$('input[name="level"]:checked').val():$('input[name="level"]').val();
				updateSubCategories($(this).val(), $('#proposal-add-edit-proposal').data('get-subcategories-url')+'/level/'+level);
			});
	    }
	    
	    

	    
	    function proposalAddRemoveFavoriteHandler(favId){
	    	
	    	var addFavorite='Add Favorite', removeFavorite='Remove Favorite';
//	    	console.log(favId);
	    	if (favId.length == 0) return;
	    	if (typeof favId.parents('.parent').data('add-favorite') !== 'undefined') {
	    		addFavorite = favId.parents('.parent').data('add-favorite');
	    	}
	    	if (typeof favId.parents('.parent').data('remove-favorite') !== 'undefined') {
	    		removeFavorite = favId.parents('.parent').data('remove-favorite');
	    	}
	    	
	    	favId.click(function () {
                var ref = $(this);
                var id = ref.data("favorite");
                var text = ref.html();
                ref.find('i').removeClass("fa-heart").removeClass("fa-heart-o").addClass('fa-spinner').addClass('fa-pulse');
                $.post(ref.attr("href"), {},
                    function (itemJson) {
                        if (itemJson.success) {
                        	if (typeof $('#proposal-my-favorites').data('url') !== 'undefined') {
                        		$('#proposal-my-favorites').parent().load($('#proposal-my-favorites').data('url'));
                        	}
                            ref.html((itemJson.success == 1) ? "<i class=\"fa fa-heart-o\"></i> "+addFavorite : "<i class=\"fa fa-heart\"></i> "+removeFavorite);
                        }
                        else {
                        	ref.html(text); 
                        }
                    }, 'json')
                    .done(function() {
                    });
                return false;
            });
	    }

	    function proposalAddProposalToProgramHandler(id){
	    	id.click(function(){
    		    modalDialog('add-proposal-to-program-dialog', id.parents('.parent').data('add-to-my-program'), $(this).attr("href"), false);
    		    return false;
    		});
	    }
	    
	    
	    function proposalRemoveProposalFromProgramHandler(id) {
	    	id.click(function(){
            	var ref = $(this);
//                var id = ref.data("favorite");
                ref.find('i').removeClass("fa-unlink").addClass('fa-spinner').addClass('fa-pulse');
                $.post(ref.attr("href"), {},
                    function (itemJson) {
                		var id=ref.data('id');
                		ref.html('<i class="fa fa-link"></i> '+ref.parents('.parent').data('add-to-my-program'));
                		ref.attr('href', ref.attr('href').replace('remove-', ''));
                		ref.attr('id', 'add-to-program');
                		ref.unbind('click');
                		proposalAddProposalToProgramHandler(ref);
                		if (typeof $('#program-all-programs').data('url') !== 'undefined') {
                			updatePages(['#program-all-programs', '#program-my-programs']);
                		}
                		else {
//                			console.log('update aggregated program');
                			updatePages(['#program-view-aggregated-program']);
                		}
                    }, 'json')
                    .done(function() {
                    });
                return false;
    		});
	    }
	    
	    /**
	     *  Handlers for the ProposalController -> countryProposalsAction
	     */
	    function proposalAllProposalsHandlers() {
	    	
	    	var parent = $('#proposal-all-proposals');
            
            proposalAddProposalToProgramHandler(parent.find('a#add-to-program'));
	    	proposalRemoveProposalFromProgramHandler(parent.find("a#remove-from-program"));
	    	
	    	userMiniProfilePopover(parent.find('a#view-profile'));
            // calling AJAX for add and remove favorites
	    	proposalAddRemoveFavoriteHandler(parent.find("a#favorite"));
	    	parent.find('#add-new-proposal').click(function(){
    		    modalDialog('add-new-proposal-dialog', $(this).data('dialog-title').replace('$level$', parent.find('#level-name').html()), $(this).data('url'));
    		    return false;
    		});
            $("a#page-all-proposals").click(function(){
    		    $("#proposal-all-proposals").parent().load($(this).attr("href"));
    		    return false;
    		});
              
	    }
	    
	    /**
	     *  Handlers for the ProposalController -> deleteAction
	     */
	    function proposalDeleteHandlers() {
			$('#delete-proposal-yes-button').click(function () {
				$('#delete-proposal-yes-button').prop('disabled', 'disabled');
				$('#delete-proposal-yes-button-icon').removeClass("fa-trash").addClass('fa-spinner').addClass('fa-pulse');
				$.ajax({ // create an AJAX call...
			        type: 'POST', // GET or POST
			        url: $(this).attr('href'),
			        success: function(response) { // on success..
			        	if (typeof response ==  'object') {
			        		 $('.modal').modal('hide');
			        		 parent.history.back();
			        	}
			        	else {
			        	    // its not json
			        		$('#delete-proposal-content').parent().html(response); // update the DIV
			        	}
			        },
			    });
			    return false; // cancel original event to prevent form submitting
			});
	    }
	    /**
	     *  Handlers for the ProposalController -> myFavoritesAction
	     */
	    function proposalMyFavoritesHandlers() {
	    	
	    	var parent = $('#proposal-my-favorites');
	    	
	    	userMiniProfilePopover($('a#proposal-my-favorites-view-profile'));
	    	proposalAddRemoveFavoriteHandler($('a#proposal-my-favorites-remove-favorite'));
	    	proposalAddProposalToProgramHandler(parent.find('a#add-to-program'));
	    	proposalRemoveProposalFromProgramHandler(parent.find('a#remove-from-program'));
//	    	proposalAddProposalToProgramHandler($('a#proposal-my-favorites-add-to-program'), $('#proposal-my-favorites').data('add-proposal-to-program'));
            
	    	$("a#page-fav").click(function(){
	  		  $('#proposal-my-favorites').reload($(this).attr("href"));
	  		    return false;
	  		});
		  	
	    }
	    /**
	     *  Handlers for the ProposalController -> myProposalsAction
	     */
	    function proposalMyProposalsHandlers() {
	    	
	    	var parent = $('#proposal-my-proposals');
            
            proposalAddProposalToProgramHandler(parent.find('a#add-to-program'));
	    	proposalRemoveProposalFromProgramHandler(parent.find("a#remove-from-program"));
	    	
	    	
	    	proposalAddRemoveFavoriteHandler($('a#proposal-my-proposals-favorite'));
	    	
	    	$("a#page-my-prop").click(function(){
	  		    $("#proposal-my-proposals").parent().load($(this).attr("href"));
	  		    return false;
	  		});
	  	  	
	    	$('#proposal-my-proposals-new-proposal').click(function(){
	  		    modalDialog('add-new-proposal-dialog', $(this).data('dialog-title'), $(this).data('url'));
	  		    return false;
	  		});
	  	  	
	    }
	    
	    /**
	     *  Handlers for the ProposalController -> publishAction
	     */
	    function proposalProlongDebateHandlers() {
	    	$('#proposal-prolong-debate-yes-button').click(function () {
				$(this).prop('disabled', 'disabled').find('i').removeClass("fa-calendar-plus-o").addClass('fa-spinner').addClass('fa-pulse');
				$.ajax({ // create an AJAX call...
			        type: 'POST', // GET or POST
			        url: $(this).data('url'), 
			        success: function(response) { // on success..
			        	//console.log(response);
			        	if (typeof response ==  'object') {
//			        		console.log(response);
			        		updatePages(['#proposal-view']);
			        	} 
			        	$('.modal').modal('hide');
			        },
			    });
			    return false; // cancel original event to prevent form submitting
			});
	    }
	    
	    /**
	     *  Handlers for the ProposalController -> publishAction
	     */
	    function proposalPublishProposalHandlers() {
	    	$('#proposal-publish-proposal-yes-button').click(function () {
				$(this).prop('disabled', 'disabled').find('i').removeClass("fa-floppy-o").addClass('fa-spinner').addClass('fa-pulse');
				$.ajax({ // create an AJAX call...
			        type: 'POST', // GET or POST
			        url: $(this).data('url'), 
			        success: function(response) { // on success..
			        	//console.log(response);
			        	if (typeof response ==  'object') {
			        		updatePages(['#proposal-view']);
			        	    $('.modal').modal('hide');
			        	    //$('#add-new-proposal-dialog').remove();
			        	} 
			        	else {
			        	    // its not json
			        		$('#publish-proposal-dialog').parent().html(response); // update the DIV
			        	}
			        },
			    });
			    return false; // cancel original event to prevent form submitting
			});
	    }
	    
	    /**
	     *  Handlers for the ProposalController -> viewAction
	     */
	    function proposalUserProposalsHandlers() {
	    	
	    	var parent = $('#proposal-user-proposals');
	    	
	    	proposalAddRemoveFavoriteHandler($('a#proposal-user-proposals-favorite'));
	    	
	    	proposalAddProposalToProgramHandler(parent.find('a#add-to-program'));
	    	proposalRemoveProposalFromProgramHandler(parent.find("a#remove-from-program"));
            
	    	$("a#page-user-proposals").click(function(){
    		    $("#proposal-user-proposals").parent().load($(this).attr("href"));
    		    return false;
    		});
	    }
	    /**
	     *  Handlers for the ProposalController -> viewAction
	     */
	    function proposalViewHandlers() {
	    	updateContextualHelp('#proposal-view-proposal');
	    	$('.equal .panel-body').matchHeight({
	    	    byRow: true,
	    	    property: 'min-height',
	    	    target: null,
	    	    remove: false
	    	});
//	    	$('.equal .item').matchHeight({
//	    	    byRow: true,
//	    	    property: 'min-height',
//	    	    target: null,
//	    	    remove: false
//	    	});
	    	
	    	var parent = $('#proposal-view');
            
            proposalAddProposalToProgramHandler(parent.find('a#add-to-program'));
	    	proposalRemoveProposalFromProgramHandler(parent.find("a#remove-from-program"));
	    	
	    	userMiniProfilePopover($('a#proposal-view-profile'));
	    	proposalAddRemoveFavoriteHandler($('#proposal-view-favorite'));
	    	
	    	$('#proposal-view-report').click(function(){
		  	    modalDialog('report-dialog', $(this).data('dialog-title'), $(this).attr("href"), false);
		  	    return false;
		  	});
	    
	    	$('#proposal-view-proposal-publish').click(function(){
				//$('#proposal-edit-draft-publish-button').prop('disabled', 'disabled');
			    modalDialog('publish-proposal-dialog', $(this).data('dialog-title'), $(this).data('url'), false);
			    return false;
			});
    	 
	    	$('#proposal-view-proposal-delete').click(function(){
				//$('#proposal-edit-proposal-publish-button').prop('disabled', 'disabled');
			    modalDialog('delete-proposal-dialog', $(this).data('dialog-title'), $(this).data('url'), false);
			});
	    	
	    	$('#proposal-view-proposal-extend-debate').click(function(){
		  	    modalDialog('report-dialog', $(this).data('dialog-title'), $(this).data("url"), false);
		  	    return false;
		  	});
	    	
	    	$('#proposal-view-proposal-edit').click(function(){
		  	    modalDialog('add-edit-proposal-dialog', $(this).data('dialog-title'), $(this).data("url"), true);
		  	    return false;
		  	});
	    }
	    function partnerSelectDepartmentsPartialFormHandlers(prefix) {
	    	$(prefix+'-add-departments').click(function(){
	 	        if (!$(prefix+'-from-departments option:selected').length) {
	 	        	return false;
	 	        }
	 	        moveSelect2Select($(prefix+'-from-departments :selected'), $(prefix+'-to-departments'));
	 	        orderOptgroups();
	 	    });
	 	    $(prefix+'-add-all-departments').click(function(){ // add all categories
	 	        $(prefix+'-from-departments option').each(function() {
	 	           moveSelect2Select($(this), $(prefix+'-to-departments'));
	 	        });
	 	        orderOptgroups();
	 	       return false;
	 	    });
	 	    $(prefix+'-remove-departments').click(function(){
	 	        if (!$(prefix+'-to-departments option:selected').length) {
	 	        	return false;
	 	        }
	 	        moveSelect2Select($(prefix+'-to-departments :selected'), $(prefix+'-from-departments'));
	 	        orderOptgroups();
	 	       return false;
	 	    });
	 	    $(prefix+'-remove-all-departments').click(function(){ // add all categories
	 	        $(prefix+'-to-departments option').each(function() {
	 	           moveSelect2Select($(this), $(prefix+'-from-departments'));
	 	        });
	 	        orderOptgroups();
	 	       return false;
	 	    });
	    }
	    function partnerProfilePartnerSelectCategoriesHandlers() {
	    	removeEmptyGroups();
	    	orderOptgroups();
	    	partnerSelectCategoriesPartialFormHandlers('#user-profile-partner-select-categories');
	    	$('#user-profile-partner-select-categories-form').submit(function() { // catch the form's submit event
	    		$('#user-profile-partner-select-categories-to-categories option').prop('selected', true);
	  		    $("#user-profile-partner-select-categories-save > i").removeClass("fa-floppy-o").addClass('fa-spinner').addClass('fa-pulse');
	  		    $.ajax({ // create an AJAX call...
	  		        data: $(this).serialize(), // get the form data
	  		        type: $(this).attr('method'), // GET or POST
	  		        url: $(this).attr('action'), // the file to call
	  		        success: function(response) { // on success..
	  		        	$('#partner-profile-partner-presentation-category-list').find('span').remove();
	  		        	$('#user-profile-partner-select-categories-to-categories optgroup').each(function() {
	  		        		var imageFile = $(this).find('option').first().css('background-image');
							imageFile = imageFile.substring(5, imageFile.length-2);
							//console.log(imageFile);
							var span = $('<span class="right5">');
	  		        		var cat = $('<img src="'+imageFile+'" valign="middle" width="34" height="34" title="" class="round5">');
	  		        		var main_title = $(this).attr('label');
	  		        		var title = '';
	  		        		$(this).find('option').each(function() {
	  		        			title += main_title + ' - ' + $(this).text() + '<br>';
	  		        		});
	  		        		cat.attr('title', title);
	  		        		span.append(cat);
	  		        		span.insertBefore('#partner-profile-partner-presentation-goto-categories');
	  		        	});
	  		        },
	  		        complete: function() {
	  		        	$("#user-profile-partner-select-categories-save > i").addClass("fa-floppy-o").removeClass('fa-spinner').removeClass('fa-pulse');
	  		        	$('#user-profile-partner-select-categories-to-categories option').prop('selected', false);
	  		        }
	  		    });
	  		    return false; // cancel original event to prevent form submitting
	  		});
	    }
	    function partnerProfilePartnerSelectDepartmentsHandlers() {
	    	removeEmptyGroups();
	    	orderOptgroups();
	    	partnerSelectDepartmentsPartialFormHandlers('#user-profile-partner-select-regions');
	    	$('#user-profile-partner-select-regions-form').submit(function() { // catch the form's submit event
	    		$('#user-profile-partner-select-regions-to-departments option').prop('selected', true);
	  		    $("#user-profile-partner-select-regions-save > i").removeClass("fa-floppy-o").addClass('fa-spinner').addClass('fa-pulse');
	  		    $.ajax({ // create an AJAX call...
	  		        data: $(this).serialize(), // get the form data
	  		        type: $(this).attr('method'), // GET or POST
	  		        url: $(this).attr('action'), // the file to call
	  		        success: function(response) { // on success..
	  		        	//$('#partner-profile-partner-presentation-category-list').find('img').remove();
	  		        	$('#partner-profile-partner-presentation-department-no-regions').html($('#user-profile-partner-select-regions-to-departments optgroup').length);
	  		        	$('#partner-profile-partner-presentation-department-no-departments').html($('#user-profile-partner-select-regions-to-departments option').length);
	  		        	var title = '';
	  		        	$('#user-profile-partner-select-regions-to-departments optgroup').each(function() {
	  		        		var main_title = $(this).attr('label');
	  		        		$(this).find('option').each(function() {
	  		        			title += main_title + ' - ' + $(this).text() + '<br>';
	  		        		});
	  		        		$('#partner-profile-partner-presentation-department-list').attr('title', title);
	  		        	});
	  		        },
	  		        complete: function() {
	  		        	$("#user-profile-partner-select-regions-save > i").addClass("fa-floppy-o").removeClass('fa-spinner').removeClass('fa-pulse');
	  		        	$('#user-profile-partner-select-regions-to-departments option').prop('selected', false);
	  		        }
	  		    });
	  		    return false; // cancel original event to prevent form submitting
	  		});
	    }
//	    function updateCityList(cities){
//	        $('#city').empty();
//	        if (!jQuery.isEmptyObject(cities)) {
//	        	$.each(cities, function (index) {
////		        	console.log($(this)[0]);
//		           var option = $('<option></option>');
//		           option.val($(this)[0].id);
//		           option.text($(this)[0].name);
//		           $("#city").append(option);
//		        });
//            }
//            else {
//            	var option = $('<option></option>');
//	           option.val(-1);
//	           option.text('No cities for this postal code!');
//	           $("#city").append(option);
//            }
//
//	        $("#city").val($("#city option:first").val());
//	        updateCity();
//	    }
//	    function updateCity() {
//	    	var selCityId = $("#city").find(":selected").val();
//	    	var selCityName = $("#city").find(":selected").text();
//	    	if (selCityId!=0) {
//	    		$('#user-edit-profile-other-city').hide();
//	    		   $('#usrCity').attr('readonly', 'readonly');
//	    		   $('#usrCity').val(selCityName);
//	    	}
//	    	else {
//	    		$('#user-edit-profile-other-city').show();
//	    		   $('#usrCity').removeAttr('readonly');
//	    		   $('#usrCity').val('');
//	    	}
////	    	console.log( $('#usrCity').val());
//	    }
	    function partnerProfilePartnerPresentationHandlers(getCityURL) {
	    	setLastCitySearch($('#usrPostalcode').val());
	    	$('#partner-profile-partner-presentation-goto-categories').click(function(){
	    		$('[href=#categ]').tab('show');
	    	});
	    	$('#partner-profile-partner-presentation-goto-departments').click(function(){
	    		$('[href=#area]').tab('show');
	    	});
	    	removeKeywordHandler('#partner-profile-partner-presentation-keyword-list');
	    	$('#partner-profile-partner-presentation-keyword-input').on('keypress', function (e) {
	            if (e.which==13) {
	            	$('#partner-profile-partner-presentation-add-keyword-button').click();
	            	return false;
	            }
	    	});
	    	$('#partner-profile-partner-presentation-add-keyword-button').on('click', function() {
	            var keyword = $.trim($('#partner-profile-partner-presentation-keyword-input').val());
	            if (keyword.length>0 && $('#partner-profile-partner-presentation-keyword-list span[id="'+keyword+'"]').length==0) {
	                $('#partner-profile-partner-presentation-keyword-list').append('<span class="badgegray right5" '+
	                		'id="'+keyword+'">'+keyword+
	                	    '<input name="keywords[]" type="hidden" value="'+keyword+'"> '+
	                	    '<a href=""><i class="fa fa-times-circle darkgray"></i></a> </span>');
	                removeKeywordHandler('#partner-profile-partner-presentation-keyword-list');
	                //console.log(keyword);
	                $('#partner-profile-partner-presentation-keyword-input').val('');
	            }
	        });
	    	$("#usrPostalcode").on('keyup change', function() {
	    		getCities(getCityURL);
	    	});
//	    	$("#country").on('keyup change', getCities(getCityURL));
//	        $("#city").on('change', updateCity);
	        $('.partner-presentation-text-change').on('keypress', function (e) {
	            if (e.which==13) {
	            	 var inputs = $(this).closest('form').find(':focusable').not('a, button');
	                 inputs.eq(inputs.index(this) + 1).focus();
	            	return false;
	            }
	    	});
	        $('#user-profile-partner-presentation-form').submit(function() { // catch the form's submit event
	    		$("#partner-profile-partner-presentation-save > i").removeClass("fa-floppy-o").addClass('fa-spinner').addClass('fa-pulse');
	  		    $.ajax({ // create an AJAX call...
	  		        data: $(this).serialize(), // get the form data
	  		        type: $(this).attr('method'), // GET or POST
	  		        url: $(this).attr('action'), // the file to call
	  		        success: function(response) { // on success..
	  		        	$('#partner-profile-partner-presentation').parent().html(response);
	  		        },
	  		    });
	  		    return false; // cancel original event to prevent form submitting
	  		});
	        $('#partner-profile-partner-presentation-reset').click(function() {
	        	$("#partner-profile-partner-presentation-reset > i").removeClass("fa-undo").addClass('fa-spinner').addClass('fa-pulse');
	        	$('#partner-profile-partner-presentation').parent().load($(this).attr('href'));
	        	return false;
	        });
	    }
	    function partnerSearchOppotunitiesFormResetDepartments() {
	    	// remove all departments
	 		  $('#partner-dashboard-search-opportunities-form-to-departments option').each(function() {
	 	           moveSelect2Select($(this), $('#partner-dashboard-search-opportunities-form-from-departments'));
	 	        });
	 		  // insert only previously selected departments
	 		 $.each(partnerDashboardSearchOpportunitiesFormCoveredDepartments, function(index,value) {
	 			 moveSelect2Select($('#partner-dashboard-search-opportunities-form-from-departments > optgroup > option[value="'+value+'"]'), $('#partner-dashboard-search-opportunities-form-to-departments'));
	 		  });
	 		  orderOptgroups();
	 		  // add to list
	 		 $('#partner-dashboard-search-opportunities-form-department-list').empty();
	 		  $('#partner-dashboard-search-opportunities-form-no-departments').html($("#partner-dashboard-search-opportunities-form-to-departments > optgroup ").length);
	 		  //partnerDashboardSearchOpportunitiesFormSearchDepartments = [];
	 		  $("#partner-dashboard-search-opportunities-form-to-departments > optgroup > option").each(function() {
	 			 //partnerDashboardSearchOpportunitiesFormSearchDepartments.push($(this).val());
	 			  $('#partner-dashboard-search-opportunities-form-department-list').append('<span class="badgegray" '+
	 					(($.inArray($(this).val(), partnerDashboardSearchOpportunitiesFormCoveredDepartments)==-1)?'style="background-color:#FFE1C0;"':'')+
	 					'data-id="'+$(this).val()+'">'+$(this).text()+' <input name="departments[]" type="hidden" value="'+$(this).val()+'"><a href=""><i class="fa fa-times-circle darkgray"></i></a></span> ');
	 		  });
	 		  $('#partner-dashboard-search-opportunities-form-department-list span a').click(function() {
	 			  $(this).parent().remove();
	 			  return false;
	 		  });
	    }
	    function partnerSearchOppotunitiesFormDepartmentHandlers(coveredDepartments) {
	    	// save user covered departments
	    	partnerDashboardSearchOpportunitiesFormCoveredDepartments = coveredDepartments;
	    	removeEmptyGroups();
	   	 	orderOptgroups();
	    	partnerSelectDepartmentsPartialFormHandlers('#partner-dashboard-search-opportunities-form');
	    	//partnerSelectCategoriesPartialFormHandlers('#partner-dashboard-search-opportunities-form');
		 	   $('#partner-dashboard-search-opportunities-form-department-list span a').click(function() {
		 		   moveSelect2Select($('#partner-dashboard-search-opportunities-form-to-departments > optgroup > option[value="'+ $(this).parent().data('id')+'"]'), $('#partner-dashboard-search-opportunities-form-from-departments'));
		 		   orderOptgroups();
	               $(this).parent().remove();
	               return false;
	           });
		 	  $('#partner-dashboard-search-opportunities-form-collapse-region-button').click(function() {
		 		  //$('#partner-dashboard-search-opportunities-form-collapse-region-button').click();
		 		 $('#partner-dashboard-search-opportunities-form-collapse-region').collapse('show');
		 		 $('#partner-dashboard-search-opportunities-form-collapse-region-button').attr('disabled', 'disabled');
				  return false;
			  });
		 	  $('#partner-dashboard-search-opportunities-form-save-departments').click(function() {
		 		  $('#partner-dashboard-search-opportunities-form-department-list').empty();
		 		  $('#partner-dashboard-search-opportunities-form-no-departments').html($("#partner-dashboard-search-opportunities-form-to-departments > optgroup ").length);
		 		  //partnerDashboardSearchOpportunitiesFormSearchDepartments = [];
		 		  $("#partner-dashboard-search-opportunities-form-to-departments > optgroup > option").each(function() {
		 			 //partnerDashboardSearchOpportunitiesFormSearchDepartments.push($(this).val());
		 			  $('#partner-dashboard-search-opportunities-form-department-list').append('<span class="badgegray" '+
		 					(($.inArray($(this).val(), partnerDashboardSearchOpportunitiesFormCoveredDepartments)==-1)?'style="background-color:#FFE1C0;"':'')+
		 					'data-id="'+$(this).val()+'">'+$(this).text()+' <input name="departments[]" type="hidden" value="'+$(this).val()+'"><a href=""><i class="fa fa-times-circle darkgray"></i></a></span> ');
		 		  });
		 		  $('#partner-dashboard-search-opportunities-form-department-list span a').click(function() {
		 			 moveSelect2Select($('#partner-dashboard-search-opportunities-form-to-departments > optgroup > option[value="'+ $(this).parent().data('id')+'"]'), $('#partner-dashboard-search-opportunities-form-from-departments'));
			 		   orderOptgroups();
		 			  $(this).parent().remove();
		 			  return false;
		 		  });
		 		 $('#partner-dashboard-search-opportunities-form-collapse-region').collapse('hide');
		 		 $('#partner-dashboard-search-opportunities-form-collapse-region-button').removeAttr('disabled');
		 		  return false;
		 	  });
		 	 $('#partner-dashboard-search-opportunities-form-cancel-departments').click(function() {
		 		  //$('#partner-dashboard-search-opportunities-form-collapse-region-button').click();
		 		  // remove all departments
		 		  $('#partner-dashboard-search-opportunities-form-to-departments option').each(function() {
		 	           moveSelect2Select($(this), $('#partner-dashboard-search-opportunities-form-from-departments'));
		 	        });
		 		  // insert only previously selected departments
		 		 $('#partner-dashboard-search-opportunities-form-department-list > span').each(function() {
		 			 moveSelect2Select($('#partner-dashboard-search-opportunities-form-from-departments > optgroup > option[value="'+$(this).data('id')+'"]'), $('#partner-dashboard-search-opportunities-form-to-departments'));
		 		  });
		 		  orderOptgroups();
		 		 $('#partner-dashboard-search-opportunities-form-collapse-region').collapse('hide');
		 		 $('#partner-dashboard-search-opportunities-form-collapse-region-button').removeAttr('disabled');
				  return false;
			  });
		 	$('#partner-dashboard-search-opportunities-form-reset-department-list').click(function() {
		 		  partnerSearchOppotunitiesFormResetDepartments();
		 		  //$('#partner-dashboard-search-opportunities-form-collapse-region-button').click();
		 		  return false;
			  });
		 	$('#partner-dashboard-search-opportunities-form-clear-department-list').click(function() {
		 		  // remove all departments
		 		  $('#partner-dashboard-search-opportunities-form-to-departments option').each(function() {
		 	           moveSelect2Select($(this), $('#partner-dashboard-search-opportunities-form-from-departments'));
		 	        });
		 		  orderOptgroups();
		 		 $('#partner-dashboard-search-opportunities-form-no-departments').html('0');
		 		  // add to list
		 		 $('#partner-dashboard-search-opportunities-form-department-list').empty();
		 		  return false;
			  });
	    }
	    function partnerSelectCategoriesPartialFormHandlers(prefix) {
	    	$(prefix+'-add-categories').click(function(){
	 	        if (!$(prefix+'-from-categories option:selected').length) {
	 	        	return false;
	 	        }
	 	        moveSelect2Select($(prefix+'-from-categories :selected'), $(prefix+'-to-categories'));
	 	        orderOptgroups();
	 	       return false;
	 	    });
	 	    $(prefix+'-add-all-categories').click(function(){ // add all categories
	 	        $(prefix+'-from-categories option').each(function() {
	 	           moveSelect2Select($(this), $(prefix+'-to-categories'));
	 	        });
	 	        orderOptgroups();
	 	       return false;
	 	    });
	 	    $(prefix+'-remove-categories').click(function(){
	 	        if (!$(prefix+'-to-categories option:selected').length) {
	 	        	return false;
	 	        }
	 	        moveSelect2Select($(prefix+'-to-categories :selected'), $(prefix+'-from-categories'));
	 	        orderOptgroups();
	 	       return false;
	 	    });
	 	    $(prefix+'-remove-all-categories').click(function(){ // add all categories
	 	        $(prefix+'-to-categories option').each(function() {
	 	           moveSelect2Select($(this), $(prefix+'-from-categories'));
	 	        });
	 	        orderOptgroups();
	 	       return false;
	 	    });
	    }
	    function partnerSearchOppotunitiesFormRemoveCategoryHandler() {
	    	$('#partner-dashboard-search-opportunities-form-line span a').click(function() {
		 		   moveSelect2Select($('#partner-dashboard-search-opportunities-form-to-categories > optgroup > option[value="'+ $(this).parent().data('id')+'"]'), $('#partner-dashboard-search-opportunities-form-from-categories'));
		 		   orderOptgroups();
			 		if ($(this).parent().siblings().length) {
			 			$(this).parent().remove();
			 		}
			 		else {
			 			$(this).parents('tr').remove();
			 		}
		            return false;
		        });
	    }
	    function partnerSearchOppotunitiesFormResetCategories() {
	    	// remove all departments
	 		  $('#partner-dashboard-search-opportunities-form-to-categories option').each(function() {
	 	           moveSelect2Select($(this), $('#partner-dashboard-search-opportunities-form-from-categories'));
	 	        });
	 		  // insert only previously selected departments
	 		 $.each(partnerDashboardSearchOpportunitiesFormCoveredCategories, function(index,value) {
	 			 moveSelect2Select($('#partner-dashboard-search-opportunities-form-from-categories > optgroup > option[value="'+value+'"]'), $('#partner-dashboard-search-opportunities-form-to-categories'));
	 		  });
	 		  orderOptgroups();
	 		  // add to list
	 		  $('#partner-dashboard-search-opportunities-form-categories-table').empty();
	 		  $("#partner-dashboard-search-opportunities-form-to-categories > optgroup").each(function() {
	 			  // if line not exist => tr
	 			  var imageFile = $(this).children().first().css('background-image');
	 			  imageFile = imageFile.substring(5, imageFile.length-2);
	 			  console.log(imageFile);
	 			  var tableLine = $('<tr>'+
	 					  '<td style="width:32px; border-top:none;"><img src="'+imageFile+'" height="24" width="24" title="'+$(this).attr('label')+'">&nbsp;</td>'+
	 					  '<td style="line-height:1.6em; border-top:none;" id="partner-dashboard-search-opportunities-form-line">'+
	 			  '</td></tr>');
	 			  $('#partner-dashboard-search-opportunities-form-categories-table').append(tableLine);
	 			  $(this).children().each(function() {
	 				  console.log('Add option '+ $(this).text());
	 				  tableLine.find('#partner-dashboard-search-opportunities-form-line').append('<span class="badgegray" '+
	 						  (($.inArray($(this).val(), partnerDashboardSearchOpportunitiesFormCoveredCategories)==-1)?'style="background-color:#FFE1C0;"':'')+
	 						  'data-id="'+$(this).val()+'">'+$(this).text()+' <input name="categories[]" type="hidden" value="'+$(this).val()+'"><a href=""><i class="fa fa-times-circle darkgray"></i></a></span> ');
	 			  });
	 		  });
	 		  partnerSearchOppotunitiesFormRemoveCategoryHandler();
	    }
	    /**
	     *
	     * @param coveredCategories
	     */
	    function partnerSearchOppotunitiesFormCategoryHandlers(coveredCategories) {
	    	// save user covered categories
	    	partnerDashboardSearchOpportunitiesFormCoveredCategories = coveredCategories;
	    	/*$.each(partnerDashboardSearchOpportunitiesFormCoveredCategories, function(index,value) {
	    		 moveSelect2Select($('#partner-dashboard-search-opportunities-form-from-categories > optgroup > option[value="'+value+'"]'), $('#partner-dashboard-search-opportunities-form-to-categories'));
	 		  });*/
	    	removeEmptyGroups();
	   	 	orderOptgroups();
	    	partnerSelectCategoriesPartialFormHandlers('#partner-dashboard-search-opportunities-form');
	    	/**
	    	 * Remove a category from the list
	    	 */
	    	partnerSearchOppotunitiesFormRemoveCategoryHandler();
	    	$('#partner-dashboard-search-opportunities-form-collapse-categories-button').click(function() {
		 		  //$('#partner-dashboard-search-opportunities-form-collapse-region-button').click();
		 		 $('#partner-dashboard-search-opportunities-form-collapse-categories').collapse('show');
		 		 $('#partner-dashboard-search-opportunities-form-collapse-categories-button').attr('disabled', 'disabled');
				  return false;
			  });
	    	$('#partner-dashboard-search-opportunities-form-save-categories').click(function() {
	    		$('#partner-dashboard-search-opportunities-form-categories-table').empty();
	    		$("#partner-dashboard-search-opportunities-form-to-categories > optgroup").each(function() {
	    			// if line not exist => tr
	    			var imageFile = $(this).children().first().css('background-image');
	    			imageFile = imageFile.substring(5, imageFile.length-2);
	    			//console.log(imageFile);
	    			var tableLine = $('<tr>'+
	    					'<td style="width:32px; border-top:none;"><img src="'+imageFile+'" height="24" width="24" title="'+$(this).attr('label')+'">&nbsp;</td>'+
	    					'<td style="line-height:1.6em; border-top:none;" id="partner-dashboard-search-opportunities-form-line">'+
	    			'</td></tr>');
	    			$('#partner-dashboard-search-opportunities-form-categories-table').append(tableLine);
	    			$(this).children().each(function() {
	    				//console.log('Add option '+ $(this).text());
	    				tableLine.find('#partner-dashboard-search-opportunities-form-line').append('<span class="badgegray" '+
	    						(($.inArray($(this).val(), partnerDashboardSearchOpportunitiesFormCoveredCategories)==-1)?'style="background-color:#FFE1C0;"':'')+
	    						'data-id="'+$(this).val()+'">'+$(this).text()+' <input name="categories[]" type="hidden" value="'+$(this).val()+'"><a href=""><i class="fa fa-times-circle darkgray"></i></a></span> ');
	    			});
	    		});
	    		partnerSearchOppotunitiesFormRemoveCategoryHandler();
	    		$('#partner-dashboard-search-opportunities-form-collapse-categories').collapse('hide');
	    		$('#partner-dashboard-search-opportunities-form-collapse-categories-button').removeAttr('disabled');
	    		return false;
	    	});
	    	$('#partner-dashboard-search-opportunities-form-cancel-categories').click(function() {
	    		//$('#partner-dashboard-search-opportunities-form-collapse-region-button').click();
	    		// remove all departments
	    		$('#partner-dashboard-search-opportunities-form-to-categories option').each(function() {
	    			moveSelect2Select($(this), $('#partner-dashboard-search-opportunities-form-from-categories'));
	    		});
	    		// insert only previously selected departments
	    		$('#partner-dashboard-search-opportunities-form-line > span').each(function() {
	    			moveSelect2Select($('#partner-dashboard-search-opportunities-form-from-categories > optgroup > option[value="'+$(this).data('id')+'"]'), $('#partner-dashboard-search-opportunities-form-to-categories'));
	    		});
	    		orderOptgroups();
	    		$('#partner-dashboard-search-opportunities-form-collapse-categories').collapse('hide');
	    		$('#partner-dashboard-search-opportunities-form-collapse-categories-button').removeAttr('disabled');
	    		return false;
	    	});
		 	$('#partner-dashboard-search-opportunities-form-reset-categories-list').click(function() {
		 		partnerSearchOppotunitiesFormResetCategories();
		 		  //$('#partner-dashboard-search-opportunities-form-collapse-region-button').click();
		 		  return false;
			  });
		 	$('#partner-dashboard-search-opportunities-form-clear-categories-list').click(function() {
		 		// remove all departments
		 		$('#partner-dashboard-search-opportunities-form-to-categories option').each(function() {
		 			moveSelect2Select($(this), $('#partner-dashboard-search-opportunities-form-from-categories'));
		 		});
		 		orderOptgroups();
		 		// add to list
		 		$('#partner-dashboard-search-opportunities-form-categories-table').empty();
		 		return false;
		 	});
	    }
	    /**
	     * Function to add handler to close button for keywords
	     */
	    function removeKeywordHandler(prefix) {
	    	$(prefix + ' span a').click(function() {
	     		   $(this).parent().remove();
	               return false;
	           });
	    }
	    function partnerSearchOppotunitiesFormResetKeywords() {
	    	$('#partner-dashboard-search-opportunities-form-keyword-list').empty();
	   		 $.each(partnerDashboardSearchOpportunitiesFormCoveredKeywords, function(index,keyword) {
	   			 $('#partner-dashboard-search-opportunities-form-keyword-list').append('<span class="badgegray" id="'+keyword+'">'+keyword+
	   	            	    '<input name="keywords[]" type="hidden" value="'+keyword+'"> '+
	   	            	    '<a href=""><i class="fa fa-times-circle darkgray"></i></a></span>');
	   		  });
	   		removeKeywordHandler('#partner-dashboard-search-opportunities-form-keyword-list');
	    }
	    /**
	     *
	     * @param coveredKeywords
	     */
	    function partnerSearchOppotunitiesFormKeywordsHandlers(coveredKeywords) {
	    	partnerDashboardSearchOpportunitiesFormCoveredKeywords = coveredKeywords;
	    	removeKeywordHandler('#partner-dashboard-search-opportunities-form-keyword-list');
	    	// if ENTER (13) is press in input
	    	$('#partner-dashboard-search-opportunities-form-keyword-input').on('keypress', function (e) {
	            if (e.which==13) {
	            	$('#partner-dashboard-search-opportunities-form-add-keyword-button').click();
	            	return false;
	            }
	    	});
	    	$('#partner-dashboard-search-opportunities-form-add-keyword-button').on('click', function() {
	            var keyword = $.trim($('#partner-dashboard-search-opportunities-form-keyword-input').val());
	            if (keyword.length>0 && $('#partner-dashboard-search-opportunities-form-keyword-list span[id="'+keyword+'"]').length==0) {
	                $('#partner-dashboard-search-opportunities-form-keyword-list').append('<span class="badgegray" '+
	                		(($.inArray(keyword, partnerDashboardSearchOpportunitiesFormCoveredKeywords)==-1)?'style="background-color:#FFE1C0;" ':'')+
	                		'id="'+keyword+'">'+keyword+
	                	    '<input name="keywords[]" type="hidden" value="'+keyword+'"> '+
	                	    '<a href=""><i class="fa fa-times-circle darkgray"></i></a></span>');
	                removeKeywordHandler('#partner-dashboard-search-opportunities-form-keyword-list');
	                //console.log(keyword);
	                $('#partner-dashboard-search-opportunities-form-keyword-input').val('');
	            }
	        });
	    	$('#partner-dashboard-search-opportunities-form-reset-keyword-list').click(function() {
	    		  partnerSearchOppotunitiesFormResetKeywords();
	    		  return false;
	    	  });
	    	$('#partner-dashboard-search-opportunities-form-clear-keyword-list').click(function() {
	    		$('#partner-dashboard-search-opportunities-form-keyword-list').empty();
	    		return false;
	    	  });
	    }
	    function partnerSearchOppotunitiesFormHandlers(coveredDepartments, coveredCategories, coveredKeywords) {
	    	partnerSearchOppotunitiesFormDepartmentHandlers(coveredDepartments);
	    	partnerSearchOppotunitiesFormCategoryHandlers(coveredCategories);
	    	partnerSearchOppotunitiesFormKeywordsHandlers(coveredKeywords);
	    	$('#partner-dashboard-search-opportunities-form-reset-form').click(function() {
	    		  partnerSearchOppotunitiesFormResetKeywords();
	    		  partnerSearchOppotunitiesFormResetCategories();
	    		  partnerSearchOppotunitiesFormResetDepartments();
	    		  return false;
	    	  });
	    	$('#partner-dashboard-search-opportunities-form-form').submit(function() { // catch the form's submit event
	  		   console.log('Search opportunities');
	  		    $("#partner-dashboard-search-opportunities-form-update-results > i").removeClass("fa-refresh").addClass('fa-spinner').addClass('fa-pulse');
	  		    $.ajax({ // create an AJAX call...
	  		        data: $(this).serialize(), // get the form data
	  		        type: $(this).attr('method'), // GET or POST
	  		        url: $(this).attr('action'), // the file to call
	  		        success: function(response) { // on success..
	  		        	//alert(response);
	  		        	$('#partner-dashboard-search-opportunities').parent().html(response);
	  			 	  // update the DIV
	  		        },
	      	        complete: function() {
	      	        	$("#partner-dashboard-search-opportunities-form-update-results > i").addClass("fa-refresh").removeClass('fa-spinner').removeClass('fa-pulse');
	      	        }
	  		    });
	  		    return false; // cancel original event to prevent form submitting
	  		});
	    }
	    
	    /**
	     *  Handlers for the ReportController -> addReportAction
	     */
	    function reportAddReportHandlers() {
	    	
			$('#report-add-report-form').submit(function() { // catch the form's submit event
				$('#report-add-report-submit-button').prop('disabled', 'disabled').find('i').removeClass("fa-exclamation-triangle").addClass('fa-spinner').addClass('fa-pulse');
				  //console.log('Calling submit...');  
				  $.ajax({ // create an AJAX call...
				        data: $(this).serialize(), // get the form data
				        type: $(this).attr('method'), // GET or POST
				        url: $(this).attr('action'), // the file to call
				        success: function(response) { // on success..
//				        	updatePages(['#scenario-my-scenarios', '#scenario-city-scenarios'])
				        	$('.modal-body').html(response); 
				        },
				    });
				    return false; // cancel original event to prevent form submitting
				});
			
	    }
	    
    	function reportSubmitBugHandlers() {
	    	
	    	$('#report-submit-bug-form').submit(function() { // catch the form's submit event
//				console.log('submit');
				var fd = new FormData($(this)[0]);
				$( "#report-submit-bug-submit-icon" ).removeClass("fa-bug").addClass('fa-spinner').addClass('fa-pulse');
				  //console.log('Calling submit...');
	  		    $.ajax({ // create an AJAX call...
	  		        data: fd, // get the form data
	  		        type: $(this).attr('method'), // GET or POST
	  		        url: $(this).attr('action'), // the file to call
	  		        processData: false,
			        contentType: false,
	  		        success: function(response) { // on success..
	  		        	$('#report-submit-bug').html(response); // update the DIV
	  		        },
	  		    });
	  		    return false; // cancel original event to prevent form submitting
			});
	    	
	    }
    	
    	
    	/**
	     *  Handlers for the ProgramController -> addProgramAction
	     */
	    function programAddEditProgramHandlers() {
	    	
			$('#program-add-program-form').submit(function() { // catch the form's submit event
				$('#progDescription').html($('#program-add-program-textbox').html());
				$('#program-add-program-save').prop('disabled', 'disabled').find('i').removeClass("fa-floppy-o").addClass('fa-spinner').addClass('fa-pulse');
				  //console.log('Calling submit...');  
				  $.ajax({ // create an AJAX call...
				        data: $(this).serialize(), // get the form data
				        type: $(this).attr('method'), // GET or POST
				        url: $(this).attr('action'), // the file to call
				        success: function(response) { // on success..
				        	console.log(response);
				        	if (typeof response ==  'object') {
				        		
				        		if (typeof $('#program-add-program-save').data('url') !== 'undefined') {
				        			$('#modal-dialog-title').html($('#program-add-program-save').data('dialog-title'));
				        			$('.modal-body').load(response.link);
				        			$('#dialog-help-button').attr('href', '/pages/help#add-proposal-to-program-dialog')
//				        			modalDialog('add-proposal-dialog', , response.link, false);
				        		}
				        		else {
				        			updatePages(['#program-view-program']);
				        			$('.modal').modal('hide');
				        		}
				        	    //$('#add-new-proposal-dialog').remove();
				        	} 
				        	else {
					        	updatePages(['#program-my-programs', '#program-all-programs']);
					        	$('.modal-body').html(response);
				        	}
				        },
				    });
				    return false; // cancel original event to prevent form submitting
				});
	    }
	    
	    /**
	     *  Handlers for the ProgramController -> getProposalsAction
	     */
	    function programGetProposalsHandlers(owner) {
	    	$('.equal .panel-body').matchHeight({
	    	    byRow: true,
	    	    property: 'min-height',
	    	    target: null,
	    	    remove: false
	    	});
	    	userMiniProfilePopover($('a#program-get-proposals-view-profile'));
	    	
	    	$("a#program-get-proposals-favorite").click(function () {
                var ref = $(this);
                var id = ref.data("favorite");
                var text = ref.html();
                ref.find('i').removeClass("fa-heart").removeClass("fa-heart-o").addClass('fa-spinner').addClass('fa-pulse');
                $.post(ref.attr("href"), {},
                    function (itemJson) {
                        if (itemJson.success) {
                        	ref.html((itemJson.success == 1) ? "<i class=\"fa fa-heart-o\"></i>": "<i class=\"fa fa-heart\"></i>");
                        }
                        else {
                        	ref.html(text); 
                        }
                    }, 'json')
                    .done(function() {
                    });
                return false;
            });
	    	
	    	
	    	$("a#page-prog-prop").click(function(){
    		    $("#program-get-proposals").parent().load($(this).attr("href"));
    		    return false;
    		});
	    	  // calling AJAX to remove proposals from scenario
	    	$("a#program-get-proposals-remove-proposal").click(function () {
	              $(this).find('i').removeClass("fa-times-circle").addClass('fa-spinner').addClass('fa-pulse');
	              $.get($(this).attr("href"), {},
	                  function (itemJson) {
	            	  	updatePages(['#program-get-proposals']);
	                  }, 'json');
	              return false;
	          });
	          
	    	$('#add-remove-proposals').click(function(){
    		    modalDialog('add-remove-proposals-dialog', $(this).data('dialog-title'), $(this).data("url"));
	    	});
	    	$('#sort-proposals-button').click(function(){
    		    modalDialog('sort-proposals-dialog',  $(this).data('dialog-title'), $(this).data("url"));
	    	});
	          
	    }
	    
	    /**
	     *  Handlers for the ProgramController -> myScenarioAction
	     */
	    function programMyProgramsHandlers() {
		  $('a#program-my-programs-add-remove-proposals').click(function(){
//			  console.log( $(this).data('dialog-title'));
			    modalDialog('add-remove-proposals-dialog', $(this).data('dialog-title'), $(this).attr("href"));
			    return false;
			});
          $('button#program-my-programs-add-new-program').click(function(){
        	    modalDialog('add-new-program-dialog', $(this).data("dialog-title"), $(this).data('url'), false);
        	    return false;
        	});
	    }
	    
	    /**
	     *  Handlers for the ProgramController -> AddProposalAction
	     */
	    function programAddProposalHandlers() {
		  $('#program-add-proposal-add-button').click(function(){
			  var ref = $(this);
			  $(this).prop('disabled', 'disabled').find('span').replaceWith($('<i class="fa fa-spinner fa-pulse"></i>'));
			  //console.log('Calling submit...');  
			  $.post(ref.data('url'), // the file to call
			        function(response) { // on success..
//				  		updatePages(['#proposal-my-proposals', '#proposal-all-proposals', '#measure-city-measures', '#measure-country-measures']);
				  		
			        	$('.modal-body').html(response); 
			        }
			    );
			});
          $('#program-add-proposal-create-program').click(function(){
        	  	$('.modal').modal('hide');
        	    modalDialog('add-new-program-dialog', $(this).data("dialog-title"), $(this).data('url'), false);
        	    return false;
        	});
	    }
	    
	    /**
	     *  Handlers for the ProgramController -> AddProposalAction
	     */
	    function programAddProposalSuccessHandlers() {
		  $('#program-add-proposal-success-close').click(function(){
			  var ref=$(this);
			  $("a#add-to-program[data-id='"+$(this).data('id')+"']").each(function() {
//				  console.log($(this));
				  $(this).html('<i class="fa fa-unlink"></i> '+ref.data('remove-from-my-program'));
				  $(this).attr('href', ref.data('url'));
				  $(this).attr('id', 'remove-from-program');
				  $(this).unbind('click');
				  proposalRemoveProposalFromProgramHandler($(this));
		              
			  });
			  if (typeof $('#program-all-programs').data('url') !== 'undefined') {
      			updatePages(['#program-all-programs', '#program-my-programs']);
      			}
	      		else {
//	      			console.log('update aggregated program');
	      			updatePages(['#program-view-aggregated-program']);
	      		}
			  $('.modal').modal('hide');
		  });
	    }
	    
	    /**
	     *  Handlers for the ProgramController -> viewProgramAction
	     */
	    function programViewProgramHandlers() {
	    	updateContextualHelp('#program-view-program');
	    	$('.equal .panel-body').matchHeight({
	    	    byRow: true,
	    	    property: 'min-height',
	    	    target: null,
	    	    remove: false
	    	});
	    	
	    	$('#program-view-program-report').click(function(){
		  	    modalDialog('report-dialog', $(this).data('dialog-title'), $(this).attr("href"), false);
		  	    return false;
		  	});
	    	
	    	$('#program-view-program-edit').click(function(){
		  	    modalDialog('program-edit-dialog', $(this).data('dialog-title'), $(this).data("url"), false);
		  	    return false;
		  	});
	    	
	    	$('#program-view-program-delete').click(function(){
		  	    modalDialog('program-delete-dialog', $(this).data('dialog-title'), $(this).data("url"), false);
		  	    return false;
		  	});
	    }
	    
	    /**
	     *  Handlers for the ProgramController -> viewAggregatedProgramAction
	     */
	    function programViewAggregatedProgramHandlers() {
	    	
	    	var parent = $('#program-view-aggregated-program');
//	    	console.log(parent);
            
            proposalAddProposalToProgramHandler(parent.find('a#add-to-program'));
	    	proposalRemoveProposalFromProgramHandler(parent.find("a#remove-from-program"));
	    	userMiniProfilePopover(parent.find('a#view-profile'));
            proposalAddRemoveFavoriteHandler(parent.find("a#favorite"));
	    	  
	    }
	    
	    
	    
	    /**
	     *  Handlers for the ProgramController -> addProposalsFromCityAction
	     */
	    function programAddProposalsFromCityHandlers() {
	    	
	    	userMiniProfilePopover($('a#program-add-proposals-from-city-view-profile'));
            $("button#add-remove-proposal").click(function(){
            	var ref = $(this);
            	ref.children('i').removeClass("fa-plus-circle").removeClass("fa-times-circle").addClass('fa-spinner').addClass('fa-pulse');
                $.get(ref.data("url"), {},
                    function (response) {
//                		console.log(response);
                		var button = $('<a href="'+ref.attr('href')+'" class="btn" id="add-remove-proposal"></a>');
                		if (response.added) {
                			ref.removeClass('btn-orange').addClass('btn-primary');
                			ref.find('i').removeClass('fa-spinner').removeClass('fa-pulse').addClass('fa-times-circle');
                			ref.find('span').html($('#program-add-proposals-from-city').data(ref.data('measure')?'remove-measure':'remove-proposal'));
//                			button.addClass('btn-primary').html('<i class="fa fa-times-circle"></i> Remove Proposal');
                			$('#program-add-proposals-from-city-count').html(parseInt($('#program-add-proposals-from-city-count').html())+1);
                		}
                		else {
                			ref.addClass('btn-orange').removeClass('btn-primary');
                			ref.find('i').removeClass('fa-spinner').removeClass('fa-pulse').addClass('fa-plus-circle');
                			ref.find('span').html($('#program-add-proposals-from-city').data(ref.data('measure')?'add-measure':'add-proposal'));
                			
//                			button.addClass('btn-orange').html('<i class="fa fa-plus-circle"></i> Add Proposal');
                			$('#program-add-proposals-from-city-count').html(parseInt($('#program-add-proposals-from-city-count').html())-1);
                		}
                		updatePages(['#program-get-proposals', '#program-my-programs', '#program-all-programs']);
//                		button.click(scenarioAddProposalsFromCityAddRemoveProposalHandler);
//                		ref.replaceWith(button);
                    }, 'json');
                // Prevent browsers default behavior to follow the link when clicked
                return false;
            });
            $("a#page-add-city-proposals").click(function(){
    		    $("#program-add-proposals-from-city").parent().load($(this).attr("href"));
    		    return false;
    		});
//        	$("#scenario-add-proposals-from-city-donned").click(function(){
//        		updatePages(['#scenario-get-proposals', '#scenario-my-scenarios', '#scenario-city-scenarios']);
//        		$('.modal').modal('hide');
//			});
	    }
	    
	    /**
	     *  Handlers for the ProgramController -> deleteProgramAction
	     */
	    function programDeleteProgramHandlers() {
	    	$('#program-delete-program-delete').click(function () {
				$(this).prop('disabled', 'disabled').find('i').removeClass("fa-trash").addClass('fa-spinner').addClass('fa-pulse');
				var ref= $(this);
				$.ajax({ // create an AJAX call...
			        type: 'POST', // GET or POST
			        url: $(this).data('url'), 
			        success: function(response) { // on success..
			        	//console.log(response);
			        	if (typeof response ==  'object') {
			        		window.location.href = ref.data('goto');
			        	    $('.modal').modal('hide');
			        	    //$('#add-new-proposal-dialog').remove();
			        	} 
			        	else {
			        	    // its not json
			        		$('#program-delete-program').parent().html(response); // update the DIV
			        	}
			        },
			    });
			    return false; // cancel original event to prevent form submitting
			});
	    }
	    
	    
	    
	    
	    /**
	     *  Handlers for the ScenarioController -> cityScenariosAction
	     */
	    function programAllProgramsHandlers() {
//	    	var parent = $("#program-all-programs");
	    	userMiniProfilePopover($('a#program-all-programs-view-profile'));
			// loading city proposition pages in div
			$("a#page-all-programs").click(function () {
				$("#program-all-programs").parent().load($(this).attr("href"));
				return false;
			});
	          $('#program-all-programs-add-new-program').click(function(){
	        	  modalDialog('add-new-program-dialog', $(this).data("dialog-title"), $(this).data('url'), false);
	        	});
	          $('#program-all-programs-view-my-program').click(function(){
	        	  window.location.href = $(this).data('url');
	        	});
	    }

	    /**
	     *  Handlers for the ScenarioController -> getProposalsAction
	     */
	    function programSortProposalsHandlers() {
	    	renumber_table('#sort-proposals-table');
	      $("#sort-proposals-table tbody").sortable({
	            helper: fixHelperModified,
	            stop: function(event,ui) {
	                renumber_table('#sort-proposals-table')
	            }
	        }).disableSelection();
	      $('#sort-proposals-save-button').click(function () {
	    		$('#sort-proposals-save-button').prop('disabled', 'disabled');
	    		$('#sort-proposals-save-button-icon').removeClass("fa-floppy-o").addClass('fa-spinner').addClass('fa-pulse');
	    		$('#sort-proposals-form').submit();
	    	});
	    	$('#sort-proposals-form').submit(function() { // catch the form's submit event
	    		  //console.log('Calling submit...');
	    		  $.ajax({ // create an AJAX call...
	    		        data: $(this).serialize(), // get the form data
	    		        type: $(this).attr('method'), // GET or POST
	    		        url: $(this).attr('action'), // the file to call
	    		        success: function(response) { // on success..
	    		        	updatePages(['#program-get-proposals']);
    		        		$('.modal').modal('hide');
	    		        },
	    		    });
	    		    return false; // cancel original event to prevent form submitting
	    		});
	    }

	    /**
	     *  Handlers for the UserProfileController -> changePictureAction
	     */
    	function userProfileChangePictureHandlers() {
	    	$('#layout-picture').attr('src', $('#user-profile-change-picture-picture').attr('src'));
	    	$('#user-profile-edit-picture').attr('src', $('#user-profile-change-picture-picture').attr('src'));
	    	$('#user-profile-change-picture-form').submit(function() { // catch the form's submit event
	    		var fd = new FormData($('#user-profile-change-picture-form')[0]);
	  		    $("#user-profile-change-picture-save-button > i").removeClass("fa-floppy-o").addClass('fa-spinner').addClass('fa-pulse');
	  		    $.ajax({ // create an AJAX call...
	  		        data: fd, // get the form data
	  		        type: $(this).attr('method'), // GET or POST
	  		        url: $(this).attr('action'), // the file to call
	  		        processData: false,
			        contentType: false,
	  		        success: function(response) { // on success..
	  		        	//alert(response);
	  		        	$('#user-profile-change-picture').replaceWith(response);
	  			 	  // update the DIV
	  		        },
	  		    });
	  		    return false; // cancel original event to prevent form submitting
	  		});
	    }
    	
    	/**
	     *  Handlers for the ProposalController -> deleteAction
	     */
	    function userProfileDeleteHandlers() {
			$('#user-profile-delete-yes').click(function () {
				$(this).prop('disabled', 'disabled').find('i').removeClass("fa-trash-o").addClass('fa-spinner').addClass('fa-pulse');
				$.ajax({ // create an AJAX call...
			        type: 'POST', // GET or POST
			        url: $('#user-profile-delete').data('url'),
			        success: function(response) { // on success..
			        	if (typeof response ==  'object') {
			        		 $('.modal').modal('hide');
			        		 window.location.href = response.url;
			        	}
			        	else {
			        	    // its not json
			        		$('#user-profile-delete').parent().html(response); // update the DIV
			        	}
			        },
			    });
			    return false; // cancel original event to prevent form submitting
			});
	    }
    	
    	/**
	     *  Handlers for the UserProfileController -> userSettingsAction
	     */
    	function userProfileUserSettingsHandlers() {
    		
    		$('a#user-profile-user-settings-language').click(function() {
    			$('a#user-profile-user-settings-language').parent('li').removeClass('active');
    			$('#language').val($(this).data('lang'));
    			$('#user-profile-user-settings-current-language-image').attr('src', $(this).find('img').attr('src'));
    			$('#user-profile-user-settings-current-language-image').attr('alt', $(this).find('alt').attr('src'));
    			$('#user-profile-user-settings-current-language').html($(this).find('span').html());
    			$(this).parent('li').addClass('active');
    			$('#user-profile-user-settings-language-select').removeClass('open');
    			return false;
    		});
    		
    		$('#usrOldPassword, #usrNewPassword, #usrNewPasswordConfirm').on("change propertychange click keyup input paste", function() {
    			if ($('#usrOldPassword').val()!='' || $('#usrNewPassword').val()!='' || $('#usrNewPasswordConfirm').val()!='') {
    				$('#usrOldPassword, #usrNewPassword, #usrNewPasswordConfirm').attr('required', 'required');
    			}
    			else {
    				$('#usrOldPassword, #usrNewPassword, #usrNewPasswordConfirm').removeAttr('required');
    			}
    		});
	    	
    		
    		$('#user-profile-user-settings-cancel').click(function() {
    			$(this).find('i').removeClass("fa-undo").addClass('fa-spinner').addClass('fa-pulse');
    			$('#user-profile-user-settings').parent().load($('#user-profile-user-settings').data('url'));
    			return false;
    		});
    		
    		$('#user-settings-form').submit(function() { // catch the form's submit event
    			$('#user-profile-user-settings-save').find('i').removeClass("fa-floppy-o").addClass('fa-spinner').addClass('fa-pulse');
	    		  //console.log('Calling submit...');
	    		  $.ajax({ // create an AJAX call...
	    		        data: $(this).serialize(), // get the form data
	    		        type: $(this).attr('method'), // GET or POST
	    		        url: $(this).attr('action'), // the file to call
	    		        success: function(response) { // on success..
	    		        	//alert(response);
	    		        	$('#user-profile-user-settings').parent().html(response); // update the DIV
	    		        	if ($('#user-profile-user-settings-refresh').length) {// no error  
	    		        		location.reload();
	    		        	}
	    		        },
	    		    });
	    		    return false; // cancel original event to prevent form submitting
	    		});
    		
    		$('#user-profile-user-settings-delete').click(function(){
			    modalDialog('delete-account-dialog', $('#user-profile-user-settings').data('delete-account'), $(this).attr("href"));
			    return false;
			});
	    }
    	
	    /**
	     *  Handlers for the UserProfileController -> miniProfileAction
	     */
	    function userProfileMiniProfileHandlers() {
	    	
	    	$("a#user-profile-mini-profile-add-remove-contact").click(function () {
                var ref = $(this);
                ref.find('i').removeClass("fa-plus-square-o").removeClass("fa-minus-square-o").addClass('fa-spinner').addClass('fa-pulse');
                $.get(ref.attr("href"), {},
                    function (response) {
                		if (!response.success) {
							alert('An error occured. Please retry later.');
                		}
                		else {
                    		if (response.added) {
                    			$('a#user-profile-mini-profile-add-remove-contact[data-id="'+ref.data('id')+'"]').removeClass('btn-orange').addClass('btn-primary').html('<i class="fa fa-minus-circle"></i> '+$('#user-profile-mini-profile').data('remove-contact'));
//								$('span[data-contact="'+response.contact+'"]').removeClass('gray').addClass('orange');
                    		}
                    		else {
                    			$('a#user-profile-mini-profile-add-remove-contact[data-id="'+ref.data('id')+'"]').addClass('btn-orange').removeClass('btn-primary').html('<i class="fa fa-plus-circle"></i> '+$('#user-profile-mini-profile').data('add-contact'));
//								$('span[data-contact="'+response.contact+'"]').removeClass('orange').addClass('gray');
                    		}
                    		if (typeof $('#inbox-my-contacts').data('url') !== 'undefined') {
                    			$('#inbox-my-contacts').parent().load($('#inbox-my-contacts').data('url'));
                    		}
                		}
                    }, 'json');
                // Prevent browsers default behavior to follow the link when clicked
                return false;
            });
	    	
	    	$('button#user-profile-mini-profile-new-message').click(function(){
	    		$('.popover').popover('hide');
        	    modalDialog('new-message-dialog', $(this).data('dialog-title'), $(this).data('url'), false);
        	    return false;
        	});
	     }
	    function userProfileUserPresentationHandlers(getCityURL) {
	    	//$('.element_usrDepartment').hide();
	    	//$('.element_usrCity').hide();
	    	//getCities();
	    	$("#usrPostalcode, #country").on('keyup change', function() {
	    		getCities(getCityURL);
	    	});
//	        $("#city").on('change', updateCity);
	       /* $('#cancelEditContact').on('click',function(event){
	    		  event.preventDefault();
	    		  $( "#cancelEditContactIcon" ).removeClass("fa-undo").addClass('fa-spinner').addClass('fa-pulse');
	    		  $('#contact').load('<?= $this->url('user/profile', array('action'=>'contact'));?>');
	    		  return false;
	    	});*/
	        $('#user-profile-presentation-form').submit(function() { // catch the form's submit event
	    		  //console.log('Calling submit...');
	    		  $.ajax({ // create an AJAX call...
	    		        data: $(this).serialize(), // get the form data
	    		        type: $(this).attr('method'), // GET or POST
	    		        url: $(this).attr('action'), // the file to call
	    		        success: function(response) { // on success..
	    		        	//alert(response);
	    		        	$('#user-profile-presentation').parent().html(response); // update the DIV
	    		        },
	    		    });
	    		    return false; // cancel original event to prevent form submitting
	    		});
	    }
	    /**
	     *  Handlers for the UserRegistrationController -> administrationRegistrationAction
	     */
	    function userRegistrationAdministrationRegistrationHandlers() {
//	    	$('#administration-register').data('getCities')
//	    	$('#administration-register').data('getRegions')
//	    	setLastCitySearch($('#usrPostalcode').val());
	    	getCities($('#administration-register').data('getcities'));
	    	getRegions($('#administration-register').data('getregions'));
	    	changeAdminLevel();
	    	$("#usrPostalcode").on('keyup change', function() {
	    		getCities($('#administration-register').data('getcities'));
	    	});
	    	$("#adminPostalcode").on('keyup change', function() {
	    		getCities($('#administration-register').data('getcities'), '#adminPostalcode', '#adminCity');
	    	});
	    	$("#country").on('keyup change', function() {
	    		getCities($('#administration-register').data('getcities'));
	    		getRegions($('#administration-register').data('getregions'));
	    		getCities($('#administration-register').data('getcities'), '#adminPostalcode', '#adminCity');
	    	});
//	    	$("#country").on('keyup change', getCities(getCityURL));
//	        $("#city").on('change', updateCity);
	        $("#adminLevel").change(function() {
	        	changeAdminLevel();
	        });
	        function changeAdminLevel() {
		    	var level = $('#adminLevel').val();
	        	if (level==3) {
	        		$('#administration-register-administrated-region').hide();
	        		$('#administration-register-administrated-city').hide();
	        		$('#adminRegion').removeAttr('required');
	        		$("#adminPostalcode").removeAttr('required');
	        		$('#adminCity').removeAttr('required');
	        	}
	        	else if (level==2) {
	        		$('#administration-register-administrated-region').show();
	        		$('#administration-register-administrated-city').hide();
	        		$('#adminRegion').attr('required', 'required');
	        		$("#adminPostalcode").removeAttr('required');
	        		$('#adminCity').removeAttr('required');
	        	}
	        	else {
	        		$('#administration-register-administrated-region').hide();
	        		$('#administration-register-administrated-city').show();
	        		$('#adminRegion').removeAttr('required');
	        		$("#adminPostalcode").attr('required', 'required');
	        		$('#adminCity').removeAttr('required', 'required');
	        	}
		    }
	        /*
	        $('#user-profile-partner-presentation-form').submit(function() { // catch the form's submit event
	    		$("#partner-profile-partner-presentation-save > i").removeClass("fa-floppy-o").addClass('fa-spinner').addClass('fa-pulse');
	  		    $.ajax({ // create an AJAX call...
	  		        data: $(this).serialize(), // get the form data
	  		        type: $(this).attr('method'), // GET or POST
	  		        url: $(this).attr('action'), // the file to call
	  		        success: function(response) { // on success..
	  		        	$('#partner-profile-partner-presentation').parent().html(response);
	  		        },
	  		    });
	  		    return false; // cancel original event to prevent form submitting
	  		});
	        $('#partner-profile-partner-presentation-reset').click(function() {
	        	$("#partner-profile-partner-presentation-reset > i").removeClass("fa-undo").addClass('fa-spinner').addClass('fa-pulse');
	        	$('#partner-profile-partner-presentation').parent().load($(this).attr('href'));
	        	return false;
	        });
	        */
	    }
	    /**
	     *  Handlers for the UserRegistrationController -> indexAction
	     */
	    function userRegistrationUserRegistrationHandlers(getCityURL, getRegionsURL) {
//	    	setLastCitySearch($('#usrPostalcode').val());
	    	getCities($('#user-registration').data('getcities'));
	    	$("#usrPostalcode").on('keyup change', function() {
	    		getCities($('#user-registration').data('getcities'));
	    	});
	    	$("#country").on('keyup change', function() {
	    		getCities($('#user-registration').data('getcities'));
//	    		console.log('get country');
	    	});
//	       $("#city").on('change', updateCity);
	       $('#usrLanguage').on('change', function () {
	           var url = $(this).val(); // get selected value
	           if (url) { // require a URL
	               window.location = url; // redirect
	           }
	           return false;
	       });
	    }
	    
	    /**
	     *  Handlers for the UserRegistrationController -> indexAction
	     */
	    function userRegistrationPleaseRegisterHandlers() {
	    	
	    	
	       $('#user-register-please-register-register-button').click(function () {
	           window.location = $(this).data('url'); // redirect
	           
	           return false;
	       });
	       
	       $('#user-register-please-register-login-button').click(function () {
	           window.location = $(this).data('url'); // redirect
	           
	           return false;
	       });
	       
	    }
	    /**
	     *  Handlers for the VoteController -> addAction
	     */
	    function voteAddHandlers(userVote) {
		    $('#vote').on('change', function () {
		    	var vote = $("#vote option:selected").val();
		    	if (userVote != vote) {
		    		$('#changeVoteBtn').removeAttr('disabled');
		    	} else {
//		    		console.log(vote);
		    		$('#changeVoteBtn').attr('disabled', 'disabled');
		    	}
		    });
		    $('#voteForm').submit(function() { // catch the form's submit event
		    	$("#add-change-vote-div-icon").removeClass("fa-sticky-note-o").addClass('fa-spinner').addClass('fa-pulse');
		    	$.ajax({ // create an AJAX call...
		    		data: $(this).serialize(), // get the form data
		    		type: $(this).attr('method'), // GET or POST
		    		url: $(this).attr('action'), // the file to call
		    		success: function(response) { // on success..
		    			//alert(response);
		    			$('#add-change-vote-div').parent().html(response); // update the DIV
		    		}
		    	});
		    	return false; // cancel original event to prevent form submitting
		    });
	    }
