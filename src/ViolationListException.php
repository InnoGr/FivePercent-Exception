<?php

/**
 * This file is part of the Exception package
 *
 * (c) InnovationGroup
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace FivePercent\Component\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Exception with violation list for Symfony2 Validator
 * Attention: The package "symfony/validator" must be installed.
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class ViolationListException extends \Exception
{
    /**
     * @var ConstraintViolationListInterface
     */
    private $violationList;

    /**
     * Construct
     *
     * @param ConstraintViolationListInterface $violationList
     * @param string                           $message
     * @param int                              $code
     * @param \Exception                       $prev
     */
    public function __construct(
        ConstraintViolationListInterface $violationList,
        $message = 'Not valid.',
        $code = 0,
        \Exception $prev = null
    ) {
        $this->violationList = $violationList;

        parent::__construct($message, $code, $prev);
    }

    /**
     * Get violation list
     *
     * @return ConstraintViolationListInterface
     */
    public function getViolationList()
    {
        return $this->violationList;
    }

    /**
     * Create a new instance with violation list
     *
     * @param ConstraintViolationListInterface $violationList
     * @param int                              $code
     * @param \Exception                       $prev
     *
     * @return ViolationListException
     */
    public static function create(ConstraintViolationListInterface $violationList, $code = 0, \Exception $prev = null)
    {
        $violationMessages = [];

        /** @var \Symfony\Component\Validator\ConstraintViolationInterface $violation */
        foreach ($violationList as $violation) {
            $violationMessages[] = sprintf(
                '[%s]: %s;',
                $violation->getPropertyPath(),
                $violation->getMessage()
            );
        }

        $message = 'Not valid. ' . implode(' ', $violationMessages);

        return new static($violationList, $message, $code, $prev);
    }
}
