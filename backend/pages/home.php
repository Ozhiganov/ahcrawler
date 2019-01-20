<?php
/**
 * HOME
 */
$oRenderer=new ressourcesrenderer($this->_sTab);
$sHtml='';


if(!$this->oDB){
    
    // ------------------------------------------------------------
    // INITIAL SETUP PART ONE
    // program settings
    // ------------------------------------------------------------
    $oRenderer=new ressourcesrenderer($this->_sTab);
    $sHtml.=''
    . '<h3>' . $this->lB('home.welcome') . '</h3>'
    .$this->lB('home.welcome.introtext').'<br><br>'
    .$oRenderer->oHtml->getTag('a',array(
        'href' => '?page=setup',
        'class' => 'pure-button button-secondary',
        'title' => $this->lB('nav.setup.label.hint'),
        'label' => $this->lB('nav.setup.label'),
    ))
    ;
} else {

    $aOptions = $this->_loadOptions();
    $aProfiles=$this->getProfileIds();
    $aTable=array();
    $sTable='';
    $sTiles='';
    if(!$aProfiles || !count($aProfiles)){
        // ------------------------------------------------------------
        // INITIAL SETUP PART TWO
        // setup a website profile
        // ------------------------------------------------------------
        $sTiles.=''
            . '<h3>' . $this->lB('home.welcome') . '</h3>'
            // . $oRenderer->renderTile('', $this->lB('nav.profiles.label'), 0, '', '')
            . $this->lB('home.noprojectyet').'<br><br>'
            . $this->_getButton(array(
                        'href'=>'?page=profiles&tab=add',
                        'popup'=>false,
                        'class'=>'button-secondary',
                        'label'=>'button.add',
                        ))
                ;
    } else {
        // ------------------------------------------------------------
        // DEFAULT INTRO PAGE
        // ------------------------------------------------------------
        $iPagesTotal=$this->getRecordCount('pages');
        $iResTotal=$this->getRecordCount('ressources');
        $iSearchesTotal=$this->getRecordCount('searches');
        
        
        $sTiles.=''
                . $oRenderer->renderTile('', $this->lB('nav.profiles.label'), count($aProfiles), '', '')
                . $oRenderer->renderTile('', $this->lB('nav.search.label'), $iPagesTotal, '', '')
                . $oRenderer->renderTile('', $this->lB('nav.ressources.label'), $iResTotal, '', '')
                . $oRenderer->renderTile('', $this->lB('nav.searches.label'), $iSearchesTotal, '', '')
                ;
        $aTable[]=array(
            $this->lB('nav.profiles.label'),
            // '',
            $this->lB('nav.search.label'),
            $this->lB('nav.ressources.label'),
            $this->lB('nav.searches.label'),
        );
        foreach($aProfiles as $iProfileId){
            $this->setSiteId($iProfileId);
            
            $iPages=$this->getRecordCount('pages', array('siteid'=>$iProfileId));
            $iRes=$this->getRecordCount('ressources', array('siteid'=>$iProfileId));
            $iSearches=$this->getRecordCount('searches', array('siteid'=>$iProfileId));
            
            $aTable[]=array(
                '<strong>'.$this->aProfile['label'].'</strong><br>'
                    . $this->aProfile['description'].'<br><br>'
                    . $this->_getButton(array(
                                'href'=>'?page=profiles&tab='.$iProfileId,
                                'popup'=>false,
                                'class'=>'button-secondary',
                                'label'=>'button.edit',
                                ))
                ,
                $iPages
                        ? '<div class="tdcenter">'
                            . '<strong>'.$iPages.'</strong><br><br>'
                            . $this->getLastTsRecord('pages', array('siteid'=>$iProfileId)).'<br>'
                            . $oRenderer->hrAge(date('U', strtotime($this->getLastTsRecord('pages', array('siteid'=>$iProfileId))))).'<br>'
                            . $this->_getButton(array(
                                'href'=>'?page=status&tab='.$iProfileId,
                                'popup'=>false,
                                'class'=>'button-secondary',
                                ))
                          .'</div>'
                        : '-'
                    ,
                
                    $iRes
                        ? '<div class="tdcenter">'
                            . '<strong>'.$iRes.'</strong><br><br>'
                            . $this->getLastTsRecord('ressources', array('siteid'=>$iProfileId)).'<br>'
                            . $oRenderer->hrAge(date('U', strtotime($this->getLastTsRecord('ressources', array('siteid'=>$iProfileId))))).'<br>'
                            . $this->_getButton(array(
                                'href'=>'?page=ressources&tab='.$iProfileId,
                                'popup'=>false,
                                'class'=>'button-secondary',
                                ))
                          .'</div>'
                        : '-'
                    ,
                
                    $iSearches
                        ? '<div class="tdcenter">'
                            . '<strong>'.$iSearches.'</strong><br><br>'
                            .$this->getLastTsRecord('searches', array('siteid'=>$iProfileId)).'<br>'
                            . $oRenderer->hrAge(date('U', strtotime($this->getLastTsRecord('searches', array('siteid'=>$iProfileId))))).'<br>'
                            . $this->_getButton(array(
                                'href'=>'?page=searches&tab='.$iProfileId,
                                'popup'=>false,
                                'class'=>'button-secondary',
                                // 'label'=>'searches',
                                ))
                          .'</div>'
                        : '-'
                    ,
            );
        }
        $sHtml.=$this->_renderChildItems($this->_aMenu)
            // . '<h3>' . $this->lB('home.welcome') . '</h3>'
            /*
            . (!$this->_getUser() && (
                    !array_key_exists('PHP_AUTH_USER', $_SERVER)
                    || !$_SERVER['PHP_AUTH_USER']
                    )
             ? '<br><br><div class="message message-warning">' . $oRenderer->renderShortInfo('warn') . $this->lB('home.cfg.unprotected') . '</div><br><br>' 
            : ''
            )
             */
            //. '<p>' . $this->lB('home.welcome-introtext') . '</p>'


            // . '<h3>' . $this->lB('home.status') . '</h3>'
            . '<p>' . $this->lB('home.status.hint') . '</p>'
            ;
        $sTable=$this->_getSimpleHtmlTable($aTable, true);
    }

    $sHtml.=''
            .$oRenderer->renderTileBar($sTiles).'<div style="clear: both;"></div>'
            .$sTable
            ;
}
return $sHtml;