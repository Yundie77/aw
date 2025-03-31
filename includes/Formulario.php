<?php

abstract class Formulario
{
    /**
     * @var string Identificador único del formulario.
     */
    protected $formId;
    /**
     * @var string Método HTTP para enviar el formulario.
     */
    protected $method;
    /**
     * @var string URL del action.
     */
    protected $action;
    /**
     * @var string Valor del atributo class.
     */
    protected $classAtt;
    /**
     * @var string Valor del atributo enctype.
     */
    protected $enctype;
    /**
     * @var string URL de redirección en caso de éxito.
     */
    protected $urlRedireccion;
    /**
     * @var array Errores del formulario.
     */
    protected $errores;

    /**
     * Constructor del formulario.
     * Opciones: action, method, class, enctype, urlRedireccion.
     */
    public function __construct($formId, $opciones = array())
    {
        $this->formId = $formId;
        $opcionesPorDefecto = array('action' => null, 'method' => 'POST', 'class' => null, 'enctype' => null, 'urlRedireccion' => null);
        $opciones = array_merge($opcionesPorDefecto, $opciones);
        $this->action = $opciones['action'] ? $opciones['action'] : htmlspecialchars($_SERVER['REQUEST_URI']);
        $this->method = $opciones['method'];
        $this->classAtt = $opciones['class'];
        $this->enctype  = $opciones['enctype'];
        $this->urlRedireccion = $opciones['urlRedireccion'];
    }

    /**
     * Genera la lista de errores globales.
     */
    protected static function generaListaErroresGlobales($errores = array(), $classAtt = '')
    {
        $clavesErroresGlobales = array_filter(array_keys($errores), function ($elem) {
            return is_numeric($elem);
        });
        if (count($clavesErroresGlobales) === 0) {
            return '';
        }
        $html = "<ul class=\"$classAtt\">";
        foreach ($clavesErroresGlobales as $clave) {
            $html .= "<li>{$errores[$clave]}</li>";
        }
        $html .= '</ul>';
        return $html;
    }

    protected static function createMensajeError($errores = [], $idError = '', $htmlElement = 'span', $atts = [])
    {
        if (!isset($errores[$idError])) {
            return '';
        }
        $att = '';
        foreach ($atts as $key => $value) {
            $att .= "$key=\"$value\" ";
        }
        return "<$htmlElement $att>{$errores[$idError]}</$htmlElement>";
    }

    protected static function generaErroresCampos($campos, $errores, $htmlElement = 'span', $atts = [])
    {
        $erroresCampos = [];
        foreach ($campos as $campo) {
            $erroresCampos[$campo] = self::createMensajeError($errores, $campo, $htmlElement, $atts);
        }
        return $erroresCampos;
    }

    /**
     * Orquesta la gestión del formulario.
     */
    public function gestiona()
    {
        $datos = &$_POST;
        if (strcasecmp('GET', $this->method) == 0) {
            $datos = &$_GET;
        }
        $this->errores = [];
        if (!$this->formularioEnviado($datos)) {
            return $this->generaFormulario($datos);
        }
        $this->procesaFormulario($datos);
        if (count($this->errores) !== 0) {
            return $this->generaFormulario($datos);
        }
        if ($this->urlRedireccion !== null) {
            header("Location: {$this->urlRedireccion}");
            exit();
        }
    }

    /**
     * Comprueba si el formulario ha sido enviado.
     */
    protected function formularioEnviado(&$datos)
    {
        return isset($datos['formId']) && $datos['formId'] == $this->formId;
    }

    /**
     * Genera el HTML del formulario.
     */
    protected function generaFormulario(&$datos = array())
    {
        $htmlCamposFormularios = $this->generaCamposFormulario($datos);
        $classAtt = $this->classAtt != null ? "class=\"{$this->classAtt}\"" : '';
        $enctypeAtt = $this->enctype != null ? "enctype=\"{$this->enctype}\"" : '';
        $htmlForm = <<<EOS
        <form method="{$this->method}" action="{$this->action}" id="{$this->formId}" {$classAtt} {$enctypeAtt}>
            <input type="hidden" name="formId" value="{$this->formId}" />
            $htmlCamposFormularios
        </form>
        EOS;
        return $htmlForm;
    }

    abstract protected function generaCamposFormulario(&$datos);

    abstract protected function procesaFormulario(&$datos);
}
