<?php
/**
 * EventListInputFiltersDto resolver.
 */

namespace App\Resolver;

use App\Dto\EventListInputFiltersDto;
use App\Entity\Enum\EventStatus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

/**
 * EventListInputFiltersDtoResolver class.
 */
class EventListInputFiltersDtoResolver implements ValueResolverInterface
{
    /**
     * Returns the possible value(s).
     *
     * @param Request          $request  HTTP Request
     * @param ArgumentMetadata $argument Argument metadata
     *
     * @return iterable Iterable
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();

        if (!$argumentType || !is_a($argumentType, EventListInputFiltersDto::class, true)) {
            return [];
        }

        $categoryId = $request->query->get('categoryId');
        $statusId = $request->query->get('statusId', EventStatus::ACTIVE->value);

        return [new EventListInputFiltersDto($categoryId, $statusId)];
    }
}
