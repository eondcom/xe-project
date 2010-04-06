<?php
    /**
     * @class  projectSmartphone
     * @author zero (skklove@gmail.com)
     * @brief  project 모듈의 SmartPhone class
     **/

    class projectSPhone extends project {

        function procSmartPhone(&$oSmartPhone) {
            $oTemplate = new TemplateHandler();
            $content = $oTemplate->compile($this->module_path.'tpl', 'smartphone');
            $oSmartPhone->setContent($content);
        }
    }
?>
