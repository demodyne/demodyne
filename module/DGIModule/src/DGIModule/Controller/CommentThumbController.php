<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Controller;

use Zend\Json\Json;
use Zend\Mvc\Controller\AbstractActionController;
use DGIModule\Entity\CommentThumb;
use Doctrine\ORM\EntityManager;

class CommentThumbController extends AbstractActionController
{
    protected $entityManager;

    public function __construct(
        EntityManager $entityManager
    )
    {
        $this->entityManager = $entityManager;
    }

    /// @todo move this action to CommentController
    public function addAction()
    {
        $response = $this->getResponse();
        
        $user = $this->identity();
        
        $type = $this->params()->fromRoute('type', 'down');
        $UUID = $this->params()->fromRoute('id', 0);

        $comment = $this->entityManager->getRepository('DGIModule\Entity\Comment')->findOneBy(['comUUID'=>$UUID]); 
        
        $success = 0;
        
        if ($comment==NULL) {
            return $response->setContent(Json::encode($success));
        }
        
        // search if thumb already registered
        $commentThumb = $this->entityManager->getRepository('DGIModule\Entity\CommentThumb')->findOneBy(['com'=>$comment, 'usr'=>$user]); 
        
        if ($commentThumb) {
            return $response->setContent(Json::encode($success));
        }
        else {
        
            $commentThumb = new CommentThumb();
            
            $commentThumb->setCom($comment)
                         ->setUsr($user)
                         ->setUp(($type=='up')?1:0);
        
            $this->entityManager->persist($commentThumb);
            $this->entityManager->flush();
            
            $success = 1;
            
            return $response->setContent(Json::encode($success));
        }
    }
    
}