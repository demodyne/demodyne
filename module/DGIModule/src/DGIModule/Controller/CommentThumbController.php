<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use DGIModule\Entity\Comment;
use DGIModule\Entity\CommentThumb;

class CommentThumbController extends AbstractActionController
{
    
    public function addAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();
        
        $user = $this->identity();
        
        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        
        $type = $this->params()->fromRoute('type', 'down');
        $UUID = $this->params()->fromRoute('id', 0);
        
        $comment = $entityManager->getRepository('DGIModule\Entity\Comment')->findOneBy(['comUUID'=>$UUID]); 
        
        $success = 0;
        
        if ($comment==NULL) return $response->setContent(\Zend\Json\Json::encode($success)); // TODO: do nothing and return number 0
        
        // search if thumb already registered
        $commentThumb = $entityManager->getRepository('DGIModule\Entity\CommentThumb')->findOneBy(['com'=>$comment, 'usr'=>$user]); 
        
        if ($commentThumb) {
            return $response->setContent(\Zend\Json\Json::encode($success));
        }
        else {
        
            $commentThumb = new CommentThumb();
            
            $commentThumb->setCom($comment)
                         ->setUsr($user)
                         ->setUp(($type=='up')?1:0);
        
                
            $entityManager->persist($commentThumb);
            $entityManager->flush();
            
            $success = 1;
            
            return $response->setContent(\Zend\Json\Json::encode($success));
            
        }
                
    }
    
}