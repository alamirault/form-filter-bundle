<?php

namespace Lexik\Bundle\FormFilterBundle\Tests\Fixtures\Filter;

use Doctrine\ODM\MongoDB\Query\Expr;
use Lexik\Bundle\FormFilterBundle\Filter\Query\QueryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Form filter for tests.
 *
 * @author Cédric Girard <c.girard@lexik.fr>
 */
class FormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class);
        $builder->add('position', IntegerType::class, ['apply_filter' => function (QueryInterface $filterQuery, $field, $values) {
            if (!empty($values['value'])) {
                if ($filterQuery->getExpr() instanceof Expr) {
                    $expr = $filterQuery->getExpr()->field($field)->equals($values['value']);
                } else {
                    $expr = $filterQuery->getExpr()->eq($field, $values['value']);
                }

                return $filterQuery->createCondition($expr);
            }

            return null;
        }]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'my_form';
    }
}
