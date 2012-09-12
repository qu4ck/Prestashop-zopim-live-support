<?php
/*
 * Author : Airlangga bayu seto
 * Email  : qu4ck@Iso.web.id
 *
 * Free Prestashop Module
 */

if (!defined('_PS_VERSION_'))
    exit;

class Zopim extends Module{
    private $_html='';

    /* instalasi */
    public function  __construct() {
        $this->name     = 'zopim';
        $this->tab      = 'front_office_features';
        $this->version  = 1.0;
        $this->author   = 'Airlangga bayu seto';
        $this->need_instance = 0;
        parent::__construct();
        $this->displayName = $this->l('Zopim Chat');
        $this->description = $this->l('Zopim Chat for prestashop Module [http://www.iso.web.id]');
    }

    public function install() {
        if(!parent::install()
           OR !$this->registerHook('Header')
           OR !Configuration::updateValue('ZOPIM_KEYS', '')
          ){
            return false;
        }
        return true;
    }

    public function uninstall() {
        if(!parent::uninstall()
            OR !$this->unregisterHook('Header')
            OR !Configuration::deleteByName('ZOPIM_KEYS')
        ){
            return false;
        }
        return true;
    }

    /* konfigurasi */
    public function getContent(){
        if (Tools::isSubmit('submitZopim')){
            $key = trim(Tools::getValue('key'));
            if (!$key AND $key != ""){
                $errors[] = $this->l('Zopim key Cannot Empty!');
            }else{
                Configuration::updateValue('ZOPIM_KEYS', $key);
            }

            if (isset($errors) AND sizeof($errors)){
                $this->_html .= $this->displayError(implode('<br />', $errors));
            }else{
                $this->_html .= $this->displayConfirmation($this->l('Zopim Success Configure'));
            }
        }

        $this->_displayForm();

        return $this->_html;
    }

    private function _displayForm(){
        $this->_html  .= "<link type=\"text/css\" rel=\"stylesheet\" href=\""._PS_BASE_URL_.__PS_BASE_URI__."modules/".$this->name."/assets/css/admin.css\" />";
        $this->_html .="<fieldset>
                <legend><img src=\""._PS_BASE_URL_.__PS_BASE_URI__."modules/".$this->name."/logo.gif\" alt=\"".$this->name."\" /> ".$this->l('Zopim Configuration')."</legend>
                <div class=\"zopim\">
                    <div class=\"left\">
                        <form action=\"".$this->_baseUrl."\" method=\"post\">
                            <label>".$this->l('Zopim Key')."</label>
                            <div class=\"margin-form\">
                                <input type=\"text\" size=\"40\" name=\"key\" value=\"".Tools::safeOutput(Tools::getValue('key', (Configuration::get('ZOPIM_KEYS'))))."\" />
                            </div>
                            <input type=\"submit\" name=\"submitZopim\" value=\"".$this->l('Save')."\" class=\"button\" />
                        </form>
                    </div>
                    <div class=\"right\">
                        ".$this->l('Login to your Zopim Dashboard, Click Widget menu and copy keys zopim')."<br />
                        <img src=\""._PS_BASE_URL_.__PS_BASE_URI__."modules/".$this->name."/assets/images/keys.png\" alt=\"".$this->l('Example zopim Key')."\" />
                    </div>
                </div>
                <div class=\"clear\"></div>
            </fieldset>";

        return $this->_html;
    }


    /* Hook */
    function hookHeader($params){
        global $smarty;

        $smarty->assign(
            array("key" => Configuration::get('ZOPIM_KEYS'))
        );

        return $this->display(__FILE__, 'assets/tpl/zopim.tpl');
    }
}
?>
