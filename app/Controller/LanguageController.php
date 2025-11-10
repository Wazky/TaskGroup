<?php
// file: /app/Controller/LanguageController.php

require_once(__DIR__.'/../core/I18n.php');

class LanguageController {
    const LANGUAGE_SETTING = "__language__";

    public function change() {
        if (!isset($_GET["lang"])) {
            throw new Exception("no lang parameter was provided");
        }

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        I18n::getInstance()->setLanguage($_GET["lang"]);

        header("Location: ".$_SERVER["HTTP_REFERER"]);
        die();
    }

    public function i18njs() {
        header("Content-type: application/javascript");
        echo "var i18Messages = [];\n";
        echo "function ji18n(key) { if (key in i18Messages) return i18Messages[key]; else return key; }\n";
        foreach (I18n::getInstance()->getAllMessages() as $key => $value) {
            echo "i18Messages['$key'] = '$value';\n";
        }
    }
}

?>