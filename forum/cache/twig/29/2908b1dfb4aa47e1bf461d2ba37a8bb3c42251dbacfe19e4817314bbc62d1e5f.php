<?php

/* viewtopic_print.html */
class __TwigTemplate_19c2b528246341f5e6337d026158ee233488e770930c82d4b60973d3c13393d8 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html dir=\"";
        // line 2
        echo (isset($context["S_CONTENT_DIRECTION"]) ? $context["S_CONTENT_DIRECTION"] : null);
        echo "\" lang=\"";
        echo (isset($context["S_USER_LANG"]) ? $context["S_USER_LANG"] : null);
        echo "\">
<head>
<meta charset=\"utf-8\" />
<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
<meta name=\"robots\" content=\"noindex\" />
";
        // line 7
        echo (isset($context["META"]) ? $context["META"] : null);
        echo "
<title>";
        // line 8
        echo (isset($context["SITENAME"]) ? $context["SITENAME"] : null);
        echo " &bull; ";
        echo (isset($context["PAGE_TITLE"]) ? $context["PAGE_TITLE"] : null);
        echo "</title>

<link href=\"";
        // line 10
        echo (isset($context["T_THEME_PATH"]) ? $context["T_THEME_PATH"] : null);
        echo "/print.css\" rel=\"stylesheet\">
";
        // line 11
        // line 12
        echo "</head>
<body id=\"phpbb\">
<div id=\"wrap\">
\t<a id=\"top\" class=\"anchor\" accesskey=\"t\"></a>

\t<div id=\"page-header\">
\t\t<h1>";
        // line 18
        echo (isset($context["SITENAME"]) ? $context["SITENAME"] : null);
        echo "</h1>
\t\t<p>";
        // line 19
        echo (isset($context["SITE_DESCRIPTION"]) ? $context["SITE_DESCRIPTION"] : null);
        echo "<br /><a href=\"";
        echo (isset($context["U_FORUM"]) ? $context["U_FORUM"] : null);
        echo "\">";
        echo (isset($context["U_FORUM"]) ? $context["U_FORUM"] : null);
        echo "</a></p>

\t\t<h2>";
        // line 21
        echo (isset($context["TOPIC_TITLE"]) ? $context["TOPIC_TITLE"] : null);
        echo "</h2>
\t\t<p><a href=\"";
        // line 22
        echo (isset($context["U_TOPIC"]) ? $context["U_TOPIC"] : null);
        echo "\">";
        echo (isset($context["U_TOPIC"]) ? $context["U_TOPIC"] : null);
        echo "</a></p>
\t</div>

\t<div id=\"page-body\">
\t\t<div class=\"page-number\">";
        // line 26
        echo (isset($context["PAGE_NUMBER"]) ? $context["PAGE_NUMBER"] : null);
        echo "</div>
\t\t";
        // line 27
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "postrow", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["postrow"]) {
            // line 28
            echo "\t\t\t<div class=\"post\">
\t\t\t\t<h3>";
            // line 29
            echo $this->getAttribute($context["postrow"], "POST_SUBJECT", array());
            echo "</h3>
\t\t\t\t<div class=\"date\">";
            // line 30
            echo $this->env->getExtension('phpbb')->lang("POSTED");
            echo $this->env->getExtension('phpbb')->lang("COLON");
            echo " <strong>";
            echo $this->getAttribute($context["postrow"], "POST_DATE", array());
            echo "</strong></div>
\t\t\t\t<div class=\"author\">";
            // line 31
            echo $this->env->getExtension('phpbb')->lang("POST_BY_AUTHOR");
            echo " <strong>";
            echo $this->getAttribute($context["postrow"], "POST_AUTHOR", array());
            echo "</strong></div>
\t\t\t\t<div class=\"content\">";
            // line 32
            echo $this->getAttribute($context["postrow"], "MESSAGE", array());
            echo "</div>
\t\t\t</div>
\t\t\t<hr />
\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['postrow'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 36
        echo "\t</div>

\t<div id=\"page-footer\">
\t\t<div class=\"page-number\">";
        // line 39
        echo (isset($context["S_TIMEZONE"]) ? $context["S_TIMEZONE"] : null);
        echo "<br />";
        echo (isset($context["PAGE_NUMBER"]) ? $context["PAGE_NUMBER"] : null);
        echo "</div>
\t\t<div class=\"copyright\">Powered by phpBB&reg; Forum Software &copy; phpBB Limited<br />https://www.phpbb.com/</div>
\t</div>
</div>

</body>
</html>
";
    }

    public function getTemplateName()
    {
        return "viewtopic_print.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  125 => 39,  120 => 36,  110 => 32,  104 => 31,  97 => 30,  93 => 29,  90 => 28,  86 => 27,  82 => 26,  73 => 22,  69 => 21,  60 => 19,  56 => 18,  48 => 12,  47 => 11,  43 => 10,  36 => 8,  32 => 7,  22 => 2,  19 => 1,);
    }
}
/* <!DOCTYPE html>*/
/* <html dir="{S_CONTENT_DIRECTION}" lang="{S_USER_LANG}">*/
/* <head>*/
/* <meta charset="utf-8" />*/
/* <meta http-equiv="X-UA-Compatible" content="IE=edge">*/
/* <meta name="robots" content="noindex" />*/
/* {META}*/
/* <title>{SITENAME} &bull; {PAGE_TITLE}</title>*/
/* */
/* <link href="{T_THEME_PATH}/print.css" rel="stylesheet">*/
/* <!-- EVENT viewtopic_print_head_append -->*/
/* </head>*/
/* <body id="phpbb">*/
/* <div id="wrap">*/
/* 	<a id="top" class="anchor" accesskey="t"></a>*/
/* */
/* 	<div id="page-header">*/
/* 		<h1>{SITENAME}</h1>*/
/* 		<p>{SITE_DESCRIPTION}<br /><a href="{U_FORUM}">{U_FORUM}</a></p>*/
/* */
/* 		<h2>{TOPIC_TITLE}</h2>*/
/* 		<p><a href="{U_TOPIC}">{U_TOPIC}</a></p>*/
/* 	</div>*/
/* */
/* 	<div id="page-body">*/
/* 		<div class="page-number">{PAGE_NUMBER}</div>*/
/* 		<!-- BEGIN postrow -->*/
/* 			<div class="post">*/
/* 				<h3>{postrow.POST_SUBJECT}</h3>*/
/* 				<div class="date">{L_POSTED}{L_COLON} <strong>{postrow.POST_DATE}</strong></div>*/
/* 				<div class="author">{L_POST_BY_AUTHOR} <strong>{postrow.POST_AUTHOR}</strong></div>*/
/* 				<div class="content">{postrow.MESSAGE}</div>*/
/* 			</div>*/
/* 			<hr />*/
/* 		<!-- END postrow -->*/
/* 	</div>*/
/* */
/* 	<div id="page-footer">*/
/* 		<div class="page-number">{S_TIMEZONE}<br />{PAGE_NUMBER}</div>*/
/* 		<div class="copyright">Powered by phpBB&reg; Forum Software &copy; phpBB Limited<br />https://www.phpbb.com/</div>*/
/* 	</div>*/
/* </div>*/
/* */
/* </body>*/
/* </html>*/
/* */
