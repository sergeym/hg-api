<?php
namespace CorsBundle\EventListener;

use ApiBundle\Entity\Client;
use ApiBundle\Security\CorsManager;
use FOS\OAuthServerBundle\Entity\AccessTokenManager;
use FOS\OAuthServerBundle\Entity\ClientManager;
use Nelmio\CorsBundle\EventListener\CorsListener as BaseCorsListener;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\Debug\Exception\FatalErrorException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Nelmio\CorsBundle\Options\ResolverInterface;

/**
 * Adds CORS headers and handles pre-flight requests
 *
 * @author Jordi Boggiano <j.boggiano@seld.be>
 */
class CorsListener extends BaseCorsListener
{
    /**
     * Simple headers as defined in the spec should always be accepted
     */
    protected static $simpleHeaders = array(
        'accept',
        'accept-language',
        'content-language',
        'origin',
    );

    protected $dispatcher;
    protected $options;

    /** @var ResolverInterface */
    protected $configurationResolver;
    /** @var ClientManager */
    protected $clientManager;
    /** @var CorsManager */
    protected $corsManager;
    /**
     * @var Logger
     */
    private $logger;
    /**
     * @var AccessTokenManager
     */
    private $accessTokenManager;

    public function __construct(EventDispatcherInterface $dispatcher, ResolverInterface $configurationResolver)
    {
        $this->dispatcher = $dispatcher;
        $this->configurationResolver = $configurationResolver;
    }



    protected function checkOrigin(Request $request, array $options)
    {
        // check origin
        $origin = $request->headers->get('Origin');

        if (!$this->corsManager->isValid($request, $origin)) {
            $this->logger->error('invalid');
            return false;
        }

        if ($options['allow_origin'] === true) return true;

        if ($options['origin_regex'] === true) {
            // origin regex matching
            foreach($options['allow_origin'] as $originRegexp) {
                if (preg_match('{'.$originRegexp.'}i', $origin)) {
                    return true;
                }
            }
        } else {
            // old origin matching
            if (in_array($origin, $options['allow_origin'])) {
                return true;
            }
        }

        return false;
    }

    public function setCorsManager(CorsManager $corsManager)
    {
        $this->corsManager = $corsManager;
    }

    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;
    }
}
