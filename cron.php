<?php
try {
   $taskId = '188586462577ed40a325599037697166'; //set to the ID of the self service task
   $caseId = @@APPLICATION;
   //lookup delegation index of self service task in database:
   $sql = "SELECT DEL_INDEX FROM APP_DELEGATION WHERE APP_UID='$caseId' AND 
      TAS_UID='$taskId' ORDER BY DEL_INDEX DESC";
   $aDelegations = executeQuery($sql) or 
      throw new Exception("Error in DB query: $sql");
   if (count($aDelegations) == 0) {
      throw new Exception("Unable to find delegation index for self service task with query: $sql");
   }
   $index = $aDelegations[1]['DEL_INDEX'];
   $d = new Derivation();
   $aUsers = $d->getAllUsersFromAnyTask($taskId);
   $cnt = count($aUsers);
   if ($cnt == 0) {
      throw new Exception("No users assigned to self service task.");
   }
   $userToAssign = $aUsers[rand(0, $cnt - 1)];
   $aUser = userInfo($userToAssign);
   $to = $aUser['firstname'].' '.$aUser[lastname].' <'.$aUser['mail'].'>';
   $c = new Cases();
   $c->setCatchUser($caseId, $index, $userToAssign);
   PMFSendMessage($caseId, 'admin@example.com', $to, '', '', 
      "You're assigned to case #".@@APP_NUMBER, 'caseAssignment.html', array());
} 
catch (Exception $e) {
   $msg = "\nException in timeout trigger in case #".@@APP_NUMBER.' at '.date('Y-m-d_H:i:s').": \n".$e->getMessage();
   print $msg."\n"; //print in output of cron.php
   file_put_contents(PATH_DATA.'log'.PATH_SEP.'timeoutExceptions.php', $msg, FILE_APPEND);
}
?>