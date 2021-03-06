<?php

/** A view helper for getting fullname of user
 *
 * An example of use:
 *
 * <code>
 * <?php
 * echo $this->fullName();
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @version 1
 * @since 1
 * @copyright Daniel Pett <dpett@britishmuseum.org>
 * @package View
 * @category Pas
 * @subpackage Helper
 * @uses Zend_Auth Zend Auth
 * @uses Zend_View_Helper_Escape
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 */
class Pas_View_Helper_FullName extends Zend_View_Helper_Abstract
{
    /** The auth object
     * @access protected
     * @var object
     */
    protected $_auth;

    /** Get the auth object
     * @access public
     * @return object
     */
    public function getAuth()
    {
        $this->_auth = Zend_Auth::getInstance();
        return $this->_auth;
    }

    /** The fullname to use and display
     * @access protected
     * @var string
     */
    protected $_fullname;

    /** Get the fullname from the identity
     * @access public
     * @return string
     */
    public function getFullname()
    {
        $user = $this->getAuth()->getIdentity();
        if ($user) {
            $this->_fullname = $this->view->escape(ucfirst($user->fullname));
        }

        return $this->_fullname;
    }

    /** The to string function
     * @access public
     * @return string
     */
    public function __toString()
    {
        return $this->getFullname();
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_FullName
     */
    public function fullName()
    {
        return $this;
    }
}