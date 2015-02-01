<?php

namespace WaterProof\IssueBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Assignee
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="WaterProof\IssueBundle\Entity\AssigneeRepository")
 */
class Assignee
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="WaterProof\UserBundle\Entity\User", inversedBy="assignments")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="WaterProof\UserBundle\Entity\UserGroup", inversedBy="assignments")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     */
    private $group;

    /**
     * @ORM\ManyToOne(targetEntity="WaterProof\IssueBundle\Entity\Issue", inversedBy="assignees")
     * @ORM\JoinColumn(name="issue_id", referencedColumnName="id")
     */
    private $issue;

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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Assignee
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
     * @return Assignee
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
     * Set user
     *
     * @param \WaterProof\UserBundle\Entity\User $user
     * @return Assignee
     */
    public function setUser(\WaterProof\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \WaterProof\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set group
     *
     * @param \WaterProof\UserBundle\Entity\UserGroup $group
     * @return Assignee
     */
    public function setGroup(\WaterProof\UserBundle\Entity\UserGroup $group = null)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get group
     *
     * @return \WaterProof\UserBundle\Entity\UserGroup 
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Set issue
     *
     * @param \WaterProof\IssueBundle\Entity\Issue $issue
     * @return Assignee
     */
    public function setIssue(\WaterProof\IssueBundle\Entity\Issue $issue = null)
    {
        $this->issue = $issue;

        return $this;
    }

    /**
     * Get issue
     *
     * @return \WaterProof\IssueBundle\Entity\Issue 
     */
    public function getIssue()
    {
        return $this->issue;
    }
}
