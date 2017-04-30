<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

return array(
    'acl' => array(
        'roles' => array(
            'guest'   => null,
            'member'  => 'guest',
            'partner' => 'member',
            'administration' => 'member',
            'admin'  => 'administration',
        ),
        'resources' => array(
            'allow' => array(
                'DGIModule\Controller\Admin' => array(
                    'all'	         => 'admin',
                ),
                'DGIModule\Controller\Blog' => array(
                    'all'	         => 'guest',
                ),
                'DGIModule\Controller\Administration' => array(
                    'all'	         => 'administration',
                ),
                'DGIModule\Controller\Banner' => array(
                    'all'	         => 'administration',
                    'carousel-banners' => 'member'
                ),
                'DGIModule\Controller\Category' => array(
                    'all'     => 'admin',
                    'get-categories'	=> 'member',
                    'get-subcategories'	=> 'member',
                    'get-all-categories'	=> 'guest',
                    'json-list' => 'guest'
                ),
                'DGIModule\Controller\Chat' => array(
                    'all'	         => 'member',
                    'message-list' => 'guest'
                ),
                'DGIModule\Controller\Comment' => array(
                    'all'	         => 'member',
                    'list' => 'guest'
                ),
                'DGIModule\Controller\CommentThumb' => array(
                    'add'	=> 'member',
                    'remove'	=> 'member',
                ),
                'DGIModule\Controller\Country' => array(
                    'index'	=> 'guest',
                ),
                'DGIModule\Controller\Region' => array(
                    'index'	=> 'member',
                ),
                'DGIModule\Controller\Dashboard' => array(
                    'all'	=> 'member',
                ),
                'DGIModule\Controller\PartnerDashboard' => array(
                    'all'	=> 'member', 
                ),
                'DGIModule\Controller\AccountWorkspace' => array(
                    'all'	=> 'member', 
                ),
                'DGIModule\Controller\AdministrationProfile' => array(
                    'all'	=> 'administration', 
                    'view' => 'member'
                ),
                'DGIModule\Controller\Department' => array(
                    'get-departments'	=> 'member',
                ),
                'DGIModule\Controller\Error' => array( 
                    'all'	=> 'guest',
                ),
                'DGIModule\Controller\Email' => array(
//                    'email-validation'	=> 'guest',
//                    'change-password' => 'guest',
//                    'admin-digest' => 'guest',
//                    'index' => 'admin'
                    'all' => 'guest',
                ),
                'DGIModule\Controller\Event' => array(
                    'all'	         => 'member',
                    'all-events'    => 'guest',
                    'view-event' => 'guest',
                ),
                'DGIModule\Controller\Execution' => array(
                    'all'	=> 'member',
                ),
                'DGIModule\Controller\Inbox' => array( 
                    'all'    => 'member',
                    'list-received'	        => 'guest',
                    'list-sent'	            => 'guest',
                    'list-search'	        => 'guest',
                    'list-trash'	        => 'guest',
                    'my-contacts'           => 'guest',
                ),
                'DGIModule\Controller\Index' => array( 
                    'all'	=> 'guest',
                ),
                'DGIModule\Controller\Location' => array(
                    'all'	=> 'guest',
                ),
                'DGIModule\Controller\Measure' => array(
                    'all'	            => 'member',
                    'publish-measure'   => 'administration',
                    'draft-measures'    => 'administration',
                    'claim-ownership'   => 'administration',
                    'view-measure'      => 'guest',
                    'view-history'      => 'guest',
//                    'country-measures'  => 'guest',
                    'all-measures'      => 'guest'
                ),
                'DGIModule\Controller\News' => array(
                    'all'	=> 'guest',
                ),
                'DGIModule\Controller\Newsletter' => array(
                    'all'	         => 'administration',
                    'view-newsletter' => 'member'
                ),
                'DGIModule\Controller\Pages' => array( 
                    'all'	=> 'guest',
                ),
                'DGIModule\Controller\PartnerProfile' => array( 
                    'all'	           => 'partner',
                    'mini-profile'	       => 'member',
                    'view'	       => 'member',
                ),
                'DGIModule\Controller\Program' => array( 
                    'all'           => 'member',
                    'view-program'  => 'guest',
                    'view-aggregated-program'  => 'guest',
                    'all-programs'  => 'guest',
                ),
                'DGIModule\Controller\Proposal' => array(
                    'all'	            => 'member',
                    'view'	            => 'guest',
                    'all-proposals'     => 'guest',
                    'my-favorites'     => 'guest',
                    'my-proposals'     => 'guest',
                    'user-proposals'    => 'guest',
                    'check-proposals' => 'guest',
                    'json-proposals' => 'guest',
                ),
                'DGIModule\Controller\Referendum' => array(
                    'all'	=> 'member',
                    'index' => 'guest',
                    'register' => 'guest',

                ),
                'DGIModule\Controller\Report' => array(
                    'all'	=> 'member',
                ),
                'DGIModule\Controller\Session' => array(
                    'all'	=> 'member',
					'my-sessions'    => 'guest',
                    'view-session' => 'guest',
                    'idea-list' => 'guest'
                ),
                'DGIModule\Controller\UserLogin' => array( 
                    'all'	    => 'guest',
                    'logout'    => 'member',
                ),
                'DGIModule\Controller\UserProfile' => array( 
                    'all'               => 'member',
                    'mini-profile'      => 'guest',
                    'view'              => 'guest',  
                    'update-counters'   => 'guest',
                ),
                'DGIModule\Controller\UserSettings' => array( 
                    'all'	=> 'member',
                ),
                'DGIModule\Controller\UserRegistration' => array( 
                    'all' => 'guest',
                    'activate-administration-account' => 'admin'
                ),
                'DGIModule\Controller\Vote' => array(
                    'all'	=> 'member',
                ),
            )
        )
    )
);
