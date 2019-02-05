<?php
//if set, then a decision was submitted by the manager
if (isset(@@ManagerDecision)) {
    $caseId= @@APPLICATION;
    $userLogged = @@USER_LOGGED;
    $index = @%INDEX;
    
    //Select all the other parallel tasks for the current case that are still open
    $query = "SELECT DEL_INDEX, U.USR_USERNAME FROM APP_DELEGATION AD, USERS U ".
             " WHERE AD.APP_UID = '$caseId' AND AD.DEL_THREAD_STATUS='OPEN' AND ".
             " AD.USR_UID = U.USR_UID AND AD.DEL_INDEX <> $index";
    $threads = executeQuery($query);
    
    if (is_array($threads) and count($threads) > 0) {   
        foreach ($threads as $thread) {
          //Login as the assigned users and route on their tasks 
          $sql = "SELECT USR_PASSWORD FROM USERS WHERE ".
                 "USR_USERNAME = '{$thread['USR_USERNAME']}'";   
          $task_user = executeQuery($sql); 
          if (is_array($task_user) && count($task_user) > 0) {
            $nextpass = 'md5:' . $task_user[1]['USR_PASSWORD'];
            $sessionId = WSLogin($thread['USR_USERNAME'], $nextpass);
$client = new SoapClient('http://localhost/sysworkflow/en/neoclassic/services/wsdl2');        
            $params = array(array(
                'sessionId'=> $sessionId,
                'caseId'    => $caseId, 
                'delIndex'  => $thread['DEL_INDEX']
            ));
            $result = $client->__SoapCall('routeCase', $params);
            if ($result->status_code != 0)
                die("Error routing case: {$result->message}\n");    
          }
        }   
    }
}