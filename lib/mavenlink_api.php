<?php

if (!function_exists('curl_init'))
{
  throw new Exception('Mavenlink PHP API Client requires the CURL PHP extension');
}

require_once 'classes.php';

class MavenlinkApi
{
  private static $devMode = true;
  private $loginInfo = null;

  function __construct($oauthToken, $production = true)
  {
    $this->loginInfo = $oauthToken;

    if ($production)
    {
      self::$devMode = false;
    }
  }

  function getWorkspaces()
  {
    return $this->getJsonForAll('Workspace');
  }

  function getEvents()
  {
      return $this->getJsonForAll('Event');
  }

  function getTimeEntries()
  {
    return $this->getJsonForAll('TimeEntry');
  }

  function getExpenses()
  {
    return $this->getJsonForAll('Expense');
  }

  function getInvoices()
  {
    return $this->getJsonForAll('Invoice');
  }

  function getStories()
  {
    return $this->getJsonForAll('Story');
  }

  function getUsers()
  {
    return $this->getJsonForAll('User');
  }

  function getTimeEntry($id)
  {
    return $this->getShowJsonFor('TimeEntry', $id);
  }

  function getExpense($id)
  {
    return $this->getShowJsonFor('Expense', $id);
  }

  function getInvoice($id)
  {
    return $this->getShowJsonFor('Invoice', $id);
  }

  function getStory($id)
  {
    return $this->getShowJsonFor('Story', $id);
  }

  function getWorkspace($id)
  {
    return $this->getShowJsonFor('Workspace', $id);
  }

  function createWorkspace($workspaceParamsArray)
  {
    $workspaceParamsArray = $this->labelParamKeys('Workspace', $workspaceParamsArray);
    $newPath  = Workspace::getResourcesPath();
    $curl     = $this->createPostRequest($newPath, $this->loginInfo, $workspaceParamsArray);
    $response = curl_exec($curl);

    return $response;
  }

  function updateWorkspace($workspaceId, $workspaceParamsArray)
  {
    $workspaceParamsArray = $this->labelParamKeys('Workspace', $workspaceParamsArray);

    $updatePath = Workspace::getResourcePath($workspaceId);
    $curl       = $this->createPutRequest($updatePath, $this->loginInfo, $workspaceParamsArray);
    $response   = curl_exec($curl);

    return $response;
  }

  function inviteToWorkspace($workspaceId, $invitationParamsArray)
  {
    return $this->createNew('Invitation', $workspaceId, $invitationParamsArray);
  }

  function getAllParticipantsFromWorkspace($workspaceId)
  {
    return $this->getJson(User::getResourcesPath() . "?participant_in=" . $workspaceId);
  }

  function getAllInvoicesFromWorkspace($workspaceId)
  {
    return $this->getJson(Invoice::getResourcesPath() . "?workspace_id=" . $workspaceId);
  }

  function getWorkspaceInvoice($workspaceId, $invoiceId)
  {
    return $this->getJson(Invoice::getWorkspaceResourcePath($workspaceId, $invoiceId));
  }

  function getAllPostsFromWorkspace($workspaceId)
  {
    return $this->getJson(Post::getResourcesPath() . "?workspace_id=" . $workspaceId);
  }

  function createPostForWorkspace($workspaceId, $postParamsArray)
  {
    return $this->createNew('Post', $workspaceId, $postParamsArray);
  }

  function getWorkspacePost($workspaceId, $postId)
  {
    return $this->getJson(Post::getWorkspaceResourcePath($workspaceId, $postId));
  }

  function updateWorkspacePost($workspaceId, $postId, $updateParams)
  {
    return $this->updateModel('Post', $workspaceId, $postId, $updateParams);
  }

  function deleteWorkspacePost($workspaceId, $postId)
  {
    return $this->deleteModel('Post', $workspaceId, $postId);
  }

  function getAllStoriesFromWorkspace($workspaceId)
  {
    return $this->getJson(Story::getResourcesPath() . "?workspace_id=" . $workspaceId);
  }

  function createStoryForWorkspace($workspaceId, $storyParamsArray)
  {
    return $this->createNew('Story', $workspaceId, $storyParamsArray);
  }

  function getWorkspaceStory($workspaceId, $storyId)
  {
    return $this->getJson(Story::getWorkspaceResourcePath($workspaceId, $storyId));
  }

  function updateWorkspaceStory($workspaceId, $storyId, $updateParams)
  {
    return $this->updateModel('Story', $workspaceId, $storyId, $updateParams);
  }

  function deleteWorkspaceStory($workspaceId, $storyId)
  {
    return $this->deleteModel('Story', $workspaceId, $storyId);
  }

  function getAllTimeEntriesFromWorkspace($workspaceId)
  {
    return $this->getJson(TimeEntry::getResourcesPath() . "?workspace_id=" . $workspaceId);
  }

  function createTimeEntryForWorkspace($workspaceId, $timeEntryParamsArray)
  {
    return $this->createNew('TimeEntry', $workspaceId, $timeEntryParamsArray);
  }

  function getWorkspaceTimeEntry($workspaceId, $timeEntryId)
  {
    return $this->getJson(TimeEntry::getWorkspaceResourcePath($workspaceId, $timeEntryId));
  }

  function updateWorkspaceTimeEntry($workspaceId, $timeEntryId, $updateParams)
  {
    return $this->updateModel('TimeEntry', $workspaceId, $timeEntryId, $updateParams);
  }

