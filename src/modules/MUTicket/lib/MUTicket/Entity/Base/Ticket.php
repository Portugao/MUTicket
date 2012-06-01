<?php
/**
 * MUTicket.
 *
 * @copyright Michael Ueberschaer
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @package MUTicket
 * @author Michael Ueberschaer <kontakt@webdesign-in-bremen.com>.
 * @link http://www.webdesign-in-bremen.com
 * @link http://zikula.org
 * @version Generated by ModuleStudio 0.5.4 (http://modulestudio.de) at Thu Feb 02 13:43:18 CET 2012.
 */

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use DoctrineExtensions\StandardFields\Mapping\Annotation as ZK;

/**
 * Entity class that defines the entity structure and behaviours.
 *
 * This is the base entity class for ticket entities.
 *
 * @abstract
 */
abstract class MUTicket_Entity_Base_Ticket extends Zikula_EntityAccess
{

    /**
     * @var string The tablename this object maps to
     */
    protected $_objectType = 'ticket';

    /**
     * @var array List of primary key field names
     */
    protected $_idFields = array();

    /**
     * @var MUTicket_Entity_Validator_Ticket The validator for this entity
     */
    protected $_validator = null;

    /**
     * @var boolean Whether this entity supports unique slugs
     */
    protected $_hasUniqueSlug = false;

    /**
     * @var array List of available item actions
     */
    protected $_actions = array();



    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", unique=true)
     * @var integer $id.
     */
    protected $id = 0;


    /**
     * @ORM\Column(length=255)
     * @var string $title.
     */
    protected $title = '';


    /**
     * @ORM\Column(length=2000)
     * @var string $text.
     */
    protected $text = '';


    /**
     * @ORM\Column(type="integer", nullable=true)
     * @var integer $parent_id.
     */
    protected $parent_id = 0;
    /**
     * Images meta data array.
     *
     * @ORM\Column(type="array")
     * @var array $imagesMeta.
     */
    protected $imagesMeta = array();



    /**
     * @ORM\Column(length=255)
     * @var string $images.
     */
    protected $images = '';

    /**
     * The full path to the images.
     *
     * @var string $imagesFullPath.
     */
    protected $imagesFullPath = '';

    /**
     * Full images path as url.
     *
     * @var string $imagesFullPathUrl.
     */
    protected $imagesFullPathUrl = '';
    /**
     * Files meta data array.
     *
     * @ORM\Column(type="array")
     * @var array $filesMeta.
     */
    protected $filesMeta = array();



    /**
     * @ORM\Column(length=255)
     * @var string $files.
     */
    protected $files = '';

    /**
     * The full path to the files.
     *
     * @var string $filesFullPath.
     */
    protected $filesFullPath = '';

    /**
     * Full files path as url.
     *
     * @var string $filesFullPathUrl.
     */
    protected $filesFullPathUrl = '';


    /**
     * @ORM\Column(type="boolean")
     * @var boolean $state.
     */
    protected $state = true;


    /**
     * @ORM\Column(type="boolean")
     * @var boolean $t_rating.
     */
    protected $t_rating = false;


    /**
     * @ORM\Column(type="boolean")
     * @var boolean $rated.
     */
    protected $rated = false;


    /**
     * @ORM\OneToMany(targetEntity="MUTicket_Entity_TicketCategory", 
     *                mappedBy="entity", cascade={"all"}, 
     *                orphanRemoval=true, indexBy="categoryRegistryId")
     * @var MUTicket_Entity_TicketCategory
     */
    protected $categories;

    /**
     * @ORM\Column(type="integer")
     * @ZK\StandardFields(type="userid", on="create")
     * @var integer $createdUserId.
     */
    protected $createdUserId;

    /**
     * @ORM\Column(type="integer")
     * @ZK\StandardFields(type="userid", on="update")
     * @var integer $updatedUserId.
     */
    protected $updatedUserId;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     * @var datetime $createdDate.
     */
    protected $createdDate;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     * @var datetime $updatedDate.
     */
    protected $updatedDate;


    /**
     * Self relations were not working yet, must be retested with Doctrine 2.
     * See #9 for more information
     */
    /**
     * Bidirectional - Many children [tickets] are linked by one parent [ticket] (OWNING SIDE).
     *
     * @ORM\ManyToOne(targetEntity="MUTicket_Entity_Ticket", inversedBy="children")
     * @ORM\JoinTable(name="muticket_ticket",
     *      joinColumns={@ORM\JoinColumn(name="id", referencedColumnName="id" )},
     *      inverseJoinColumns={@ORM\JoinColumn(name="parent_id", referencedColumnName="id" )}
     * )
     * @var MUTicket_Entity_Ticket $parent.
     */
    protected $parent;



