<?php
namespace App\Auth;

use Cake\Controller\ComponentRegistry;
use Cake\Http\ServerRequest;
use Cake\Auth\BaseAuthorize;
use App\Utils\AuthUtils;

/**
 * AdminAuthorize
 *
 * @see \Cake\Controller\Component\AuthComponent::$authenticate
 * @author tikuwa
 *
 */
class AdminAuthorize extends BaseAuthorize
{
    /**
     * ComponentRegistry instance for getting more components.
     *
     * @var \Cake\Controller\ComponentRegistry
     */
    protected $_registry;

    /**
     * Default config for authorize objects.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    /**
     * Constructor
     *
     * @param \Cake\Controller\ComponentRegistry $registry The controller for this request.
     * @param array $config An array of config. This class does not use any config.
     */
    public function __construct(ComponentRegistry $registry, array $config = [])
    {
        $this->_registry = $registry;
        $this->setConfig($config);
    }

    /**
     * Checks user authorization.
     *
     * @param array|\ArrayAccess $user Active user data
     * @param \Cake\Http\ServerRequest $request Request instance.
     * @return bool
     */
    public function authorize($user, ServerRequest $request)
    {
        if ($request->controller === 'Top') {
            return true;
        }

        $has_role = AuthUtils::hasRole($request);

        return $has_role;
    }
}