  function deleteWorkspaceTimeEntry($workspaceId, $timeEntryId)
  {
    return $this->deleteModel('TimeEntry', $workspaceId, $timeEntryId);
  }

  function getAllExpensesFromWorkspace($workspaceId)
  {
    return $this->getJson(Expense::getResourcesPath() . "?workspace_id=" . $workspaceId);
  }

  function createExpenseForWorkspace($workspaceId, $expenseParamsArray)
  {
    return $this->createNew('Expense', $workspaceId, $expenseParamsArray);
  }

  function getWorkspaceExpense($workspaceId, $expenseId)
  {
    return $this->getJson(Expense::getWorkspaceResourcePath($workspaceId, $expenseId));
  }

  function updateWorkspaceExpense($workspaceId, $expenseId, $updateParams)
  {
    return $this->updateModel('Expense', $workspaceId, $expenseId, $updateParams);
  }

  function deleteWorkspaceExpense($workspaceId, $expenseId)
  {
    return $this->deleteModel('Expense', $workspaceId, $expenseId);
  }
  
    // Field Sets
  function getCustomFieldSets()
  {
    return $this->getJsonForAll('CustomFieldSets');
  }
  
  function getCustomFieldSet($id)
  {
    return $this->getShowJsonFor('CustomFieldSets', $id);
  }

  // Custom field values
  function getCustomFieldValues($subject = 'Workspace')
  {
    return $this->getJsonForAll('CustomFieldValues', $subject);
  }

  function getCustomFieldValue($id)
  {
    return $this->getShowJsonFor('CustomFieldValues', $id);
  }

  // Custom fields
  function getCustomFields()
  {
    return $this->getJsonForAll('CustomFields');
  }

  function getJsonForAll($model)
  {
    $resourcesPath = $model::getResourcesPath();
    return $this->getJson($resourcesPath);
  }

  function getShowJsonFor($model, $id)
  {
    $resourcePath = $model::getResourcePath($id);
    return $this->getJson($resourcePath);
  }

  function getJson($path)
  {
    $curl = $this->getCurlHandle($path, $this->loginInfo);

    $json = curl_exec($curl);

    return $json;
  }

  function createNew($model, $workspaceId, $params)
  {
    $params = $this->labelParamKeys($model, array_merge($params, array('workspace_id' => $workspaceId)));

    $newPath = $model::getResourcesPath();
    $curl     = $this->createPostRequest($newPath, $this->loginInfo, $params);
    $response = curl_exec($curl);

    return $response;
  }

  function wrapParamFor($model, $arrayKey)
  {
    return strtolower(preg_replace('/(?<=\\w)(?=[A-Z])/',"_$1", "$model") . "[$arrayKey]");
  }

  function labelParamKeys($model, $paramsArray)
  {
    $labelledArray = array();

    foreach ($paramsArray as $key => $value) {

      if ($this->keyAlreadyWrapped($model, $key))
        {
          $wrappedKey = strtolower($key);
        }
      else {

        $wrappedKey = $this->wrapParamFor($model, $key);
      }

      $labelledArray[$wrappedKey] = $value;
    }

    return $labelledArray;
  }

  function keyAlreadyWrapped($object, $key)
  {
    $object = strtolower(preg_replace('/(?<=\\w)(?=[A-Z])/',"_$1", "$object"));
    $matchPattern = "$object" . "\[\w+\]";
    $matchWrapped = 0;
    $matchWrapped = preg_match("/$matchPattern/", $key);

    return $matchWrapped == 1;
  }

  function updateModel($model, $workspaceId, $resourceId, $params)
  {
    $updatePath = $model::getWorkspaceResourcePath($workspaceId, $resourceId);
    $curl = $this->createPutRequest($updatePath, $this->loginInfo, $params);

    $response = curl_exec($curl);

    return $response;
  }

  function deleteModel($model, $workspaceId, $resourceId)
  {
    $resourcePath = $model::getWorkspaceResourcePath($workspaceId, $resourceId);
    $curl = $this->createDeleteRequest($resourcePath, $this->loginInfo);

    return $response = curl_exec($curl);
  }

  function createPostRequest($url, $accessCredentials, $params)
  {
    $curlHandle = $this->getCurlHandle($url, $accessCredentials);
    curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $params);

    return $curlHandle;
  }

  function createPutRequest($url, $accessCredentials, $params)
  {
    $curlHandle = $this->getCurlHandle($url, $accessCredentials);
    curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $params);

    return $curlHandle;
  }

  function createDeleteRequest($url, $accessCredentials)
  {
    $curlHandle = $this->getCurlHandle($url, $accessCredentials);
    curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, 'DELETE');

    return $curlHandle;
  }

  public static function getBaseUri()
  {
    return self::$devMode ? 'https://mavenlink.local/api/v1/' : 'https://api.mavenlink.com/api/v1/';
  }

  function getCurlHandle($url, $accessCredentials)
  {
    $curlOptions = array
    (
      CURLOPT_URL            => $url,
      CURLOPT_HTTPHEADER     => array('Authorization: Bearer ' . $accessCredentials),
      CURLOPT_RETURNTRANSFER => TRUE
    );

    $curlHandle = curl_init();
    curl_setopt_array($curlHandle, $curlOptions);

    if (self::$devMode)
    {
      curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 0);
    }

    return $curlHandle;
  }
}
?>
