<?php
require("./configuration/model/Configuration.class.php");
require("./configuration/model/Colors.class.php");

class ConfigurationController
{
    public function __construct() {
        $this->config = new Configuration();
        $this->colors = new Colors();
    }

    public function selectConfiguration() {
        return $this->config->getConfiguration();
    }

    public function selectThemeFromConfiguration($id_color) {
        $colors = $this->colors->getColorsFromConfiguration($id_color);
        
        if ($colors) {
            $colori = explode(',',$colors['palette']);
            $color['sfondo']     = $colori[0];
            $color['sfondo2']    = $colori[1];
            $color['principale'] = $colori[2];
            $color['subtitle']   = $colori[3];
            $color['secondario'] = $colori[4];
            $color['link']       = $colori[5];
        }

        return $color;
    }

    public function viewThemePicker() {
        $html = 'Scegli un tema:';
        $html .= '<div style="display: flex;">';
        $html .= '<select class="form-control" name="theme-id" style="flex:1;">';
        foreach ($this->colors->getAllThemes() as $theme) {
            $html .= '<option value="' . $theme['id'] . '"';
            if ($theme['id'] == $this->config->getConfiguration()['id_color']) {
                $html .= ' selected="selected"';
            }
            $html .= '>' . ucfirst($theme['description']) . '</option>';
        }
        $html .= '</select>';
        $html .= '</div>';

        echo $html;
    }

    public function updateConfigInfo($request, $file) {
        $nameSite = $request['namesite'];
        $descriptionSite = $request['descriptionsite'];
        $themeId = $request['theme-id'];
        $nameLogo = $file['name'];
        $validate = false;

        
        if (!empty($themeId) && isset($themeId)) {
            if ($this->config->saveTheme($themeId)) {
                $validate = true;
            } else {
                $idmsg = 14;
                $validate = false;
            }
        }

        if (!empty($nameLogo) && isset($nameLogo)) {
            if (isset($file['tmp_name'])) {
                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $newFileName = './assets/images/logo_' . strtolower(str_replace(" ", "", $this->config->getConfiguration()['name'])) . '.' . $extension;
        
                // Percorso del file temporaneo
                $fileTemp = $file['tmp_name'];
        
                // Salva l'immagine nel database
                if ($this->config->storeLogo($newFileName)) {
                    move_uploaded_file($fileTemp, $newFileName);
                    $validate = true;
                } else {
                    $idmsg = 15;
                    $validate = false;
                }
            }
        }

        if (!empty($nameSite) || !empty($descriptionSite)) {
            if ($this->config->saveInfoSite($nameSite, $descriptionSite)) {
                $validate = true;
            } else {
                $idmsg = 16;
                $validate = false;
            }
        }

        if ($validate) {
            header("Location: " . refreshPage() . "&idmsg=17");
            return true;
        } else {
            header("Location: " . refreshPage() . "&" . $idmsg);
            return false;
        }
    }
      
}