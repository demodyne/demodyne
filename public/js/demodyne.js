// yc demodyne.js -o demodyne.min.js
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

    $('body').on('touchstart.dropdown', '.dropdown-menu', function (e) { e.stopPropagation(); });

    var myTabs = [ "#inbox", "#mycontacts", "#favorites", "#myprop2", "#myscn", "#my-sessions", "#impl", "#hours", "#fundings", "#draftmeasures", "#banner", "#newsletter", "#events" ];
    if (location.hash !== '') {
        $('a[href="' + location.hash + '"]').tab('show');
        if ($.inArray(location.hash, myTabs) > -1) {
            $('[href="#myspace"]').tab('show');
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
            var tabs = '.'+$(this).data('tabs');
            href = $(tabs).find('.active > a').attr('href');
            history.pushState(null, null, href);
        }
    });
    // navigate to a tab when the history changes
    window.addEventListener("popstate", function(e) {
        var activeTab;
        if (location.hash !== '') {
            activeTab = $('[href="' + location.hash + '"]');
            if (activeTab.length) {
                activeTab.tab('show');
            }
            if ($.inArray(location.hash, myTabs) > -1) {
                $('[href="#myspace"]').tab('show');
            }
        }
        else {
            $('.nav-tabsj a:first').tab('show');
        }
    });
});
/**
 * Layout management & history management
 */

function userLayoutHandlers() {
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
}


$(function(){

    $('a#homepage-user-registration').click(function() {
        modalDialog('user-registration', $(this).data("dialog-title"), $(this).attr("href"), false);
        return false;
    });


    $('#layout-browse').click(function() {
        modalDialog('browse-dialog', $(this).data("dialog-title"), $(this).attr("href"), false);
        return false;
    });

    $('body').on('click', function (e) {
        if (!$(e.target).parents('.popover').length) {
            $('.popover').popover('hide');
        }
    });

    $('a#layout-advanced-search').click(function() {
        modalDialog('search-advanced-dialog', $(this).data('dialog-title'), $(this).attr("href"), true);
        return false;
    });

    $('#layout-search-form').submit(function () {
        var keywords =$.trim($('#layout-search-keywords').val());

        if (keywords=="") {
            return false;
        }
    });


    $('button#history-back').click(function() {
        window.history.back();
        window.location.href = '/';

    });

    $('button#history-home').click(function() {
        window.location.href = $(this).attr('href');
    });

    $('#layout-goto-inbox').on('click', function() {
        if ($('#inbox').length) {
            $('[href="#myspace"]').tab('show');
            $('[href="#inbox"]').tab('show');
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


    $('a#vote-popover').click(function() {
        $(this).popover('show');

        return false;
    });

});
/****************************************************************
 *															  *
 * 			 Proposal execution: partner find  				  *
 * 							BEGIN						      *
 * 															  *
 ****************************************************************/
function partnerFindHandlers() {
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
    $('#findPartnersForm').submit(function() {
        // verify if at least a category or a keyword is given
        if ($('#partnerCategoryList span').length==0 && $('#partnerKeywordList span').length==0) {
            $.msg('You must choose at least a category or write a keyword.', 'error');
            return false;
        }
        $("#findPartnersIcon").removeClass("fa-search").addClass('fa-spinner').addClass('fa-pulse');
        $.ajax({
            data: $(this).serialize(),
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            success: function(response) {
                $('#findPartnersDiv').html(response);
            },
            complete: function() {
                $("#findPartnersIcon").removeClass("fa-spinner").removeClass('fa-pulse').addClass('fa-search');
            }
        });
        return false;
    });
    $( "#mainPartnerCategories" )
        .iconselectmenu()
        .iconselectmenu( "menuWidget")
        .addClass( "ui-menu-icons avatar" );
    $( "#subPartnerCategories" )
        .selectmenu();
    $("#mainPartnerCategories").iconselectmenu({
        select: function (event, ui) {
            updatePartnerSubCategories($(this).val());
        }
    });
    function updatePartnerSubCategories(main_category) {
        $.post('/category/get-subcategories', // this is the actual link, update if necessary
            { 'main_category' : main_category },
            function(itemJson){

                $('#subPartnerCategories').find('option').remove().end();
                $('#subPartnerCategories').selectmenu('destroy').selectmenu({ style: 'dropdown' });
                if (typeof itemJson.name != 'undefined') { // check if category has subcategories
                    $("#subPartnerCategories").append('<option value="'+(-main_category)+'">All</option>');
                    for(var i=0;i<itemJson.name.length;i++) {
                        $("#subPartnerCategories").append('<option value="'+itemJson.id[i]+'">'+itemJson.name[i]+'</option>');
                    }
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
        var ref = $(this);
        var user = $(this).attr('user');
        var proposal = $(this).attr('proposal');
        var url = '/city/proposal/implementation/add-remove-user/'+proposal+'/user/'+user;
        $.ajax({
            type: 'POST',
            url: url,
            complete: function() {
                if (ref.is(":checked")) {
                    $.msg(ref.parent().parent().find('#partnerName').text()+' has been added to Partners list.', 'success');
                }
                else {
                    $.msg(ref.parent().parent().find('#partnerName').text()+' has been removed from Partners list.', 'success');
                }
                $('#partnerListSection').load('/city/proposal/implementation/get-partners/'+proposal);
            }
        });
    });
}
/****************************************************************
 *															  *
 *							END							  	  *
 * 			 Proposal execution: partner find  				  *
 * 															  *
 ****************************************************************/
/****************************************************************** ********************************************************************************/
/****************************************************************** ********************************************************************************/
/*************           **    **  ********                                                                                         ***************************/
/*************           **    **  **    **                                                                                     ***************************/
/*************           **    **  **    **                                                                                         ***************************/
/*************           ********  ********                                                                                         ***************************/
/*************           ********  ********                                                                                             ***************************/
/*************           **    **  **    **                                                                                         ***************************/
/*************           **    **  **    **                                                                                     ***************************/
/*************           **    **  **    **                                                                                     ***************************/
/****************************************************************** ********************************************************************************/
/****************************************************************** ********************************************************************************/

function adminSessionsHandlers() {


    $("a#page-all-sessions").click(function(){
        $("#session-all-sessions").parent().load($(this).attr("href"));
        return false;
    });
    $('a#session-all-sessions-attend').click(function () {
        var ref = $(this);
        ref.find('i').removeClass("fa-check-circle-o").removeClass("fa-times-circle-o").addClass('fa-spinner').addClass('fa-pulse');
        $.post(ref.attr("href"), {},
            function (itemJson) {
                if (itemJson.success) {
                    var cnt = ref.parent().find('#session-all-sessions-attendees-count');
                    var attendeesCount = parseInt(cnt.text())
                    if (itemJson.success == 1) {
                        ref.html('<i class="fa fa-check-circle-o size15em" title="'+ $("#session-all-sessions").data(ref.data('session')?'attend-session':'attend-session')+'"></i>');
                        cnt.text(attendeesCount-1);
                    }
                    else {
                        ref.html('<i class="fa fa-times-circle-o size15em" title="'+$("#session-all-sessions").data('decline')+'"></i>');
                        cnt.text(attendeesCount+1);
                    }
                }
            }, 'json');
        return false;
    });
}

function adminUsersHandlers() {
    /**
     * Pagination for user contacts
     */
    $("a#page-contact").click(function(){
        $('#inbox-my-contacts').parent().load($(this).attr("href"));
        return false;
    });

    $('a#inbox-my-contacts-send-message').click(function() {
        modalDialog('new-message-dialog', $('#inbox-my-contacts').data('new-message'), $(this).attr("href"), true);
        return false; 
    });

    $('#admin-users-new-message-selected').click(function() {
        if ($('#admin-users input:checked').length==0) {
            return false;
        }
        var to = '';
        $('#admin-users input:checked').each(function() {
            to = to + $(this).val() + '%2C%20';
        });

        modalDialog('new-message-dialog', $('#admin-users').data('new-message'), $(this).data("href") + '/to/' + to, true);
        return false; 
    });

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
                        $('a#user-profile-mini-profile-add-remove-contact[data-id="'+ref.data('id')+'"]').html('<i class="fa fa-minus-circle"></i> '+$('#admin-users').data('remove-contact'));
                    }
                    else {
                        $('a#user-profile-mini-profile-add-remove-contact[data-id="'+ref.data('id')+'"]').html('<i class="fa fa-plus-circle"></i> '+$('#admin-users').data('add-contact'));
                    }
                }
            }, 'json');
        
        return false;
    });
}

function adminDigestHandlers() {
    /**
     * Pagination for user contacts
     */
    $("a#page-contact").click(function(){
        $('#inbox-my-contacts').parent().load($(this).attr("href"));
        return false;
    });

    $('a#inbox-my-contacts-send-message').click(function() {
        modalDialog('new-message-dialog', $('#inbox-my-contacts').data('new-message'), $(this).attr("href"), true);
        return false; 
    });

    $('#admin-users-new-message-selected').click(function() {
        if ($('#admin-users input:checked').length==0) {
            return false;
        }
        var to = '';
        $('#admin-users input:checked').each(function() {
            to = to + $(this).val() + '%2C%20';
        });

        modalDialog('new-message-dialog', $('#admin-users').data('new-message'), $(this).data("href") + '/to/' + to, true);
        return false; 
    });

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
                        $('a#user-profile-mini-profile-add-remove-contact[data-id="'+ref.data('id')+'"]').html('<i class="fa fa-minus-circle"></i> '+$('#admin-users').data('remove-contact'));
                    }
                    else {
                        $('a#user-profile-mini-profile-add-remove-contact[data-id="'+ref.data('id')+'"]').html('<i class="fa fa-plus-circle"></i> '+$('#admin-users').data('add-contact'));
                    }
                }
            }, 'json');
        
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
    $('#banner-add-banner-form').submit(function() { 

        var fd = new FormData($('#banner-add-banner-form')[0]);
        $.ajax({ 
            data: fd, 
            type: $(this).attr('method'), 
            url: $(this).attr('action'), 
            processData: false,
            contentType: false,
            success: function(response) { 
                if (typeof response ==  'object') {
                    updatePages(['#banner-active-banners', '#banner-inactive-banners', '#banner-carousel-banners']);
                    $('.modal').modal('hide');
                }
                else {
                    $('#banner-add-banner').parent().html(response); 
                }
            },
        });
        return false; 
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

    $('#banner-active-banners-sort-form').submit(function() { 
        
        $.ajax({ 
            data: $(this).serialize(), 
            type: $(this).attr('method'), 
            url: $(this).attr('action'), 
            success: function(response) { 

                if (typeof response ==  'object') {
                    updatePages(['#banner-carousel-banners']);

                }
            },
        });
        return false; 
    });
}



function bannerPublishBannerHandlers() {

    $('#publish-banner-yes-button').click(function () {
        $(this).prop('disabled', 'disabled');
        $(this).find('i').removeClass("fa-floppy-o").addClass('fa-spinner').addClass('fa-pulse');
        $.ajax({ 
            type: 'POST', 
            url: $('#banner-publish-banner').data('url'),
            success: function(response) { 
                if (typeof response ==  'object') {
                    updatePages(['#banner-active-banners', '#banner-inactive-banners', '#banner-carousel-banners']);
                    $('.modal').modal('hide');
                }
                else {
                    $('#banner-publish-banner').parent().html(response); 
                }
            },
        });
        return false; 
    });

}
/**
 *  Handlers for the BannerController -> deleteBannerAction
 */
function bannerDeleteBannerHandlers() {
    $('#banner-delete-banner-yes').click(function () {
        $(this).prop('disabled', 'disabled');
        $(this).find('i').removeClass("fa-trash").addClass('fa-spinner').addClass('fa-pulse');
        $.ajax({ 
            type: 'POST', 
            url: $(this).data('href'),
            success: function(response) { 
                if (typeof response ==  'object') {
                    $('.modal').modal('hide');
                    updatePages(['#banner-active-banners', '#banner-inactive-banners', '#banner-carousel-banners']);
                }
                else {
                    $('#banner-delete-banner').parent().html(response); 
                }
            },
        });
        return false; 
    });
}

function blogAddArticleHandlers() {
    textboxFormatHandlers($('#edit-description'));

    $('#picture-file').on("change", function() {
        imageUploadPreview(this, $("#blog-view-article-preview"));
    });

    function articlePreview() {
        $('#blog-view-article-articleTitle').html($('#articleTitle').val().length?$('#articleTitle').val():"Title");
        $('#blog-view-article-articleDescription').html($('#blog-add-article-textbox').html().length?$('#blog-add-article-textbox').html():"Article content...");
        $('#blog-view-article-articleCategory').html($('#articleCategory').find(":selected").text());
    };

    if ($('#newsletter-add-newsletter').find('.text-danger').length) {
        articlePreview();
    }

    $('#articleTitle, #blog-add-article-textbox, #articleCategory').on("change propertychange click keyup input paste", articlePreview);


    $('#blog-add-article-publish-button').click(function () {
        $(this).prop('disabled', 'disabled');
        $(this).find('i').removeClass("fa-share-square-o").addClass('fa-spinner').addClass('fa-pulse');
        var action = $('#blog-add-edit-article-form').attr('action');
        if (action.indexOf("publish") < 0) {
            $('#blog-add-edit-article-form').attr('action', action+'/publish/true');
        }
        $('#blog-add-edit-article-form').submit();
    });

    $('#blog-add-edit-article-form').submit(function() { 

        $('#articleDescription').html($('#blog-add-article-textbox').html());
        $('#newsletter-add-newsletter-save-button > i').removeClass("fa-floppy-o").addClass('fa-spinner').addClass('fa-pulse');
        
    });

}

function blogListHandlers() {

    $('a#page-list').click(function(){
        $('#blog-list-published').parent().load($(this).attr("href"));
        return false;
    });


    $('a#articles-tag').click(function(){
        $('#blog-list-published').parent().load($(this).attr("href"));
        $('#blog-list-draft').parent().load($(this).attr("href").replace('list', 'list-draft'));

        return false;
    });

}

function blogViewArticleHandlers() {

    var parent = $('#proposal-view');

    userMiniProfilePopover($('a#blog-view-article-profile'));


    // $('#blog-view-article-edit').click(function(){
    //     modalDialog('blog-view-article-edit-dialog', $(this).data('dialog-title'), $(this).data("url"), true);
    //     return false;
    // });
}

function blogListDraftHandlers() {

    userMiniProfilePopover($('a#comment-list-profile'));

    $('a#page-list-draft').click(function(){
        $('#blog-list-draft').parent().load($(this).attr("href"));
        return false;
    });

    $('#article-list-see-published').click(function() {
        $(this).removeClass('btn-default').addClass('btn-success');
        $('#article-list-see-draft').removeClass('btn-success').addClass('btn-default');
        $('#blog-list-published').parent().show();
        $('#blog-list-draft').parent().hide();
    });

    $('#article-list-see-draft').click(function() {
        $(this).removeClass('btn-default').addClass('btn-success');
        $('#article-list-see-published').removeClass('btn-success').addClass('btn-default');
        $('#blog-list-draft').parent().show();
        $('#blog-list-published').parent().hide();
    });

}

function browseDialogHandlers() {
    $("#browse-search, #browse-region").on('keyup change paste', function() {
        $('#browse-level-form').submit();
    });

    $("#browse-country").on('keyup change paste', function() {
        var country = $(this).find(":selected").val();
        $.post($('#index-browse-dialog').data('get-regions'),
            {'country' : country},
            function(regions){
                $('#browse-region').empty();
                if (!jQuery.isEmptyObject(regions)) {
                    $.each(regions, function (index) {
                        var option = $('<option></option>');
                        option.val($(this)[0].id);
                        option.text($(this)[0].name);
                        $("#browse-region").append(option);
                    });
                }
                else {
                    $("#browse-region").append($('<option value>No regions for this country!</option>'));
                }
                $("#browse-region").val($("#browse-region option:first").val());
                $('#browse-level-form').submit();
            }, 'json');

    });

    $('#view-region-level').click(function() {
        window.location = $(this).data('url')
            .replace('$region$', $('#browse-region').find('option:selected').text())
            .replace('$country$', $('#browse-country').find('option:selected').text());
    });

    $('#view-country-level').click(function() {
        window.location = $(this).data('url')
            .replace('$country$', $('#browse-country').find('option:selected').text());
    });

    $('#browse-level-form').submit(function() { 
        if ($('#browse-search').val().length >= 2) {
            $('#browse-cityLoad').show();
            
            $.ajax({ 
                data: $(this).serialize(), 
                type: $(this).attr('method'), 
                url: $(this).attr('action'), 
                success: function(response) { 
                    $('#browse-dialog-city-list').html(response);
                    $('#browse-cityLoad').hide();
                },
            });
        }
        return false; 
    });
}



function contentEditableTextLimit(content, count, buttons) {
    var maxCharacters = parseInt(content.attr('maxlength'));
    var characters = content.text().length;

    if (characters > (maxCharacters)) {
        count.html(content.attr('over-text'));
        count.find('span').addClass('label label-danger');
        buttons.forEach(function(item, index) {
            if (item.length) {
                item.prop('disabled', true);
            }
        });
        count.find('span').text(-(maxCharacters - characters));
    } else {
        count.html(content.attr('below-text'));
        count.find('span').removeClass('label label-danger');
        buttons.forEach(function(item, index) {
            if (item.length) {
                item.prop('disabled', false);
            }
        });
        count.find('span').text(maxCharacters - characters);
    }


}

function commentAddCommentHandlers() {

    contentEditableTextLimit($('#comment-add-comment-textbox'), $('#comment-add-comment-textbox-count'), [$('#comment-add-comment-submit')]);
    textboxFormatHandlers($('#edit-description-comment'));

    $('#comment-add-comment-textbox').on('change propertychange click keyup input paste focus', function (event) {
        if ($('#comment-add-comment-textbox').text() == '' ) {
            $('#comment-add-comment-submit').prop('disabled', true);
        } else {
            $('#comment-add-comment-submit').prop('disabled', false);
        }

        contentEditableTextLimit($('#comment-add-comment-textbox'), $('#comment-add-comment-textbox-count'), [$('#comment-add-comment-submit')]);

    });

    $('#comment-form').submit(function() { 
        $( "#addCommentIcon" ).removeClass("fa-comment-o").addClass('fa-spinner').addClass('fa-pulse');
        $('#comText').html($('#comment-add-comment-textbox').html());
        $.ajax({ 
            data: $(this).serialize(), 
            type: $(this).attr('method'), 
            url: $(this).attr('action'), 
            success: function(response) { 
                $('#comment-add-comment').html(response); 
            }
        });
        return false; 
    });

}

function commentListHandlers() {

    userMiniProfilePopover($('a#comment-list-profile'));

    
    $('a#page-com').click(function(){
        $('#comment-list').load($(this).attr("href"));
        return false;
    });
    
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
        
        return false;
    });

    $('a#comment-list-report').click(function(){
        modalDialog('report-dialog', $('#comment-list').data('report-comment'), $(this).attr("href"), false);
        return false;
    });

    $('a#comment-list-reply').click(function(){
        var ref = $(this).parents('#comment-item');
        var mes = $('#comment-list').data('reply-text');
        mes = mes.replace('%date%', ref.find('#comment-list-datetime').html())
        mes = mes.replace('%user%', ref.find('#comment-list-username').html())
        mes = mes.replace('%text%', ref.find('#comment-list-text').html());

        $('#comment-add-comment-textbox').html(mes).trigger('focus');
        $("#comment-add-comment")[0].scrollIntoView({
            behavior: "smooth", // or "auto" or "instant"
            block: "start" // or "end"
        });
        return false;
    });

}

