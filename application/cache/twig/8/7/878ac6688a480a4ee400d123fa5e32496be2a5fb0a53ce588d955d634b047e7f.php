<?php

/* admin_user.tpl */
class __TwigTemplate_878ac6688a480a4ee400d123fa5e32496be2a5fb0a53ce588d955d634b047e7f extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("default_layout.tpl", "admin_user.tpl", 1);
        $this->blocks = array(
            'pageTitle' => array($this, 'block_pageTitle'),
            'container' => array($this, 'block_container'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "default_layout.tpl";
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
            <a href='";
        // line 16
        echo twig_escape_filter($this->env, (isset($context["base_url"]) ? $context["base_url"] : null), "html", null, true);
        echo "'><code>application/controllers/welcome.php</code></a>

            <p>If you are exploring CodeIgniter for the very first time, you should start by reading the <a href=\"user_guide/\">User Guide</a>.</p>
        </div>

        <p class=\"footer\">Page rendered in <strong>0.1530</strong> seconds</p>
    </div>
";
    }

    public function getTemplateName()
    {
        return "admin_user.tpl";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  53 => 16,  41 => 7,  38 => 6,  35 => 5,  29 => 3,  11 => 1,);
    }
}