    /**
     * Unidirectional - One ticket [ticket] has many rating [ratings] (INVERSE SIDE).
     *
     * @ORM\ManyToMany(targetEntity="MUTicket_Entity_Rating", cascade={"all"})
     * @ORM\JoinTable(name="muticket_ticketrating")
     * @var MUTicket_Entity_Rating[] $rating.
     */
    protected $rating = null;

    /**
     * Self relations were not working yet, must be retested with Doctrine 2.
     * See #9 for more information
     */

    /**
     * Bidirectional - One parent [ticket] has many children [tickets] (INVERSE SIDE).
     *
     * @ORM\OneToMany(targetEntity="MUTicket_Entity_Ticket", mappedBy="parent", cascade={"all"})
     * @ORM\JoinTable(name="muticket_parentchildren",
     *      joinColumns={@ORM\JoinColumn(name="parent_id", referencedColumnName="id" )},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id", referencedColumnName="id" )}
     * )
     * @var MUTicket_Entity_Ticket[] $children.
     */
    protected $children = null;

    /**
     * Constructor.
     * Will not be called by Doctrine and can therefore be used
     * for own implementation purposes. It is also possible to add
     * arbitrary arguments as with every other class method.
     */
    public function __construct()
    {
        $this->id = 1;
        $this->_idFields = array('id');
        $this->initValidator();
        $this->_hasUniqueSlug = false;
        $this->rating = new ArrayCollection();
        $this->children = new ArrayCollection();
        $this->categories = new Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get _object type.
     *
     * @return string
     */
    public function get_objectType()
    {
        return $this->_objectType;
    }

    /**
     * Set _object type.
     *
     * @param string $_objectType.
     *
     * @return void
     */
    public function set_objectType($_objectType)
    {
        $this->_objectType = $_objectType;
    }


    /**
     * Get _id fields.
     *
     * @return array
     */
    public function get_idFields()
    {
        return $this->_idFields;
    }

    /**
     * Set _id fields.
     *
     * @param array $_idFields.
     *
     * @return void
     */
    public function set_idFields(array $_idFields = Array())
    {
        $this->_idFields = $_idFields;
    }


    /**
     * Get _validator.
     *
     * @return MUTicket_Entity_Validator_Ticket
     */
    public function get_validator()
    {
        return $this->_validator;
    }

    /**
     * Set _validator.
     *
     * @param MUTicket_Entity_Validator_Ticket $_validator.
     *
     * @return void
     */
    public function set_validator(MUTicket_Entity_Validator_Ticket $_validator = null)
    {
        $this->_validator = $_validator;
    }


    /**
     * Get _has unique slug.
     *
     * @return boolean
     */
    public function get_hasUniqueSlug()
    {
        return $this->_hasUniqueSlug;
    }

    /**
     * Set _has unique slug.
     *
     * @param boolean $_hasUniqueSlug.
     *
     * @return void
     */
    public function set_hasUniqueSlug($_hasUniqueSlug)
    {
        $this->_hasUniqueSlug = $_hasUniqueSlug;
    }


    /**
     * Get _actions.
     *
     * @return array
     */
    public function get_actions()
    {
        return $this->_actions;
    }

    /**
     * Set _actions.
     *
     * @param array $_actions.
     *
     * @return void
     */
    public function set_actions(array $_actions = Array())
    {
        $this->_actions = $_actions;
    }



    /**
     * Get id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id.
     *
     * @param integer $id.
     *
     * @return void
     */
    public function setId($id)
    {
        if ($id != $this->id) {
            $this->id = $id;
        }
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title.
     *
     * @param string $title.
     *
     * @return void
     */
    public function setTitle($title)
    {
        if ($title != $this->title) {
            $this->title = $title;
        }
    }

    /**
     * Get text.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set text.
     *
     * @param string $text.
     *
     * @return void
     */
    public function setText($text)
    {
        if ($text != $this->text) {
            $this->text = $text;
        }
    }

    /**
     * Get parent_id.
     *
     * @return integer
     */
    public function getParent_id()
    {
        return $this->parent_id;
    }

    /**
     * Set parent_id.
     *
     * @param integer $parent_id.
     *
     * @return void
     */
    public function setParent_id($parent_id)
    {
        if ($parent_id != $this->parent_id) {
            $this->parent_id = $parent_id;
        }
    }

    /**
     * Get images.
     *
     * @return string
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Set images.
     *
     * @param string $images.
     *
     * @return void
     */
    public function setImages($images)
    {
        if ($images != $this->images) {
            $this->images = $images;
        }
    }

    /**
     * Get images full path.
     *
     * @return string
     */
    public function getImagesFullPath()
    {
        return $this->imagesFullPath;
    }

    /**
     * Set images full path.
     *
     * @param string $imagesFullPath.
     *
     * @return void
     */
    public function setImagesFullPath($imagesFullPath)
    {
        if ($imagesFullPath != $this->imagesFullPath) {
            $this->imagesFullPath = $imagesFullPath;
        }
    }

    /**
     * Get images full path url.
     *
     * @return string
     */
    public function getImagesFullPathUrl()
    {
        return $this->imagesFullPathUrl;
    }

    /**
     * Set images full path url.
     *
     * @param string $imagesFullPathUrl.
     *
     * @return void
     */
    public function setImagesFullPathUrl($imagesFullPathUrl)
    {
        if ($imagesFullPathUrl != $this->imagesFullPathUrl) {
            $this->imagesFullPathUrl = $imagesFullPathUrl;
        }
    }

    /**
     * Get images meta.
     *
     * @return array
     */
    public function getImagesMeta()
    {
        return $this->imagesMeta;
    }

    /**
     * Set images meta.
     *
     * @param array $imagesMeta.
     *
     * @return void
     */
    public function setImagesMeta($imagesMeta = Array())
    {
        if ($imagesMeta != $this->imagesMeta) {
            $this->imagesMeta = $imagesMeta;
        }
    }

    /**
     * Get files.
     *
     * @return string
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Set files.
     *
     * @param string $files.
     *
     * @return void
     */
    public function setFiles($files)
    {
        if ($files != $this->files) {
            $this->files = $files;
        }
    }

    /**
     * Get files full path.
     *
     * @return string
     */
    public function getFilesFullPath()
    {
        return $this->filesFullPath;
    }

    /**
     * Set files full path.
     *
     * @param string $filesFullPath.
     *
     * @return void
     */
    public function setFilesFullPath($filesFullPath)
    {
        if ($filesFullPath != $this->filesFullPath) {
            $this->filesFullPath = $filesFullPath;
        }
    }

    /**
     * Get files full path url.
     *
     * @return string
     */
    public function getFilesFullPathUrl()
    {
        return $this->filesFullPathUrl;
    }

    /**
     * Set files full path url.
     *
     * @param string $filesFullPathUrl.
     *
     * @return void
     */
    public function setFilesFullPathUrl($filesFullPathUrl)
    {
        if ($filesFullPathUrl != $this->filesFullPathUrl) {
            $this->filesFullPathUrl = $filesFullPathUrl;
        }
    }

    /**
     * Get files meta.
     *
     * @return array
     */
    public function getFilesMeta()
    {
        return $this->filesMeta;
    }

    /**
     * Set files meta.
     *
     * @param array $filesMeta.
     *
     * @return void
     */
    public function setFilesMeta($filesMeta = Array())
    {
        if ($filesMeta != $this->filesMeta) {
            $this->filesMeta = $filesMeta;
        }
    }

    /**
     * Get state.
     *
     * @return boolean
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set state.
     *
     * @param boolean $state.
     *
     * @return void
     */
    public function setState($state)
    {
        if ($state !== $this->state) {
            $this->state = (bool)$state;
        }
    }

    /**
     * Get t_rating.
     *
     * @return boolean
     */
    public function getT_rating()
    {
        return $this->t_rating;
    }

    /**
     * Set t_rating.
     *
     * @param boolean $t_rating.
     *
     * @return void
     */
    public function setT_rating($t_rating)
    {
        if ($t_rating !== $this->t_rating) {
            $this->t_rating = (bool)$t_rating;
        }
    }

    /**
     * Get rated.
     *
     * @return boolean
     */
    public function getRated()
    {
        return $this->rated;
    }

    /**
     * Set rated.
     *
     * @param boolean $rated.
     *
     * @return void
     */
    public function setRated($rated)
    {
        if ($rated !== $this->rated) {
            $this->rated = (bool)$rated;
        }
    }


    /**
     * Get categories.
     *
     * @return array
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set categories.
     *
     * @param array $categories.
     *
     * @return void
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
    }

    /**
     * Get created user id.
     *
     * @return integer[]
     */
    public function getCreatedUserId()
    {
        return $this->createdUserId;
    }

    /**
     * Set created user id.
     *
     * @param integer[] $createdUserId.
     *
     * @return void
     */
    public function setCreatedUserId($createdUserId)
    {
        $this->createdUserId = $createdUserId;
    }

    /**
     * Get updated user id.
     *
     * @return integer[]
     */
    public function getUpdatedUserId()
    {
        return $this->updatedUserId;
    }

    /**
     * Set updated user id.
     *
     * @param integer[] $updatedUserId.
     *
     * @return void
     */
    public function setUpdatedUserId($updatedUserId)
    {
        $this->updatedUserId = $updatedUserId;
    }

    /**
     * Get created date.
     *
     * @return datetime[]
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * Set created date.
     *
     * @param datetime[] $createdDate.
     *
     * @return void
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;
    }

    /**
     * Get updated date.
     *
     * @return datetime[]
     */
    public function getUpdatedDate()
    {
        return $this->updatedDate;
    }

    /**
     * Set updated date.
     *
     * @param datetime[] $updatedDate.
     *
     * @return void
     */
    public function setUpdatedDate($updatedDate)
    {
        $this->updatedDate = $updatedDate;
    }

    /**
     * Get parent.
     *
     * @return MUTicket_Entity_Ticket
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set parent.
     *
     * @param MUTicket_Entity_Ticket $parent.
     *
     * @return void
     */
    public function setParent(MUTicket_Entity_Ticket $parent = null)
    {
        $this->parent = $parent;
    }


    /**
     * Adds an instance of MUTicket_Entity_Ticket to the list of parent.
     *
     * @param MUTicket_Entity_Ticket $ticket.
     *
     * @return void
     */
    public function addParent(MUTicket_Entity_Ticket $ticket)
    {
        $this->parent[] = $ticket;
        $ticket->setChildren($this);
    }

    /**
     * Removes an instance of MUTicket_Entity_Ticket from the list of parent.
     *
     * @param MUTicket_Entity_Ticket $parent.
     *
     * @return void
     */
    public function removeParent(MUTicket_Entity_Ticket $ticket)
    {
        $this->parent->removeElement($ticket);
        $ticket->setChildren(null);
    }


    /**
     * Get rating.
     *
     * @return MUTicket_Entity_Rating[]
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set rating.
     *
     * @param MUTicket_Entity_Rating[] $rating.
     *
     * @return void
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    }


    /**
     * Adds an instance of MUTicket_Entity_Rating to the list of rating.
     *
     * @param MUTicket_Entity_Rating $rating.
     *
     * @return void
     */
    public function addRating(MUTicket_Entity_Rating $rating)
    {
        $this->rating[] = $rating;
    }

    /**
     * Removes an instance of MUTicket_Entity_Rating from the list of rating.
     *
     * @param MUTicket_Entity_Rating $rating.
     *
     * @return void
     */
    public function removeRating(MUTicket_Entity_Rating $rating)
    {
        $this->rating->removeElement($rating);
    }

    /**
     * Get children.
     *
     * @return MUTicket_Entity_Ticket[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set children.
     *
     * @param MUTicket_Entity_Ticket[] $children.
     *
     * @return void
     */
    public function setChildren($children)
    {
        $this->children = $children;
    }


    /**
     * Adds an instance of MUTicket_Entity_Ticket to the list of children.
     *
     * @param MUTicket_Entity_Ticket $ticket.
     *
     * @return void
     */
    public function addChildren(MUTicket_Entity_Ticket $ticket)
    {
        $this->children[] = $ticket;
    }

    /**
     * Removes an instance of MUTicket_Entity_Ticket from the list of children.
     *
     * @param MUTicket_Entity_Ticket $children.
     *
     * @return void
     */
    public function removeChildren(MUTicket_Entity_Ticket $ticket)
    {
        $this->children->removeElement($ticket);
    }



    /**
     * Initialise validator and return it's instance.
     *
     * @return MUTicket_Entity_Validator_Ticket The validator for this entity.
     */
    public function initValidator()
    {
        if (!is_null($this->_validator)) {
            return $this->_validator;
        }
        $this->_validator = new MUTicket_Entity_Validator_Ticket($this);
        return $this->_validator;
    }

    /**
     * Start validation and raise exception if invalid data is found.
     *
     * @return void.
     */
    public function validate()
    {
        $result = $this->initValidator()->validateAll();
        if (is_array($result)) {
            throw new Zikula_Exception($result['message'], $result['code'], $result['debugArray']);
        }
    }

    /**
     * Return entity data in JSON format.
     *
     * @return string JSON-encoded data.
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }

    /**
     * Collect available actions for this entity.
     */
    protected function prepareItemActions()
    {
        if (!empty($this->_actions)) {
            return;
        }

        $currentType = FormUtil::getPassedValue('type', 'user', 'GETPOST', FILTER_SANITIZE_STRING);
        $currentFunc = FormUtil::getPassedValue('func', 'main', 'GETPOST', FILTER_SANITIZE_STRING);
        $dom = ZLanguage::getModuleDomain('MUTicket');
        if ($currentType == 'admin') {
            if (in_array($currentFunc, array('main', 'view'))) {
                    $this->_actions[] = array(
                        'url' => array('type' => 'user', 'func' => 'display', 'arguments' => array('ot' => 'ticket', 'id' => $this['id'])),
                        'icon' => 'preview',
                        'linkTitle' => __('Open preview page', $dom),
                        'linkText' => __('Preview', $dom)
                    );
                    $this->_actions[] = array(
                        'url' => array('type' => 'admin', 'func' => 'display', 'arguments' => array('ot' => 'ticket', 'id' => $this['id'])),
                        'icon' => 'display',
                        'linkTitle' => str_replace('"', '', $this['title']),
                        'linkText' => __('Details', $dom)
                    );
            }

            if (in_array($currentFunc, array('main', 'view', 'display'))) {
                if (SecurityUtil::checkPermission('MUTicket::', '.*', ACCESS_EDIT)) {

                    $this->_actions[] = array(
                        'url' => array('type' => 'admin', 'func' => 'edit', 'arguments' => array('ot' => 'ticket', 'id' => $this['id'])),
                        'icon' => 'edit',
                        'linkTitle' => __('Edit', $dom),
                        'linkText' => __('Edit', $dom)
                    );
                    $this->_actions[] = array(
                        'url' => array('type' => 'admin', 'func' => 'edit', 'arguments' => array('ot' => 'ticket', 'astemplate' => $this['id'])),
                        'icon' => 'saveas',
                        'linkTitle' => __('Reuse for new item', $dom),
                        'linkText' => __('Reuse', $dom)
                    );
                }
                if (SecurityUtil::checkPermission('MUTicket::', '.*', ACCESS_DELETE)) {
                    $this->_actions[] = array(
                        'url' => array('type' => 'admin', 'func' => 'delete', 'arguments' => array('ot' => 'ticket', 'id' => $this['id'])),
                        'icon' => 'delete',
                        'linkTitle' => __('Delete', $dom),
                        'linkText' => __('Delete', $dom)
                    );
                }
            }
            if ($currentFunc == 'display') {
                    $this->_actions[] = array(
                        'url' => array('type' => 'admin', 'func' => 'view', 'arguments' => array('ot' => 'ticket')),
                        'icon' => 'back',
                        'linkTitle' => __('Back to overview', $dom),
                        'linkText' => __('Back to overview', $dom)
                    );
            }
        }
        if ($currentType == 'user') {
            if (in_array($currentFunc, array('main', 'view'))) {
                    $this->_actions[] = array(
                        'url' => array('type' => 'user', 'func' => 'display', 'arguments' => array('ot' => 'ticket', 'id' => $this['id'])),
                        'icon' => 'display',
                        'linkTitle' => str_replace('"', '', $this['title']),
                        'linkText' => __('Details', $dom)
                    );
            }

            if (in_array($currentFunc, array('main', 'view', 'display'))) {
                if (SecurityUtil::checkPermission('MUTicket::', '.*', ACCESS_EDIT)) {

                    $this->_actions[] = array(
                        'url' => array('type' => 'user', 'func' => 'edit', 'arguments' => array('ot' => 'ticket', 'id' => $this['id'])),
                        'icon' => 'edit',
                        'linkTitle' => __('Edit', $dom),
                        'linkText' => __('Edit', $dom)
                    );
                    $this->_actions[] = array(
                        'url' => array('type' => 'user', 'func' => 'edit', 'arguments' => array('ot' => 'ticket', 'astemplate' => $this['id'])),
                        'icon' => 'saveas',
                        'linkTitle' => __('Reuse for new item', $dom),
                        'linkText' => __('Reuse', $dom)
                    );
                }
                if (SecurityUtil::checkPermission('MUTicket::', '.*', ACCESS_DELETE)) {
                    $this->_actions[] = array(
                        'url' => array('type' => 'user', 'func' => 'delete', 'arguments' => array('ot' => 'ticket', 'id' => $this['id'])),
                        'icon' => 'delete',
                        'linkTitle' => __('Delete', $dom),
                        'linkText' => __('Delete', $dom)
                    );
                }
            }
            if ($currentFunc == 'display') {
                    $this->_actions[] = array(
                        'url' => array('type' => 'user', 'func' => 'view', 'arguments' => array('ot' => 'ticket')),
                        'icon' => 'back',
                        'linkTitle' => __('Back to overview', $dom),
                        'linkText' => __('Back to overview', $dom)
                    );
            }
        }
    }




    /**
     * Post-Process the data after the entity has been constructed by the entity manager.
     * The event happens after the entity has been loaded from database or after a refresh call.
     *
     * Restrictions:
     *     - no access to entity manager or unit of work apis
     *     - no access to associations (not initialised yet)
     *
     * @see MUTicket_Entity_Ticket::postLoadCallback()
     * @return boolean true if completed successfully else false.
     */
    protected function performPostLoadCallback()
    {
        // echo 'loaded a record ...';

        $currentType = FormUtil::getPassedValue('type', 'user', 'GETPOST', FILTER_SANITIZE_STRING);
        $currentFunc = FormUtil::getPassedValue('func', 'main', 'GETPOST', FILTER_SANITIZE_STRING);
        // initialise the upload handler
        $uploadManager = new MUTicket_UploadHandler();

        $this['id'] = (int) ((isset($this['id']) && !empty($this['id'])) ? DataUtil::formatForDisplay($this['id']) : 0);
    if ($currentFunc != 'edit') {
        $this['title'] = ((isset($this['title']) && !empty($this['title'])) ? DataUtil::formatForDisplayHTML($this['title']) : '');
    }
    if ($currentFunc != 'edit') {
        $this['text'] = ((isset($this['text']) && !empty($this['text'])) ? DataUtil::formatForDisplayHTML($this['text']) : '');
    }
        $this['parent_id'] = (int) ((isset($this['parent_id']) && !empty($this['parent_id'])) ? DataUtil::formatForDisplay($this['parent_id']) : 0);
        if (!empty($this['images'])) {
            $basePath = MUTicket_Util_Controller::getFileBaseFolder('ticket', 'images');
            $fullPath = $basePath .  $this['images'];
            $this['imagesFullPath'] = $fullPath;
            $this['imagesFullPathURL'] = System::getBaseUrl() . $fullPath;

            // just some backwards compatibility stuff
            if (!isset($this['imagesMeta']) || !is_array($this['imagesMeta']) || !count($this['imagesMeta'])) {
                // assign new meta data
                $this['imagesMeta'] = $uploadManager->readMetaDataForFile($this['images'], $fullPath);
            }
        }
        if (!empty($this['files'])) {
            $basePath = MUTicket_Util_Controller::getFileBaseFolder('ticket', 'files');
            $fullPath = $basePath .  $this['files'];
            $this['filesFullPath'] = $fullPath;
            $this['filesFullPathURL'] = System::getBaseUrl() . $fullPath;

            // just some backwards compatibility stuff
            if (!isset($this['filesMeta']) || !is_array($this['filesMeta']) || !count($this['filesMeta'])) {
                // assign new meta data
                $this['filesMeta'] = $uploadManager->readMetaDataForFile($this['files'], $fullPath);
            }
        }
        $this['state'] = (bool) $this['state'];
        $this['t_rating'] = (bool) $this['t_rating'];
        $this['rated'] = (bool) $this['rated'];
        $this->prepareItemActions();
        return true;
    }

    /**
     * Pre-Process the data prior to an insert operation.
     * The event happens before the entity managers persist operation is executed for this entity.
     *
     * Restrictions:
     *     - no access to entity manager or unit of work apis
     *     - no identifiers available if using an identity generator like sequences
     *     - Doctrine won't recognize changes on relations which are done here
     *       if this method is called by cascade persist
     *     - no creation of other entities allowed
     *
     * @see MUTicket_Entity_Ticket::prePersistCallback()
     * @return boolean true if completed successfully else false.
     */
    protected function performPrePersistCallback()
    {
        // echo 'inserting a record ...';
        $this->validate();
        return true;
    }

    /**
     * Post-Process the data after an insert operation.
     * The event happens after the entity has been made persistant.
     * Will be called after the database insert operations.
     * The generated primary key values are available.
     *
     * Restrictions:
     *     - no access to entity manager or unit of work apis
     *
     * @see MUTicket_Entity_Ticket::postPersistCallback()
     * @return boolean true if completed successfully else false.
     */
    protected function performPostPersistCallback()
    {
        // echo 'inserted a record ...';
        return true;
    }

    /**
     * Pre-Process the data prior a delete operation.
     * The event happens before the entity managers remove operation is executed for this entity.
     *
     * Restrictions:
     *     - no access to entity manager or unit of work apis
     *     - will not be called for a DQL DELETE statement
     *
     * @see MUTicket_Entity_Ticket::preRemoveCallback()
     * @return boolean true if completed successfully else false.
     */
    protected function performPreRemoveCallback()
    {
/*        // delete workflow for this entity
        $result = Zikula_Workflow_Util::deleteWorkflow($this);
        if ($result === false) {
            $dom = ZLanguage::getModuleDomain('MUTicket');
            return LogUtil::registerError(__('Error! Could not remove stored workflow.', $dom));
        }*/
        return true;
    }

    /**
     * Post-Process the data after a delete.
     * The event happens after the entity has been deleted.
     * Will be called after the database delete operations.
     *
     * Restrictions:
     *     - no access to entity manager or unit of work apis
     *     - will not be called for a DQL DELETE statement
     *
     * @see MUTicket_Entity_Ticket::postRemoveCallback()
     * @return boolean true if completed successfully else false.
     */
    protected function performPostRemoveCallback()
    {
        // echo 'deleted a record ...';
        // initialise the upload handler
        $uploadManager = new MUTicket_UploadHandler();

        $uploadFields = array('images', 'files');
        foreach ($uploadFields as $uploadField) {
            if (empty($this->$uploadField)) {
                continue;
            }

            // remove upload file (and image thumbnails)
            $uploadManager->deleteUploadFile('ticket', $this, $uploadField);
        }
        return true;
    }

    /**
     * Pre-Process the data prior to an update operation.
     * The event happens before the database update operations for the entity data.
     *
     * Restrictions:
     *     - no access to entity manager or unit of work apis
     *     - will not be called for a DQL UPDATE statement
     *     - changes on associations are not allowed and won't be recognized by flush
     *     - changes on properties won't be recognized by flush as well
     *     - no creation of other entities allowed
     *
     * @see MUTicket_Entity_Ticket::preUpdateCallback()
     * @return boolean true if completed successfully else false.
     */
    protected function performPreUpdateCallback()
    {
        // echo 'updating a record ...';
        $this->validate();
        return true;
    }

    /**
     * Post-Process the data after an update operation.
     * The event happens after the database update operations for the entity data.
     *
     * Restrictions:
     *     - no access to entity manager or unit of work apis
     *     - will not be called for a DQL UPDATE statement
     *
     * @see MUTicket_Entity_Ticket::postUpdateCallback()
     * @return boolean true if completed successfully else false.
     */
    protected function performPostUpdateCallback()
    {
        // echo 'updated a record ...';
        return true;
    }

    /**
     * Pre-Process the data prior to a save operation.
     * This combines the PrePersist and PreUpdate events.
     * For more information see corresponding callback handlers.
     *
     * @see MUTicket_Entity_Ticket::preSaveCallback()
     * @return boolean true if completed successfully else false.
     */
    protected function performPreSaveCallback()
    {
        // echo 'saving a record ...';
        $this->validate();
        return true;
    }

    /**
     * Post-Process the data after a save operation.
     * This combines the PostPersist and PostUpdate events.
     * For more information see corresponding callback handlers.
     *
     * @see MUTicket_Entity_Ticket::postSaveCallback()
     * @return boolean true if completed successfully else false.
     */
    protected function performPostSaveCallback()
    {
        // echo 'saved a record ...';
        return true;
    }

}