function commentListNoActionsHandlers() {
    $('a#page-com').click(function(){
        $('#comment-list-no-actions').load($(this).attr("href"));
        return false;
    });
}

function eventAddEventHandlers() {

    contentEditableTextLimit($('#event-add-event-textbox'), $('#event-add-event-textbox-count'), [$('#event-add-event-save-button'), $('#event-add-event-publish-button')]);
    textboxFormatHandlers($('#edit-description'));

    $('#event-add-event-textbox').on('change propertychange click keyup input paste focus', function (event) {
        contentEditableTextLimit($('#event-add-event-textbox'), $('#event-add-event-textbox-count'), [$('#event-add-event-save-button'), $('#event-add-event-publish-button')]);
    });


    $('#eventStartDateGroup').datetimepicker({
        format: 'DD/MM/YYYY HH:mm',
    });
    $('#eventEndDateGroup').datetimepicker({
        useCurrent: false, 
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
    $('#event-add-event-form').submit(function() { 
        $('#eventDescription').html($('#event-add-event-textbox').html());
        var fd = new FormData($('#event-add-event-form')[0]);
        $.ajax({
            data: fd, 
            type: $(this).attr('method'), 
            url: $(this).attr('action'), 
            processData: false,
            contentType: false,
            success: function(response) { 
                if (typeof response ==  'object') {
                    updatePages(['#event-my-events', '#event-all-events', '#event-upcoming-events']);
                    $('.modal').modal('hide');
                }
                else {
                    $('#event-add-event').parent().html(response);
                }
            },
        });
        return false; 
    });
}

/**
 *  Handlers for the BannerController -> deleteBannerAction
 */
function eventDeleteEventHandlers() {
    $('#event-delete-event-yes').click(function () {
        $(this).prop('disabled', 'disabled');
        $(this).find('i').removeClass("fa-trash").addClass('fa-spinner').addClass('fa-pulse');
        $.ajax({ 
            type: 'POST', 
            url: $(this).data('href'),
            success: function(response) { 
                if (typeof response ==  'object') {
                    updatePages(['#event-my-events', '#event-all-events', '#event-upcoming-events']);
                    $('.modal').modal('hide');
                }
                else {
                    $('#event-delete-event').parent().html(response);
                }
            },
        });
        return false; 
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
        var date= e.date;
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

    $('#event-my-events-search-form').submit(function() { 
        $(this).find('i').removeClass("fa-search").addClass('fa-spinner').addClass('fa-pulse');
        $.ajax({ 
            data: $(this).serialize(), 
            type: $(this).attr('method'), 
            url: $(this).attr('action'), 
            success: function(html) { 
                $('#event-my-events').replaceWith(html);
            },
        });
        return false; 
    });


    $('a#event-my-events-edit').click(function(){
        modalDialog('edit-event-dialog', $('#event-my-events').data('edit-event'), $(this).attr("href"), true);
        return false;
    });
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
function loadText(div) {
    var loadingText = $('div[data-loading-text]').length?$('div[data-loading-text]').data('loading-text'):'';
    div.html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i> '+loadingText);
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
        loadText($('#event-all-events-results'));
        var date= e.date;
        $('#event-all-events').parent().load($('#event-all-events-see-all').data('url')+'/date/'+(date.getMonth()+1)+'/'+date.getFullYear());
    });

    $('#event-all-events-add-new-event').click(function(){
        modalDialog('new-event-dialog', $('#event-all-events').data('add-event'), $(this).data("href"), true);
        return false;
    });

    $('#event-all-events-see-all').click(function(){
        loadText($('#event-all-events-results'));
        $('#event-all-events').parent().load($(this).data('url'));
        return false;
    });

    $('#event-all-events-see-month').click(function(){
        loadText($('#event-all-events-results'));
        $('#event-all-events').parent().load($(this).data('url'));
        return false;
    });

    $('#event-all-events-add-new-session').click(function(){
        modalDialog('new-session-dialog', $('#session-my-sessions').data('add-session'), $(this).data("href"), true);
        return false;
    });

    $('#event-all-events-search-form').submit(function() { 
        loadText($('#event-all-events-results'));
        $(this).find('i').removeClass("fa-search").addClass('fa-spinner').addClass('fa-pulse');
        $.ajax({ 
            data: $(this).serialize(), 
            type: $(this).attr('method'), 
            url: $(this).attr('action'), 
            success: function(html) { 
                $('#event-all-events').replaceWith(html);
            },
        });
        return false; 
    });

    $('a#event-all-events-edit').click(function(){
        modalDialog('edit-event-dialog', $(this).data('dialog-title'), $(this).attr("href"), true);
        return false;
    });
    $('a#event-all-events-duplicate').click(function(){
        modalDialog('duplicate-event-dialog', $(this).data('dialog-title'), $(this).attr("href"), true);
        return false;
    });
    $('a#event-all-events-cancel').click(function(){
        modalDialog('cancel-event-dialog', $(this).data('dialog-title'), $(this).attr("href"), true);
        return false;
    });
    $("a#page-all-events").click(function(){
        $("#event-all-events").parent().load($(this).attr("href"));
        loadText($('#event-all-events-results'));
        return false;
    });
    $('a#event-all-events-attend').click(function () {
        var ref = $(this);
        ref.find('i').removeClass("fa-check-circle-o").removeClass("fa-times-circle-o").addClass('fa-spinner').addClass('fa-pulse');
        $.post(ref.attr("href"), {},
            function (itemJson) {
                if (itemJson.success) {
                    var cnt = ref.parent().find('#event-all-events-attendees-count');
                    var attendeesCount = parseInt(cnt.text())
                    if (itemJson.success == 1) {
                        ref.html('<i class="fa fa-check-circle-o size15em" title="'+ $("#event-all-events").data(ref.data('event')?'attend-event':'attend-session')+'"></i>');
                        cnt.text(attendeesCount-1);
                    }
                    else {
                        ref.html('<i class="fa fa-times-circle-o size15em" title="'+$("#event-all-events").data('decline')+'"></i>');
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
        $.ajax({ 
            type: 'POST', 
            url: $('#event-publish-event').data('url'),
            success: function(response) { 

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
                }
                else {
                    
                    $('#event-publish-event').parent().html(response); 
                }
            },
        });
        return false; 
    });
}
function eventCancelEventHandlers() {
    $('#cancel-event-yes-button').click(function () {
        $(this).prop('disabled', 'disabled');
        $(this).find('i').removeClass("fa-floppy-o").addClass('fa-spinner').addClass('fa-pulse');
        $.ajax({ 
            type: 'POST', 
            url: $('#event-cancel-event').data('url'),
            success: function(response) { 
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
                }
                else {
                    $('#event-publish-event').parent().html(response);
                }
            },
        });
        return false; 
    });
}
function eventUpcomingEventsHandlers() {
    $('#event-upcoming-events-see-all').click(function(){
        $('[href="#all-events"]').tab('show');
        return false;
    });
}
function eventViewEventHandlers() {
//	    	$('#event-view-event-see-attendees').click(function(){
//		  	    modalDialog('view-attendees-dialog', 'View Event Attendees', $(this).data("href"), true);
//		  	    return false;
//		  	});
//     updateContextualHelp('#event-view-event');
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
        ref.find('i').removeClass("fa-check-circle-o").removeClass("fa-times-circle-o").addClass('fa-spinner').addClass('fa-pulse');
        $.post(ref.data("url"), {},
            function (itemJson) {
                if (itemJson.success) {
                    updatePages(['#event-view-attendees']);
                }
            }, 'json');
        return false;
    });
}


function eventViewInvitationsHandlers() {
    userMiniProfilePopover($('a#event-view-invitations-view-profile'));

    $('#event-view-invitations-invite').click(function(){
        modalDialog('invite-attendees-dialog', $(this).data('dialog-title'), $(this).data("url"), false);
        return false;
    });

    $("a#page-view-invitations").click(function(){
        $("#event-view-invitations").parent().load($(this).attr("href"));
        return false;
    });

    $('#event-view-attendees-attend').click(function () {
        var ref = $(this);
        ref.find('i').removeClass("fa-check-circle-o").removeClass("fa-times-circle-o").addClass('fa-spinner').addClass('fa-pulse');
        $.post(ref.data("url"), {},
            function (itemJson) {
                if (itemJson.success) {
                    updatePages(['#event-view-attendees']);
                }
            }, 'json');
        return false;
    });
}

function eventInviteAttendeesReset(parent) {
    parent.find('.btn-success').removeClass('btn-success').addClass('btn-default');
    parent.find('#my-contacts-list, #level-list, #all-users-list, #invitations-list, #selected-list').hide();
}

function eventInviteAttendeesCheckboxHandlers(parent) {

    userMiniProfilePopover(parent.find('a#view-profile'));

    parent.find('input:checkbox').change(function () {

        if ($(this).prop('checked')) { // copy to selected users

            var userDiv = $(this).parents('#user').clone();

            eventInviteAttendeesCheckboxHandlers(userDiv);
            $('#invite-form').find('#user-list').append(userDiv);

            $('#invite-form').find('#no-users').hide();

            var id = $(this).parents('#user').data('id');
            $('div#user[data-id="'+id+'"]').each(function() {
                $(this).find('input:checkbox').prop('checked', true);
                $(this).find('#userstat').html($('#event-invite-attendees').data('selected-user-stat'));
                $(this).find('.label-default').removeClass('label-default').addClass('label-warning');
            });
        }
        else {
            var id = $(this).parents('#user').data('id');
            $('#invite-form').find('#user[data-id='+id+']').remove();
            $('div#user[data-id="'+id+'"]').each(function() {
                $(this).find('input:checkbox').prop('checked', false);
                $(this).find('#userstat').html($('#event-invite-attendees').data('not-invited'));
                $(this).find('.label-warning').removeClass('label-warning').addClass('label-default');
            });

            if ($('#invite-form').find('#user').length==0) {
                $('#invite-form').find('#no-users').show();
            }
        }

        $('#invite-form')
            .find('#sort-name-desc, #sort-city-desc, #sort-state-desc, #sort-name-asc, #sort-city-asc, #sort-state-asc, #search-text')
            .off();

        eventInviteAttendeesSearchSortHandlers($('#invite-form'));

        var no = $('span#event-invite-attendees-no-invitations');
        var noUsers = $('#invite-form').find('#user').length;

        no.html(noUsers);
        if (noUsers) {
            $('#event-invite-attendees-invite-button').removeAttr('disabled');
        }
        else {
            $('#event-invite-attendees-invite-button').attr('disabled', 'disabled');
        }
    });
}

function eventInviteAttendeesSearchSortHandlers(parent) {

    var $divs = parent.find('div#user');

    parent.find('#sort-name-desc').off();

    parent.find('#sort-name-desc').click(function () {
        var alphabeticallyOrderedDivs = $divs.sort(function (a, b) {
            return $(a).find("#username").text().toLowerCase() < $(b).find("#username").text().toLowerCase();
        });
        parent.find("#user-list").html(alphabeticallyOrderedDivs);
        parent.find('#sort-value').html($(this).text()+' <i class="fa fa-arrow-down"></i>');
        parent.find('#sort-value').data('sort', 'name-down');
        eventInviteAttendeesCheckboxHandlers(parent);
    });

    parent.find('#sort-city-desc').off();

    parent.find('#sort-city-desc').click(function () {
        var alphabeticallyOrderedDivs = $divs.sort(function (a, b) {
            return $(a).find("#usercity").text().toLowerCase() < $(b).find("#usercity").text().toLowerCase();
        });
        parent.find("#user-list").html(alphabeticallyOrderedDivs);
        parent.find('#sort-value').html($(this).text()+' <i class="fa fa-arrow-down"></i>');
        parent.find('#sort-value').data('sort', 'city-down');
        eventInviteAttendeesCheckboxHandlers(parent);
    });

    parent.find('#sort-state-desc').off();

    parent.find('#sort-state-desc').click(function () {
        var alphabeticallyOrderedDivs = $divs.sort(function (a, b) {
            return $(a).find("#userstat").text().toLowerCase() < $(b).find("#userstat").text().toLowerCase();
        });
        parent.find("#user-list").html(alphabeticallyOrderedDivs);
        parent.find('#sort-value').html($(this).text()+' <i class="fa fa-arrow-down"></i>');
        parent.find('#sort-value').data('sort', 'state-down');
        eventInviteAttendeesCheckboxHandlers(parent);
    });

    parent.find('#sort-name-asc').off();

    parent.find('#sort-name-asc').click(function () {
        var alphabeticallyOrderedDivs = $divs.sort(function (a, b) {
            return $(a).find("#username").text().toLowerCase() > $(b).find("#username").text().toLowerCase();
        });
        parent.find("#user-list").html(alphabeticallyOrderedDivs);
        parent.find('#sort-value').html($(this).text()+' <i class="fa fa-arrow-up"></i>');
        parent.find('#sort-value').data('sort', 'name-up');
        eventInviteAttendeesCheckboxHandlers(parent);
    });

    parent.find('#sort-city-asc').off();

    parent.find('#sort-city-asc').click(function () {
        var alphabeticallyOrderedDivs = $divs.sort(function (a, b) {
            return $(a).find("#usercity").text().toLowerCase() > $(b).find("#usercity").text().toLowerCase();
        });
        parent.find("#user-list").html(alphabeticallyOrderedDivs);
        parent.find('#sort-value').html($(this).text()+' <i class="fa fa-arrow-up"></i>');
        parent.find('#sort-value').data('sort', 'city-up');
        eventInviteAttendeesCheckboxHandlers(parent);
    });

    parent.find('#sort-state-asc').off();

    parent.find('#sort-state-asc').click(function () {
        var alphabeticallyOrderedDivs = $divs.sort(function (a, b) {
            return $(a).find("#userstat").text().toLowerCase() > $(b).find("#userstat").text().toLowerCase();
        });
        parent.find("#user-list").html(alphabeticallyOrderedDivs);
        parent.find('#sort-value').html($(this).text()+' <i class="fa fa-arrow-up"></i>');
        parent.find('#sort-value').data('sort', 'state-up');
        eventInviteAttendeesCheckboxHandlers(parent);
    });

    parent.find('#search-text').off();

    parent.find('#search-text').on('input', function (event) {
        var text = $(this).val().toLowerCase();
        $divs.each(function() {
            $(this).show();
            var hide= ($(this).find("#username").text().toLowerCase().indexOf(text)==-1 &&
                $(this).find("#usercity").text().toLowerCase().indexOf(text)==-1 /*&&
                $(this).find("#userstat").text().toLowerCase().indexOf(text)==-1*/
            );
            if (hide) {
                $(this).hide();
            }
        });


    });

    parent.find('#search-users').off();

    parent.find('#search-users').on('input', function (event) {
        var text = $(this).val().toLowerCase();
        if (text.length<3) {
            parent.find('#user-list').empty();
            parent.find('#no-users').show();
            parent.find('#no-users').find('.col-md-12').html($('#event-invite-attendees').data('search-users'));
            $('#event-invite-attendees').find('#all-button').find('#no-users').html('0');

            return;
        }

        $.get( $('#event-invite-attendees').data('search-users-url')+text, function(response) {
                parent.find('#user-list').html(response);
                if (response.length<100) {
                    parent.find('#no-users').show();
                    parent.find('#no-users').find('.col-md-12').html($('#event-invite-attendees').data('no-users-found'));

                }
                else {
                    parent.find('#no-users').hide();
                }
                // update fields
                parent.find('#user').each(function() {
                    var id=$(this).data('id');
                    if ($('#user[data-id="'+id+'"]').find('input:checkbox:checked').length) { // is selected for invitation
                        $(this).find('input:checkbox').prop('checked', true);
                        $(this).find('#userstat').html($('#event-invite-attendees').data('selected-user-stat'));
                        $(this).find('.label-default').removeClass('label-default').addClass('label-warning');
                    }

                    if ($('#user[data-id="'+id+'"]').find('.label-info, .label-primary').length) { // is already invited
                        $(this).find('input:checkbox').remove();
                        $(this).find('.label').replaceWith($('#user[data-id="'+id+'"]').find('.label').first().clone());
//		                		$(this).find('.label-default').removeClass('label-default').addClass('label-warning');
                    }

                    eventInviteAttendeesCheckboxHandlers($(this));
                });

                $('#event-invite-attendees').find('#all-button').find('#no-users').html($('#all-users-list').find('div#user').length);

                eventInviteAttendeesSearchSortHandlers($('#all-users-list'));

                // sort new list
                switch (parent.find('#sort-value').data('sort')) {
                    case 'name-up':
                        parent.find('#sort-name-asc').click();
                        break;
                    case 'name-down':
                        parent.find('#sort-name-desc').click();
                        break;
                    case 'city-up':
                        parent.find('#sort-city-asc').click();
                        break;
                    case 'city-down':
                        parent.find('#sort-city-desc').click();
                        break;
                    case 'state-up':
                        parent.find('#sort-state-asc').click();
                        break;
                    case 'state-down':
                        parent.find('#sort-state-desc').click();
                        break;
                }

            }
        );

    });


}

function eventInviteAttendeesUserListHandlers(parentId) {

    var parent = $('#'+parentId);



    eventInviteAttendeesCheckboxHandlers(parent);


    parent.find('#select').click(function(){
        parent.find('input[type=checkbox]').each(function() {
            $(this).prop('checked', true);
        });
        parent.find('#no-invitations').html($('input[type=checkbox]').length);
        parent.find('#invite').removeAttr('disabled');
    });

    parent.find('#deselect').click(function(){
        parent.find('input[type=checkbox]').each(function() {
            $(this).prop('checked', false);
        });
        parent.find('#no-invitations').html('0');
        parent.find('#invite').attr('disabled', 'disabled');
    });


    eventInviteAttendeesSearchSortHandlers(parent);

}


function eventInviteAttendeesAllUserListHandlers(parentId) {

    var parent = $('#'+parentId);

    userMiniProfilePopover(parent.find('a#view-profile'));

    eventInviteAttendeesCheckboxHandlers(parent);


    parent.find('#select').click(function(){
        parent.find('input[type=checkbox]').each(function() {
            $(this).prop('checked', true);
        });
        parent.find('#no-invitations').html($('input[type=checkbox]').length);
        parent.find('#invite').removeAttr('disabled');
    });

    parent.find('#deselect').click(function(){
        parent.find('input[type=checkbox]').each(function() {
            $(this).prop('checked', false);
        });
        parent.find('#no-invitations').html('0');
        parent.find('#invite').attr('disabled', 'disabled');
    });

    eventInviteAttendeesSearchSortHandlers(parent);

}

/**
 *  Handlers for the EventController -> inviteAttendeesAction
 */
function eventInviteAttendeesHandlers() {

    var parent = $('#event-invite-attendees');

    parent.find('#contacts-button').find('#no-users').html($('#my-contacts-list').find('div#user').length);
    parent.find('#invited-button').find('#no-users').html($('#invitations-list').find('div#user').length);
    parent.find('#level-button').find('#no-users').html($('#level-list').find('div#user').length);



    parent.find('#contacts-button').click(function(){
        eventInviteAttendeesReset(parent);
        $(this).removeClass('btn-default').addClass('btn-success');
        parent.find('#my-contacts-list').show();
    });

    parent.find('#contacts-button').click();


    parent.find('#level-button').click(function(){
        eventInviteAttendeesReset(parent);
        $(this).removeClass('btn-default').addClass('btn-success');
        parent.find('#level-list').show();
    });

    parent.find('#all-button').click(function(){
        eventInviteAttendeesReset(parent);
        $(this).removeClass('btn-default').addClass('btn-success');
        parent.find('#all-users-list').show();
    });

    parent.find('#invited-button').click(function(){
        eventInviteAttendeesReset(parent);
        $(this).removeClass('btn-default').addClass('btn-success');
        parent.find('#invitations-list').show();
    });

    parent.find('#selected-button').click(function(){
        eventInviteAttendeesReset(parent);
        $(this).removeClass('btn-default').addClass('btn-success');
        parent.find('#selected-list').show();
    });



    parent.find('#invite-form').submit(function() {
        $.ajax({
            data: $(this).serialize(), 
            type: $(this).attr('method'), 
            url: $(this).attr('action'), 
            success: function(response) { 
                if (typeof response ==  'object') {
                    updatePages(['#event-view-invitations']);
                }
                $('.modal').modal('hide');
            }
        });
        return false; 
    });

    $('#event-invite-attendees-invite-button').click(function() {
        $(this).find('i').removeClass('fa-users').addClass('fa-spinner').addClass('fa-pulse');
        parent.find('#invite-form').submit();
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
	$('#addStepForm').submit(function() {
        $.ajax({
            data: $(this).serialize(), 
            type: $(this).attr('method'), 
            url: $(this).attr('action'), 
            success: function(response) { 
                //alert(response);
                $('#dialogAddNewStep').html(response); 
                if ($('.text-danger').length==0) { // if no errors
                    setTimeout(function(){
                        $('#dialogAddNewStep').dialog("close");
                    }, 2000);
                    $('#timeline').load("/city/proposal/implementation/timeline/"+$('#propUUID').val(), function() {
                    });
                }
                else {
                    $(":button:contains('Add step')").attr("disabled", false);
                }
            }
        });
        return false; 
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

    $('#city-level-chooser').click(function(){
        $('#home-step-user-1').show();
        $('#home-step-user-0').hide();

        return false;

    });

    $('a#home-step-user-1-next').click(function() {
        $('#home-step-user-'+$(this).data('value')).show();
        $('#home-step-user-1').hide();
        return false;
    });

    $('#admin-bne').click(function() {
        $('#home-step-admin-bne').show();
        $('#home-step-admin-1').hide();
    });

    $('#admin-program').click(function() {
        $('#home-step-admin-program').show();
        $('#home-step-admin-1').hide();
    });

    $('#admin-prop').click(function() {
        $('#home-step-admin-scenario').show();
        $('#home-step-admin-1').hide();
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
    $('#inbox-list-delete-selected-form').submit(function() { 
        $('#inbox-delete-selected-yes-button').prop('disabled', 'disabled');
        $('#inbox-delete-selected-yes-button-icon').removeClass("fa-trash").addClass('fa-spinner').addClass('fa-pulse');
        
        $.ajax({ 
            data: $(this).serialize(), 
            type: $(this).attr('method'), 
            url: $(this).attr('action'), 
            success: function(response) { 
                $('#inbox-delete-selected').parent().html(response); 

            },
        });
        return false; 
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
        return false; 
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
        return false; 
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
        
        return false;
    });
}

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
    userMiniProfilePopover($('a#inbox-list-profile-'+type));
    $("a#page-ibx-"+type).click(function(){
        // Load the content of the page referenced in the a-tags href
        $.get($(this).attr("href"), function(data) {
            $("#inbox-list-"+type).replaceWith(data);
        });

        return false;
    });
    $('input#checkbox-'+type).change(function(){
        if ($('input#checkbox-'+type+':checked').length) {
            $('#inbox-delete-selected-'+type).show();
        }
        else {
            $('#inbox-delete-selected-'+type).hide();
        }
        return false;
    });
    $('#inbox-list-delete-more-form-'+type).submit(function() { 
        modalDialog('inbox-delete-more-dialog', $(this).data('dialog-title'), $(this).attr('action')+'?'+$(this).serialize(), false);
        return false; 
    });
    $("a#inbox-list-message-item-"+type).click(function(){
        $('#inbox-list-'+type).find('.list-group-item').removeClass('active');
        $(this).addClass('active').removeClass('notview');
        // Load the content of the page referenced in the a-tags href
        $("#inbox-view-div").load($(this).attr("href"));
        $('#inbox-list-'+type).data('view-url', $(this).attr("href"));
        
        return false;
    });

    clearInterval(inboxListTimer);
    inboxListTimer = setInterval(function() {
        $.get($('#inbox-list-received').data('url'), function(data) {
            var display = $('#inbox-list-received').css('display');
            $('#inbox-list-received').replaceWith(data);
            $('#inbox-list-received').css('display', display);
        });
    }, 600000);
    $("#inbox-list-view-received-"+type).click(function(){
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

    contentEditableTextLimit($('#inbox-new-message-textbox'), $('#inbox-new-message-textbox-count'), [$('#inbox-new-message-send-message')]);
    textboxFormatHandlers($('#edit-description'));

    $('#inbox-new-message-textbox').on('change propertychange click keyup input paste focus', function (event) {
        contentEditableTextLimit($('#inbox-new-message-textbox'), $('#inbox-new-message-textbox-count'), [$('#inbox-new-message-send-message')]);
    });

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
    $('#newMessageForm').submit(function() { 
        $('#inbox-new-message-send-message-icon').removeClass("fa-envelope-o").addClass('fa-spinner').addClass('fa-pulse');
        $('#msgText').html($('#inbox-new-message-textbox').html());
        $.ajax({ 
            data: $(this).serialize(), 
            type: $(this).attr('method'), 
            url: $(this).attr('action'), 
            success: function(response) { 
                $('#inbox-new-message').parent().html(response);
                updateInboxLists('all');
            }
        });
        return false; 
    });
}
/**
 *  Handlers for the InboxController -> inbox-partial-menu.phtml
 */
function inboxPartialMenuHandlers() {
    $('button#inbox-partial-menu-new-message').click(function(){
        modalDialog('new-message-dialog', $(this).data('dialog-title'), $(this).data('url'), false);
        return false;
    });
    $("a#page-ibx_filter").click(function(){
        // Load the content of the page referenced in the a-tags href
        $('#inbox-partia-menu-filter').removeClass('open');
        $('#inbox-partia-menu-filter-check').prependTo($(this));
        $('#inbox-list').parent().load($(this).attr("href"));
        
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
    $('#inbox-partial-menu-search-form').submit(function() { 
        $('#inbox-partial-menu-search-submit').find('i').removeClass("fa-search").addClass('fa-spinner').addClass('fa-pulse');
        var url = $(this).attr('action') + '/sk/' + $('#inbox-partial-menu-search-keywords').val().trim()/*.replace(/\s+/g, '|')*/ +
            '/sr/' + $('#inbox-partial-menu-search-receiver-input').attr('value') +
            '/ss/' + $('#inbox-partial-menu-search-sender-input').attr('value') +
            '/st/' + $('#inbox-partial-menu-search-subject-input').attr('value') +
            '/sm/' + $('#inbox-partial-menu-search-message-input').attr('value');
        
        $.ajax({ 
            data: $(this).serialize(), 
            type: $(this).attr('method'), 
            url: url, 
            success: function(html) { 
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
        return false; 
    });
}

function inboxViewInvitationHandlers() {
    $('#inbox-view-invitation-attend').click(function () {
        var ref = $(this);
        ref.find('i').removeClass("fa-check-circle-o").removeClass("fa-times-circle-o").addClass('fa-spinner').addClass('fa-pulse');
        $.post(ref.data("url"), {},
            function (itemJson) {
                if (itemJson.success) {
                    updatePages(['#session-my-sessions', '#inbox-view-message']);
                }
            }, 'json');
        return false;
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
function proposalRemoveLinkHandler(div, admin) {
    div.find('a').click(function() {
        $(this).parents('#link-row').remove();
        if (!admin && !$('#proposal-links').find('a').length) {
            $('#measure-add-measure-publish').prop('disabled', 'disabled');
        }
        return false;
    });
    if ($('#proposal-links').find('a').length) {
        $('#measure-add-measure-publish').prop('disabled', false);
    }
}

/**
 *  Handlers for the MesureController -> cityMeasuresAction
 */
function mesureAllMeasuresHandlers() {

    var parent = $('#measure-all-measures');

    proposalAddProposalToProgramHandler(parent.find('a#add-to-program'));
    proposalRemoveProposalFromProgramHandler(parent.find('a#remove-from-program'));

    proposalAddRemoveFavoriteHandler(parent.find('a#favorite'));
    userMiniProfilePopover(parent.find('a#view-profile'));
    $('#measure-all-measures-add-new-measure').click(function(){
        modalDialog('new-measure-dialog', $(this).data('dialog-title').replace('$level$', parent.find('#level-name').html()), $(this).data('url'), true);
    });
    $("a#page-all-measures").click(function(){
        parent.parent().load($(this).attr("href"));
        return false;
    });
}

/**
 *  Handlers for the MeasureController -> addAction
 */
function measureClaimOwnershipHandlers() {
    $('#measure-claim-ownership-form').submit(function() { 7
        $('#measure-claim-ownership-submit').prop('disabled', 'disabled').find('i').removeClass("fa-certificate").addClass('fa-spinner').addClass('fa-pulse');
        
        $.ajax({ 
            type: $(this).attr('method'), 
            url: $(this).attr('action'), 
            success: function(response) { 
                if (typeof response ==  'object') {
                    updatePages(['#measure-view-measure']);
                    $('.modal').modal('hide');
                }
                else {
                    $('#measure-claim-ownership').parent().html(response);
                }
            },
        });
        return false; 
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
    $("a#page-draft-measures").click(function(){
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

    proposalAddRemoveFavoriteHandler(parent.find("a#favorite"));
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
    clearInterval(newsListTimer);
    newsListTimer = setInterval(function() {
        updatePages(['#news-all-news']);

    }, 600000);
}
function newsletterAddNewsletterHandlers() {
    var selectedCategoryValue = $('#mainCategories option:first').val();
    var imageFile = $('#mainCategories option:first').data('style');
    var selectedCategoryImage;
    var selectedCategoryName;


    $("#mainCategories").on("change", function () {
        var selectedOption = $("#mainCategories").find('option:selected');
        var imageFile = selectedOption.css('background-image');
        imageFile = imageFile.substring(4, imageFile.length-1);
        $('#add-proposal-category-image').html('<img width="32px" height="32px" src='+imageFile+' title="'+selectedOption.text()+'">');
        selectedCategoryValue = selectedOption.val();
    });

    $('#newsletter-add-newsletter-add-category').click(function() {
        if (!$('#category-'+selectedCategoryValue).length && $('#newsletter-add-newsletter-category-list').find('input').length<3) {
            var span = $('<span id="category-'+selectedCategoryValue+'">');
            $('<span> </span>').appendTo(span);
            $('#add-proposal-category-image > img').clone().appendTo(span);
            $('<input type="hidden" name="categories[]" value="'+selectedCategoryValue+'">').appendTo(span);
            $('<span> </span>').appendTo(span);
            $('<a href=""><i class="fa fa-times-circle darkgray"></i></a>')
                .click(function() {
                    $(this).parent().remove();
                    updateCategoriesPreview();
                    return false;
                })
                .appendTo(span);
            span.appendTo($('#newsletter-add-newsletter-category-list'));
            updateCategoriesPreview();
        }
    });

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
                $('<span> </span>').appendTo(span);
                span.appendTo($('#newsletter-add-newsletter-category-preview'));
            });
        }
    }


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

    $('#newsletter-add-newsletter-form').submit(function() { 
        $('#nlMessage').html($('#newsletter-add-newsletter-textbox').html());
        var fd = new FormData($('#newsletter-add-newsletter-form')[0]);
        $('#newsletter-add-newsletter-save-button > i').removeClass("fa-floppy-o").addClass('fa-spinner').addClass('fa-pulse');
        
        $.ajax({ 
            data: fd, 
            type: $(this).attr('method'), 
            url: $(this).attr('action'), 
            processData: false,
            contentType: false,
            success: function(response) { 
                if (typeof response ==  'object') {
                    if (typeof $('#newsletter-my-newsletters').data('url') !== 'undefined') {
                        $('#newsletter-my-newsletters').parent().load($('#newsletter-my-newsletters').data('url'));
                    }
                    $('.modal').modal('hide');
                    //$('#add-new-proposal-dialog').remove();
                }
                else {
                    
                    $('#newsletter-add-newsletter').parent().html(response); 
                }
            },
        });
        return false; 
    });

}

/**
 *  Handlers for the NewsletterController -> deleteNewsletterAction
 */
function newsletterDeleteHandlers() {
    $('#delete-newsletter-yes-button').click(function () {
        $('#delete-newsletter-yes-button').prop('disabled', 'disabled').find('i').removeClass("fa-trash").addClass('fa-spinner').addClass('fa-pulse');
        $.ajax({
            type: 'POST',
            url: $(this).attr('href'),
            success: function(response) {
                updatePages(['#newsletter-my-newsletters']);
                $('.modal').modal('hide');
            },
        });
        return false;
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
    $("a#newsletter-my-newsletters-duplicate").click(function(){
        modalDialog('duplicate-newsletter-dialog',  $('#newsletter-my-newsletters').data('duplicate-newsletter'), $(this).attr("href"), true);
        return false;
    });
    $("a#newsletter-my-newsletters-delete").click(function(){
        modalDialog('delete-newsletter-dialog',  $('#newsletter-my-newsletters').data('delete-newsletter'), $(this).attr("href"), "modal-sm");
        return false;
    });
}

function resizeImages(fileInput, canvas,  complete, error) {
    var file = fileInput.prop('files')[0];
    if (file.type.match('image.*')) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var img = new Image();
            img.onload = function() {
                complete(resizeInCanvas(img, canvas));
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
    else {
        error();

    }
}

function resizeInCanvas(img, canvas){
    var perferedWidth = 500;
    var ratio = perferedWidth / img.width;

    canvas?canvas = canvas[0]:$('<canvas>')[0];
    canvas.width = img.width * ratio;
    canvas.height = img.height * ratio;
    canvas.style.width  = '100%';
    var ctx = canvas.getContext("2d");
    ctx.drawImage(img, 0,0,canvas.width, canvas.height);
    return canvas.toDataURL("image/jpeg");
}


/**
 *  Handlers for the ProposalController -> addAction
 */
function measureAddMeasureHandlers(admin) {

    proposalAddEditProposalHandlers(admin);
    $('.input-daterange').datepicker({
        format: "dd/mm/yyyy",
        calendarWeeks: true,
        autoclose: true,
        todayHighlight: true
    });

}


function textboxFormatHandlers(parent) {
    parent.find('button#textbox-format-button').click(function(){
        if ($(this).hasClass('btn-success')) {
            $(this).removeClass('btn-success').addClass('btn-default');
            $(this).find('.fa').removeClass('fa-check').addClass('fa-times');
        }
        else {
            $(this).addClass('btn-success').removeClass('btn-default');
            $(this).find('.fa').addClass('fa-check').removeClass('fa-times');
        }
        $('#'+$(this).data('contenteditable-id')).focus();
        document.execCommand($(this).data('format'));
    });
}

/**
 *  Handlers for the ProposalController -> addProposalAction + editProposalAction
 */
function proposalAddEditProposalHandlers(admin) {

    contentEditableTextLimit($('#proposal-textbox'), $('#proposal-textbox-count'), [$('#proposal-save-button'), $('#proposal-publish-button')]);

    $('#proposal-textbox').on('change propertychange click keyup input paste focus', function (event) {
        contentEditableTextLimit($('#proposal-textbox'), $('#proposal-textbox-count'), [$('#proposal-save-button'), $('#proposal-publish-button')]);
    });

    textboxFormatHandlers($('#edit-description'));



    $('canvas').each(function() {
        if (typeof $(this).data('image') !== 'undefined') {
            var canvas = $(this)[0];
            var imageObj = new Image();

            imageObj.onload = function() {
                canvas.width = imageObj.width;
                canvas.height = imageObj.height;
                canvas.style.width  = '100%';
                var ctx = canvas.getContext("2d");
                ctx.drawImage(imageObj, 0,0,canvas.width, canvas.height);
            };
            imageObj.src = $(this).data('image');
        }
    });

    $('#imageFile').change(function() {
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
                $('#imageFile').wrap('<form>').parent('form').trigger('reset');
                $('#imageFile').unwrap();
                $('.modal-body').unblock();
            }
            if (div) {
                resizeImages($(this), div.find('canvas'), function(dataUrl) {
                    div.find('input').val(dataUrl);
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
            $('.modal-body').unblock();
        }
    });

    proposalRemoveLinkHandler($('#proposal-links'), admin);
    $('#proposal-new-link').click(function () {
        var urlInput=$('<div class="bot5" id="link-row">'+
            '<div style="width: 250px; float: left;" class="bot5">'+
            '<input type="url" name="links[]" class="form-control col-md-11 text-change" value="">'+
            '</div>'+
            '<div style="width: 20px; float: left; padding: 10px 0 0 5px;">'+
            '<a href="#" id="remove-link"><i class="fa fa-times-circle darkgray"></i></a>'+
            '</div></div>');
        urlInput.appendTo($('#proposal-links'));
        proposalRemoveLinkHandler(urlInput, admin);
    });


    $('a#proposal-add-edit-proposal-remove-image').click(function() {
        $(this).siblings('input').val('');
        $(this).parent().hide();
        $('#imageFile').prop('disabled', false);
        return false;
    });


    $('#proposal-save-button').click(function () {
        $('#proposal-save-button').prop('disabled', 'disabled');
        $('#proposal-save-button').find('i').removeClass("fa-floppy-o").addClass('fa-spinner').addClass('fa-pulse');

        $('#proposal-form').submit();

    });

    $('#proposal-publish-button').click(function () {
        $('#proposal-publish-button').prop('disabled', 'disabled').find('i').removeClass("fa-floppy-o").addClass('fa-spinner').addClass('fa-pulse');
        var action = $('#proposal-form').attr('action');
        if (action.indexOf("publish") < 0) {
            $('#proposal-form').attr('action', action+'/publish/true');
        }
        $('#proposal-form').submit();
    });

    $('#proposal-form').submit(function() { 
        $('#propDescription').html($('#proposal-textbox').html());

        $.ajax({ 
            data: $(this).serialize(), 
            type: $(this).attr('method'), 
            url: $(this).attr('action'), 
            success: function(response) { 
                if (typeof response ==  'object') {
                    if (response.measure) {
                        updatePages(['#measure-view-measure', '#administration-dashboard-draft-measures', '#measure-all-measures', '#news-all-news']);
                    }
                    else {
                        if (response.level == 'edit') {
                            updatePages(["#proposal-view"]);
                        }
                        else {
                            updatePages(["#proposal-my-proposals", "#proposal-all-proposals", "#news-all-news"]);
                        }
                    }
                    $('.modal').modal('hide');
                }
                else {
                    
                    $('.modal-body').html(response); 
                }
            },
        });

        return false; 
    });


    $('input[name=level]').change(function() {
        $('#add-proposal-category-image').html('<i class="fa fa-spinner fa-pulse fa-2x"></i>');

        $.post($('div[data-get-categories-url]').data('get-categories-url')+'/level/'+$('input[name="level"]:checked').val(),
            function (itemJson) {
                $("#main_category option").remove();
                if (typeof itemJson.name != 'undefined') { // check if category has subcategories
                    for (var i = 0; i < itemJson.name.length; i++) {
                        $("#main_category").append('<option value="' + itemJson.id[i] + '" style="background: url('+itemJson.image[i]+') no-repeat; background-size: 32px 32px;  padding-left: 35px; padding-top:10px; height:35px;">' + itemJson.name[i] + '</option>');
                    }
                }
                $("#main_category").val($("#main_category option:first").val());
                var imageFile = $("#main_category").find('option:selected').css('background-image');
                imageFile = imageFile.substring(4, imageFile.length-1);
                $('#add-proposal-category-image').html('<img width="32px" height="32px" src='+imageFile+'>');
                updateSubCategories($("#main_category").find('option:selected').val(), $('#proposal-add-edit-proposal').data('get-subcategories-url')+'/level/'+$('input[name="level"]:checked').val());
            }, 'json');
        return false;
    });

    $("#main_category").on("change", function () {
        var level = $('input[name="level"]:checked').length?$('input[name="level"]:checked').val():$('input[name="level"]').val();
        updateSubCategories($(this).val(), $('div[data-get-subcategories-url]').data('get-subcategories-url')+'/level/'+level);
    });
}




function proposalAddRemoveFavoriteHandler(favId){

    var addFavorite='Add Favorite', removeFavorite='Remove Favorite';
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
                    updatePages(['#proposal-my-favorites']);
                    ref.html((itemJson.success == 1) ? '<i class="fa fa-heart-o size15em" title="'+addFavorite+'"></i>' : '<i class="fa fa-heart size15em" title="'+removeFavorite+'"></i>'/*+*/);
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
        $.post(ref.attr("href"), {},
            function (itemJson) {
                var id=ref.data('id');
                ref.find('img').attr('src', '/img/icon-programme-violet.svg').attr('title', ref.parents('.parent').data('add-to-my-program'));
                ref.attr('href', ref.attr('href').replace('remove-', ''));
                ref.attr('id', 'add-to-program');
                ref.unbind('click');
                proposalAddProposalToProgramHandler(ref);

                updatePages(['#program-all-programs', '#program-my-programs', '#program-view-aggregated-program']);

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
    
    proposalAddRemoveFavoriteHandler(parent.find("a#favorite"));
    parent.find('button#add-new-proposal').click(function(){
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
        $.ajax({ 
            type: 'POST', 
            url: $(this).attr('href'),
            success: function(response) { 
                if (typeof response ==  'object') {
                    $('.modal').modal('hide');
                    parent.history.back();
                }
                else {
                    
                    $('#delete-proposal-content').parent().html(response); 
                }
            },
        });
        return false; 
    });
}
/**
 *  Handlers for the ProposalController -> myFavoritesAction
 */
function proposalMyFavoritesHandlers() {

    var parent = $('#proposal-my-favorites');

    userMiniProfilePopover($('a#proposal-my-favorites-view-profile'));
    proposalAddRemoveFavoriteHandler(parent.find('a#favorite'));
    proposalAddProposalToProgramHandler(parent.find('a#add-to-program'));
    proposalRemoveProposalFromProgramHandler(parent.find('a#remove-from-program'));
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

    $('button#proposal-my-proposals-new-proposal').click(function(){
        modalDialog('add-new-proposal-dialog', $(this).data('dialog-title'), $(this).data('url'));
        return false;
    });

}

/**
 *  Handlers for the ProposalController -> ideaImportedProposalsAction
 */
function proposalIdeaImportedProposalsHandlers() {

    var parent = $('#proposal-idea-proposals');

    userMiniProfilePopover($('a#proposal-idea-proposals-view-profile'));
    proposalAddProposalToProgramHandler(parent.find('a#add-to-program'));
    proposalRemoveProposalFromProgramHandler(parent.find("a#remove-from-program"));


    proposalAddRemoveFavoriteHandler($('a#proposal-idea-proposals-favorite'));

    $("a#page-idea-prop").click(function(){
        $("#proposal-idea-proposals").parent().load($(this).attr("href"));
        return false;
    });


}

/**
 *  Handlers for the ProposalController -> publishAction
 */
function proposalProlongDebateHandlers() {
    $('#proposal-prolong-debate-yes-button').click(function () {
        $(this).prop('disabled', 'disabled').find('i').removeClass("fa-calendar-plus-o").addClass('fa-spinner').addClass('fa-pulse');
        $.ajax({ 
            type: 'POST', 
            url: $(this).data('url'),
            success: function(response) { 
                if (typeof response ==  'object') {
                    updatePages(['#proposal-view']);
                }
                $('.modal').modal('hide');
            },
        });
        return false; 
    });
}

/**
 *  Handlers for the ProposalController -> publishAction
 */
function proposalPublishProposalHandlers() {
    $('#proposal-publish-proposal-yes-button').click(function () {
        $(this).prop('disabled', 'disabled').find('i').removeClass("fa-floppy-o").addClass('fa-spinner').addClass('fa-pulse');
        $.ajax({ 
            type: 'POST', 
            url: $(this).data('url'),
            success: function(response) { 
                if (typeof response ==  'object') {
                    updatePages(['#proposal-view']);
                    $('.modal').modal('hide');
                }
                else {
                    
                    $('#publish-proposal-dialog').parent().html(response); 
                }
            },
        });
        return false; 
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
    $('.equal .panel-body').matchHeight({
        byRow: true,
        property: 'min-height',
        target: null,
        remove: false
    });

    var parent = $('#proposal-view');

    proposalAddProposalToProgramHandler(parent.find('a#add-to-program'));
    proposalRemoveProposalFromProgramHandler(parent.find("a#remove-from-program"));

    userMiniProfilePopover($('a#proposal-view-profile'));
    proposalAddRemoveFavoriteHandler(parent.find('a#favorite'));

    $('#proposal-view-report').click(function(){
        modalDialog('report-dialog', $(this).data('dialog-title'), $(this).attr("href"), false);
        return false;
    });

    $('#proposal-view-proposal-publish').click(function(){
        modalDialog('publish-proposal-dialog', $(this).data('dialog-title'), $(this).data('url'), false);
        return false;
    });

    $('#proposal-view-proposal-delete').click(function(){
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
    $('#user-profile-partner-select-categories-form').submit(function() { 
        $('#user-profile-partner-select-categories-to-categories option').prop('selected', true);
        $("#user-profile-partner-select-categories-save > i").removeClass("fa-floppy-o").addClass('fa-spinner').addClass('fa-pulse');
        $.ajax({ 
            data: $(this).serialize(), 
            type: $(this).attr('method'), 
            url: $(this).attr('action'), 
            success: function(response) { 
                $('#partner-profile-partner-presentation-category-list').find('span').remove();
                $('#user-profile-partner-select-categories-to-categories optgroup').each(function() {
                    var imageFile = $(this).find('option').first().css('background-image');
                    imageFile = imageFile.substring(5, imageFile.length-2);
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
        return false; 
    });
}
function partnerProfilePartnerSelectDepartmentsHandlers() {
    removeEmptyGroups();
    orderOptgroups();
    partnerSelectDepartmentsPartialFormHandlers('#user-profile-partner-select-regions');
    $('#user-profile-partner-select-regions-form').submit(function() { 
        $('#user-profile-partner-select-regions-to-departments option').prop('selected', true);
        $("#user-profile-partner-select-regions-save > i").removeClass("fa-floppy-o").addClass('fa-spinner').addClass('fa-pulse');
        $.ajax({ 
            data: $(this).serialize(), 
            type: $(this).attr('method'), 
            url: $(this).attr('action'), 
            success: function(response) { 
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
        return false; 
    });
}

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
            $('#partner-profile-partner-presentation-keyword-input').val('');
        }
    });
    $("#usrPostalcode").on('keyup change', function() {
        getCities(getCityURL);
    });
    $('.partner-presentation-text-change').on('keypress', function (e) {
        if (e.which==13) {
            var inputs = $(this).closest('form').find(':focusable').not('a, button');
            inputs.eq(inputs.index(this) + 1).focus();
            return false;
        }
    });
    $('#user-profile-partner-presentation-form').submit(function() { 
        $("#partner-profile-partner-presentation-save > i").removeClass("fa-floppy-o").addClass('fa-spinner').addClass('fa-pulse');
        $.ajax({ 
            data: $(this).serialize(), 
            type: $(this).attr('method'), 
            url: $(this).attr('action'), 
            success: function(response) { 
                $('#partner-profile-partner-presentation').parent().html(response);
            },
        });
        return false; 
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
    $('#partner-dashboard-search-opportunities-form-department-list span a').click(function() {
        moveSelect2Select($('#partner-dashboard-search-opportunities-form-to-departments > optgroup > option[value="'+ $(this).parent().data('id')+'"]'), $('#partner-dashboard-search-opportunities-form-from-departments'));
        orderOptgroups();
        $(this).parent().remove();
        return false;
    });
    $('#partner-dashboard-search-opportunities-form-collapse-region-button').click(function() {
        $('#partner-dashboard-search-opportunities-form-collapse-region').collapse('show');
        $('#partner-dashboard-search-opportunities-form-collapse-region-button').attr('disabled', 'disabled');
        return false;
    });
    $('#partner-dashboard-search-opportunities-form-save-departments').click(function() {
        $('#partner-dashboard-search-opportunities-form-department-list').empty();
        $('#partner-dashboard-search-opportunities-form-no-departments').html($("#partner-dashboard-search-opportunities-form-to-departments > optgroup ").length);
        $("#partner-dashboard-search-opportunities-form-to-departments > optgroup > option").each(function() {
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
        var tableLine = $('<tr>'+
            '<td style="width:32px; border-top:none;"><img src="'+imageFile+'" height="24" width="24" title="'+$(this).attr('label')+'"> </td>'+
            '<td style="line-height:1.6em; border-top:none;" id="partner-dashboard-search-opportunities-form-line">'+
            '</td></tr>');
        $('#partner-dashboard-search-opportunities-form-categories-table').append(tableLine);
        $(this).children().each(function() {
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
    removeEmptyGroups();
    orderOptgroups();
    partnerSelectCategoriesPartialFormHandlers('#partner-dashboard-search-opportunities-form');
    /**
     * Remove a category from the list
     */
    partnerSearchOppotunitiesFormRemoveCategoryHandler();
    $('#partner-dashboard-search-opportunities-form-collapse-categories-button').click(function() {
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
            var tableLine = $('<tr>'+
                '<td style="width:32px; border-top:none;"><img src="'+imageFile+'" height="24" width="24" title="'+$(this).attr('label')+'"> </td>'+
                '<td style="line-height:1.6em; border-top:none;" id="partner-dashboard-search-opportunities-form-line">'+
                '</td></tr>');
            $('#partner-dashboard-search-opportunities-form-categories-table').append(tableLine);
            $(this).children().each(function() {
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
    $('#partner-dashboard-search-opportunities-form-form').submit(function() { 
        $("#partner-dashboard-search-opportunities-form-update-results > i").removeClass("fa-refresh").addClass('fa-spinner').addClass('fa-pulse');
        $.ajax({ 
            data: $(this).serialize(), 
            type: $(this).attr('method'), 
            url: $(this).attr('action'), 
            success: function(response) { 
                //alert(response);
                $('#partner-dashboard-search-opportunities').parent().html(response);
                
            },
            complete: function() {
                $("#partner-dashboard-search-opportunities-form-update-results > i").addClass("fa-refresh").removeClass('fa-spinner').removeClass('fa-pulse');
            }
        });
        return false; 
    });
}

/**
 *  Handlers for the ReportController -> addReportAction
 */
function reportAddReportHandlers() {

    $('#report-add-report-form').submit(function() { 
        $('#report-add-report-submit-button').prop('disabled', 'disabled').find('i').removeClass("fa-exclamation-triangle").addClass('fa-spinner').addClass('fa-pulse');
        
        $.ajax({ 
            data: $(this).serialize(), 
            type: $(this).attr('method'), 
            url: $(this).attr('action'), 
            success: function(response) { 
                $('.modal-body').html(response);
            },
        });
        return false; 
    });

}

function reportSubmitBugHandlers() {

    textboxFormatHandlers($('#report-submit-bug'));

    $('#report-submit-bug-form').submit(function() { 
        $('#bugDescription').html($('#report-submit-bug-textbox').html());
        var fd = new FormData($(this)[0]);
        $( "#report-submit-bug-submit-icon" ).removeClass("fa-bug").addClass('fa-spinner').addClass('fa-pulse');
        
        $.ajax({ 
            data: fd, 
            type: $(this).attr('method'), 
            url: $(this).attr('action'), 
            processData: false,
            contentType: false,
            success: function(response) { 
                $('#report-submit-bug').html(response); 
            },
        });
        return false; 
    });

    contentEditableTextLimit($('#report-submit-bug-textbox'), $('#report-submit-bug-textbox-count'), [$('#report-submit-bug-send-message')]);


    $('#report-submit-bug-textbox').on('change propertychange click keyup input paste focus', function (event) {
        contentEditableTextLimit($('#report-submit-bug-textbox'), $('#report-submit-bug-textbox-count'), [$('#report-submit-bug-send-message')]);
    });

}


/**
 *  Handlers for the ProgramController -> addProgramAction
 */
function programAddEditProgramHandlers() {

    contentEditableTextLimit($('#program-add-program-textbox'), $('#program-add-program-textbox-count'), [$('#program-add-program-save')]);
    textboxFormatHandlers($('#edit-description'));

    $('#program-add-program-textbox').on('change propertychange click keyup input paste focus', function (event) {
        contentEditableTextLimit($('#program-add-program-textbox'), $('#program-add-program-textbox-count'), [$('#program-add-program-save')]);
    });

    $('#program-add-program-form').submit(function() { 
        $('#progDescription').html($('#program-add-program-textbox').html());
        $('#program-add-program-save').prop('disabled', 'disabled').find('i').removeClass("fa-floppy-o").addClass('fa-spinner').addClass('fa-pulse');
        
        $.ajax({ 
            data: $(this).serialize(), 
            type: $(this).attr('method'), 
            url: $(this).attr('action'), 
            success: function(response) { 
                if (typeof response ==  'object') {

                    if (typeof $('#program-add-program-save').data('url') !== 'undefined') {
                        $('#modal-dialog-title').html($('#program-add-program-save').data('dialog-title'));
                        $('.modal-body').load(response.link);
                        $('#dialog-help-button').attr('href', '/pages/help#add-proposal-to-program-dialog')
                    }
                    else {
                        updatePages(['#program-view-program']);
                        $('.modal').modal('hide');
                    }
                }
                else {
                    updatePages(['#program-my-programs', '#program-all-programs']);
                    $('.modal-body').html(response);
                }
            },
        });
        return false; 
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
        
        $.post(ref.data('url'), 
            function(response) { 
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
        $("a#add-to-program[data-id='"+ref.data('id')+"']").each(function() {
            $(this).find('img').attr('src', '/img/icon-programme-remove-violet.svg').attr('title', $(this).parents('.parent').data('remove-from-my-program'));
            $(this).attr('href', ref.data('url'));
            $(this).attr('id', 'remove-from-program');
            $(this).unbind('click');
            proposalRemoveProposalFromProgramHandler($(this));

        });
        updatePages(['#program-all-programs', '#program-my-programs', '#program-view-aggregated-program']);
        $('.modal').modal('hide');
    });
}

/**
 *  Handlers for the ProgramController -> viewProgramAction
 */
function programViewProgramHandlers() {
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
                var button = $('<a href="'+ref.attr('href')+'" class="btn" id="add-remove-proposal"></a>');
                if (response.added) {
                    ref.removeClass('btn-orange').addClass('btn-primary');
                    ref.find('i').removeClass('fa-spinner').removeClass('fa-pulse').addClass('fa-times-circle');
                    ref.find('span').html($('#program-add-proposals-from-city').data(ref.data('measure')?'remove-measure':'remove-proposal'));
                    $('#program-add-proposals-from-city-count').html(parseInt($('#program-add-proposals-from-city-count').html())+1);
                }
                else {
                    ref.addClass('btn-orange').removeClass('btn-primary');
                    ref.find('i').removeClass('fa-spinner').removeClass('fa-pulse').addClass('fa-plus-circle');
                    ref.find('span').html($('#program-add-proposals-from-city').data(ref.data('measure')?'add-measure':'add-proposal'));

                    $('#program-add-proposals-from-city-count').html(parseInt($('#program-add-proposals-from-city-count').html())-1);
                }
                updatePages(['#program-get-proposals', '#program-my-programs', '#program-all-programs']);
            }, 'json');
        
        return false;
    });
    $("a#page-add-city-proposals").click(function(){
        $("#program-add-proposals-from-city").parent().load($(this).attr("href"));
        return false;
    });
}

/**
 *  Handlers for the ProgramController -> deleteProgramAction
 */
function programDeleteProgramHandlers() {
    $('#program-delete-program-delete').click(function () {
        $(this).prop('disabled', 'disabled').find('i').removeClass("fa-trash").addClass('fa-spinner').addClass('fa-pulse');
        var ref= $(this);
        $.ajax({ 
            type: 'POST', 
            url: $(this).data('url'),
            success: function(response) { 
                if (typeof response ==  'object') {
                    window.location.href = ref.data('goto');
                    $('.modal').modal('hide');
                }
                else {
                    
                    $('#program-delete-program').parent().html(response); 
                }
            },
        });
        return false; 
    });
}




/**
 *  Handlers for the ScenarioController -> cityScenariosAction
 */
function programAllProgramsHandlers() {
    userMiniProfilePopover($('a#program-all-programs-view-profile'));
    
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
    $('#sort-proposals-form').submit(function() { 
        
        $.ajax({ 
            data: $(this).serialize(), 
            type: $(this).attr('method'), 
            url: $(this).attr('action'), 
            success: function(response) { 
                updatePages(['#program-get-proposals']);
                $('.modal').modal('hide');
            },
        });
        return false; 
    });
}

function sessionAddEditSessionHandlers() {

    textboxFormatHandlers($('#edit-description'));
    contentEditableTextLimit($('#session-add-session-textbox'), $('#session-add-session-textbox-count'), [$('#session-add-session-save-button'), $('#session-add-session-publish-button')]);


    $('#session-add-session-textbox').on('change propertychange click keyup input paste focus', function (event) {
        contentEditableTextLimit($('#session-add-session-textbox'), $('#session-add-session-textbox-count'), [$('#session-add-session-save-button'), $('#session-add-session-publish-button')]);
    });

    $('#session-add-session-save-button').click(function() {
        $(this).prop('disabled', 'disabled');
        $(this).find('i').removeClass("fa-floppy-o").addClass('fa-spinner').addClass('fa-pulse');
        $('#session-add-edit-session-form').submit();
    });
    $('#session-add-session-publish-button').click(function () {
        $(this).prop('disabled', 'disabled');
        $(this).find('i').removeClass("fa-share-square-o").addClass('fa-spinner').addClass('fa-pulse');
        var action = $('#session-add-edit-session-form').attr('action');
        if (action.indexOf("publish") < 0) {
            $('#session-add-edit-session-form').attr('action', action+'/publish/true');
        }
        $('#session-add-edit-session-form').submit();
    });

    $("#eventPostalcode, #city").on('keyup change paste', function() {
        getCities($('#session-add-session'), $('#eventPostalcode'), $('#city'), $('#country'), '#cityLoad');
    });

    $('#session-add-edit-session-form').submit(function() { 
        $('#eventDescription').html($('#session-add-session-textbox').html());
        $.ajax({ 
            data: $(this).serialize(), 
            type: $(this).attr('method'), 
            url: $(this).attr('action'), 
            success: function(response) { 
                if (typeof response ==  'object') {
                    updatePages(['#session-my-sessions', '#event-all-events', '#event-upcoming-events', '#session-view-session']);
                    $('.modal').modal('hide');
                    if (response.url) {
                        modalDialog('invite-attendees-dialog', response.title, response.url, false);
                    }
                }
                else {
                    
                    $('#session-add-session').parent().html(response); 
                }
            },
        });
        return false; 
    });
}

/**
 *  Handlers for the ProposalController -> publishAction
 */
function sessionProlongSessionHandlers() {
    $('#session-prolong-session-yes-button').click(function () {
        $(this).prop('disabled', 'disabled').find('i').removeClass("fa-calendar-plus-o").addClass('fa-spinner').addClass('fa-pulse');
        $.ajax({ 
            type: 'POST', 
            url: $(this).data('url'),
            success: function(response) { 
                $('.modal').modal('hide');
            },
        });
    });
}


function sessionMySessionsHandlers(lang) {


    $('#session-my-sessions-add-new-session').click(function(){
        modalDialog('new-session-dialog', $('#session-my-sessions').data('add-session'), $(this).data("href"), true);
        return false;
    });

    $('#session-my-sessions-see-all').click(function(){
        $('#session-my-sessions').parent().load($(this).data('url'));
        return false;
    });

    $('#session-my-sessions-see-drafts').click(function(){
        $('#session-my-sessions').parent().load($(this).data('url'));
        return false;
    });

    $('#session-my-sessions-search-form').submit(function() { // catch the form's submit session
        $(this).find('i').removeClass("fa-search").addClass('fa-spinner').addClass('fa-pulse');
        $.ajax({ 
            data: $(this).serialize(), 
            type: $(this).attr('method'), 
            url: $(this).attr('action'), 
            success: function(html) { 
                $('#session-my-sessions').replaceWith(html);
            },
        });
        return false; // cancel original session to prsession form submitting
    });

    $('a#session-my-sessions-cancel').click(function(){
        modalDialog('cancel-session-dialog', $('#session-my-sessions').data('cancel-session'), $(this).attr("href"), true);
        return false;
    });
    $('a#session-my-sessions-publish').click(function(){
        modalDialog('publish-session-dialog', $('#session-my-sessions').data('publish-session'), $(this).attr("href"), true);
        return false;
    });
    $("a#page-my-sessions").click(function(){
        $("#session-my-sessions").parent().load($(this).attr("href"));
        return false;
    });
}

function sessionMySessionsListHandlers(lang) {

    var parent=$('#session-my-sessions-list');

    userMiniProfilePopover(parent.find('a#view-profile'));

    $("#session-my-sessions-list-filter").find('a').click(function(){
        if ($(this).find('i').hasClass('fa-check')){
            $(this).find('i').removeClass('fa-check').addClass('fa-times');
        }
        else {
            $(this).find('i').removeClass('fa-times').addClass('fa-check');
        }
        return false;
    });

    parent.find('#filter-deselect-all').click(function(){
        $("#session-my-sessions-list-filter").find('a').each(function() {
            var i = $(this).find('i');
            if (i.data('filter')!='completed' && i.hasClass('fa-check')){
                i.removeClass('fa-check').addClass('fa-times');
            }
        });
        return false;
    });

    parent.find('#filter-select-all').click(function(){
        $("#session-my-sessions-list-filter").find('a').each(function() {
            var i = $(this).find('i');
            if (i.data('filter')!='completed' && i.hasClass('fa-times')){
                i.removeClass('fa-times').addClass('fa-check');
            }
        });
        return false;
    });

    $('#session-my-sessions-list-filter-apply').click(function() {
        var filterList =$("#session-my-sessions-list-filter");
        var filter='';
        var filterCount = filterList.find('.fa-check[data-filter!="completed"]').length;
        if (filterCount && filterCount<9) {
            filterList.find('i').each(function(index) {
                if ($(this).hasClass('fa-check')) {
                    filter += $(this).data('filter')+'|';
                }
            });
        }
        else {
            filter = filterList.find('.fa-check[data-filter="completed"]').length?'completed':'none';
        }

        $("#session-my-sessions-list").parent().load($(this).data("url")+'/filter/'+filter);
    });

    $('#session-my-sessions-list-filter-reset').click(function() {

        parent.find('#filter-select-all').click();
        $('#session-my-sessions-list-filter-apply').click();

    });

    $("a#page-my-sessions-list").click(function(){
        $("#session-my-sessions-list").parent().load($(this).attr("href"));
        return false;
    });

    parent.find('#completed-sessions').click(function() {
        if ($(this).hasClass('btn-success')) {
            return true;
        }
        $(this).removeClass('btn-default').addClass('btn-success');
        parent.find('#upcoming-sessions').addClass('btn-default').removeClass('btn-success');
        $('#session-my-sessions-list-filter').find('i[data-filter="completed"]').removeClass('fa-times').addClass('fa-check');
        $('#session-my-sessions-list-filter-apply').click();

    });
    parent.find('#upcoming-sessions').click(function() {
        if ($(this).hasClass('btn-success')) {
            return true;
        }
        $(this).removeClass('btn-default').addClass('btn-success');
        parent.find('#completed-sessions').addClass('btn-default').removeClass('btn-success');
        $('#session-my-sessions-list-filter').find('i[data-filter="completed"]').removeClass('fa-check').addClass('fa-times');
        $('#session-my-sessions-list-filter-apply').click();

    });
}

function sessionViewSessionHandlers() {

    userMiniProfilePopover($('a#session-view-session-view-profile'));

    $('#session-view-session-edit').click(function(){
        modalDialog('edit-session-dialog', $('#session-view-session').data('edit-session'), $(this).data("url"), true);
        return false;
    });

    $('#session-view-session-cancel').click(function(){
        modalDialog('cancel-session-dialog', $('#session-view-session').data('cancel-session'), $(this).data("url"), false);
        return false;
    });

}

$.fn.scrollBottom = function() {
    return $(document).height() - this.scrollTop() - this.height();
};

function sessionLiveHandlers($user, timer) {

    $('#session-live-invite').click(function(){
        modalDialog('invite-attendees-dialog', $(this).data('dialog-title'), $(this).data("url"), true);
        return false;
    });

    $('#add-more-time').click(function(){
        modalDialog('add-more-time-dialog', $(this).data('dialog-title'), $(this).attr("href"), 'modal-md');
        return false;
    });

    $('#session-live-close-button').click(function(){
        modalDialog('end-session-dialog', $(this).data('dialog-title'), $(this).data("url"), 'modal-lg', true);
    });

    $('#add-new-idea-form').formValidation(
        {
            framework: 'bootstrap',
            button: {
                selector: '#add-new-idea-submit-button',
                disabled: 'disabled'
            },
            icon: null,
            fields: {
                ideaTitle: {
                    validators: {
                        notEmpty: {
                            message: $('#idea-title').data('empty-error')
                        },
                        regexp: {
                            regexp: /^[\s]*[0-9A-Za-z\u00C0-\u017F]+[^:]*(:[\s]*[0-9A-Za-z\u00C0-\u017F]+[^:]*)*$/,
                            message: $('#idea-title').data('regexp-error')
                        },
                    }
                }
            },
            err: {
                clazz: 'text-help red'
            },
        });

    $('a#category-menu-item').click(function() {
        var imgTo = $('#ideaCategoryDropdown').find('img');
        var imgFrom = $(this).find('img');

        imgTo.attr('src', imgFrom.attr('src'));
        imgTo.attr('title', imgFrom.data('title'));
        $('#ideaCategory').val($(this).data('category-id'));
    });

    $('#add-new-idea-submit-button').click(function() {
        $('#add-new-idea-form').submit();
    });

    $('#add-new-idea-form').submit(function() { 
        $('#add-new-idea-submit-button').prop('disabled', 'disabled').find('i').removeClass("fa-floppy-o").addClass('fa-spinner').addClass('fa-pulse');
        $.ajax({ 
            data: $(this).serialize(), 
            type: $(this).attr('method'), 
            url: $(this).attr('action'), 
            success: function(response) { 
                if (response.success) {
                    $('#idea-title').val('');
                    ideas.getIdeas();
                }
                $('#idea-title').parent().removeClass('has-success');
            },
            error: function() {
                $('#idea-title').parent().removeClass('has-success').addClass('has-error');
            }
        }).always(function() {
            $('#add-new-idea-submit-button').prop('disabled', false).find('i').addClass("fa-floppy-o").removeClass('fa-spinner').removeClass('fa-pulse');
        });
        return false; 
    });


    jsGrid.setDefaults({
        tableClass: "jsgrid-table table table-striped table-hover"
    });

    jsGrid.setDefaults("text", {
        _createTextBox: function() {
            return $("<input>").attr("type", "text").attr("class", "form-control input-sm");
        }
    });


    var parentIdeas = $('#idea-list').parent();
    var ideaList = $('#idea-list');
    var suggestedIdeaList = $('#suggested-idea-list');
    var ideas = new Ideas(ideaList, $('#suggested-idea-list'), true, timer);

    $('#suggested-idea-list').jsGrid({
        height: "200px",
        width: "100%",

        rowClass: function(item, itemIndex) {
            return "fullwidth idea-" + item.id +  ($('#chat-message-list').data('chat-filter-uuid')==item.id?" commented-idea":"");
        },
        headerRowRenderer: function() {
            return $('<th colspan="5" class="text-center suggested-ideas">').text(parentIdeas.data('suggested-idea-header'));
        },

        editing: false,
        autoload: true,
        heading: true,

        loadIndication:false,

        deleteConfirm: parentIdeas.data('delete-idea'),
        noDataContent: parentIdeas.data('no-suggested-ideas'),

        controller: {
            loadData: function() {
                return ideas.suggestedIdeas;
            },
            deleteItem: function(idea) {
                ideas.deleteIdea(idea);
            }
        },

        onRefreshed: function(grid) {
            var body = suggestedIdeaList.find('.jsgrid-grid-body');
            body.scrollTop(body.find('table').height());
        },

        fields: [{
            width: 20,
            align: "center"
        }, {
            name: "category",
            type: "CategoryField",
            width: 30,
            align: "center",
        }, {
            name: "element",
            type: "IdeaField",
            width: 200,
//			        validate: "ideaValidator",
        },  {
            name: "user",
            type: "UserField",
            width: 24,
            align: "center"
        },
            {
                type: "control",
                itemTemplate: function(value, item) {
                    var $result = $([]);
                    if($user==parentIdeas.data('owner')) {
                        $result = $result.add(this._createGridButton("jsgrid-update-button", parentIdeas.data('validate-idea'), function(grid, e) {
                            ideas.validateIdea(item);
                            e.stopPropagation();
                        }));
                        $result = $result.add(this._createDeleteButton(item));
                    }
                    $result = $result.add(createJSGridCommentButton(this, item, ideas));
                    return $result;
                }
            }]
    });

    $('#idea-list').jsGrid({
        height: "323px",
        width: "100%",

        rowClass: function(item, itemIndex) {
            return "fullwidth idea-" + item.id +  ($('#chat-message-list').data('chat-filter-uuid')==item.id?" commented-idea":"");
        },
        headerRowRenderer: function() {
            return $('<th>').attr('colspan', 5).addClass('text-center').addClass('validated-ideas').text(parentIdeas.data('validated-idea-header'));
        },
        editing: $user==parentIdeas.data('owner'),
        autoload: true,
        heading: true,

        loadIndication:true,

        deleteConfirm: parentIdeas.data('delete-idea'),
        noDataContent: parentIdeas.data('no-validated-ideas'),

        controller: {
            loadData: function() {
                return ideas.validatedIdeas;
            },
            updateItem: function(idea) {
                $.ajax({
                    data: {'idea':idea},
                    type: $('#add-new-idea-form').attr('method'),
                    url: $('#add-new-idea-form').attr('action').replace('add-idea', 'update-idea'),
                });
            },
            deleteItem: function(idea) {
                ideas.deleteIdea(idea);
            }
        },

        onDataLoaded: function(grid, data) {
            var body = $("#idea-list").find('.jsgrid-grid-body');
            body.scrollTop(body.find('table').height());
        },
        onItemEditing: function(args) {
            ideas.stopTimer();
            sortableDisable($('#idea-list .jsgrid-grid-body tbody'));
        },
        onItemUpdating: function(grid, row, item, itemIndex, previousItem) {
            if(this._editingRow)
                return;

            ideas.startTimer();
            sortableEnable($('#idea-list .jsgrid-grid-body tbody'));
        },
        onRefreshed: function(grid) {

            var $gridData = $("#idea-list .jsgrid-grid-body tbody");

            if ($user==ideaList.parent().data('owner')) {
                $gridData.sortable({
                    cancel: ".disable-sort" ,
                    update: function(e, ui) {
                        var items = $.map($gridData.find("tr"), function(row) {
                            return $(row).data("JSGridItem");
                        });

                        ideas.sortIdeas(items);
                    }
                });
            }


        },
        cancelEdit: function() {
            if(!this._editingRow)
                return;

            this._getEditRow().remove();
            this._editingRow.show();
            this._editingRow = null;
            if(!this._editingRow)
                sortableEnable($('#idea-list .jsgrid-grid-body tbody'));
        },
        fields: [{
            name: "position",
            width: 20,
            align: "center",
            sorter: function(pos1, pos2) {
                return pos1<pos2;
            }
        }, {
            name: "category",
            type: "CategoryField",
            width: 30,
            align: "center",
        }, {
            name: "element",
            type: "IdeaField",
            width: 200,
        },  {
            name: "user",
            type: "UserField",
            width: 24,
            align: "center"
        },
            {
                type: "control",
                itemTemplate: function(value, item) {
                    var $result = $([]);

                    if(this.editButton && $user==parentIdeas.data('owner') && item.element.validated) {
                        $result = $result.add(this._createEditButton(item));
                    }

                    if(this.deleteButton && $user==parentIdeas.data('owner')) {
                        $result = $result.add(this._createDeleteButton(item));
                    }

                    var commentButton=createJSGridCommentButton(this, item, ideas);
                    $result = $result.add(commentButton);
                    return $result;
                }
            }]
    });

    $('#chat-message-list').data('entity-lists', ["idea-list", "suggested-idea-list"]);
    $('#chat-message-list').data('entity', 'idea');
}

function sessionEndedHandlers($user) {

    userMiniProfilePopover($('#session-view-session-view-profile'));

    jsGrid.setDefaults({
        tableClass: "jsgrid-table table table-striped table-hover"
    });

    jsGrid.setDefaults("text", {
        _createTextBox: function() {
            return $("<input>").attr("type", "text").attr("class", "form-control input-sm");
        }
    });

    var parentIdeas = $('#idea-list').parent();
    var ideaList = $('#idea-list');
    var ideas = new Ideas(ideaList, $('#suggested-idea-list'), false);

    $('#idea-list').jsGrid({
        height: "523px",
        width: "100%",

        rowClass: function(item, itemIndex) {
            return "fullwidth idea-" + item.id +  ($('#chat-message-list').data('chat-filter-uuid')==item.id?" commented-idea":"");
        },
        headerRowRenderer: function() {
            return $('<th>').attr('colspan', 5).addClass('text-center').addClass('validated-ideas').text(parentIdeas.data('validated-idea-header'));
        },
        editing: false,
        autoload: true,
        heading: true,
        loadIndication:true,
        controller: {
            loadData: function() {
                return ideas.validatedIdeas;
            },
        },
        onDataLoaded: function(grid, data) {
            ideas.stopTimer();
        },
        fields: [{
            name: "position",
            width: 20,
            align: "center",
            sorter: function(pos1, pos2) {
                return pos1<pos2;
            }
        }, {
            name: "category",
            type: "CategoryField",
            width: 30,
            align: "center",
        }, {
            name: "element",
            type: "IdeaField",
            width: 200,
        },  {
            name: "user",
            type: "UserField",
            width: 24,
            align: "center"
        },
            {
                type: "control",
                width: 70,
                itemTemplate: function(value, item) {
                    var $result = $([]);

                    var importButton = this._createGridButton("import-idea", $('#idea-list').parent().data('import-idea'), function(grid, e) {

                        var url = $('#idea-list').data('import-idea-url');
                        var guest = (url.search('register')!=-1);
                        url = guest?url:url+'/idea/'+item.id;

                        modalDialog('add-new-proposal-dialog', $('#idea-list').data('add-new-proposal-title'), url, guest?'modal-md':'modal-lg');

                        $('.modal').on('hidden.bs.modal', function () {
                            ideas.getIdeas();
                        });
                        e.stopPropagation();
                    });
                    $result = $result.add(importButton);

                    if (item.element.prop) {
                        var grid=this;
                        var validateButton=
                            this._createGridButton("proposals-idea", $('#idea-list').data('view-imported-proposal'), function(grid, e) {
                                var url = $('#idea-list').data('view-imported-proposals-url')+'/'+item.id;
                                modalDialog('idea-imported-proposals-dialog', $('#idea-list').data('view-imported-proposals-title'), url);
//				        	    	ideas.validateIdea(item);
                                e.stopPropagation();
                            });

                        $result = $result.add(validateButton);

                    }

                    var commentButton=createJSGridCommentButton(this, item, ideas);
                    $result = $result.add(commentButton);
                    return $result;
                }
            }]
    });

    $('#chat-message-list').data('entity-lists', ["idea-list"]);
    $('#chat-message-list').jsGrid({
        height: "523px"});

}

function createJSGridCommentButton(grid, item, ideas) {
    return grid._createGridButton("comment-idea", $('#idea-list').parent().data('comment-idea'), function(grid, e) {

        if ($('#chat-message-list').data('chat-filter-uuid')!=item.id) {
            $('#chat-message-list').data('chat-filter-uuid', item.id);
            $('#chat-message-list').jsGrid({
                headerRowRenderer: function() {
                    return $('<th colspan="5" class="text-center badge-info" style="padding:5px!important;">').text($('#chat-message-list').data('comment-element')+' ')
                        .append($('<i>').text(item.element.name))
                        .append('  ')
                        .append($('<a>').attr('href', '#')
                            .attr('title', $('#chat-message-list').data('back-full-chat'))
                            .html('<i class="fa fa-times-circle w size15em"></i>')
                            .click(function() {
                                $('#chat-message-list').data('chat-filter-uuid', '');
                                $('#chat-message-list').jsGrid({heading: false});
                                ideas.refresh();
                                return false;
                            }));
                },
                heading: true
            });
        }
        else {
            $('#chat-message-list').data('chat-filter-uuid', '');
            $('#chat-message-list').jsGrid({heading: false});
        }
        $('#chat-message-list').jsGrid("search");
        ideas.refresh();
        e.stopPropagation();
    });
}

function sessionIdeaListEditCategory(div) {
    div.find('a#category-menu-item').click(function() {
        var imgTo = div.find('#ideaCategoryDropdown').find('img');
        var imgFrom = $(this).find('img');

        imgTo.attr('src', imgFrom.attr('src'));
        imgTo.attr('title', imgFrom.data('title'));
    });

}

function sessionIdeaListIdeaValidation(div) {
    div.formValidation(
        {
            framework: 'bootstrap',
            icon: null,
            fields: {
                idea: {
                    validators: {
                        notEmpty: {
                            message: $('#idea-title').data('empty-error')
                        },
                        regexp: {
                            regexp: /^[\s]*[0-9A-Za-z\u00C0-\u017F]+[^:]*(:[\s]*[0-9A-Za-z\u00C0-\u017F]+[^:]*)*$/,
                            message: $('#idea-title').data('regexp-error')
                        },
                    }
                }
            },
            err: {
                clazz: 'text-help red'
            },
            row: {
                selector: 'form-horizontal',
                valid: 'has-success',
                invalid: 'has-danger'
            }
        });
}

function sortableEnable(div) {
    div.sortable();
    div.sortable( "option", "disabled", false );
    // ^^^ this is required otherwise re-enabling sortable will not work!
    div.disableSelection();
    return false;
}
function sortableDisable(div) {
    if (!div.hasClass( "ui-sortable-disabled" )) {
        div.sortable("disable");
    }
    return false;
}

function findIdea(idea) {
    return this.id==idea.id;
}

function compareIdeas(a, b) {
    if (a.position < b.position)
        return -1;
    if (a.position > b.position)
        return 1;
    // a doit être égal à b
    return 0;
}

function Ideas(validatedIdeasGrid, suggestedIdeasGrid, live, timer) {
    this.validatedIdeas = [];
    this.suggestedIdeas = [];
    this.live = live;
    this.getMessagesTime = 5000; //ms
    this.getMessagesTimer = null;
    this.validatedIdeasGrid = validatedIdeasGrid;
    this.suggestedIdeasGrid = suggestedIdeasGrid;
    this.timer = timer;

    this.getIdeas = function() {
        var _this=this;
        $.ajax({
            url: this.validatedIdeasGrid.parent().data('idea-list'),
            dataType: "json"
        }).done(function(response) {
            var vIdeas = [];
            var sIdeas = [];
            for (var i=0;i<_this.validatedIdeas.length;i++) {
                vIdeas.push(0);
            }
            for (var i=0;i<_this.suggestedIdeas.length;i++) {
                sIdeas.push(0);
            }
            var refresh = false;

            if (live && response.ended) {
                modalDialog('end-session-dialog', _this.validatedIdeasGrid.parent().data('session-ended'), _this.validatedIdeasGrid.parent().data('idea-list').replace('idea-list', 'session-ended'),  'modal-lg', false);
                _this.stopTimer();
                return;
            }
            if (typeof _this.timer!=='undefined') {
                var newDate = new Date(response.endDate);
                if (live && newDate.getTime() != _this.timer.getDate().getTime()) {
                    _this.timer.setDate(newDate);
                }
            }

            $.each(response.ideas, function(index, idea) {
                var i;
                if (idea.element.validated) {
                    if ((i=_this.validatedIdeas.findIndex(function findIdea(e) {return e.id==idea.id;}))>=0) {
                        var sameIdea = _this.validatedIdeas[i];
                        vIdeas[i] = 1;
                        if (sameIdea.element.name != idea.element.name || sameIdea.element.description!=idea.element.description ||
                            sameIdea.category.id != idea.category.id || sameIdea.position!=idea.position || sameIdea.element.prod!=idea.element.prop) {
                            _this.validatedIdeas[i] = idea;
                            refresh = true;
                        }
                    }
                    else {
                        _this.validatedIdeas.push(idea);
                        refresh = true;
                    }
                }
                else {
                    if ((i=_this.suggestedIdeas.findIndex(function findIdea(e) {return e.id==idea.id;}))>=0) {
                        var sameIdea = _this.suggestedIdeas[i];
                        sIdeas[i] = 1;
                        if (sameIdea.element.name != idea.element.name || sameIdea.element.description!=idea.element.description ||
                            sameIdea.category.id != idea.category.id || sameIdea.position!=idea.position) {
                            _this.suggestedIdeas[i] = idea;
                            refresh = true;
                        }
                    }
                    else {
                        _this.suggestedIdeas.push(idea);
                        refresh = true;
                    }
                }
            });
            // remove deleted/validated ideas
            var dif = 0;
            for (var i=0;i<vIdeas.length;i++) {
                if (!vIdeas[i]) {
                    _this.validatedIdeas.splice(i-dif, 1);
                    dif++;
                }
            }
            dif = 0;
            for (var i=0;i<sIdeas.length;i++) {
                if (!sIdeas[i]) {
                    _this.suggestedIdeas.splice(i-dif, 1);
                    dif++;
                }
            }
            // resort ideas:
            _this.validatedIdeas.sort(compareIdeas);
            _this.suggestedIdeas.sort(compareIdeas);
            if (refresh) {
                _this.load();
            }

        });
    }

    this.refresh = function() {
        this.validatedIdeasGrid.jsGrid("refresh");
        this.suggestedIdeasGrid.jsGrid("refresh");

    }

    this.load = function() {
        this.validatedIdeasGrid.jsGrid("loadData");
        this.suggestedIdeasGrid.jsGrid("loadData");
    }


    this.reload = function() {
        this.stopTimer();
        this.validatedIdeas = [];
        this.suggestedIdeas = [];

        this.startTimer();
        this.validatedIdeasGrid.jsGrid("loadData");
        this.suggestedIdeasGrid.jsGrid("loadData");
    }

    this.validateIdea = function(idea) {
        var ideas=this;
        $.ajax({
            data: {'idea':idea},
            type: 'post',
            url: ideas.validatedIdeasGrid.parent().data('idea-list').replace('idea-list', 'validate-idea')
        }).done(function(response) {
            if (response.success) {
                ideas.suggestedIsdeas = $.grep(ideas.suggestedIdeas, function(e){ return e.id != idea.id; });
                ideas.validatedIdeasGrid.push(idea);
                ideas.reload();
            }
        });
    }

    this.sortIdeas = function(ideas) {
        var _this = this;
        $.each(ideas, function(index, idea) {
            var i=_this.validatedIdeas.findIndex(function(e) {return e.id==idea.id;});
            _this.validatedIdeas[i].position = index+1;

        });

        _this.validatedIdeas.sort(function(idea1, idea2) {
            return idea1.position - idea2.position;
        })

        $.ajax({ 
            data: {'ideas':_this.validatedIdeas}, 
            type: 'post', 
            url: $('#idea-list').parent().data('idea-list').replace('idea-list', 'sort-ideas'),
        }).done(function(result) {
            _this.refresh();
        });
    }

    this.updateIdea = function(idea) {
        var ideas=this;
        $.ajax({
            data: {'idea':idea},
            type: 'post',
            url: ideas.validatedIdeasGrid.parent().data('idea-list').replace('idea-list', 'update-idea')
        }).done(function(response) {
            if (response.success) {
                ideas.reload();
            }
        });

    }

    this.deleteIdea = function(idea) {
        var ideas=this;
        $.ajax({
            data: {'idea':idea},
            type: 'post',
            url: ideas.validatedIdeasGrid.parent().data('idea-list').replace('idea-list', 'delete-idea'),
        }).done(function(response) { 
            if (response.success) {
                ideas.reload();
            }
        });
    }

    this.startTimer = function() {
        var ideas = this;
        if (this.getMessages) {
            clearInterval(this.getMessages);
        }
        ideas.getIdeas();
        this.getMessages = setInterval(function() {
            ideas.getIdeas();
        }, this.getMessagesTime);
    }

    this.stopTimer = function() {
        if (this.getMessages) {
            clearInterval(this.getMessages);
            this.getMessages = null;
        }
    }

    this.startTimer();

}



function updateSortIdea($gridData) {
    var items = $.map($gridData.find("tr"), function(row) {
        return $(row).data("JSGridItem");
    });
    grid._loadIndicator.show();
    $.ajax({ 
        data: {'ideas':items}, 
        type: 'post', 
        url: parentIdeas.data('sort-ideas'), 
    });
}

function chatAddFilter($filter) {
    $('#chat-message-list').data('chat-filter-uuid', filter);

}

function chatViewChatHandlers() {

    userMiniProfilePopover($('a#chat-view-chat-moderator-profile'));


    $('#chat-title').on('keypress', function (e) {
        if (e.which==13) {

            $.ajax({ 
                data: {title: $('#chat-title').text()}, 
                type: 'post', 
                url: $(this).data('url'), 
                success: function(response) { 
                    if (response.success) {
                        $("#chat-message-list").jsGrid("loadData");
                        $('#chat-title').blur();
                    }
                },
            });

            $('#partner-profile-partner-presentation-add-keyword-button').click();
            return false;
        }
    });

    $('#chat-add-message-form').submit(function() { 
        if ($.trim($('#chat-message').val())=='') return false;
        var message = $('#chat-message').val();

        $('#chat-message').val('');
        $.ajax({
            data: {
                message: message,
                id: $('#chat-message-list').data('chat-filter-uuid'),
                entity: $('#chat-message-list').data('entity')
            }, 
            type: $(this).attr('method'), 
            url: $(this).attr('action'), 
            success: function(response) { 
                if (response.success) {
                    $("#chat-message-list").jsGrid("loadData");
                }
                $('#chat-message').parent().removeClass('has-success');
            },
            error: function() {
                $('#chat-message').parent().removeClass('has-success').addClass('has-error');
            }
        }).always(function() {

        });
        return false; 
    });

    var inc = 2;

    var getMessages = setInterval(function() {

        if ($('#chat-message-list').length) {
            $("#chat-message-list").jsGrid("loadData");
            if (!$('#chat-message-list').data("chat-opened")) {
                inc--;
                if (inc<=0) clearInterval(getMessages);
            }
        }
        else {
            clearInterval(getMessages);
        }

    }, 5000);

    jsGrid.setDefaults({
        tableClass: "jsgrid-table table table-striped table-hover"
    });

    $('#chat-message-list').jsGrid({
        height: "410px",
        width: "100%",

        headerRowRenderer: function() {
            return $('<th>').attr('colspan', 5).addClass('text-center').addClass('badge-info').text('Idea name');
        },
        heading: false,

        rowClass: function(item, itemIndex) {
            return "fullwidth";
        },
        rowRenderer: function(message) {
            // no blocked user message
            if (message.blockedUser == null) {
                var user = $("<div>").append($('<img style="cursor:pointer;">')
                    .addClass('round5')
                    .attr("src", message.user.picture)
                    .attr('width', 24)
                    .attr('height', 24)
                    .attr('title', '@'+message.user.name))
                    .append('  ')
                    .append($('<a>')
                        .attr('href', '/user/profile/mini-profile/'+message.user.uuid+'/context/'+$('#chat-message-list').data('chat-id'))
                        .attr('data-title', $('#chat-message-list').data('mini-profile-title').replace('%username%', message.user.name))
                        .attr('data-id', message.user.uuid)
                        .append($('<span>').addClass('badge').addClass('left5').text(message.user.name))
                    )
                    .append('  ');

                // it is an element comment
                if (message.uuid) {
                    var breakNow = false;
                    var elementNameLength = 30;
                    var ideaExists = false;
                    var list = "";
                    var itemName = message.name;
                    var itemShortName = itemName.length>elementNameLength?itemName.slice(0,elementNameLength)+'...':itemName;
                    $.each($('#chat-message-list').data('entity-lists'), function(index, value) {
                        gridData = $("#"+value+" .jsgrid-grid-body tbody");

                        var items = $.map(gridData.find("tr"), function(row) {
                            return $(row).data("JSGridItem");
                        });

                        $.each(items, function(index, item) {
                            if (item.id==message.uuid) {
                                ideaExists = true;
                                itemName = item.element.name;
                                itemShortName = itemName.length>elementNameLength?itemName.slice(0,elementNameLength)+'...':itemName;
                                list = value;
                                breakNow = true;
                                return false;
                            }
                        });
                        if (breakNow) {
                            return false;
                        }
                    });
                    user.append($('<span>').text(message.date+' comment on '))
                    if (ideaExists) {
                        user.append($('<span>').addClass('label').addClass('label-warning').css("cursor", "pointer").attr('title', itemName).text(itemShortName))
                        user.find('.label-warning').click(function() {
                            $('#chat-message-list').data('chat-filter-uuid', message.uuid);
                            $('#chat-message-list').jsGrid({
                                headerRowRenderer: function() {
                                    return $('<th colspan="5" class="text-center badge-info" style="padding:5px!important;">')
                                        .text($('#chat-message-list').data('comment-element')+' ')
                                        .append($('<i>').text(itemName))
                                        .append('  ')
                                        .append($('<a>').attr('href', '#')
                                            .attr('title', $('#chat-message-list').data('back-full-chat'))
                                            .html('<i class="fa fa-times-circle w size15em"></i>')
                                            .click(function() {
                                                $('#chat-message-list').data('chat-filter-uuid', '');
                                                $('#chat-message-list').jsGrid({heading: false});
                                                $("#"+list).jsGrid("refresh");
                                                return false;
                                            }));
                                },
                                heading: true
                            });
                            $('#chat-message-list').jsGrid("search");
                            $("#"+list).jsGrid("refresh");
                        });
                    }
                    else {
                        user.append($('<span>').addClass('label').addClass('label-danger').attr('title', itemName+' - '+$('#chat-message-list').data('deleted')).text(itemShortName))
                    }
                    user.append(':');

                }
                else {
                    user.append($('<span>').text(message.date+' '+$('#chat-message-list').data('says')));
                }


                var msgText = message.message;
                var msg = $("<div>").addClass('top5');//.append($('<span>').text(message.message));
                $.each(msgText.split("<br>"), function(index, value) {
                    var span = $('<span>').html(value);
                    span.html(span.text().replace(/\@{1}([a-zA-Z][a-zA-Z0-9_.-]+)/g, '<span class="badge badge-chat">@$1</span> '));
                    msg.append(span)
                        .append($('<br>'));
                });
                if (message.user.me) {
                    user.find('.badge').addClass('self').addClass('white');
                }
                userMiniProfilePopover(user.find('a'));
                user.find('img').click(function() {
                    $('#chat-message').val($('#chat-message').val()+"@"+message.user.name);
                });
                return $("<tr>").append($("<td>").append(user).append(msg));
            }
            else {
                var user = $("<div>").append($('<img style="cursor:pointer;">')
                    .addClass('round5')
                    .attr("src", message.blockedUser.picture)
                    .attr('width', 24)
                    .attr('title', '@'+message.blockedUser.name))
                    .append('  ')
                    .append($('<a>')
                        .attr('href', '/user/profile/mini-profile/'+message.blockedUser.uuid+'/context/'+$('#chat-message-list').data('chat-id'))
                        .attr('data-title', $('#chat-message-list').data('mini-profile-title').replace('%username%', message.blockedUser.name))
                        .attr('data-id', message.blockedUser.uuid)
                        .append($('<span>').addClass('badge').addClass('badge-danger').addClass('left5').text(message.blockedUser.name))
                    )
                    .append('  ');
                if (message.message=='true') {
                    user.append($('<span>').addClass('red').text($('#chat-message-list').data('blocked-user-text')));
                    if (message.blockedUser.me) {
                        $('#chat-message, #chat-add-message-button').attr('disabled', true).attr('placeholder', $('#chat-message-list').data('you-have-been-blocked'));
                        $('#idea-title, #add-new-idea-submit-button, #category-select').attr('disabled', true);
                    }
                }
                else {
                    user.append($('<span>').addClass('green').text($('#chat-message-list').data('unblocked-user-text')));
                    user.find('a span').removeClass('badge-danger').addClass('badge-success');
                    if (message.blockedUser.me) {
                        $('#chat-message, #chat-add-message-button').attr('disabled', false).attr('placeholder', '');
                        $('#idea-title, #add-new-idea-submit-button, #category-select').attr('disabled', false);
                    }
                }
                if (message.blockedUser.me) {
                }
                userMiniProfilePopover(user.find('a'));
                user.find('img').click(function() {
                    $('#chat-message').val($('#chat-message').val()+"@"+message.blockedUser.name);
                });
                return $("<tr>").append($("<td>").append(user));

            }
        },
        filtering: false,
        editing: false,
        sorting: false,
        autoload: true,

        loadIndication:false,

        noDataContent: $('#chat-message-list').data('no-messages'),

        controller: {
            loadData: function(filter) {
                var d = $.Deferred();
                $.ajax({
                    data: {id: $('#chat-message-list').data('chat-filter-uuid')},
                    url: $('#chat-message-list').data('message-list'),
                    dataType: "json"
                }).done(function(result) {
                    if (typeof $('#chat-title').attr('contenteditable')==='undefined' && $('#chat-title').text()!=result.title) {
                        $('#chat-title').text(result.title);
                    }
                    d.resolve(result.messages);
                });
                return d.promise();
            },

        },

        onRefreshed: function(grid) {
            var body = $("#chat-message-list").find('.jsgrid-grid-body');
            body.scrollTop(body.find('table').height());
        },



        fields: [{ title: "Messages" }]
    });

}

/**
 *  Handlers for the UserProfileController -> changePictureAction
 */
function userProfileChangePictureHandlers() {
    $('#layout-picture').attr('src', $('#user-profile-change-picture-picture').attr('src'));
    $('#user-profile-edit-picture').attr('src', $('#user-profile-change-picture-picture').attr('src'));
    $('#user-profile-change-picture-form').submit(function() { 
        var fd = new FormData($('#user-profile-change-picture-form')[0]);
        $("#user-profile-change-picture-save-button > i").removeClass("fa-floppy-o").addClass('fa-spinner').addClass('fa-pulse');
        $.ajax({ 
            data: fd, 
            type: $(this).attr('method'), 
            url: $(this).attr('action'), 
            processData: false,
            contentType: false,
            success: function(response) { 
                $('#user-profile-change-picture').replaceWith(response);
                
            },
        });
        return false; 
    });
}

/**
 *  Handlers for the ProposalController -> deleteAction
 */
function userProfileDeleteHandlers() {
    $('#user-profile-delete-yes').click(function () {
        $(this).prop('disabled', 'disabled').find('i').removeClass("fa-trash-o").addClass('fa-spinner').addClass('fa-pulse');
        $.ajax({ 
            type: 'POST', 
            url: $('#user-profile-delete').data('url'),
            success: function(response) { 
                if (typeof response ==  'object') {
                    $('.modal').modal('hide');
                    window.location.href = response.url;
                }
                else {
                    
                    $('#user-profile-delete').parent().html(response); 
                }
            },
        });
        return false; 
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

    $('#user-settings-form').submit(function() { 
        $('#user-profile-user-settings-save').find('i').removeClass("fa-floppy-o").addClass('fa-spinner').addClass('fa-pulse');
        
        $.ajax({ 
            data: $(this).serialize(), 
            type: $(this).attr('method'), 
            url: $(this).attr('action'), 
            success: function(response) { 
                $('#user-profile-user-settings').parent().html(response);
                if ($('#user-profile-user-settings-refresh').length) {// no error
                    location.reload();
                }
            },
        });
        return false; 
    });

    $('#user-profile-user-settings-delete').click(function(){
        modalDialog('delete-account-dialog', $('#user-profile-user-settings').data('delete-account'), $(this).attr("href"));
        return false;
    });
}

function userProfileUserMailingHandlers() {

    $('#user-profile-user-mailing-cancel').click(function() {
        $(this).find('i').removeClass("fa-undo").addClass('fa-spinner').addClass('fa-pulse');
        $('#user-profile-user-mailing').parent().load($('#user-profile-user-mailing').data('url'));
        return false;
    });

    $('#user-mailing-form').submit(function() { 
        $('#user-profile-user-mailing-save').find('i').removeClass("fa-floppy-o").addClass('fa-spinner').addClass('fa-pulse');
        
        $.ajax({ 
            data: $(this).serialize(), 
            type: $(this).attr('method'), 
            url: $(this).attr('action'), 
            success: function(response) { 
                $('#user-profile-user-mailing').parent().html(response);
            },
        });
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
                    }
                    else {
                        $('a#user-profile-mini-profile-add-remove-contact[data-id="'+ref.data('id')+'"]').addClass('btn-orange').removeClass('btn-primary').html('<i class="fa fa-plus-circle"></i> '+$('#user-profile-mini-profile').data('add-contact'));
                    }
                    if (typeof $('#inbox-my-contacts').data('url') !== 'undefined') {
                        $('#inbox-my-contacts').parent().load($('#inbox-my-contacts').data('url'));
                    }
                }
            }, 'json');
        
        return false;
    });

    $('button#user-profile-mini-profile-new-message').click(function(){
        $('.popover').popover('hide');
        modalDialog('new-message-dialog', $(this).data('dialog-title'), $(this).data('url'), false);
        return false;
    });

    $("button#block-user, button#unblock-user").click(function () {
        var ref = $(this);
        ref.find('i').removeClass("fa-ban").addClass('fa-spinner').addClass('fa-pulse');
        $.post(ref.data("url"), {},
            function (response) {
                if (!response.success) {
                    alert('An error occured. Please retry later.');
                }
                else {
                    ref.find('i').addClass("fa-ban").removeClass('fa-spinner').removeClass('fa-pulse');
                    if (response.added) {
                        $('button#block-user[data-id="'+ref.data('id')+'"]').hide();
                        $('button#unblock-user[data-id="'+ref.data('id')+'"]').show();
                    }
                    else {
                        $('button#block-user[data-id="'+ref.data('id')+'"]').show();
                        $('button#unblock-user[data-id="'+ref.data('id')+'"]').hide();
                    }
                }
            }, 'json');
        
        return false;
    });

}

/**
 *  Handlers for the UserProfileController -> miniProfileAction
 */
function userProfileViewUserInfoHandlers() {

    $("a#user-profile-view-user-info-add-remove-contact").click(function () {
        var ref = $(this);
        ref.find('i').removeClass("fa-plus-square-o").removeClass("fa-minus-square-o").addClass('fa-spinner').addClass('fa-pulse');
        $.get(ref.attr("href"), {},
            function (response) {
                if (!response.success) {
                    alert('An error occured. Please retry later.');
                }
                else {
                    if (response.added) {
                        $('a#user-profile-view-user-info-add-remove-contact[data-id="'+ref.data('id')+'"]').removeClass('btn-orange').addClass('btn-primary').html('<i class="fa fa-minus-circle"></i> '+$('#user-profile-view-user-info').data('remove-contact'));
                    }
                    else {
                        $('a#user-profile-view-user-info-add-remove-contact[data-id="'+ref.data('id')+'"]').addClass('btn-orange').removeClass('btn-primary').html('<i class="fa fa-plus-circle"></i> '+$('#user-profile-view-user-info').data('add-contact'));
                    }
                    if (typeof $('#inbox-my-contacts').data('url') !== 'undefined') {
                        $('#inbox-my-contacts').parent().load($('#inbox-my-contacts').data('url'));
                    }
                }
            }, 'json');
        
        return false;
    });

    $('button#user-profile-view-user-info-new-message').click(function(){
        modalDialog('new-message-dialog', $(this).data('dialog-title'), $(this).data('url'), false);
        return false;
    });
}

function userProfileAdministrationPresentationHandlers() {
    $("#usrPostalcode, #country").on('keyup change', function() {
        getCities($('#administration-register'));
    });
    $('#administration-presentation-form').submit(function() {
        $('#user-profile-presentation-save').find('i').removeClass("fa-floppy-o").addClass('fa-spinner').addClass('fa-pulse');
        $.ajax({ 
            data: $(this).serialize(), 
            type: $(this).attr('method'), 
            url: $(this).attr('action'), 
            success: function(response) { 
                $('#administration-profile-presentation').parent().html(response);
                if ($('#user-profile-presentation-refresh').length) {// no error
                    location.reload();
                }
            },
        });
        return false; 
    });
}


function userProfileUserPresentationHandlers() {
    $("#usrPostalcode, #country").on('keyup change', function() {
        getCities($('#user-profile-presentation'));
    });
    $('#user-profile-presentation-form').submit(function() {
        $('#user-profile-presentation-save').find('i').removeClass("fa-floppy-o").addClass('fa-spinner').addClass('fa-pulse');
        $.ajax({ 
            data: $(this).serialize(), 
            type: $(this).attr('method'), 
            url: $(this).attr('action'), 
            success: function(response) { 
                $('#user-profile-presentation').parent().html(response);
                if ($('#user-profile-presentation-refresh').length) {// no error
                    location.reload();
                }
            },
        });
        return false; 
    });
}
/**
 *  Handlers for the UserRegistrationController -> administrationRegistrationAction
 */
function userRegistrationAdministrationRegistrationHandlers() {
    getCities($('#administration-register'));
    getRegions($('#administration-register').data('getregions'));
    changeAdminLevel();
    $("#usrPostalcode").on('keyup change', function() {
        getCities($('#administration-register'));
    });
    $("#adminPostalcode").on('keyup change', function() {
        getCities($('#administration-register'), '#adminPostalcode', '#adminCity');
    });
    $("#country").on('keyup change', function() {
        getCities($('#administration-register'));
        getRegions($('#administration-register').data('getregions'));
        getCities($('#administration-register'), '#adminPostalcode', '#adminCity');
    });
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

    $('#user-profile-partner-presentation-form').submit(function() { 
        $("#partner-profile-partner-presentation-save > i").removeClass("fa-floppy-o").addClass('fa-spinner').addClass('fa-pulse');
        $.ajax({ 
            data: $(this).serialize(), 
            type: $(this).attr('method'), 
            url: $(this).attr('action'), 
            success: function(response) { 
                $('#partner-profile-partner-presentation').parent().html(response);
            },
        });
        return false; 
    });
    $('#partner-profile-partner-presentation-reset').click(function() {
        $("#partner-profile-partner-presentation-reset > i").removeClass("fa-undo").addClass('fa-spinner').addClass('fa-pulse');
        $('#partner-profile-partner-presentation').parent().load($(this).attr('href'));
        return false;
    });

}
/**
 *  Handlers for the UserRegistrationController -> indexAction
 */
function userRegistrationUserRegistrationHandlers() {

    $('#user-registration-form').submit(function() {
        $('#user-registration-submit').prop('disabled', 'disabled').find('i').removeClass("fa-sign-in").addClass('fa-spinner').addClass('fa-pulse');
        
        $.ajax({ 
            data: $(this).serialize(), 
            type: $(this).attr('method'), 
            url: $(this).attr('action'), 
            success: function(response) { 
                $('.modal-body').html(response);
            }

        });
        return false; 
    });
}


function userRegistrationFacebookRegistrationHandlers() {

    $('#add-user-facebook-form').submit(function() {
        var ref = $(this);
        $('#facebook-registration-submit').prop('disabled', 'disabled').find('i').removeClass("fa-sign-in").addClass('fa-spinner').addClass('fa-pulse');
        
        $.ajax({ 
            data: $(this).serialize(), 
            type: $(this).attr('method'), 
            url: $(this).attr('action'), 
            success: function(response) { 
                if (typeof response ==  'object') {

                    window.location = $('#facebook-registration').data('facebook-login-url');
                }
                else {
                    $('.modal-body').html(response);
                }

            }

        });
        return false; 
    });
}


function userRegistrationUserCityHandlers() {
    $("#user-city-country, #user-city-usrPostalcode").on('keyup change paste', function() {
        getCities($('#user-city'), $('#user-city-usrPostalcode'), $('#user-city-city'), $('#user-city-country'), '#cityLoad');
    });

    $('#add-user-city-form').submit(function() { 
        $('#user-city-submit').prop('disabled', 'disabled').find('i').removeClass("fa-save").addClass('fa-spinner').addClass('fa-pulse');
        
        $.ajax({ 
            data: $(this).serialize(), 
            type: $(this).attr('method'), 
            url: $(this).attr('action'), 
            success: function(response) { 
                if (typeof response ==  'object') {

                    location.reload();
                }
                else {
                    $('.modal-body').html(response);
                }
            },
        });
        return false; 
    });
}

function userRegistrationUserNameHandlers() {

    $('#add-user-name-form').submit(function() { 
        $('#user-name-submit').prop('disabled', 'disabled').find('i').removeClass("fa-save").addClass('fa-spinner').addClass('fa-pulse');
        
        $.ajax({ 
            data: $(this).serialize(), 
            type: $(this).attr('method'), 
            url: $(this).attr('action'), 
            success: function(response) { 
                if (typeof response ==  'object') {
                    $('.modal').modal('hide');
                }
                else {
                    $('.modal-body').html(response);
                }
            },
        });
        return false; 
    });
}


/**
 *  Handlers for the UserRegistrationController -> indexAction
 */
function userRegistrationPleaseRegisterHandlers() {


    $('#user-register-please-register-register-button').click(function () {
        modalDialog('user-registration', $(this).data("dialog-title"), $(this).attr("href"), false);
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
            $('#changeVoteBtn').attr('disabled', 'disabled');
        }
    });
    $('#voteForm').submit(function() { 
        $("#add-change-vote-div-icon").removeClass("fa-sticky-note-o").addClass('fa-spinner').addClass('fa-pulse');
        $.ajax({ 
            data: $(this).serialize(), 
            type: $(this).attr('method'), 
            url: $(this).attr('action'), 
            success: function(response) { 
                $('#add-change-vote-div').parent().html(response);
            }
        });
        return false; 
    });
}
