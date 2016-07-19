<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

return array(
    'controllers' => array(
        'invokables' => array(
            'DGIModule\Controller\Proposal'         => 'DGIModule\Controller\ProposalController',
            'DGIModule\Controller\Measure'          => 'DGIModule\Controller\MeasureController',
            'DGIModule\Controller\Program'          => 'DGIModule\Controller\ProgramController',
            'DGIModule\Controller\Comment'          => 'DGIModule\Controller\CommentController',
            'DGIModule\Controller\CommentThumb'     => 'DGIModule\Controller\CommentThumbController',
            'DGIModule\Controller\UserLogin'        => 'DGIModule\Controller\UserLoginController',
            'DGIModule\Controller\UserRegistration' => 'DGIModule\Controller\UserRegistrationController',
            'DGIModule\Controller\UserProfile'      => 'DGIModule\Controller\UserProfileController',
            'DGIModule\Controller\PartnerProfile'   => 'DGIModule\Controller\PartnerProfileController',
            'DGIModule\Controller\AdministrationProfile'   => 'DGIModule\Controller\AdministrationProfileController',
            'DGIModule\Controller\Inbox'            => 'DGIModule\Controller\InboxController',
            'DGIModule\Controller\Location'         => 'DGIModule\Controller\LocationController',
            'DGIModule\Controller\Banner'           => 'DGIModule\Controller\BannerController',
            'DGIModule\Controller\Newsletter'       => 'DGIModule\Controller\NewsletterController',
            'DGIModule\Controller\Event'            => 'DGIModule\Controller\EventController',
            'DGIModule\Controller\Dashboard'        => 'DGIModule\Controller\DashboardController',
            'DGIModule\Controller\PartnerDashboard' => 'DGIModule\Controller\PartnerDashboardController',
            'DGIModule\Controller\AccountWorkspace' => 'DGIModule\Controller\AccountWorkspaceController',
            'DGIModule\Controller\News'             => 'DGIModule\Controller\NewsController',
            'DGIModule\Controller\Report'           => 'DGIModule\Controller\ReportController',
            'DGIModule\Controller\Vote'             => 'DGIModule\Controller\VoteController',
            'DGIModule\Controller\Category'         => 'DGIModule\Controller\CategoryController',
            'DGIModule\Controller\Index'            => 'DGIModule\Controller\IndexController',
            'DGIModule\Controller\Pages'            => 'DGIModule\Controller\PagesController',
            'DGIModule\Controller\Error'            => 'DGIModule\Controller\ErrorController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'city' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/city', 
                    'defaults' => array(
                        '__NAMESPACE__' => 'DGIModule\Controller',
                        'controller'    => 'Dashboard',
                        'action'        => 'city-dashboard',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'event' => array( // TODO: city/event
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/event[/:action[/:id]][/page/:page][/sort/:sort][/order/:order][/results/:results][/publish/:publish][/date/:month/:year]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'DGIModule\Controller',
                                'controller'    => 'Event', 
                                'action' => 'view-event',
                                'publish'   => false
                            ),
                            'constraints' => array(
                                'action'     => '(view-attendees|attend-event|view-event|city-events|upcoming-events|add-event|my-events|edit-event|publish-event|cancel-event|delete-event|duplicate-event|search-events)',
                                'id'     	 => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}',
                                'page' => '[0-9]*',
                                'month' => '1[0-2]|[1-9]',
//                                 'year' => '^\d{4}?$',
                                'filter' => '(none|new_comment|new_step|champion_news|private_message)', 
                                'sort' => '(name|status|start_date|end_date|type)',
                                'order' => '(asc|desc)'
                            ),
                        ),
                    ),
                )
            ),
            'proposal' => array( // @todo Proposals
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/proposal[/:action][/:id][/page/:page][/sort/:sort][/order/:order][/filter/:filter][/results/:results][/level/:level][/publish/:publish]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'DGIModule\Controller',
                        'controller'    => 'Proposal',
                        'action'        => 'view',
                        'publish'   => false,
                    ),
                    'constraints' => [
                        'action' => '(prolong-debate|check-proposals|user-proposals|index|add-proposal|view|edit-proposal|publish-proposal|delete|all-proposals|my-proposals|my-favorites|favorite|top-proposals|status|status-details)', /*|add-favorite|remove-favorite*/
                        'id' => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}',
                        'page' => '[0-9]*',
                        'sort' => '(name|user|category|created-date|votes|vote-average|draft|published|status|level)',
                        'order' => '(asc|desc)',
                        'results' => '[0-9]*',
                        'filter' => '(none|city|region|country)',
                         'level' => '(city|region|country)'
                    ]
                ),
            ),
            'measure' => array( // TODO: administration/measure
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/measure[/:action][/:id][/page/:page][/sort/:sort][/order/:order][/results/:results][/publish/:publish]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'DGIModule\Controller',
                        'controller'    => 'Measure',
                        'action'        => 'view',
                        'publish'   => false,
                    ),
                    'constraints' => [
                        'action' => '(claim-ownership|view-history|edit-measure|draft-measures|all-measures|add-measure|view-measure|publish-measure|delete-measure)',
                        'id' => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}',
                        'page' => '[0-9]*',
                        'sort' => '(name|user|category|created-date|votes|vote-average|draft|published-date|created-date|status)',
                        'order' => '(asc|desc)',
                        'results' => '[0-9]*',
                    ]
                ),
            ),
            'program' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/program[/:action][/:id][/proposal/:proposal][/page/:page][/sort/:sort][/order/:order][/filter/:filter][/results/:results][/ajax/:ajax][/level/:level]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'DGIModule\Controller',
                        'controller'    => 'Program',
                        'action'        => 'view-program',
                    ),
                    'constraints' => [
                        'action' => '(sort-proposals|get-categories-count|add-proposals-from-city|add-remove-proposal|get-proposals|add-proposal|edit-program|add-program|view-program|view-aggregated-program|delete-program|my-programs|user-programs|all-programs)',
                        'id' => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}',
                        'proposal' => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}',
                        'page' => '[0-9]*',
                        'sort' => '(name|owner|category|created_date|saved_date|published-date|status|priority)',
                        'order' => '(asc|desc)',
                        'results' => '[0-9]*',
                        'filter' => '(included|proposal-not-included|my-proposals|none|measures-not-included)',
                        'level' => '(city|region|country)'
                    ]
                ),
            ),
            'comment' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/comment/:type/:id/:action[/actions/:actions][/page/:page][/sort/:sort][/order/:order][/results/:results][/ajax/:ajax][/com/:com]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'DGIModule\Controller',
                        'controller'    => 'Comment',
                        'action'        => 'index',
                        'sort'          => 'published',
                        'order'          => 'desc',
                        'results'         => 5,
                        'ajax'  => true,
                        'actions' => 'true'
                    ),
                    'constraints' => [
                        'action' => '(create-comment|add-comment|list)',
                        'type' => '(program|proposal|comment)',
                        'id' => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}',
                        'page' => '[0-9]*',
                        'sort' => '(name|user|createdDate|published|votes)',
                        'order' => '(asc|desc)',
                        'results' => '[0-9]*',
                    ]
                ),
            ),
            'comment-thumb' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/comment/:id/thumb[/:action][/:type]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'DGIModule\Controller',
                        'controller'    => 'CommentThumb',
                        'action'        => 'add',
                    ),
                    'constraints' => [
                        'action' => '(add|remove)',
                        'id' => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}',
                        'type' => '(up|down)',
                    ]
                ),
            ),
            'event' => array( // TODO: city/event
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/event[/:action[/:id]][/page/:page][/sort/:sort][/order/:order][/results/:results][/publish/:publish][/date/:month/:year]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'DGIModule\Controller',
                        'controller'    => 'Event',
                        'action' => 'view-event',
                        'publish'   => false
                    ),
                    'constraints' => array(
                        'action'     => '(view-attendees|attend-event|view-event|city-events|all-events|upcoming-events|add-event|my-events|edit-event|publish-event|cancel-event|delete-event|duplicate-event|search-events)',
                        'id'     	 => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}',
                        'page' => '[0-9]*',
                        'month' => '1[0-2]|[1-9]',
                        'filter' => '(none|new_comment|new_step|champion_news|private_message)',
                        'sort' => '(name|status|start_date|end_date|type)',
                        'order' => '(asc|desc)'
                    ),
                ),
            ),
            'news' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/news[/:id][/page/:page][/filter/:filter][/results/:results]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'DGIModule\Controller',
                        'controller'    => 'News',
                        'action'        => 'all-news',
                    ),
                    'constraints' => [
                        'action' => '(all-news|create-news)',
                        'id' => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}',
                        'page' => '[0-9]*',
                        'filter' => '(none|new_measure|new_proposal|implementation_phase|completed_proposal|deleted_proposal|task_suggestion|updated_payement)', //new_scenario|updated_scenario|
                        'results' => '[0-9]*',
                    ]
                ),
            ),
            'country' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/country[/]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'DGIModule\Controller',
                        'controller'    => 'Dashboard',
                        'action'        => 'country-dashboard',
                    ),
                ),
            ),
            'browse' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/browse[/:action][/:country[/region/:region[/city/:postalcode/:cityname]]]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'DGIModule\Controller',
                        'controller'    => 'Index',
                        'action'        => 'browse',
                    ),
                    'constraints' => [
                        'action' => '(browse|browse-content)',
                    ]
                ),
            ),
            'vote' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/vote/:action/:id[/terminal/:terminal][/text/:text]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'DGIModule\Controller',
                        'controller'    => 'Vote',
                        'action'        => 'add',
                        'terminal' => true,
                        'text' => true,
                    ),
                    'constraints' => [
                       'action' => '(add|view)',
                       'id' => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}',
                    ]
                ),
            ),
            // @todo: partner
            'partner' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/partner',
                    'defaults' => array(
                        '__NAMESPACE__' => 'DGIModule\Controller',
                        'controller'    => 'UserLogin',
                        'action'        => 'login',
                    ),
                    'constraints' => array(
                        'id'     	 => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}',
                        'uuid'     	 => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}',
                        'page' => '[0-9]*',
                        'filter' => '(none|new_comment|new_step|champion_news|private_message)'
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'dashboard' => array( 
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/dashboard[/:action[/:id]][/type/:type][/uuid/:uuid][/page/:page][/filter/:filter][/sort/:sort][/order/:order]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'DGIModule\Controller',
                                'controller'    => 'PartnerDashboard',
                                'action'        => 'index',
                                'type'          => 0,
                                'uuid'          => '00000000-0000-0000-0000-000000000000',
                                'filter'        => 'none',
                                'sort'          => 'name',
                                'order'          => 'desc',
                            ),
                            'constraints' => array(
                                'action'     => '(search-opportunities-form|inbox|news|citizen-proposals|ongoing-projects|view|find-partners|search-opportunities)',
                                'id'     	 => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}',
                                //'to'     	 => '[a-zA-Z0-9_-+]*',
                                'uuid'     	 => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}',
                                'page' => '[0-9]*',
                                'filter' => '(none|new_comment|new_step|champion_news|private_message)', // @todo to modify
                                'sort' => '(pertinence|name|user|category|city|published|votes|scenario)', // TODO 
                                'order' => '(asc|desc)'
                            ),
                        ),
                    ),
                    'profile' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/profile[/:action[/:id]][/page/:page][/sort/:sort][/order/:order][/results/:results][/ajax/:ajax]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'DGIModule\Controller',
                                'controller'    => 'PartnerProfile',
                                'action'        => 'view',
                                'sort'          => 'name',
                                'order'          => 'asc',
                                'action'        => 'view',
                                'results'         => 5,
                                'ajax'          => true
                            ),
                            'constraints' => array(
                                'action'     => '(partner-account-settings|partner-contact|partner-presentation|mini-profile|partner-profile|contact|edit-profile|view|select-categories|select-regions)',// change-password|change-email|change-city|
                                'id'     	 => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}',
                                'page' => '[0-9]*',
                                'sort' => '(name|user|category|created-date|votes|draft|published-date|status)',
                                'order' => '(asc|desc)',
                                'results' => '[0-9]*',
                            ),
                        ),
                    ),
                ),
            ),
            // TODO: administration
            'administration' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/administration',
                    'defaults' => array(
                        '__NAMESPACE__' => 'DGIModule\Controller',
                        'controller'    => 'UserLogin',
                        'action'        => 'login',
                    ),
                    'constraints' => array(
                        'id'     	 => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}',
                        'uuid'     	 => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}',
                        'page' => '[0-9]*',
                        'filter' => '(none|new_comment|new_step|champion_news|private_message)'
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    // TODO: administration/profile 
                    'profile' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/profile[/:action[/:id]][/page/:page][/sort/:sort][/order/:order][/results/:results][/ajax/:ajax]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'DGIModule\Controller',
                                'controller'    => 'AdministrationProfile',
                                'action'        => 'view',
                                'action'        => 'view',
                                'ajax'          => true
                            ),
                            'constraints' => array(
                                'action'     => '(view-user-info|partner-account-settings|partner-contact|partner-presentation|mini-profile|administration-profile|contact|edit-profile|view|select-categories|select-regions)',// change-password|change-email|change-city|
                                'id'     	 => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}',
                                'page' => '[0-9]*',
                                'sort' => '(name|user|category|created-date|votes|draft|published-date|status)',
                                'order' => '(asc|desc)',
                                'results' => '[0-9]*',
                            ),
                        ),
                    ),
                    'banner' => array( // TODO: administration/banner
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/banner[/:action[/:id]][/page/:page][/sort/:sort][/order/:order][/results/:results][/publish/:publish]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'DGIModule\Controller',
                                'controller'    => 'Banner',
                                'publish'   => false
                            ),
                            'constraints' => array(
                                'action'     => '(my-banners|carousel-banners|sort-active-banners|active-banners|inactive-banners|add-banner|edit-banner|publish-banner|delete-banner|duplicate-banner)',
                                'id'     	 => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}',
                                'page' => '[0-9]*',
                                'sort' => '(name)',
                                'order' => '(asc|desc)'
                            ),
                        ),
                    ),
                    'newsletter' => array( // TODO: administration/newsletter
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/newsletter[/:action[/:id]][/page/:page][/sort/:sort][/order/:order][/results/:results][/publish/:publish]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'DGIModule\Controller',
                                'controller'    => 'Newsletter',
                                'type'          => 0,
                                'publish'   => false
                            ),
                            'constraints' => array(
                                'action'     => '(view-newsletter|add-newsletter|my-newsletters|edit-newsletter|publish-newsletter|delete-newsletter|duplicate-newsletter)',
                                'id'     	 => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}',
                                'page' => '[0-9]*',
                                'sort' => '(name|status|created_date)',
                                'order' => '(asc|desc)'
                            ),
                        ),
                    ),
                ),
            ),
            'user' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/user',
                    'defaults' => array(
                        '__NAMESPACE__' => 'DGIModule\Controller',
                        'controller'    => 'UserLogin',
                        'action'        => 'login',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'inbox' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/inbox[/:action[/:id]][/to/:to][/type/:type][/uuid/:uuid][/sk/:sk][/sr/:sr][/ss/:ss][/st/:st][/sm/:sm][/page/:page][/sort/:sort][/order/:order][/filter/:filter][/results/:results]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'DGIModule\Controller',
                                'controller'    => 'Inbox',
                                'action'        => 'list',
                                'type'          => 0,
                                'uuid'          => '00000000-0000-0000-0000-000000000000',
                                'sk' => '', // search keywords
                                'sr' => 1, // search receiver
                                'ss' => 1, // search sender
                                'st' => 1, // search title/subject
                                'sm' => 1, // search message
                            ),
                            'constraints' => array(
                                'action'     => '(forward|reply-all|reply|list-search|delete-selected|delete-one|list-trash|list-sent|get-contacts|add-remove-contact|my-inbox|new-message|create-message|view|list-received|my-contacts)',
                                'id'     	 => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}',
                                'uuid'     	 => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}',
                                'page' => '[0-9]*',
                                'sort' => '(name)',
                                'filter' => '(none|new_comment|private_message|unread|newsletter)',//new_step|champion_news|
                                'results' => '[0-9]*',
                            ),
                        ),
                    ),
                    'profile' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/profile[/:action[/:id]][/page/:page][/sort/:sort][/order/:order][/results/:results][/ajax/:ajax][/list/:list]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'DGIModule\Controller',
                                'controller'    => 'UserProfile',
                                'action'        => 'view',
                                'page'          => 1,
                                'sort'          => 'name',
                                'order'          => 'asc',
                                'action'        => 'view',
                                'results'         => 5,
                                'ajax'          => true,
                                'list'          => false,
                                
                            ),
                            'constraints' => array(
                                'action'     => '(view-my-activity|get-scores|get-user-notes|update-counters|change-picture|mini-profile|user-profile|user-settings|edit-info|contact|user-presentation|delete|view|view-user-info)',
                                'id'     	 => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}', 
                                'page' => '[0-9]*',
                                'sort' => '(name|user|category|created-date|votes|draft|published-date|status)',
                                'order' => '(asc|desc)',
                                'results' => '[0-9]*',
                            ),
                        ),
                    ),
                    
                ),
            ),
            'captcha_form_generate' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    =>  '/captcha/[:id]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'DGIModule\Controller',
                        'controller'    => 'UserRegistration',
                        'action'     => 'generate',
                    ),
                ),
            ),
            
            'location' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/location[/[:action[/:id]]][/country/:country][/city/:city]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'DGIModule\Controller',
                        'controller'    => 'Location',
                        'action'        => 'index',
                    ),
                    'constraints' => [
                        'action' => '(get-regions|get-cities|cities|get-departments|add-city)',
                        'id' => '[0-9]+',
                    ]
                ),
            ),
           // TODO: home
            'home' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'DGIModule\Controller',
                        'controller' => 'Index',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'login' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => 'login[/[:action[/[:id]]]]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'DGIModule\Controller',
                                'controller'    => 'UserLogin',
                                'action'        => 'login',
                            ),
                            'constraints' => array(
                                'action'     => '(index|login|logout)',
                                'id'     	 => '[a-zA-Z0-9_-]*',
                            ),
                        ),
                    ),
                    'report' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => 'report/:action[/:type/:id]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'DGIModule\Controller',
                                'controller'    => 'Report',
                                'action'        => 'add-report',
                            ),
                            'constraints' => [
                                'action' => '(add-report|submit-bug)',
                                'type' => '(program|proposal|comment|inbox)',
                                'id' => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}',
                            ]
                        ),
                    ),
                    'partner-register' => array(
                        'type'    => 'Literal',
                        'options' => array(
                            'route'    => 'partner-register',
                            'defaults' => array(
                                '__NAMESPACE__' => 'DGIModule\Controller',
                                'controller'    => 'UserRegistration',
                                'action'        => 'partner-registration',
                            ),
                            'constraints' => array(
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     	 => '[a-zA-Z0-9_-]*',
                            ),
                        ),
                    ),
                    'administration-register' => array(
                        'type'    => 'Literal',
                        'options' => array(
                            'route'    => 'administration-register',
                            'defaults' => array(
                                '__NAMESPACE__' => 'DGIModule\Controller',
                                'controller'    => 'UserRegistration',
                                'action'        => 'administration-registration',
                            ),
                            'constraints' => array(
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     	 => '[a-zA-Z0-9_-]*',
                            ),
                        ),
                    ),
                    'user-register' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => 'user-register[/:action[/:id]]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'DGIModule\Controller',
                                'controller'    => 'UserRegistration',
                                'action'        => 'user-registration',
                            ),
                            'constraints' => array(
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'     	 => '[a-zA-Z0-9_-]*',
                            ),
                        ),
                    ),
                    'pages' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => 'pages[/:action]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'DGIModule\Controller',
                                'controller'    => 'Pages',
                                'action'        => 'about',
                            ),
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ]
                        ),
                    ),
                    'error' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => 'error[/:action][/dialog/:dialog][/message/:message]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'DGIModule\Controller',
                                'controller'    => 'Error',
                                'action'        => 'index',
                                'dialog'        => false,
                            ),
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ]
                        ),
                    ),
                    'category' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => 'category[/:action][/:id][/country/:country][/level/:level]',
                            'defaults' => array(
                                '__NAMESPACE__' => 'DGIModule\Controller',
                                'controller'    => 'Category',
                                'action'        => 'index',
                                'id'            => 0,
                                'country'       => 73,
                            ),
                            'constraints' => [
                                'action' => '(index|edit|add|delete|get-subcategories|get-categories)',
                                'id' => '[0-9]+',
                                'level' => '(city|region|country)'
                            ]
                        ),
                    ),
                ),
            ),
            'region' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/region[/:action]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'DGIModule\Controller',
                        'controller'    => 'Dashboard',
                        'action'        => 'region-dashboard',
                    ),
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ]
                ),
            ),
            
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
             'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
             'Zend\Log\LoggerAbstractServiceFactory',
             'Zend\Form\FormAbstractServiceFactory',
        ),
    ),
    'translator' => array(
        'locale' => 'en',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
                'text_domain' => 'DGIModule',
            ),
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => false,
        'display_exceptions'       => false,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'no_access_template'       => 'error/403',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/403'               => __DIR__ . '/../view/error/403.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    'view_helpers' => array(
        'invokables'=> array(
            'SortingDivPaginationHelper' => 'DGIModule\View\Helper\SortingDivPaginationHelper'        
        )
    ),
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
    'doctrine' => array(
        'authentication' => array( 
            'orm_default' => array(
                'object_manager' => 'Doctrine\ORM\EntityManager',
                'identity_class' => 'DGIModule\Entity\User', 
                'identity_property' => 'usrName', // 'username', 
                'credential_property' => 'usrPassword', // 'password',
            ),
        ),
       /* 'cache' => array(
            'class' => 'Doctrine\Common\Cache\ApcCache'
        ),
        'configuration' => array(
            'orm_default' => array(
                // 'generate_proxies' => false,
                'metadata_cache'    => 'apc',
                'query_cache'       => 'apc',
                'result_cache'      => 'apc',
            )
        ),*/
        'driver' => array(
            'DGI_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/DGIModule/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'DGIModule\Entity' =>  'DGI_driver'
                ),
            ),
        ),
        'configuration' => array(
            'orm_default' => array(
                'string_functions' => array(
                     'regexp' => 'DoctrineExtensions\Query\Mysql\Regexp',
                    'ifelse' => 'DoctrineExtensions\Query\Mysql\IfElse',
                    'dateformat' => 'DoctrineExtensions\Query\Mysql\DateFormat',
                ),
                'datetime_functions' => array(
                    'datediff' => 'DoctrineExtensions\Query\Mysql\DateDiff',
                    'datesub' => 'DoctrineExtensions\Query\Mysql\DateSub',
                    'dateadd' => 'DoctrineExtensions\Query\Mysql\DateAdd',
                )
            )
        )
    ),
);
