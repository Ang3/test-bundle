<?php

namespace Ang3\Bundle\TestBundle\Test;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilder;

/**
 * @author Joanis ROUANET
 */
trait ValidationTrait
{
    /**
     * @param array<scalar>|scalar|null $expectedMessages
     */
    protected function createMockValidator(ConstraintValidator $validator, $expectedMessages): ConstraintValidator
    {
        $builder = $this->getMockBuilder(ConstraintViolationBuilder::class)
            ->disableOriginalConstructor()
            ->setMethods(['addViolation'])
            ->getMock();

        $context = $this->getMockBuilder(ExecutionContext::class)
            ->disableOriginalConstructor()
            ->setMethods(['buildViolation'])
            ->getMock();

        if ($expectedMessages) {
            $expectedMessages = array_filter((array) $expectedMessages);

            $nbMessages = count($expectedMessages);

            $builder
                ->expects($this->exactly($nbMessages))
                ->method('addViolation')
            ;

            foreach ($expectedMessages as $expectedMessage) {
                $context
                    ->expects($this->once())
                    ->method('buildViolation')
                    ->with($this->equalTo($expectedMessage))
                    ->will($this->returnValue($builder))
                ;
            }
        } else {
            $context
                ->expects($this->never())
                ->method('buildViolation')
            ;
        }

        /* @var ExecutionContext $context */
        $validator->initialize($context);

        return $validator;
    }
}
