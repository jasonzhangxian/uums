<?php

/* admin_user.html */
class __TwigTemplate_193e3cb80e79056d2a0167c17698fe3bac813704b3000523c9f5aa70f96936ca extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("default_layout.html", "admin_user.html", 1);
        $this->blocks = array(
            'pageTitle' => array($this, 'block_pageTitle'),
            'container' => array($this, 'block_container'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "default_layout.html";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_pageTitle($context, array $blocks = array())
    {
        echo twig_escape_filter($this->env, (isset($context["user_name"]) ? $context["user_name"] : null), "html", null, true);
    }

    // line 5
    public function block_container($context, array $blocks = array())
    {
        // line 6
        echo "    <div id=\"container\">
        <h1>";
        // line 7
        echo twig_escape_filter($this->env, (isset($context["user_name"]) ? $context["user_name"] : null), "html", null, true);
        echo "</h1>

        <div id=\"body\">
            <p>The page you are looking at is being generated dynamically by CodeIgniter.</p>

            <p>If you would like to edit this page you'll find it located at:</p>
            <code>application/views/welcome_message.php</code>

            <p>The corresponding controller for this page is found at:</p>
            <code>application/controllers/welcome.php</code>

            <p>If you are exploring CodeIgniter for the very first time, you should start by reading the <a href=\"user_guide/\">User Guide</a>.</p>
        </div>

        <p class=\"footer\">Page rendered in <strong>0.1530</strong> seconds</p>
    </div>
";
    }

    public function getTemplateName()
    {
        return "admin_user.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  41 => 7,  38 => 6,  35 => 5,  29 => 3,  11 => 1,);
    }
}
