<?php

/**
 * SimpleForm class file.
 *
 * @author Wang Dongyang <wangdy@trunkbow.com>
 * @copyright Copyright &copy; 2001-2010 Trunkbow international
 */
/*
 * @author Wang Dongyang <wangdy@trunkbow.com>
 * @version $Id: SimpleForm.php 2597 $
 * @package application.components.widgets
 * @since 1.1.1
 * 修改js表单验证，增加表单样式。liuxy 2014-9-17
 */
class SimpleForm extends CWidget {

    /**
     * @var mixed the form action URL (see {@link CHtml::normalizeUrl} for details about this parameter).
     * If not set, the current page URL is used.
     */
    public $action = '';

    /**
     * @var string the form submission method. This should be either 'post' or 'get'.
     * Defaults to 'post'.
     */
    public $method = 'post';

    /**
     * @var boolean whether to generate a stateful form (See {@link CHtml::statefulForm}). Defaults to false.
     */
    public $stateful = false;

    /**
     * @var string the CSS class name for error messages. Defaults to 'errorMessage'.
     * Individual {@link error} call may override this value by specifying the 'class' HTML option.
     */
    public $errorMessageCssClass = 'errorPrompt';

    /**
     * @var array additional HTML attributes that should be rendered for the form tag.
     */
    public $htmlOptions = array();

    /**
     * @var array the options to be passed to the javascript validation plugin.
     *
     * Some of the above options may be overridden in individual calls of {@link error()}.
     * They include: validationDelay, validateOnChange, validateOnType, hideErrorMessage,
     * inputContainer, errorCssClass, successCssClass, validatingCssClass, beforeValidateAttribute, afterValidateAttribute.
     */
    public $clientOptions = array();

    /**
     * @var boolean whether to enable data validation via AJAX. Defaults to false.
     * When this property is set true, you should respond to the AJAX validation request on the server side as shown below:
     * <pre>
     * public function actionCreate()
     * {
     *     $model=new User;
     *     if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
     *     {
     *         echo CActiveForm::validate($model);
     *         Yii::app()->end();
     *     }
     *     ......
     * }
     * </pre>
     */
    public $enableAjaxValidation = false;
    public $enableAjaxSubmit = false;

    /**
     * @var string 表单提交后更新的DOM元素ID
     */
    public $ajaxUpdateId = '';

    /**
     * @var mixed form element to get initial input focus on page load.
     *
     * Defaults to null meaning no input field has a focus.
     * If set as array, first element should be model and second element should be the attribute.
     * If set as string any jQuery selector can be used
     *
     * Example - set input focus on page load to:
     * <ul>
     * <li>'focus'=>array($model,'username') - $model->username input filed</li>
     * <li>'focus'=>'#'.CHtml::activeId($model,'username') - $model->username input field</li>
     * <li>'focus'=>'#LoginForm_username' - input field with ID LoginForm_username</li>
     * <li>'focus'=>'input[type="text"]:first' - first input element of type text</li>
     * <li>'focus'=>'input:visible:enabled:first' - first visible and enabled input element</li>
     * <li>'focus'=>'input:text[value=""]:first' - first empty input</li>
     * </ul>
     *
     * @since 1.1.4
     */
    public $focus;
    private $_attributes = array();
    private $_summary;
    private $_validator = array();
    static private $__fid = 1;
    public $role = 'form';
    public $formClass = 'form-horizontal'; //bootstrap表单对齐样式

    public $autoValidation = true;//是否自动执行表单认证。
    
    /**
     * Initializes the widget.
     * This renders the form open tag.
     */

    public function init() {
        
        //Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/bootstrap3-validation.js',CClientScript::POS_BEGIN);
        $this->htmlOptions['id'] = $this->id;
        $this->htmlOptions['class'] = $this->formClass;
        $this->htmlOptions['role'] = $this->role;
        
        //echo $this->callbackFunction;
        
        if ($this->stateful)
            echo CHtml::statefulForm($this->action, $this->method, $this->htmlOptions);
        else
            echo CHtml::beginForm($this->action, $this->method, $this->htmlOptions);
    }

    /**
     * 把校验器对象存入SESSION
     * @param array 校验器
     * @return 生成的ID值,存入表单中,提交的时候凭之取出校验器
     */
    private function pushFormValidator($v) {
        $t = time();
        $fid = $t . '_' . md5($this->id . self::$__fid);
        self::$__fid += 1;

        $k = '_fv_' . $fid;
        Yii::app()->session[$k] = $v; //
        //var_dump(Yii::app()->session[$k]);
        return $fid;
    }

