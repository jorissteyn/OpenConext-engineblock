<?php
/**
 * SURFconext EngineBlock
 *
 * LICENSE
 *
 * Copyright 2011 SURFnet bv, The Netherlands
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and limitations under the License.
 *
 * @category  SURFconext EngineBlock
 * @package
 * @copyright Copyright © 2010-2011 SURFnet SURFnet bv, The Netherlands (http://www.surfnet.nl)
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

class EngineBlock_Tracker
{
    public function __construct() 
    {
    }
    
    public function trackLogin($spEntityId, $idpEntityId, $subjectId) 
    {
        $db = $this->_getDbConnection();
        
        $stmt = $db->prepare("INSERT INTO log_logins (loginstamp, userid, spentityid, idpentityid) VALUES (now(), :userid, :spentityid, :idpentityid)");
        $stmt->bindParam("userid", $subjectId);
        $stmt->bindParam("spentityid", $spEntityId);
        $stmt->bindParam("idpentityid", $idpEntityId);
        
        $stmt->execute();
    }
    
    protected function _getDbConnection()
    {
        $factory = new EngineBlock_Database_ConnectionFactory();
        return $factory->create(EngineBlock_Database_ConnectionFactory::MODE_WRITE);  
    }
}