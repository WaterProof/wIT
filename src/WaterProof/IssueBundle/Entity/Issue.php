<?php

namespace WaterProof\IssueBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use WaterProof\WorkflowBundle\Workflow;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Issue {
    function __construct(){
        $this->status = Workflow::STATUS_NEW;
    }
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="WaterProof\UserBundle\Entity\User", inversedBy="issues")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     */
    private $owner;

    /**
     * @ORM\ManyToOne(targetEntity="WaterProof\IssueBundle\Entity\Assignee", inversedBy="issues")
     * @ORM\JoinColumn(name="assignee_id", referencedColumnName="id")
     */
    private $assignee;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deadline_at", type="datetime", nullable=true)
     */
    private $deadlineAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="closed_at", type="datetime", nullable=true)
     */
    private $closedAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="priority", type="integer")
     */
    private $priority;

    /**
     * @var string
     *
     * @ORM\Column(name="custom1", type="string", length=255)
     */
    private $custom1;

    /**
     * @var string
     *
     * @ORM\Column(name="custom2", type="string", length=255)
     */
    private $custom2;



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Issue
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Issue
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Issue
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Issue
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set deadlineAt
     *
     * @param \DateTime $deadlineAt
     * @return Issue
     */
    public function setDeadlineAt($deadlineAt)
    {
        $this->deadlineAt = $deadlineAt;

        return $this;
    }

    /**
     * Get deadlineAt
     *
     * @return \DateTime 
     */
    public function getDeadlineAt()
    {
        return $this->deadlineAt;
    }



    /**
     * Set status
     *
     * @param integer $status
     * @return Issue
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set priority
     *
     * @param integer $priority
     * @return Issue
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return integer 
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set custom1
     *
     * @param string $custom1
     * @return Issue
     */
    public function setCustom1($custom1)
    {
        $this->custom1 = $custom1;

        return $this;
    }

    /**
     * Get custom1
     *
     * @return string 
     */
    public function getCustom1()
    {
        return $this->custom1;
    }

    /**
     * Set custom2
     *
     * @param string $custom2
     * @return Issue
     */
    public function setCustom2($custom2)
    {
        $this->custom2 = $custom2;

        return $this;
    }

    /**
     * Get custom2
     *
     * @return string 
     */
    public function getCustom2()
    {
        return $this->custom2;
    }

    /**
     * Set owner
     *
     * @param \WaterProof\UserBundle\Entity\User $owner
     * @return Issue
     */
    public function setOwner(\WaterProof\UserBundle\Entity\User $owner = null)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return \WaterProof\UserBundle\Entity\User 
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set assignee
     *
     * @param \WaterProof\IssueBundle\Entity\Assignee $assignee
     * @return Issue
     */
    public function setAssignee(\WaterProof\IssueBundle\Entity\Assignee $assignee = null)
    {
        $this->assignee = $assignee;

        return $this;
    }

    /**
     * Get assignee
     *
     * @return \WaterProof\IssueBundle\Entity\Assignee 
     */
    public function getAssignee()
    {
        return $this->assignee;
    }

    /**
     * Set closedAt
     *
     * @param \DateTime $closedAt
     * @return Issue
     */
    public function setClosedAt($closedAt)
    {
        $this->closedAt = $closedAt;

        return $this;
    }

    /**
     * Get closedAt
     *
     * @return \DateTime 
     */
    public function getClosedAt()
    {
        return $this->closedAt;
    }



    /**
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setUpdatedAt(new \DateTime('now'));

        if ($this->getCreatedAt() == null) {
            $this->setCreatedAt(new \DateTime('now'));
        }
    }
}
