<?php
declare(strict_types=1);

namespace hiapi\Core\Http\Psr15\Middleware;

use hiapi\Core\Utils\CIDR;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class UserRealIpMiddleware implements MiddlewareInterface
{
    public const ATTRIBUTE_NAME = 'user-real-ip';
    /**
     * @var string[] Networks than are allowed to override client IP
     */
    private array $trustedNets;

    public string $ipAttribute = self::ATTRIBUTE_NAME;

    public function __construct(array $trustedNets)
    {
        $this->trustedNets = $trustedNets;
    }

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $handler->handle($this->prepare($request));
    }

    private function prepare(ServerRequestInterface $request): ServerRequestInterface
    {
        $oldip = $this->getIp($request);
        $request = $request->withAttribute($this->ipAttribute, $oldip);

        if (!CIDR::matchBulk($oldip, $this->trustedNets)) {
            return $request;
        }

        $newip = $this->getNewIp($request);
        if (empty($newip) || $newip === $oldip) {
            return $request;
        }

        return $this->setNewIp($request, $newip);
    }

    private function getIp(ServerRequestInterface $request): string
    {
        return $request->getServerParams()['REMOTE_ADDR'] ?? '';
    }

    private function getNewIp(ServerRequestInterface $request): string
    {
        $change = $request->getHeaderLine('X-User-Ip') ?: $this->getParam($request, 'auth_ip');

        return filter_var($change, FILTER_VALIDATE_IP) ?: '';
    }

    private function setNewIp(ServerRequestInterface $request, string $ip)
    {
        /// legacy compatibility
        unset($_REQUEST['auth_ip']);
        $_SERVER['REMOTE_ADDR'] = $ip;

        # XXX TODO withServerParams NOT DEFINED !!!
        #$params = $request->getServerParams();
        #$params['REMOTE_ADDR'] = $ip;
        #return $request->withServerParams($params);

        return $request->withAttribute($this->ipAttribute, $ip);
    }

    private function getParam(ServerRequestInterface $request, string $name): ?string
    {
        return $request->getParsedBody()[$name] ?? $request->getQueryParams()[$name] ?? null;
    }
}
