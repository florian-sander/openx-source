<?php

/*
+---------------------------------------------------------------------------+
| Openads v${RELEASE_MAJOR_MINOR}                                                              |
| ============                                                              |
|                                                                           |
| Copyright (c) 2003-2007 Openads Limited                                   |
| For contact details, see: http://www.openads.org/                         |
|                                                                           |
| This program is free software; you can redistribute it and/or modify      |
| it under the terms of the GNU General Public License as published by      |
| the Free Software Foundation; either version 2 of the License, or         |
| (at your option) any later version.                                       |
|                                                                           |
| This program is distributed in the hope that it will be useful,           |
| but WITHOUT ANY WARRANTY; without even the implied warranty of            |
| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             |
| GNU General Public License for more details.                              |
|                                                                           |
| You should have received a copy of the GNU General Public License         |
| along with this program; if not, write to the Free Software               |
| Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA |
+---------------------------------------------------------------------------+
$Id$
*/

class OA_UpgradePostscript
{
    var $oUpgrade;

    function OA_UpgradePostscript()
    {

    }

    function execute($aParams)
    {
        $this->oUpgrade = & $aParams[0];
        if (!$this->configMax())
        {
            return false;
        }
        $this->oUpgrade->addPostUpgradeTask('Rebuild_Banner_Cache');
        $this->oUpgrade->addPostUpgradeTask('Maintenance_Priority');
        return true;
    }

    function configMax()
    {
        if ($this->oUpgrade->oConfiguration->isMaxConfigFile())
        {
            if (!$this->oUpgrade->oConfiguration->replaceMaxConfigFileWithOpenadsConfigFile())
            {
                $this->oUpgrade->oLogger->logError('Failed to replace your old configuration file with a new Openads configuration file');
                $this->oUpgrade->message = 'Failed to replace your old configuration file with a new Openads configuration file';
                return false;
            }
            $this->oUpgrade->oLogger->log('Replaced your old configuration file with a new Openads configuration file');
            $this->oUpgrade->oConfiguration->setMaxInstalledOff();
            $this->oUpgrade->oConfiguration->writeConfig();
        }
        if (!$this->oUpgrade->oVersioner->removeMaxVersion())
        {
            $this->oUpgrade->oLogger->logError('Failed to remove your old application version');
            $this->oUpgrade->message = 'Failed to remove your old application version';
            return false;
        }
        $this->oUpgrade->oLogger->log('Removed old application version');
        $this->oUpgrade->oConfiguration->setupConfigPriority('');
        if (!$this->oUpgrade->oConfiguration->writeConfig())
        {
            $this->oUpgrade->oLogger->logError('Failed to set the randmax priority value');
            $this->oUpgrade->message = 'Failed to set the randmax priority value';
            return false;
        }
        return true;
    }
}

?>