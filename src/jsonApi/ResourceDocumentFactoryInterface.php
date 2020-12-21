<?php
declare(strict_types=1);

namespace hiapi\jsonApi;

use WoohooLabs\Yin\JsonApi\Schema\Resource\ResourceInterface;
use WoohooLabs\Yin\JsonApi\Schema\Document\ResourceDocumentInterface;

/**
 * Creates JsonApi resource for given content.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
interface ResourceDocumentFactoryInterface
{
    /**
     * @param array|object $content
     * @return ResourceDocumentInterface
     */
    public function getResourceDocumentFor($content): ResourceDocumentInterface;

    public function getResourceByClassName(string $entityClassName): ResourceInterface;

    public function getResourceFor(object $entity): ResourceInterface;
}
