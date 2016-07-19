<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\View\Helper;

use Zend\View\Helper\AbstractHelper;

class SortingDivPaginationHelper extends AbstractHelper
{
    private $resultsPerPage;
    private $totalResults;
    private $results;
    private $baseUrl;
    private $paging;
    private $page;
    private $sort;
    private $order;
    private $div;
    private $filter;
    private $post;

    public function __invoke($pagedResults, $page, $baseUrl, $div = "", $resultsPerPage=null, $sort='name', $order='asc', $filter=null, $post='')
    {
        $this->resultsPerPage = $resultsPerPage;
        $this->totalResults = count($pagedResults);
        $this->results = $pagedResults;
        $this->baseUrl = $baseUrl;
        $this->page = $page;
        $this->sort = $sort;
        $this->order = $order;
        $this->div = $div;
        $this->filter = $filter;
        $this->post = $post;
        return $this->generatePaging();
    }

    /**
     * Generate paging html
     */
    private function generatePaging()
    {
        # Get total page count
        $pages = $this->resultsPerPage=='all'?1:(ceil($this->totalResults / ($this->resultsPerPage?$this->resultsPerPage:5)));
        
        $antet = '<div class="row"><div class="col-md-12">';
        $perPage = '<div class="fltr right10 top5">';
        if ($this->totalResults>0) {
            $perPage .=  (( $this->resultsPerPage==5 || $this->totalResults <= 5)?'See: <span class="badge">5</span>':
        
                		            'See: <a id="page-'.$this->div
                                    .'" href="' . $this->baseUrl . "/page/1"
                                    .($this->sort?"/sort/".$this->sort:"")
                                    .($this->order?"/order/".$this->order:"")
                                    .($this->filter?"/filter/".$this->filter:"")
                                    .("/results/5")
                                    .$this->post
                                    .'">5</a> ');
        }
        if ($this->totalResults>5) {
            $perPage  .= (( $this->resultsPerPage==10 || ($this->totalResults <= 10 && $this->resultsPerPage > 10))?' <span class="badge">10</span>':
                                    ' <a id="page-'.$this->div
                                    .'" href="' . $this->baseUrl . "/page/1"
                                        .($this->sort?"/sort/".$this->sort:"")
                                        .($this->order?"/order/".$this->order:"")
                                        .($this->filter?"/filter/".$this->filter:"")
                                        .("/results/10")
                                        .$this->post
                                        .'">10</a> ');
        }
        if ($this->totalResults>10) {
           $perPage  .= (( $this->resultsPerPage==20 || ($this->totalResults <= 20 && $this->resultsPerPage > 20))?' <span class="badge">20</span>':
            ' <a id="page-'.$this->div
            .'" href="' . $this->baseUrl . "/page/1"
                .($this->sort?"/sort/".$this->sort:"")
                .($this->order?"/order/".$this->order:"")
                .($this->filter?"/filter/".$this->filter:"")
                .("/results/20")
                .$this->post
                .'">20</a> ');
        }
        if ($this->totalResults>20) {
            $perPage  .= (( $this->resultsPerPage==50 || ($this->totalResults <= 50 && $this->resultsPerPage > 50))?' <span class="badge">50</span>':
                ' <a id="page-'.$this->div
                .'" href="' . $this->baseUrl . "/page/1"
                .($this->sort?"/sort/".$this->sort:"")
                .($this->order?"/order/".$this->order:"")
                .($this->filter?"/filter/".$this->filter:"")
                .("/results/50")
                .$this->post
                .'">50</a> ');
        }
        
        $perPage .=  '</div></div></div>';

        # Don't show pagination if there's only one page
        if($pages <= 1)
        {
            return $antet.$perPage;
                		          
        }
        
        $this->paging = '<ul class="pagination pagination-sm">';
        
        if ($this->page != 1) {
            $this->paging .= '<li><a id="page-'.$this->div
                                        .'" href="' . $this->baseUrl . "/page/1"
                                        .($this->sort?"/sort/".$this->sort:"")
                                        .($this->order?"/order/".$this->order:"")
                                        .($this->filter?"/filter/".$this->filter:"")
                                        .($this->resultsPerPage?"/results/".$this->resultsPerPage:"")
                                        .$this->post
                                        .'"><i class="fa fa-angle-double-left"></i></a></li>';
            
            $this->paging .= '<li><a id="page-'.$this->div
                                        .'" href="' . $this->baseUrl . '/page/' . ($this->page-1) 
                                        .($this->sort?'/sort/'.$this->sort:'')
                                        .($this->order?'/order/'.$this->order:'')
                                        .($this->filter?'/filter/'.$this->filter:'')
                                        .($this->resultsPerPage?'/results/'.$this->resultsPerPage:'')
                                        .$this->post
                                        .'"><i class="fa fa-angle-left"></i></a></li>';
        }
        else {
            $this->paging .= '<li class="disabled"><a disabled="disabled"><i class="fa fa-angle-double-left"></i></a></li>';
            
            $this->paging .= '<li class="disabled"><a disabled="disabled"><i class="fa fa-angle-left"></i></a></li>';
        }
        
        # Create a link for each page
        $pageCount = (($this->page-3) < 0 || ($pages<5))?1:((($this->page-2)>($pages-4))?$pages-4:$this->page-2);
        
        $lastPage = (($this->page+3) > $pages || ($pages<5))?$pages:$pageCount+4;
        
        while($pageCount <= $lastPage)
        {
            $this->paging .= '<li '.($pageCount==$this->page?'class="active"':'').'><a id="page-'.$this->div
                                        .'" href="' . $this->baseUrl . '/page/' . $pageCount 
                                        .($this->sort?"/sort/".$this->sort:"")
                                        .($this->order?"/order/".$this->order:"")
                                        .($this->filter?"/filter/".$this->filter:"")
                                        .($this->resultsPerPage?"/results/".$this->resultsPerPage:"")
                                        .$this->post
                                        .'">' . $pageCount . '</a></li> ';
            $pageCount++;
        }
        
        if ($this->page != $pages) {
            $this->paging .= '<li><a id="page-'.$this->div
                                        .'" href="' . $this->baseUrl . "/page/". ($this->page+1) 
                                        .($this->sort?"/sort/".$this->sort:"")
                                        .($this->order?"/order/".$this->order:"")
                                        .($this->filter?"/filter/".$this->filter:"")
                                        .($this->resultsPerPage?"/results/".$this->resultsPerPage:"")
                                        .$this->post
                                        .'"><i class="fa fa-angle-right"></i></a></li>';
            
            $this->paging .= '<li><a id="page-'.$this->div
                                        .'" href="' . $this->baseUrl . "/page/". $pages 
                                        .($this->sort?"/sort/".$this->sort:"")
                                        .($this->order?"/order/".$this->order:"")
                                        .($this->filter?"/filter/".$this->filter:"")
                                        .($this->resultsPerPage?"/results/".$this->resultsPerPage:"")
                                        .$this->post
                                        .'"><i class="fa fa-angle-double-right"></i></a></li>';
        }
        else {
            $this->paging .= '<li class="disabled"><a disabled="disabled"><i class="fa fa-angle-right"></i></a></li>';
        
            $this->paging .= '<li class="disabled"><a disabled="disabled"><i class="fa fa-angle-double-right"></i></a></li>';
        }
        $this->paging .= '</ul>';

        return $antet.$this->paging.$perPage;
    }
}
