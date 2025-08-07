<?php
class Component
{
    /**
     * Creates an HTML title with the given text.
     *
     * @param string $text The text of the title.
     * @return void
     */
    public static function createTitle($text)
    {
        echo <<<HTML
        <div class="title">
            <h3>$text</h3>
        </div>
        <br>
        HTML;
    }

    /**
     * Creates an HTML input text field with optional label and placeholder.
     *
     * @param string|null $name The name of the input field.
     * @param string|null $label The label for the input field.
     * @param string|null $value The value of the input field.
     * @param string|null $placeholder The placeholder text for the input field.
     * @param bool $required Whether the input field is required.
     * @param string $type The type of the input field. Default is 'text'.
     */
    public static function createInputText(
        $name = null,
        $label = null,
        $value = null,
        $placeholder = null,
        $required = false,
        $type = 'text'
    ) {
        $inputAttributes = [
            'class' => 'form-control',
            'type' => $type,
            'step' => $type == 'number' ? 'any' : null,
            'name' => $name,
            'id' => $name,
            'value' => $value,
            'placeholder' => $placeholder,
            'required' => $required ? 'required' : null
        ];

        $html = '<div class="form-group">';
        if ($label) {
            $html .= "<label for=\"$name\">$label:</label>";
        }
        $html .= '<input ' . self::convertArrayToAttributes($inputAttributes) . '>';
        $html .= '</div>';

        echo $html;
    }

    /**
     * Converts an array of attributes to a string of HTML attribute="value" pairs.
     *
     * @param array $attributes The array of attributes.
     * @return string The HTML string of attribute="value" pairs.
     */
    private static function convertArrayToAttributes(array $attributes): string
    {
        $html = '';
        foreach ($attributes as $attribute => $value) {
            if (!empty($value)) {
                $html .= " $attribute=\"$value\"";
            }
        }
        return $html;
    }

    /**
     * Creates an HTML select input with options.
     *
     * @param string|null $name The name of the select input.
     * @param string|null $label The label for the select input.
     * @param array|null $options The options for the select input.
     * @param bool $isRequired Whether the select input is required.
     * @param bool $multiple Whether the select input allows multiple selections.
     * @param bool $includeBlank Whether to include a blank option at the beginning of the select input.
     * @param bool $formatDates Whether to format the option values as dates.
     */
    public static function createInputSelect(
        $name = null, 
        $label = null, 
        $options = null, 
        $isRequired = false, 
        $multiple = false, 
        $includeBlank = false, 
        $formatDates = false
    ) {
        global $monthsList;
        $labelHtml = !empty($label) ? "<label for=\"$name\">$label:</label>" : '';
        $inputNameHtml = !empty($name) ? " name=\"$name\" id=\"$name\"" : '';
        $requiredHtml = $isRequired ? ' required' : '';
        $multipleHtml = $multiple ? ' multiple' : '';
        $includeBlankHtml = $includeBlank ? '<option value="">Seleziona</option>' : '';
        $name = $multiple ? str_replace('[]', '', $name) : $name;
        $selectedFields = $multiple ? (isset($_REQUEST[$name]) ? $_REQUEST[$name] : []) : (isset($_REQUEST[$name]) ? ' selected' : '');
        $isSelected = '';

        $html = <<<HTML
            <div class="form-group">
                $labelHtml
                <select class="form-control" $inputNameHtml$requiredHtml$multipleHtml>
                    $includeBlankHtml
        HTML;
        
        foreach ($options as $key => $value) {
            $isSelected = (!empty($selectedFields) && in_array($key, $selectedFields)) ? ' selected' : '';
            
            if ($formatDates && isset($monthsList[$value])) {
                $optionValue = $monthsList[$value][1]; // Usa il nome italiano
            } else {
                $optionValue = $value;
            }

            $html .= "<option value=\"$key\"$isSelected>$optionValue</option>";
        }

        $html .= "</select></div>";

        echo $html;
    }

    /**
     * Creates a submit button for a form.
     *
     * @param string $label The text to display on the button.
     * @param string $priority The priority level of the button (primary, secondary, etc.).
     * @param string|null $name The name of the button.
     * @return void
     */
    public static function createSubmitButton($label, $priority, $name = null)
    {
        $buttonName = !empty($name) ? " name=\"{$name}\"" : '';
        $buttonClass = "btn btn-{$priority}";
        $buttonHtml = "<button type='submit' class='{$buttonClass}' {$buttonName}>{$label}</button>";

        echo $buttonHtml;
    }
}