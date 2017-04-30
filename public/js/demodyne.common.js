		// $.widget("custom.TFOiconSelectImg", $.ui.selectmenu, {
	     //        _renderItem: function (ul, item) {
	     //            var li = $("<li>", { html: item.element.html() });
	     //            var attr = item.element.attr("data-style");
	     //            if (typeof attr !== typeof undefined && attr !== false) {
	     //                $("<span>", {
	     //                    style: item.element.attr("data-style"),
	     //                    "class": "ui-icon TFOOptlstFiltreImg"
	     //                }).appendTo(li);
	     //            }
	     //            return li.appendTo(ul);
	     //        }
	     //    });
		$.fn.hasExtension = function(exts) {
		    return (new RegExp('(' + exts.join('|').replace(/\./g, '\\.') + ')$', 'i')).test($(this).val());
		}

		 function replaceDivWithUrl(div, url, fnct) {
		    	$.get(url, function(data) {
	   		     	$(div).replaceWith(data);
	   		     	if (typeof(fnct) == "function") {
		    			fnct();
		    		}
		    	})
		    	.fail(function() {
		    	  });
		    }

		 var lastCitySearch = '';
		 var lastCountryCode = 0;
		 function setLastCitySearch(postalCode) {
			 lastCitySearch = postalCode;
		 }

		 function getCities(parentDiv, postalCodeInputId, citySelectId, countrySelectId, cityLoad) {
		    	console.log('GetCities');
		    	postalCodeInputId = typeof postalCodeInputId !== 'undefined' ? postalCodeInputId : '#usrPostalcode';
		    	citySelectId = typeof citySelectId !== 'undefined' ? citySelectId : '#city';
		    	countrySelectId = typeof countrySelectId !== 'undefined' ? countrySelectId : '#country';
		    	var getCityURL = parentDiv.data('getcities');
		    	var writePostalCodeText = parentDiv.data('write-postal-code');
		    	var noCitiesText = parentDiv.data('no-cities');
		    	var code = $(postalCodeInputId).val();
          		var country = $(countrySelectId).is('input')?$(countrySelectId).val():$(countrySelectId).find(":selected").val();
		    	if (code.length<3) {
		    		 $(citySelectId).empty().append($('<option value>'+writePostalCodeText+'</option>'));
		    	}
		    	else if (lastCitySearch!=code || lastCountryCode!=country) {
		    			if (typeof cityLoad!== 'undefined') {
		    				$(cityLoad).show();
		    			}
		        		$.post(getCityURL,
			                    {'country' : country,  'postalcode' : code }, 
			                     function(cities){
			                        $(citySelectId).empty();
			            	        if (!jQuery.isEmptyObject(cities)) {
			            	        	$.each(cities, function (index) {
			            		           var option = $('<option></option>');
			            		           option.val($(this)[0].id);
			            		           option.text($(this)[0].name);
			            		           $(citySelectId).append(option);
			            		        });
			                        }
			                        else {
			                        	var option = $('<option value>'+noCitiesText+'</option>');
			            	           $(citySelectId).append(option);
			                        }
			            	        $(citySelectId).val($(citySelectId).children('option:first').val());
			            	        if (typeof cityLoad!== 'undefined') {
					    				$(cityLoad).hide();
					    			}
			              }, 'json');
		        		
		        		lastCitySearch = code;lastCountryCode = country;
		        	}
		    }

		//Helper function to keep table row from collapsing when being sorted
	        var fixHelperModified = function(e, tr) {
	            var $originals = tr.children();
	            var $helper = tr.clone();
	            $helper.children().each(function(index)
	            {
	              $(this).width($originals.eq(index).width())
	            });
	            return $helper;
	        };
	      //Renumber table rows
	    	function renumber_table(tableID) {
	    	    $(tableID + " tr").each(function() {
	    	        var count = $(this).parent().children().index($(this)) + 1;
	    	        $(this).find('.priority').html(count);
	    	    });
	    	}
		 function getRegions(getRegionsURL) {
		    	var country = $('#country').find(":selected").val();
		        		$.post(getRegionsURL,
			                    {'country' : country}, 
			                     function(regions){
		                            $('#adminRegion').empty();
		                	        if (!jQuery.isEmptyObject(regions)) {
		                	        	$.each(regions, function (index) {
		                		           var option = $('<option></option>');
		                		           option.val($(this)[0].id);
		                		           option.text($(this)[0].name);
		                		           $("#adminRegion").append(option);
		                		        });
		                            }
		                            else {
		                	           $("#adminRegion").append($('<option value>No regions for this country!</option>'));
		                            }
		                	        $("#adminRegion").val($("#adminRegion option:first").val());
			              }, 'json');
		    }
		    function checkURLInput(abc){
		        var string = abc.value;
		        if(!(/^https?:\/\//.test(string))){
		            string = "http://" + string;
		        }
		        abc.value=string;
		    }
	    /**
	     * Function to show an image from fileInput input to the imgOutput img before uploading the file
	     */
	    function imageUploadPreview(fileInput, imgOutput) {
	    	var files = !!fileInput.files ? fileInput.files : [];
	        if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
	        if (/^image/.test( files[0].type)){ // only image file
	            var reader = new FileReader(); // instance of the FileReader
	            reader.readAsDataURL(files[0]); // read the local file
	            reader.onloadend = function(){ // set image data as background of div
	            	imgOutput.attr('src', this.result);
	            }
	        }
	    }
	    /**
	     * Create a modal dialog and load the content from the href link
	     * 
	     * @param id The id of the modal div
	     * @param title The title to show on header
	     * @param href The link to load
	     * @param large True if modal is large 
	     */
	    function modalDialog(id, title, href, large, close, callEnd) {
	    	$('.modal').remove();
            $('.modal-backdrop').remove();
	    	var largeTest = typeof large !== 'undefined' ? (large==true?'modal-lg':large) : 'modal-lg';
	    	var closeButton = typeof close !== 'undefined' ? close : true;
	    	
	    	var modal = $('<div class="modal fade" id="'+id+'" role="dialog">')
		        .html('<div class="modal-dialog '+largeTest+'">'+
		        '<div class="modal-content">'+
		          '<div class="modal-header round5top">'+
		          (closeButton?'<button type="button" class="close" data-dismiss="modal"><span style="color:#000"><i class="fa fa-times-circle"></i></span></button>':'')+
		            '<span class="big"><img src="/img/d30.png" style="vertical-align:middle" />&nbsp;&nbsp;<strong id="modal-dialog-title">'+title+'</strong></span>'+
		          '</div>'+
		          '<div class="modal-body">'+
                    'Loading. Please wait... <i class="fa fa-spinner fa-pulse "></i> '+
		          '</div>'+
		        '</div>'+
		      '</div>'+
		      '<script type="text/javascript">$(function () {$("#'+id+'").on("hidden.bs.modal",function(e){$(this).remove();});'+
		        '});	</script>')
		        .appendTo('body');
	    	if (href !== undefined) {
	    		$('.modal-body').load(href, function() {
	    			if (typeof callEnd === 'function') {
	    				callEnd();
					}
				});
	    	}
	    	modal.modal({
	            backdrop: 'static',
	            keyboard: false,
	            show: true,
	        });
            modal.on('hidden.bs.modal', function () {
                $(this).data('bs.modal', null);
            });
	    	 $('.modal-dialog').draggable({
	             handle: ".modal-header"
	         });
	    }
	    function newsletterAddNewsletterUpdateSendTo() {
	    	var contacts = $('#newsletter-add-newsletter-contacts').prop('checked')?1:0;
	    	var citizen = $('#newsletter-add-newsletter-citizens').prop('checked')?1:0;
	    	var champions =  $('#newsletter-add-newsletter-champions').prop('checked')?1:0;
	    	var partners =  $('#newsletter-add-newsletter-partners').prop('checked')?1:0;
	    	$('#nlSendTo').attr('value', contacts<<3|citizen<<2|champions<<1|partners);
	    }
	    function nl2br (str, is_xhtml) {   
	        var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';    
	        return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
	    }
	    /**
	     * Creates and shows the mini profile from pop to div_id
	     */
	    function profileDetailsInPopup(pop, divId){
	    	if (!$('div#mini-profile[data-id="'+divId+'"]').length) {
	    		$('<div id="mini-profile" data-id="'+divId+'" style="display:none">')
			        .html('Loading...')
			        .appendTo('#pageContent'); 
	    		$.ajax({
		            url: pop.attr('href'),
		            success: function(response){
		                $('div#mini-profile[data-id="'+divId+'"]').html(response);
		                pop.popover("show");
		            }
		        });
	    	}
	    	return $('div#mini-profile[data-id="'+divId+'"]').html();
	    }
	    function updateInboxLists(type) {
	    	if (type=='received' || type=='all') {
	    		if (typeof $('#inbox-list-received').data('url') !== 'undefined') {
	   		     	$.get($('#inbox-list-received').data('url'), function(html) {
	   		     		var display = $('#inbox-list-received').css('display');
	   		     		$('#inbox-list-received').replaceWith(html);
	   		     		$('#inbox-list-received').css('display', display);
		    		});	
	    		}
		     	}
	     	if (type=='sent' || type=='all') {
	     		if (typeof $('#inbox-list-sent').data('url') !== 'undefined') {
	    	     	$.get($('#inbox-list-sent').data('url'), function(html) {
	    	    		var display = $('#inbox-list-sent').css('display');
	    	     		$('#inbox-list-sent').replaceWith(html);
	    	     		$('#inbox-list-sent').css('display', display);
	    			});	
	     		}
	     	}
	     	if (type=='trash' || type=='all') {
	     		if (typeof $('#inbox-list-trash').data('url') !== 'undefined') {
		     		$.get($('#inbox-list-trash').data('url'), function(html) {
			    		var display = $('#inbox-list-trash').css('display');
			     		$('#inbox-list-trash').replaceWith(html);
			     		$('#inbox-list-trash').css('display', display);
		    		});	
	     		}
	     	}
	    }
	    function updatePages(ids) {
	    	ids.forEach(function(item, index) {
	    		if ($(item).length) {
	    			if (typeof $(item).data('url') !== 'undefined') {
	        	    	$(item).parent().load($(item).data('url'));
	        		}	
	    		}
	    	});
	    }
	    function userMiniProfilePopover(div/*, blockId*/) {
	    	div.popover({ 
            	trigger: "manual" , 
            	html: true, 
            	placement: 'auto',
            	container: 'body',
            	animation:false, 
            	content: function(){
                    return profileDetailsInPopup($(this), $(this).data('id'));
            	}
            })
    		.on("click", function () {
        	    var _this = this;
        	    $('.popover').popover('hide');
        	    $(this).popover("show");
        	    return false;
        	})
        	;
	    }
	function moveSelect2Select(from, to) {
	    	if (from==='undefined') return;
	    	from.each(function() {
	    		var optgroup = $(this).parent();
		        var optgroupLabel = optgroup.attr('label');
		        if(to.children('optgroup[label="'+optgroupLabel+'"]').html() == null){
		        	to.append('<optgroup label="'+optgroupLabel+'"  style="padding: 5px 0 10px 0;border-top: #dedede 1px solid;" ></optgroup>');
		        	to.children('optgroup[label="'+optgroupLabel+'"]').append($(this));
		         } else {
		        	 to.children('optgroup[label="'+optgroupLabel+'"]').append($(this));
		         }
		        if(optgroup.children().length == 0) {
		        	optgroup.remove();
		        }
	    	});
	    }
	    function removeEmptyGroups() {
	    	$("select").each(function() {$
	    		$(this).find("optgroup").each(function(){
	    			if($(this).children().length == 0) {
	    				$(this).remove();
	    	        }
	    		});
	    	});
	    }
	    function orderOptgroups() {
	        $("select").each(function() {
	            var $select = $(this);
	            var $groups = $select.find("optgroup");
	            $groups.remove();
	            $groups = $groups.sort(function(g1, g2) {
	                return g1.label.localeCompare(g2.label);      
	            });      
	            $select.append($groups);
	            $groups.each(function() {
	                var $group = $(this);
	                var options = $group.find("option");
	                options.remove();
	                options = options.sort(function(a, b) {
	                    return a.innerHTML.localeCompare(b.innerHTML);
	                });
	                $group.append(options);
	            });
	        });
	    }
	    function sortSelect() {
	        $('select').each(function(){
	    		var 
	    			select=$(this),
	    			optgroup=select.children().toArray().sort(function(a,b){
	    				return $(a).text()<$(b).text() ? 1 : -1
	    			})
	    		$.each(optgroup,function(i,v){
	    			select.prepend(v)
	    		})
	    	})
	    }
	/**
	 * Don't forget to add get 
	 * 
	 * @param main_category
	 * @returns {Boolean}
	 */
	function updateSubCategories(main_category, getSubCategoriesUrl) {
		$('#add-proposal-category-image').html('<i class="fa fa-spinner fa-pulse fa-2x"></i>');
		$.post(getSubCategoriesUrl, 
			{
				'main_category': main_category
			},
			function (itemJson) {
				$("#sub_category option").remove();
				if (typeof itemJson.name != 'undefined') { // check if category has subcategories
					for (var i = 0; i < itemJson.name.length; i++) {
						$("#sub_category").append('<option value="' + itemJson.id[i] + '">' + itemJson.name[i] + '</option>');
					}
				}
				var imageFile = $("#main_category").find('option:selected').css('background-image');
				imageFile = imageFile.substring(4, imageFile.length-1);
				$('#add-proposal-category-image').html('<img width="32px" height="32px" src='+imageFile+'>');
			}, 'json');
		return false;
	}