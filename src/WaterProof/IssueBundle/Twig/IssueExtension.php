<?php

namespace WaterProof\IssueBundle\Twig;


class IssueExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('format_status', array($this, 'statusFormatter'), array('is_safe' => array('html'))),
            new \Twig_SimpleFilter('format_priority', array($this, 'priorityFormatter'), array('is_safe' => array('html'))),
            new \Twig_SimpleFilter('format_date_long', array($this, 'dateLongFormatter'), array('is_safe' => array('html'))),
            new \Twig_SimpleFilter('format_date_short', array($this, 'dateShortFormatter'), array('is_safe' => array('html'))),
            new \Twig_SimpleFilter('format_date_relative', array($this, 'dateRelativeFormatter'), array('is_safe' => array('html'))),
            new \Twig_SimpleFilter('format_issue_id', array($this, 'issueIdFormatter'), array('is_safe' => array('html'))),
        );
    }

    public function statusFormatter($value)
    {
        switch ($value) {
            case \WaterProof\WorkflowBundle\Workflow::STATUS_NEW:
                return '<span class="label label-danger">New</span>';
            case \WaterProof\WorkflowBundle\Workflow::STATUS_PENDING:
                return '<span class="label label-warning">Pending</span>';
            case \WaterProof\WorkflowBundle\Workflow::STATUS_NEED_FEEDBACK:
                return '<span class="label label-default">Need feedback</span>';
            case \WaterProof\WorkflowBundle\Workflow::STATUS_CLOSED:
                return '<span class="label label-success">Closed</span>';
        }
        return '-';
    }

    public function priorityFormatter($value)
    {
        switch ($value) {
            case 1:
                return 'Low';
            case 2:
                return 'Medium';
            case 3:
                return 'High';
        }
        return '-';
    }

    public function dateLongFormatter($value)
    {
        if ($value) {
            return $value->format('d/m/Y H:i');
        }
        return '-';
    }

    public function dateShortFormatter($value)
    {
        if ($value) {
            return $value->format('d/m/Y');
        }
        return '-';
    }

    public function dateRelativeFormatter($value)
    {
        if ($value) {
            // Déduction de la date donnée à la date actuelle
            $time = time() - strtotime($value->format('Y-m-d h:i:s'));

            // Calcule si le temps est passé ou à venir
            if ($time > 0) {
                $when = "il y a";
            } else if ($time < 0) {
                $when = "dans environ";
            } else {
                return "il y a moins d'une seconde";
            }
            $time = abs($time);

            // Tableau des unités et de leurs valeurs en secondes
            $times = array(31104000 => 'an{s}',       // 12 * 30 * 24 * 60 * 60 secondes
                2592000 => 'mois',        // 30 * 24 * 60 * 60 secondes
                86400 => 'jour{s}',     // 24 * 60 * 60 secondes
                3600 => 'heure{s}',    // 60 * 60 secondes
                60 => 'minute{s}',   // 60 secondes
                1 => 'seconde{s}'); // 1 seconde

            foreach ($times as $seconds => $unit) {
                // Calcule le delta entre le temps et l'unité donnée
                $delta = round($time / $seconds);

                // Si le delta est supérieur à 1
                if ($delta >= 1) {
                    // L'unité est au singulier ou au pluriel ?
                    if ($delta == 1) {
                        $unit = str_replace('{s}', '', $unit);
                    } else {
                        $unit = str_replace('{s}', 's', $unit);
                    }
                    // Retourne la chaine adéquate
                    return $when . " " . $delta . " " . $unit;
                }
            }
        }
        return '-';
    }

    public function issueIdFormatter($value)
    {
        return sprintf('#%06d', $value);
    }


    public function getName()
    {
        return 'waterproof_issue_extension';
    }

}