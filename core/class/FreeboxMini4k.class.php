<?php
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
class FreeboxMini4k extends eqLogic {	
	public function toHtml($_version = 'mobile') {
		$replace = $this->preToHtml($_version);
		if (!is_array($replace)) {
			return $replace;
		}
		$version = jeedom::versionAlias($_version);
		if ($this->getDisplay('hideOn' . $version) == 1) {
			return '';
		}
		$replace['#cmd#']='';
		foreach ($this->getCmd(null, null, true) as $cmd) {
			if($cmd->getIsVisible())	
			$replace['#'.$cmd->getLogicalId().'#'] = $cmd->toHtml($_version);
		}
		return $this->postToHtml($_version, template_replace($replace, getTemplate('core', $version, 'FreeboxTv', 'FreeboxMini4k')));
	}
	public function AddCommande($Name,$_logicalId,$Type="info", $SubType='binary', $Template='default', $unite='') {
		$Commande = $this->getCmd(null,$_logicalId);
		if (!is_object($Commande)){
			$Commande = new FreeboxMini4kCmd();
			$Commande->setId(null);
			$Commande->setLogicalId($_logicalId);
			$Commande->setEqLogic_id($this->getId());
			$Commande->setName($Name);
			$Commande->setUnite($unite);
			$Commande->setType($Type);
			$Commande->setSubType($SubType);
			$Commande->setTemplate('dashboard',$Template);
			$Commande->setTemplate('mobile', $Template);
			$Commande->save();
		}
		return $Commande;
	}
	public function postSave() {	
		$ActionPower=$this->AddCommande('Power','power',"action",'other','Freebox_Tv');
		$InfoPower=$this->AddCommande(' Statut Power','powerstat',"info",'binary','Freebox_Tv');
		$ActionPower->setValue($InfoPower->getId());
		$ActionPower->save();
		$this->AddCommande('Volume +','vol_inc',"action",'other','Freebox_Tv');
		$this->AddCommande('Volume -','vol_dec',"action",'other','Freebox_Tv');
		$this->AddCommande('Programme +','prgm_inc',"action",'other','Freebox_Tv');
		$this->AddCommande('Programme -','prgm_dec',"action",'other','Freebox_Tv');
		$this->AddCommande('Home','home',"action",'other','Freebox_Tv');
		$this->AddCommande('Mute','mute',"action",'other','Freebox_Tv');
		$this->AddCommande('Enregistrer','rec',"action",'other','Freebox_Tv');
		$this->AddCommande('1','1',"action",'other','Freebox_Tv');
		$this->AddCommande('2','2',"action",'other','Freebox_Tv');
		$this->AddCommande('3','3',"action",'other','Freebox_Tv');
		$this->AddCommande('4','4',"action",'other','Freebox_Tv');
		$this->AddCommande('5','5',"action",'other','Freebox_Tv');
		$this->AddCommande('6','6',"action",'other','Freebox_Tv');
		$this->AddCommande('7','7',"action",'other','Freebox_Tv');
		$this->AddCommande('8','8',"action",'other','Freebox_Tv');
		$this->AddCommande('9','9',"action",'other','Freebox_Tv');
		$this->AddCommande('0','0',"action",'other','Freebox_Tv');
		$this->AddCommande('Precedent','prev',"action",'other','Freebox_Tv');
		$this->AddCommande('Lecture','play',"action",'other','Freebox_Tv');
		$this->AddCommande('Suivant','next',"action",'other','Freebox_Tv');
		$this->AddCommande('Rouge','red',"action",'other','Freebox_Tv');
		$this->AddCommande('Vert','green',"action",'other','Freebox_Tv');
		$this->AddCommande('Bleu','blue',"action",'other','Freebox_Tv');
		$this->AddCommande('Jaune','yellow',"action",'other','Freebox_Tv');
		$this->AddCommande('Ok','ok',"action",'other','Freebox_Tv');
		$this->AddCommande('Haut','up',"action",'other','Freebox_Tv');
		$this->AddCommande('Bas','down',"action",'other','Freebox_Tv');
		$this->AddCommande('Gauche','left',"action",'other','Freebox_Tv');
		$this->AddCommande('Droite','right',"action",'other','Freebox_Tv');		
	}
	public static function dependancy_info() {
		$return = array();
		$return['log'] = 'FreeboxMini4k_update';
		$return['progress_file'] = '/tmp/compilation_FreeboxMini4k_in_progress';
		if (exec('dpkg -s netcat | grep -c "Status: install"') ==1)
				$return['state'] = 'ok';
		else
			$return['state'] = 'nok';
		return $return;
	}
	public static function dependancy_install() {
		if (file_exists('/tmp/compilation_FreeboxMini4k_in_progress')) {
			return;
		}
		log::remove('FreeboxMini4k_update');
		$cmd = 'sudo /bin/bash ' . dirname(__FILE__) . '/../../ressources/install.sh';
		$cmd .= ' >> ' . log::getPathToLog('FreeboxMini4k_update') . ' 2>&1 &';
		exec($cmd);
	}
	public static function cron() {
		foreach(eqLogic::byType('FreeboxMini4k') as $FreeboxMini4k){
			if($FreeboxMini4k->getIsEnable())
				$FreeboxMini4k->getCmd('info','powerstat')->execute();
		}
	}
}
class FreeboxMini4kCmd extends cmd {
	public function execute($_options = array()){		
		switch($this->getLogicalId()){
			case 'powerstat':
				$result=exec('nc -zv '.$this->getEqLogic()->getConfiguration('FREEBOX_TV_IP').' 7000 2>&1 | grep -E "open|succeeded" | wc -l');
				$this->getEqLogic()->checkAndUpdateCmd($this->getLogicalId(),$result);
				log::add('FreeboxMini4k','debug','Etat du player freebox '.$this->getEqLogic()->getConfiguration('FREEBOX_TV_IP').' '.$result);
			break;
			case 'power':
				$cmd = 'sudo '.dirname(__FILE__) .'/../../ressources/mini4k_cmd '.$this->getEqLogic()->getConfiguration('FREEBOX_TV_IP').' '.$this->getLogicalId();
				$result=exec($cmd);
				log::add('FreeboxMini4k','debug', 'Mini 4K : ',$cmd);
				$this->getEqLogic()->getCmd('info','powerstat')->execute();
			break;
			default:
				$cmd = 'sudo '.dirname(__FILE__) .'/../../ressources/mini4k_cmd '.$this->getEqLogic()->getConfiguration('FREEBOX_TV_IP').' '.$this->getLogicalId();
				$result=exec($cmd);
				log::add('FreeboxMini4k','debug', 'Mini 4K : ',$cmd);
			break;
		}
	}
}