    /**
     * 从SESSION取出校验器
     * @param String 校验器ID
     * @return 存入表单中,提交的时候凭之取出校验器
     */
    static public function popFormValidator($fid) {
        $k = '_fv_' . $fid;
        //var_dump(Yii::app()->session[$k]);
        if (Yii::app()->session[$k] == '') {
            return '';
        }
        $v = Yii::app()->session[$k];
        unset(Yii::app()->session[$k]);
        return $v;
    }

    /**
     * Runs the widget.
     * This registers the necessary javascript code and renders the form close tag.
     */
    public function run() {
        if (is_array($this->focus))
            $this->focus = "#" . CHtml::activeId($this->focus[0], $this->focus[1]);


        if (count($this->_validator) > 0) {
            //$v = serialize($this->_validator);
            $fid = $this->pushFormValidator($this->_validator);
            echo '<input type="hidden" name="_fid_" value="' . $fid . '" />';
        }
        echo CHtml::endForm();

        $id = $this->id;

        if ($this->enableAjaxSubmit) {
            ?>

            <script type="text/javascript">

                jQuery(document).ready(function() {

                    jQuery("#<?php echo $id; ?>").submit(function() {
                   	 	if ($("#<?php echo $id; ?>").valid() == false)
                        	return false;
						var disable_input = jQuery("#<?php echo $id; ?> :input:disabled");  //提交的disable input
                        jQuery(this).ajaxSubmit({
                            target: "#<?php echo $this->ajaxUpdateId; ?>",
                            beforeSend: function(XMLHttpRequest) {
                                jQuery("#<?php echo $id; ?> :input").attr("disabled", true);
                            },
                            success: function(data, textStatus) {
                                //$(".ajax.ajaxResult").html("");
                                //jQuery("#overlay-content3").html(data);
                                //alert(data);
                            },
                            complete: function(XMLHttpRequest, textStatus) {
                                jQuery("#<?php echo $id; ?> :input").attr("disabled", false);
                                disable_input.attr("disabled", true);
                            },
                            error: function() {
                                //请求出错处理
                                alert('请求失败');

                            }
                        });
                        return false;
                    });
                });

            </script>
            <?php
        } else {
            ?>

            <script type="text/javascript">

                jQuery(document).ready(function() {

                    /**
                     * <?php if($this->autoValidation):?>$("#<?php echo $id; ?>").validation();<?php endif;?>
                     */
                    jQuery("#<?php echo $id; ?>").submit(function() {
                        if ($("#<?php echo $id; ?>").valid() == false)
                            return false;
                    });
                });

            </script>
            <?php
        }

        if (!$this->enableAjaxValidation || empty($this->_attributes)) {
            Yii::app()->clientScript->registerScript('CActiveForm#focus', "
				if(!window.location.hash)
					$('" . $this->focus . "').focus();
			");
            return;
        }

        $options = $this->clientOptions;
        if (isset($this->clientOptions['validationUrl']) && is_array($this->clientOptions['validationUrl']))
            $options['validationUrl'] = CHtml::normalizeUrl($this->clientOptions['validationUrl']);

        $options['attributes'] = array_values($this->_attributes);

        if ($this->_summary !== null)
            $options['summaryID'] = $this->_summary;

        if ($this->focus !== null)
            $options['focus'] = $this->focus;

        $options = CJavaScript::encode($options);
        Yii::app()->clientScript->registerCoreScript('yiiactiveform');
        $id = $this->id;
        Yii::app()->clientScript->registerScript(__CLASS__ . '#' . $id, "\$('#$id').yiiactiveform($options);");
    }

    /**
     * Displays the first validation error for a model attribute.
     * This is similar to {@link CHtml::error} except that it registers the model attribute
     * so that if its value is changed by users, an AJAX validation may be triggered.
     * @param CModel $model the data model
     * @param string $attribute the attribute name
     * @param array $htmlOptions additional HTML attributes to be rendered in the container div tag.
     * Besides all those options available in {@link CHtml::error}, the following options are recognized in addition:
     * <ul>
     * <li>validationDelay</li>
     * <li>validateOnChange</li>
     * <li>validateOnType</li>
     * <li>hideErrorMessage</li>
     * <li>inputContainer</li>
     * <li>errorCssClass</li>
     * <li>successCssClass</li>
     * <li>validatingCssClass</li>
     * <li>beforeValidateAttribute</li>
     * <li>afterValidateAttribute</li>
     * </ul>
     * These options override the corresponding options as declared in {@link options} for this
     * particular model attribute. For more details about these options, please refer to {@link clientOptions}.
     * Note that these options are only used when {@link enableAjaxValidation} is set true.
     * @param boolean $enableAjaxValidation whether to enable AJAX validation for the specified attribute.
     * Note that in order to enable AJAX validation, both {@link enableAjaxValidation} and this parameter
     * must be true.
     * @return string the validation result (error display or success message).
     * @see CHtml::error
     */
    public function error($model, $attribute, $htmlOptions = array(), $enableAjaxValidation = true) {
        if (!$this->enableAjaxValidation || !$enableAjaxValidation)
            return CHtml::error($model, $attribute, $htmlOptions);

        $inputID = isset($htmlOptions['inputID']) ? $htmlOptions['inputID'] : CHtml::activeId($model, $attribute);
        unset($htmlOptions['inputID']);
        if (!isset($htmlOptions['id']))
            $htmlOptions['id'] = $inputID . '_em_';

        $option = array(
            'inputID' => $inputID,
            'errorID' => $htmlOptions['id'],
            'model' => get_class($model),
            'name' => $attribute,
        );

        $optionNames = array(
            'validationDelay',
            'validateOnChange',
            'validateOnType',
            'hideErrorMessage',
            'inputContainer',
            'errorCssClass',
            'successCssClass',
            'validatingCssClass',
            'beforeValidateAttribute',
            'afterValidateAttribute',
        );
        foreach ($optionNames as $name) {
            if (isset($htmlOptions[$name])) {
                $option[$name] = $htmlOptions[$name];
                unset($htmlOptions[$name]);
            }
        }
        if ($model instanceof CActiveRecord && !$model->isNewRecord)
            $option['status'] = 1;

        if (!isset($htmlOptions['class']))
            $htmlOptions['class'] = $this->errorMessageCssClass;
        $html = CHtml::error($model, $attribute, $htmlOptions);
        if ($html === '') {
            if (isset($htmlOptions['style']))
                $htmlOptions['style'] = rtrim($htmlOptions['style'], ';') . ';display:none';
            else
                $htmlOptions['style'] = 'display:none';
            $html = CHtml::tag('div', $htmlOptions, '');
        }

        $this->_attributes[$inputID] = $option;
        return $html;
    }

    /**
     * Displays a summary of validation errors for one or several models.
     * This method is very similar to {@link CHtml::errorSummary} except that it also works
     * when AJAX validation is performed.
     * @param mixed $models the models whose input errors are to be displayed. This can be either
     * a single model or an array of models.
     * @param string $header a piece of HTML code that appears in front of the errors
     * @param string $footer a piece of HTML code that appears at the end of the errors
     * @param array $htmlOptions additional HTML attributes to be rendered in the container div tag.
     * @return string the error summary. Empty if no errors are found.
     * @see CHtml::errorSummary
     */
    public function errorSummary($models, $header = null, $footer = null, $htmlOptions = array()) {
        if (!$this->enableAjaxValidation)
            return CHtml::errorSummary($models, $header, $footer, $htmlOptions);

        if (!isset($htmlOptions['id']))
            $htmlOptions['id'] = $this->id . '_es_';
        $html = CHtml::errorSummary($models, $header, $footer, $htmlOptions);
        if ($html === '') {
            if ($header === null)
                $header = '<p>' . Yii::t('yii', 'Please fix the following input errors:') . '</p>';
            if (!isset($htmlOptions['class']))
                $htmlOptions['class'] = CHtml::$errorSummaryCss;
            $htmlOptions['style'] = isset($htmlOptions['style']) ? rtrim($htmlOptions['style'], ';') . ';display:none' : 'display:none';
            $html = CHtml::tag('div', $htmlOptions, $header . "\n<ul><li>dummy</li></ul>" . $footer);
        }

        $this->_summary = $htmlOptions['id'];
        return $html;
    }

    /**
     * Generates a label tag.
     * @param string $label label text. Note, you should HTML-encode the text if needed.
     * @param string $for the ID of the HTML element that this label is associated with.
     * If this is false, the 'for' attribute for the label tag will not be rendered (since version 1.0.11).
     * @param array $htmlOptions additional HTML attributes.
     * Starting from version 1.0.2, the following HTML option is recognized:
     * <ul>
     * <li>required: if this is set and is true, the label will be styled
     * with CSS class 'required' (customizable with CHtml::$requiredCss),
     * and be decorated with {@link CHtml::beforeRequiredLabel} and
     * {@link CHtml::afterRequiredLabel}.</li>
     * </ul>
     * @return string the generated label tag
     */
    public static function label($label, $for, $htmlOptions = array()) {
        return CHtml::label($label, $for, $htmlOptions);
    }

    /**
     * Renders an HTML label for a model attribute.
     * This method is a wrapper of {@link CHtml::activeLabel}.
     * Please check {@link CHtml::activeLabel} for detailed information
     * about the parameters for this method.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated label tag
     */
    public function activeLabel($model, $attribute, $htmlOptions = array()) {
        return CHtml::activeLabel($model, $attribute, $htmlOptions);
    }

    /**
     * Renders an HTML label for a model attribute.
     * This method is a wrapper of {@link CHtml::activeLabelEx}.
     * Please check {@link CHtml::activeLabelEx} for detailed information
     * about the parameters for this method.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated label tag
     */
    public function labelEx($label, $for, $htmlOptions = array()) {
        return CHtml::label($label, $for, $htmlOptions);
    }

    /**
     * Renders an HTML label for a model attribute.
     * This method is a wrapper of {@link CHtml::activeLabelEx}.
     * Please check {@link CHtml::activeLabelEx} for detailed information
     * about the parameters for this method.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated label tag
     */
    public function activeLabelEx($model, $attribute, $htmlOptions = array()) {
        return CHtml::activeLabelEx($model, $attribute, $htmlOptions);
    }

    /**
     * Generates a text field input.
     * @param string $name the input name
     * @param string $value the input value
     * @param array $htmlOptions additional HTML attributes. Besides normal HTML attributes, a few special
     * attributes are also recognized (see {@link clientChange} and {@link tag} for more details.)
     * @return string the generated input field
     * @see clientChange
     * @see inputField
     */
    public function textField($name, $value = '', $htmlOptions = array(), $validator = '') {

        if ($validator != '') {
            $htmlOptions['validator'] = $validator;
            $this->_validator[$name] = $validator;
            if (strpos($htmlOptions['class'], '_x_ipt') === false)
                $htmlOptions['class'] = $htmlOptions['class'] == '' ? '_x_ipt' : $htmlOptions['class'] . ' _x_ipt';
        }

        return CHtml::textField($name, $value, $htmlOptions);
    }

    /**
     * Renders a text field for a model attribute.
     * This method is a wrapper of {@link CHtml::activeTextField}.
     * Please check {@link CHtml::activeTextField} for detailed information
     * about the parameters for this method.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated input field
     */
    public function activeTextField($model, $attribute, $htmlOptions = array(), $validator = '') {
        if ($validator != '') {
            $htmlOptions['validator'] = $validator;
            $this->_validator[$attribute] = $validator;
            if (strpos($htmlOptions['class'], '_x_ipt') === false)
                $htmlOptions['class'] = $htmlOptions['class'] == '' ? '_x_ipt' : $htmlOptions['class'] . ' _x_ipt';
        }

        return CHtml::activeTextField($model, $attribute, $htmlOptions);
    }

    /**
     * Generates a hidden input.
     * @param string $name the input name
     * @param string $value the input value
     * @param array $htmlOptions additional HTML attributes (see {@link tag}).
     * @return string the generated input field
     * @see inputField
     */
    public static function hiddenField($name, $value = '', $htmlOptions = array()) {
        return CHtml::hiddenField($name, $value, $htmlOptions);
    }

    /**
     * Renders a hidden field for a model attribute.
     * This method is a wrapper of {@link CHtml::activeHiddenField}.
     * Please check {@link CHtml::activeHiddenField} for detailed information
     * about the parameters for this method.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated input field
     */
    public function activeHiddenField($model, $attribute, $htmlOptions = array()) {
        return CHtml::activeHiddenField($model, $attribute, $htmlOptions);
    }

    /**
     * Generates a password field input.
     * @param string $name the input name
     * @param string $value the input value
     * @param array $htmlOptions additional HTML attributes. Besides normal HTML attributes, a few special
     * attributes are also recognized (see {@link clientChange} and {@link tag} for more details.)
     * @return string the generated input field
     * @see clientChange
     * @see inputField
     */
    public static function passwordField($name, $value = '', $htmlOptions = array(), $validator = '') {
        if ($validator != '') {
            $htmlOptions['validator'] = $validator;
            //$this->_validator[$name] = $validator;
            if (strpos($htmlOptions['class'], '_x_ipt') === false)
                $htmlOptions['class'] = $htmlOptions['class'] == '' ? '_x_ipt' : $htmlOptions['class'] . ' _x_ipt';
        }

        return CHtml::passwordField($name, $value, $htmlOptions);
    }

    /**
     * Renders a password field for a model attribute.
     * This method is a wrapper of {@link CHtml::activePasswordField}.
     * Please check {@link CHtml::activePasswordField} for detailed information
     * about the parameters for this method.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated input field
     */
    public function activePasswordField($model, $attribute, $htmlOptions = array(), $validator = '') {
        if ($validator != '') {
            $htmlOptions['validator'] = $validator;
            $this->_validator[$attribute] = $validator;
            if (strpos($htmlOptions['class'], '_x_ipt') === false)
                $htmlOptions['class'] = $htmlOptions['class'] == '' ? '_x_ipt' : $htmlOptions['class'] . ' _x_ipt';
        }

        return CHtml::activePasswordField($model, $attribute, $htmlOptions);
    }

    /**
     * Generates a text area input.
     * @param string $name the input name
     * @param string $value the input value
     * @param array $htmlOptions additional HTML attributes. Besides normal HTML attributes, a few special
     * attributes are also recognized (see {@link clientChange} and {@link tag} for more details.)
     * @return string the generated text area
     * @see clientChange
     * @see inputField
     */
    public static function textArea($name, $value = '', $htmlOptions = array(), $validator = '') {
        if ($validator != '') {
            $htmlOptions['validator'] = $validator;
            //$this->_validator[$name] = $validator;
            if (strpos($htmlOptions['class'], '_x_ipt') === false)
                $htmlOptions['class'] = $htmlOptions['class'] == '' ? '_x_ipt' : $htmlOptions['class'] . ' _x_ipt';
        }

        return CHtml::textArea($name, $value, $htmlOptions);
    }

    /**
     * Renders a text area for a model attribute.
     * This method is a wrapper of {@link CHtml::activeTextArea}.
     * Please check {@link CHtml::activeTextArea} for detailed information
     * about the parameters for this method.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated text area
     */
    public function activeTextArea($model, $attribute, $htmlOptions = array(), $validator = '') {
        if ($validator != '') {
            $htmlOptions['validator'] = $validator;
            $this->_validator[$attribute] = $validator;
            if (strpos($htmlOptions['class'], '_x_ipt') === false)
                $htmlOptions['class'] = $htmlOptions['class'] == '' ? '_x_ipt' : $htmlOptions['class'] . ' _x_ipt';
        }

        return CHtml::activeTextArea($model, $attribute, $htmlOptions);
    }

    /**
     * Generates a file input.
     * Note, you have to set the enclosing form's 'enctype' attribute to be 'multipart/form-data'.
     * After the form is submitted, the uploaded file information can be obtained via $_FILES[$name] (see
     * PHP documentation).
     * @param string $name the input name
     * @param string $value the input value
     * @param array $htmlOptions additional HTML attributes (see {@link tag}).
     * @return string the generated input field
     * @see inputField
     */
    public static function fileField($name, $value = '', $htmlOptions = array()) {
        return CHtml::fileField($name, $value, $htmlOptions);
    }

    /**
     * Renders a file field for a model attribute.
     * This method is a wrapper of {@link CHtml::activeFileField}.
     * Please check {@link CHtml::activeFileField} for detailed information
     * about the parameters for this method.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param array $htmlOptions additional HTML attributes
     * @return string the generated input field
     */
    public function activeFileField($model, $attribute, $htmlOptions = array()) {
        return CHtml::activeFileField($model, $attribute, $htmlOptions);
    }

    /**
     * Generates a radio button.
     * @param string $name the input name
     * @param boolean $checked whether the radio button is checked
     * @param array $htmlOptions additional HTML attributes. Besides normal HTML attributes, a few special
     * attributes are also recognized (see {@link clientChange} and {@link tag} for more details.)
     * Since version 1.1.2, a special option named 'uncheckValue' is available that can be used to specify
     * the value returned when the radio button is not checked. When set, a hidden field is rendered so that
     * when the radio button is not checked, we can still obtain the posted uncheck value.
     * If 'uncheckValue' is not set or set to NULL, the hidden field will not be rendered.
     * @return string the generated radio button
     * @see clientChange
     * @see inputField
     */
    public static function radioButton($name, $checked = false, $htmlOptions = array()) {
        return CHtml::radioButton($name, $checked, $htmlOptions);
    }

    /**
     * Renders a radio button for a model attribute.
     * This method is a wrapper of {@link CHtml::activeRadioButton}.
     * Please check {@link CHtml::activeRadioButton} for detailed information
     * about the parameters for this method.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated radio button
     */
    public function activeRadioButton($model, $attribute, $htmlOptions = array()) {
        return CHtml::activeRadioButton($model, $attribute, $htmlOptions);
    }

    /**
     * Generates a check box.
     * @param string $name the input name
     * @param boolean $checked whether the check box is checked
     * @param array $htmlOptions additional HTML attributes. Besides normal HTML attributes, a few special
     * attributes are also recognized (see {@link clientChange} and {@link tag} for more details.)
     * Since version 1.1.2, a special option named 'uncheckValue' is available that can be used to specify
     * the value returned when the checkbox is not checked. When set, a hidden field is rendered so that
     * when the checkbox is not checked, we can still obtain the posted uncheck value.
     * If 'uncheckValue' is not set or set to NULL, the hidden field will not be rendered.
     * @return string the generated check box
     * @see clientChange
     * @see inputField
     */
    public static function checkBox($name, $checked = false, $htmlOptions = array()) {
        return CHtml::checkBox($name, $checked, $htmlOptions);
    }

    /**
     * Renders a checkbox for a model attribute.
     * This method is a wrapper of {@link CHtml::activeCheckBox}.
     * Please check {@link CHtml::activeCheckBox} for detailed information
     * about the parameters for this method.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated check box
     */
    public function activeCheckBox($model, $attribute, $htmlOptions = array()) {
        return CHtml::activeCheckBox($model, $attribute, $htmlOptions);
    }

    /**
     * Generates a drop down list.
     * @param string $name the input name
     * @param string $select the selected value
     * @param array $data data for generating the list options (value=>display).
     * You may use {@link listData} to generate this data.
     * Please refer to {@link listOptions} on how this data is used to generate the list options.
     * Note, the values and labels will be automatically HTML-encoded by this method.
     * @param array $htmlOptions additional HTML attributes. Besides normal HTML attributes, a few special
     * attributes are recognized. See {@link clientChange} and {@link tag} for more details.
     * In addition, the following options are also supported specifically for dropdown list:
     * <ul>
     * <li>encode: boolean, specifies whether to encode the values. Defaults to true. This option has been available since version 1.0.5.</li>
     * <li>prompt: string, specifies the prompt text shown as the first list option. Its value is empty. Note, the prompt text will NOT be HTML-encoded.</li>
     * <li>empty: string, specifies the text corresponding to empty selection. Its value is empty.
     * Starting from version 1.0.10, the 'empty' option can also be an array of value-label pairs.
     * Each pair will be used to render a list option at the beginning. Note, the text label will NOT be HTML-encoded.</li>
     * <li>options: array, specifies additional attributes for each OPTION tag.
     *     The array keys must be the option values, and the array values are the extra
     *     OPTION tag attributes in the name-value pairs. For example,
     * <pre>
     *     array(
     *         'value1'=>array('disabled'=>true, 'label'=>'value 1'),
     *         'value2'=>array('label'=>'value 2'),
     *     );
     * </pre>
     *     This option has been available since version 1.0.3.
     * </li>
     * </ul>
     * @return string the generated drop down list
     * @see clientChange
     * @see inputField
     * @see listData
     */
    public static function dropDownList($name, $select, $data, $htmlOptions = array(), $validator = '') {
        if ($validator != '') {
            $htmlOptions['validator'] = $validator;
            //$this->_validator[$name] = $validator;
            if (strpos($htmlOptions['class'], '_x_ipt') === false)
                $htmlOptions['class'] = $htmlOptions['class'] == '' ? '_x_ipt' : $htmlOptions['class'] . ' _x_ipt';
        }

        return CHtml::dropDownList($name, $select, $data, $htmlOptions);
    }

    /**
     * Renders a dropdown list for a model attribute.
     * This method is a wrapper of {@link CHtml::activeDropDownList}.
     * Please check {@link CHtml::activeDropDownList} for detailed information
     * about the parameters for this method.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param array $data data for generating the list options (value=>display)
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated drop down list
     */
    public function activeDropDownList($model, $attribute, $data, $htmlOptions = array(), $validator = '') {
        if ($validator != '') {
            $htmlOptions['validator'] = $validator;
            $this->_validator[$attribute] = $validator;
            if (strpos($htmlOptions['class'], '_x_ipt') === false)
                $htmlOptions['class'] = $htmlOptions['class'] == '' ? '_x_ipt' : $htmlOptions['class'] . ' _x_ipt';
        }

        return CHtml::activeDropDownList($model, $attribute, $data, $htmlOptions);
    }

    /**
     * Generates a list box.
     * @param string $name the input name
     * @param mixed $select the selected value(s). This can be either a string for single selection or an array for multiple selections.
     * @param array $data data for generating the list options (value=>display)
     * You may use {@link listData} to generate this data.
     * Please refer to {@link listOptions} on how this data is used to generate the list options.
     * Note, the values and labels will be automatically HTML-encoded by this method.
     * @param array $htmlOptions additional HTML attributes. Besides normal HTML attributes, a few special
     * attributes are also recognized. See {@link clientChange} and {@link tag} for more details.
     * In addition, the following options are also supported specifically for list box:
     * <ul>
     * <li>encode: boolean, specifies whether to encode the values. Defaults to true. This option has been available since version 1.0.5.</li>
     * <li>prompt: string, specifies the prompt text shown as the first list option. Its value is empty. Note, the prompt text will NOT be HTML-encoded.</li>
     * <li>empty: string, specifies the text corresponding to empty selection. Its value is empty.
     * Starting from version 1.0.10, the 'empty' option can also be an array of value-label pairs.
     * Each pair will be used to render a list option at the beginning. Note, the text label will NOT be HTML-encoded.</li>
     * <li>options: array, specifies additional attributes for each OPTION tag.
     *     The array keys must be the option values, and the array values are the extra
     *     OPTION tag attributes in the name-value pairs. For example,
     * <pre>
     *     array(
     *         'value1'=>array('disabled'=>true, 'label'=>'value 1'),
     *         'value2'=>array('label'=>'value 2'),
     *     );
     * </pre>
     *     This option has been available since version 1.0.3.
     * </li>
     * </ul>
     * @return string the generated list box
     * @see clientChange
     * @see inputField
     * @see listData
     */
    public static function listBox($name, $select, $data, $htmlOptions = array(), $validator = '') {
        if ($validator != '') {
            $htmlOptions['validator'] = $validator;
            //$this->_validator[$attribute] = $validator;
            if (strpos($htmlOptions['class'], '_x_ipt') === false)
                $htmlOptions['class'] = $htmlOptions['class'] == '' ? '_x_ipt' : $htmlOptions['class'] . ' _x_ipt';
        }

        return CHtml::listBox($name, $select, $data, $htmlOptions);
    }

    /**
     * Renders a list box for a model attribute.
     * This method is a wrapper of {@link CHtml::activeListBox}.
     * Please check {@link CHtml::activeListBox} for detailed information
     * about the parameters for this method.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param array $data data for generating the list options (value=>display)
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated list box
     */
    public function activeListBox($model, $attribute, $data, $htmlOptions = array(), $validator = '') {
        if ($validator != '') {
            $htmlOptions['validator'] = $validator;
            $this->_validator[$attribute] = $validator;
            if (strpos($htmlOptions['class'], '_x_ipt') === false)
                $htmlOptions['class'] = $htmlOptions['class'] == '' ? '_x_ipt' : $htmlOptions['class'] . ' _x_ipt';
        }

        return CHtml::activeListBox($model, $attribute, $data, $htmlOptions);
    }

    /**
     * Generates a check box list.
     * A check box list allows multiple selection, like {@link listBox}.
     * As a result, the corresponding POST value is an array.
     * @param string $name name of the check box list. You can use this name to retrieve
     * the selected value(s) once the form is submitted.
     * @param mixed $select selection of the check boxes. This can be either a string
     * for single selection or an array for multiple selections.
     * @param array $data value-label pairs used to generate the check box list.
     * Note, the values will be automatically HTML-encoded, while the labels will not.
     * @param array $htmlOptions addtional HTML options. The options will be applied to
     * each checkbox input. The following special options are recognized:
     * <ul>
     * <li>template: string, specifies how each checkbox is rendered. Defaults
     * to "{input} {label}", where "{input}" will be replaced by the generated
     * check box input tag while "{label}" be replaced by the corresponding check box label.</li>
     * <li>separator: string, specifies the string that separates the generated check boxes.</li>
     * <li>checkAll: string, specifies the label for the "check all" checkbox.
     * If this option is specified, a 'check all' checkbox will be displayed. Clicking on
     * this checkbox will cause all checkboxes checked or unchecked. This option has been
     * available since version 1.0.4.</li>
     * <li>checkAllLast: boolean, specifies whether the 'check all' checkbox should be
     * displayed at the end of the checkbox list. If this option is not set (default)
     * or is false, the 'check all' checkbox will be displayed at the beginning of
     * the checkbox list. This option has been available since version 1.0.4.</li>
     * <li>labelOptions: array, specifies the additional HTML attributes to be rendered
     * for every label tag in the list. This option has been available since version 1.0.10.</li>
     * </ul>
     * @return string the generated check box list
     */
    public static function checkBoxList($name, $select, $data, $htmlOptions = array()) {
        return CHtml::checkBoxList($name, $select, $data, $htmlOptions);
    }

    /**
     * Renders a checkbox list for a model attribute.
     * This method is a wrapper of {@link CHtml::activeCheckBoxList}.
     * Please check {@link CHtml::activeCheckBoxList} for detailed information
     * about the parameters for this method.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param array $data value-label pairs used to generate the check box list.
     * @param array $htmlOptions addtional HTML options.
     * @return string the generated check box list
     */
    public function activeCheckBoxList($model, $attribute, $data, $htmlOptions = array()) {
        return CHtml::activeCheckBoxList($model, $attribute, $data, $htmlOptions);
    }

    /**
     * Generates a radio button list.
     * A radio button list is like a {@link checkBoxList check box list}, except that
     * it only allows single selection.
     * @param string $name name of the radio button list. You can use this name to retrieve
     * the selected value(s) once the form is submitted.
     * @param mixed $select selection of the radio buttons. This can be either a string
     * for single selection or an array for multiple selections.
     * @param array $data value-label pairs used to generate the radio button list.
     * Note, the values will be automatically HTML-encoded, while the labels will not.
     * @param array $htmlOptions addtional HTML options. The options will be applied to
     * each radio button input. The following special options are recognized:
     * <ul>
     * <li>template: string, specifies how each radio button is rendered. Defaults
     * to "{input} {label}", where "{input}" will be replaced by the generated
     * radio button input tag while "{label}" will be replaced by the corresponding radio button label.</li>
     * <li>separator: string, specifies the string that separates the generated radio buttons.</li>
     * <li>labelOptions: array, specifies the additional HTML attributes to be rendered
     * for every label tag in the list. This option has been available since version 1.0.10.</li>
     * </ul>
     * @return string the generated radio button list
     */
    public static function radioButtonList($name, $select, $data, $htmlOptions = array()) {
        return CHtml::radioButtonList($name, $select, $data, $htmlOptions);
    }

    /**
     * Renders a radio button list for a model attribute.
     * This method is a wrapper of {@link CHtml::activeRadioButtonList}.
     * Please check {@link CHtml::activeRadioButtonList} for detailed information
     * about the parameters for this method.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param array $data value-label pairs used to generate the radio button list.
     * @param array $htmlOptions addtional HTML options.
     * @return string the generated radio button list
     */
    public function activeRadioButtonList($model, $attribute, $data, $htmlOptions = array()) {
        return CHtml::activeRadioButtonList($model, $attribute, $data, $htmlOptions);
    }

    /**
     * Validates one or several models and returns the results in JSON format.
     * This is a helper method that simplies the way of writing AJAX validation code.
     * @param mixed $models a single model instance or an array of models.
     * @param array $attributes list of attributes that should be validated. Defaults to null,
     * meaning any attribute listed in the applicable validation rules of the models should be
     * validated. If this parameter is given as a list of attributes, only
     * the listed attributes will be validated.
     * @param boolean $loadInput whether to load the data from $_POST array in this method.
     * If this is true, the model will be populated from <code>$_POST[ModelClass]</code>.
     * @return string the JSON representation of the validation error messages.
     */
    public static function validate($models, $attributes = null, $loadInput = true) {
        $result = array();
        if (!is_array($models))
            $models = array($models);
        foreach ($models as $model) {
            if ($loadInput && isset($_POST[get_class($model)]))
                $model->attributes = $_POST[get_class($model)];
            $model->validate($attributes);
            foreach ($model->getErrors() as $attribute => $errors)
                $result[CHtml::activeId($model, $attribute)] = $errors;
        }
        return function_exists('json_encode') ? json_encode($result) : CJSON::encode($result);
    }

}
