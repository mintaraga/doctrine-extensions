<?php
/**
 * @link       http://superdweebie.com
 * @package    Sds
 * @license    MIT
 */
namespace Sds\DoctrineExtensions\State\AccessControl;

use Sds\Common\AccessControl\PermissionInterface;
use Sds\DoctrineExtensions\AccessControl\AllowedResult;

/**
 *
 * @since   1.0
 * @author  Tim Roediger <superdweebie@gmail.com>
 */
class StatePermission implements PermissionInterface
{

    const wild = '*';

    protected $roles;

    protected $allow;

    protected $deny;

    protected $state;

    protected $stateField;

    public function __construct(array $roles, array $allow, array $deny, $state, $stateField)
    {
        $this->roles = array_map([$this, 'roleToRegex'], $roles);;
        $this->allow = array_map([$this, 'actionToRegex'], $allow);
        $this->deny  = array_map([$this, 'actionToRegex'], $deny);
        $this->state = (string) $state;
        $this->stateField = (string) $stateField;
    }

    protected function roleToRegex($string){
        return '/^' . str_replace(self::wild, '[a-zA-Z0-9_-]*', $string) . '$/';
    }

    protected function actionToRegex($string){
        return '/^' . str_replace(self::wild, '[a-zA-Z0-9_:-]*', $string) . '$/';
    }

    /**
     * Will test if a user with the supplied roles can do ALL the supplied actions.
     *
     * @param array $roles
     * @param array $action
     * @return \Sds\DoctrineExtensions\AccessControl\IsAllowedResult
     */
    public function areAllowed(array $testRoles, array $testActions) {

        //only check allow and deny if there is at least one matching role
        if (count($testRoles) == 0){
            $testRoles = [''];
        }
        $roleMatch = false;
        foreach ($this->roles as $role){
            if (count(array_filter($testRoles, function($testRole) use ($role){
                    return preg_match($role, $testRole);
                })) > 0
            ){
                $roleMatch = true;
                break;
            }
        }
        if (!$roleMatch){
            return new AllowedResult; //Permission is neither explicitly allowed or denied.
        }

        //check allow
        $allowMatches = 0;
        foreach ($testActions as $testAction){ //check each testAction in turn
            $allowMatch = count(array_filter($this->allow, function($action) use ($testAction){ //first check that action matches at least one allow
                return preg_match($action, $testAction);
            })) > 0;

            $denyMatch = count(array_filter($this->deny, function($action) use ($testAction){ //second check that action does not matche any deny
                return preg_match($action, $testAction);
            })) > 0;

            if ($denyMatch){
                return new AllowedResult(false, null, [$this->stateField => $this->state]); //one or more actions are explicitly denied
            }
            if ($allowMatch){
                $allowMatches++;
            }
        }
        if ($allowMatches == count($testActions)){
            return new AllowedResult(true, null, [$this->stateField => $this->state]); //all actions are explicitly allowed
        }

        return new AllowedResult; //Permission is neither explicitly allowed or denied.
    }
}
