<?php
/**
 * DomesdayNear helper
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @uses viewHelper Pas_View_Helper
 * @uses Zend_Cache
 * @uses Pas_Service_Domesday_Place
 * @version 1
 * @since 18/5/2014
 * @license GNU
 * @category Pas
 * @package Pas_View_Helper
 */
class Pas_View_Helper_DomesdayNear extends Zend_View_Helper_Abstract {

    /** The base url for the service
     * @access protected
     * @var @string
     */
    protected $_url = 'http://domesdaymap.co.uk/';

    /** The url of the place for the html
     * @access protected
     * @var string
     */
    protected $_baseurl = 'http://domesdaymap.co.uk/place/';

    /** The class to call for Domesday data
     * @access protected
     * @var object
     */
    protected $_domesday;

    /** The cache object
     * @access protected
     * @var object
     */
    protected $_cache;

    /** The latitude to query
     * @access protected
     * @var string
     */
    protected $_lat;

    /** The longitude to query
     * @access protected
     * @var string
     */
    protected $_lon;

    /** the radius to query
     * @access protected
     * @var int
     */
    protected $_radius;

    /** Get the latitude to query
     * @access public
     * @return string
     */
    public function getLat() {
        return $this->_lat;
    }

    /** Get the longitude to query
     * @access public
     * @return string
     */
    public function getLon() {
        return $this->_lon;
    }

    /** Get the radius to query
     * @access public
     * @return int
     */
    public function getRadius() {
        return $this->_radius;
    }

    /** Set the latitude
     * @access public
     * @param string $lat
     * @return \Pas_View_Helper_DomesdayNear
     */
    public function setLat( string $lat) {
        $this->_lat = $lat;
        return $this;
    }

    /** Set the longitude to query
     * @access public
     * @param string $lon
     * @return \Pas_View_Helper_DomesdayNear
     */
    public function setLon( string $lon) {
        $this->_lon = $lon;
        return $this;
    }

    /** Set the radius to query
     * @access public
     * @param int $radius
     * @return \Pas_View_Helper_DomesdayNear
     * @throws Exception
     */
    public function setRadius( int $radius) {
        if(!is_int($radius)){
            throw new Exception('Defined radius needs to be an integer');
	}
        $this->_radius = $radius;
        return $this;
    }

    /** Get the domesday service class
     * @access public
     * @return object
     */
    public function getDomesday() {
        $this->_domesday = new Pas_Service_Domesday_Place();
        return $this->_domesday;
    }

    /** Get the cache object
     * @access public
     * @return object
     */
    public function getCache() {
        return $this->_cache;
    }

    /** the function to call
     * @access public
     * @return \Pas_View_Helper_DomesdayNear
     */
    public function domesdayNear() {
        return $this;
    }

    /** get the data from the service
     * @access public
     * @return function
     */
    public function getManors() {
        $params = array(
            'lat' => $this->getLat(),
            'lng' => $this->getLon(),
            'radius' => $this->getRadius()
                );
	$key = md5($params);
	$response = $this->getPlacesNear( $params, $key);
	return $this->buildHtml($response, $this->getRadius());
    }

    /** To string method
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->getManors();
    }

    /** Get the places near to point
     * @access public
     * @param array $params
     * @param type $key
     * @return type
     */
    public function getPlacesNear(array $params, $key ){
        if (!($this->getCache()->test($key))) {
            $data = $this->getDomesday()->getData('placesnear', $params);
            $this->getCache()->save($data);
        } else {
            $data = $this->getCache()->load($key);
        }
        return $data;
    }

    /** Build html string
     * @access public
     * @param object $response
     * @param int $radius
     * @return string
     */
    public function buildHtml( object $response, int $radius){
        if($response){
            $html = '<h3>Adjacent Domesday Book places</h3>';
            $html .= '<a  href="';
            $html .= $this->_url;
            $html .= '"><img class="dec flow"';
            $html .= 'src="http://domesdaymap.co.uk/media/images/lion1.gif"';
            $html .- 'width="67" height="93"/></a>';
            $html .= '<ul>';
            foreach($response as $domesday){
                $html .= '<li><a href="';
                $html .= $this->_baseurl . $domesday->grid;
                $html .= '/' . $domesday->vill_slug;
                $html .= '">'. $domesday->vill . '</a></li>';
            }
            $html .= '</ul>';
            $html .= '<p>Domesday data  within ';
            $html .= $radius;
            $html .= ' km of discovery point is surfaced via the excellent';
            $html .= '<a href="http://domesdaymap.co.uk">Open Domesday</a> website.</p>';
            return $html;
            }
	}
}

