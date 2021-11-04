<?php

namespace Drupal\rest_plugin\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Drupal\Core\Cache\CacheableResponseInterface;
/**
 * @RestResource(
 *   id = "rest_plugin_test",
 *   label = @Translation("Rest Plugin Test"),
 *   uri_paths = {
 *     "canonical" = "/rest_plugin_test/rest_resource/{id}"
 *   }
 * )
 */
class RestPluginTest extends ResourceBase {
  
    public function get($id){
        if($id){
            $user = \Drupal\user\Entity\User::load($id);
            if(is_object($user)){
                $uid = $user->id();
                $user_mail = $user->getEmail();
                $user_account_name = $user->getAccountName();
                $user_roles = $user->getRoles();
                $response_result = ['UID' => $uid, 'Name' => $user_account_name, 'Email' => $user_mail, 'Role' => $user_roles ];
                if($uid % 2 != 0){
                    $response = new ResourceResponse($response_result);
                    if ($response instanceof CacheableResponseInterface){
                        $response->addCacheableDependency($response_result);
                    }
                    return $response;
                }else{
                    throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();
                }
            }else{
                return new ResourceResponse(['message' => 'User Id doesn\'t exist'],400);
            }
        }else{
            return new ResourceResponse(['message'=> 'User Id is required'],400);
        }      
    }
}