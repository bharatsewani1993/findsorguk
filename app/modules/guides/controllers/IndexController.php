<?php
/** Controller for index of Guides section
 *
 * @category   Pas
 * @package    Pas_Controller
 * @subpackage ActionAdmin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license    GNU General Public License
 */
class Guides_IndexController extends Pas_Controller_Action_Admin {

    /** Setup the contexts by action and the ACL.
     */
    public function init(){
        $this->_helper->acl->allow('public', null);
    }
    /** Display the front page material.
     */
    public function indexAction(){
        $content = new Content();
        $this->view->contents = $content->getFrontContent('guides');
    }

}