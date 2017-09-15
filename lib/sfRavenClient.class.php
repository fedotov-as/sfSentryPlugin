<?php

class sfRavenClient extends Raven_Client
{
  protected function get_user_data()
  {
    if (!sfContext::hasInstance())
    {
      return parent::get_user_data();
    }

    $user = sfContext::getInstance()->getUser();

    $authenticated = ($user instanceof sfSecurityUser) && $user->isAuthenticated();
    $username = null;
    if ($authenticated && method_exists($user, 'getUserName'))
    {
      $username = $user->getUserName();
    }

    return array(
      'user' => array(
        'is_authenticated' => $authenticated,
        'username'         => $username,
      )
    );
  }

  protected function get_extra_data()
  {
    if (!sfContext::hasInstance())
    {
      return array();
    }

    $context = sfContext::getInstance();

    if ($conf = $context->getConfiguration())
    {
      $this->setEnvironment($conf->getEnvironment());
    }

    $extra = array(
      'sf_module_name' => $context->getModuleName(),
      'sf_action_name' => $context->getActionName(),
    );

    $user = $context->getUser();
    if ($user && ($user instanceof sfGuardSecurityUser) && $guardUser = $user->getGuardUser())
    {
      $credentials = '';
      if (!$user->isAnonymous())
      {
        if ($user->isSuperAdmin())
        {
          $credentials = 'Super admin';
        }
        elseif (method_exists($guardUser, 'getAllPermissions'))
        {
          $credentials = implode(', ' , $guardUser->getAllPermissions());
        }
      }

      $extra['sf_user_credentials'] = $credentials;
      $extra['sf_user_attributes'] = $user->getAttributeHolder()->getAll();
    }

    return $extra;
  }
}
