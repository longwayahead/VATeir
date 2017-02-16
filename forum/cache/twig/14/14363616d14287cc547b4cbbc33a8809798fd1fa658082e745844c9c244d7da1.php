<?php

/* ucp_pm_viewmessage.html */
class __TwigTemplate_63bdbc61fb6ba777e44b7a293955c8050e71fe805656bf4eff637d8543dcc84e extends Twig_Template
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
        $location = "ucp_header.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->loadTemplate("ucp_header.html", "ucp_pm_viewmessage.html", 1)->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
        // line 2
        echo "
";
        // line 3
        $location = "ucp_pm_message_header.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->loadTemplate("ucp_pm_message_header.html", "ucp_pm_viewmessage.html", 3)->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
        // line 4
        echo "
\t</div>
</div>


";
        // line 9
        if (((isset($context["S_DISPLAY_HISTORY"]) ? $context["S_DISPLAY_HISTORY"] : null) && ((isset($context["U_VIEW_PREVIOUS_HISTORY"]) ? $context["U_VIEW_PREVIOUS_HISTORY"] : null) || (isset($context["U_VIEW_NEXT_HISTORY"]) ? $context["U_VIEW_NEXT_HISTORY"] : null)))) {
            // line 10
            echo "\t<fieldset class=\"display-options clearfix\">
\t\t";
            // line 11
            if ((isset($context["U_VIEW_PREVIOUS_HISTORY"]) ? $context["U_VIEW_PREVIOUS_HISTORY"] : null)) {
                echo "<a href=\"";
                echo (isset($context["U_VIEW_PREVIOUS_HISTORY"]) ? $context["U_VIEW_PREVIOUS_HISTORY"] : null);
                echo "\" class=\"left-box arrow-";
                echo (isset($context["S_CONTENT_FLOW_BEGIN"]) ? $context["S_CONTENT_FLOW_BEGIN"] : null);
                echo "\">";
                echo $this->env->getExtension('phpbb')->lang("VIEW_PREVIOUS_HISTORY");
                echo "</a>";
            }
            // line 12
            echo "\t\t";
            if ((isset($context["U_VIEW_NEXT_HISTORY"]) ? $context["U_VIEW_NEXT_HISTORY"] : null)) {
                echo "<a href=\"";
                echo (isset($context["U_VIEW_NEXT_HISTORY"]) ? $context["U_VIEW_NEXT_HISTORY"] : null);
                echo "\" class=\"right-box arrow-";
                echo (isset($context["S_CONTENT_FLOW_END"]) ? $context["S_CONTENT_FLOW_END"] : null);
                echo "\">";
                echo $this->env->getExtension('phpbb')->lang("VIEW_NEXT_HISTORY");
                echo "</a>";
            }
            // line 13
            echo "\t</fieldset>
";
        }
        // line 15
        echo "

<div id=\"post-";
        // line 17
        echo (isset($context["MESSAGE_ID"]) ? $context["MESSAGE_ID"] : null);
        echo "\" class=\"post pm has-profile";
        if (((isset($context["S_POST_UNAPPROVED"]) ? $context["S_POST_UNAPPROVED"] : null) || (isset($context["S_POST_REPORTED"]) ? $context["S_POST_REPORTED"] : null))) {
            echo " reported";
        }
        if ((isset($context["S_ONLINE"]) ? $context["S_ONLINE"] : null)) {
            echo " online";
        }
        echo "\">
<div class=\"inner\">

\t<dl class=\"postprofile\" id=\"profile";
        // line 20
        echo (isset($context["MESSAGE_ID"]) ? $context["MESSAGE_ID"] : null);
        echo "\">
\t\t<dt class=\"";
        // line 21
        if (((isset($context["RANK_TITLE"]) ? $context["RANK_TITLE"] : null) || (isset($context["RANK_IMG"]) ? $context["RANK_IMG"] : null))) {
            echo "has-profile-rank";
        } else {
            echo "no-profile-rank";
        }
        echo " ";
        if ((isset($context["AUTHOR_AVATAR"]) ? $context["AUTHOR_AVATAR"] : null)) {
            echo "has-avatar";
        } else {
            echo "no-avatar";
        }
        echo "\">
\t\t\t<div class=\"avatar-container\">
\t\t\t\t";
        // line 23
        // line 24
        echo "\t\t\t\t";
        if ((isset($context["AUTHOR_AVATAR"]) ? $context["AUTHOR_AVATAR"] : null)) {
            echo "<a href=\"";
            echo (isset($context["U_MESSAGE_AUTHOR"]) ? $context["U_MESSAGE_AUTHOR"] : null);
            echo "\" class=\"avatar\">";
            echo (isset($context["AUTHOR_AVATAR"]) ? $context["AUTHOR_AVATAR"] : null);
            echo "</a>";
        }
        // line 25
        echo "\t\t\t\t";
        // line 26
        echo "\t\t\t</div>
\t\t\t";
        // line 27
        echo (isset($context["MESSAGE_AUTHOR_FULL"]) ? $context["MESSAGE_AUTHOR_FULL"] : null);
        echo "
\t\t</dt>

\t\t";
        // line 30
        // line 31
        echo "\t\t";
        if (((isset($context["RANK_TITLE"]) ? $context["RANK_TITLE"] : null) || (isset($context["RANK_IMG"]) ? $context["RANK_IMG"] : null))) {
            echo "<dd class=\"profile-rank\">";
            echo (isset($context["RANK_TITLE"]) ? $context["RANK_TITLE"] : null);
            if (((isset($context["RANK_TITLE"]) ? $context["RANK_TITLE"] : null) && (isset($context["RANK_IMG"]) ? $context["RANK_IMG"] : null))) {
                echo "<br />";
            }
            echo (isset($context["RANK_IMG"]) ? $context["RANK_IMG"] : null);
            echo "</dd>";
        }
        // line 32
        echo "\t\t";
        // line 33
        echo "
\t\t<dd class=\"profile-posts\"><strong>";
        // line 34
        echo $this->env->getExtension('phpbb')->lang("POSTS");
        echo $this->env->getExtension('phpbb')->lang("COLON");
        echo "</strong> ";
        if (((isset($context["U_AUTHOR_POSTS"]) ? $context["U_AUTHOR_POSTS"] : null) != "")) {
            echo "<a href=\"";
            echo (isset($context["U_AUTHOR_POSTS"]) ? $context["U_AUTHOR_POSTS"] : null);
            echo "\">";
            echo (isset($context["AUTHOR_POSTS"]) ? $context["AUTHOR_POSTS"] : null);
            echo "</a>";
        } else {
            echo (isset($context["AUTHOR_POSTS"]) ? $context["AUTHOR_POSTS"] : null);
        }
        echo "</dd>
\t\t";
        // line 35
        if ((isset($context["AUTHOR_JOINED"]) ? $context["AUTHOR_JOINED"] : null)) {
            echo "<dd class=\"profile-joined\"><strong>";
            echo $this->env->getExtension('phpbb')->lang("JOINED");
            echo $this->env->getExtension('phpbb')->lang("COLON");
            echo "</strong> ";
            echo (isset($context["AUTHOR_JOINED"]) ? $context["AUTHOR_JOINED"] : null);
            echo "</dd>";
        }
        // line 36
        echo "
\t\t";
        // line 37
        // line 38
        echo "\t\t";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "custom_fields", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["custom_fields"]) {
            // line 39
            echo "\t\t\t";
            if ( !$this->getAttribute($context["custom_fields"], "S_PROFILE_CONTACT", array())) {
                // line 40
                echo "\t\t\t\t<dd class=\"profile-custom-field profile-";
                echo $this->getAttribute($context["custom_fields"], "PROFILE_FIELD_IDENT", array());
                echo "\"><strong>";
                echo $this->getAttribute($context["custom_fields"], "PROFILE_FIELD_NAME", array());
                echo $this->env->getExtension('phpbb')->lang("COLON");
                echo "</strong> ";
                echo $this->getAttribute($context["custom_fields"], "PROFILE_FIELD_VALUE", array());
                echo "</dd>
\t\t\t";
            }
            // line 42
            echo "\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['custom_fields'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 43
        echo "\t\t";
        // line 44
        echo "
\t\t";
        // line 45
        // line 46
        echo "\t\t";
        if (twig_length_filter($this->env, $this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "contact", array()))) {
            // line 47
            echo "\t\t\t<dd class=\"profile-contact\">
\t\t\t\t<strong>";
            // line 48
            echo $this->env->getExtension('phpbb')->lang("CONTACT");
            echo $this->env->getExtension('phpbb')->lang("COLON");
            echo "</strong>
\t\t\t\t<div class=\"dropdown-container dropdown-left\">
\t\t\t\t\t<a href=\"#\" class=\"dropdown-trigger\"><span class=\"imageset icon_contact\" title=\"";
            // line 50
            echo (isset($context["CONTACT_USER"]) ? $context["CONTACT_USER"] : null);
            echo "\">";
            echo (isset($context["CONTACT_USER"]) ? $context["CONTACT_USER"] : null);
            echo "</span></a>
\t\t\t\t\t<div class=\"dropdown hidden\">
\t\t\t\t\t\t<div class=\"pointer\"><div class=\"pointer-inner\"></div></div>
\t\t\t\t\t\t<div class=\"dropdown-contents contact-icons\">
\t\t\t\t\t\t\t";
            // line 54
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "contact", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["contact"]) {
                // line 55
                echo "\t\t\t\t\t\t\t\t";
                $context["REMAINDER"] = ($this->getAttribute($context["contact"], "S_ROW_COUNT", array()) % 4);
                // line 56
                echo "\t\t\t\t\t\t\t\t";
                $value = (((isset($context["REMAINDER"]) ? $context["REMAINDER"] : null) == 3) || ($this->getAttribute($context["contact"], "S_LAST_ROW", array()) && ($this->getAttribute($context["contact"], "S_NUM_ROWS", array()) < 4)));
                $context['definition']->set('S_LAST_CELL', $value);
                // line 57
                echo "\t\t\t\t\t\t\t\t";
                if (((isset($context["REMAINDER"]) ? $context["REMAINDER"] : null) == 0)) {
                    // line 58
                    echo "\t\t\t\t\t\t\t\t\t<div>
\t\t\t\t\t\t\t\t";
                }
                // line 60
                echo "\t\t\t\t\t\t\t\t\t<a href=\"";
                if ($this->getAttribute($context["contact"], "U_CONTACT", array())) {
                    echo $this->getAttribute($context["contact"], "U_CONTACT", array());
                } else {
                    echo $this->getAttribute($context["contact"], "U_PROFILE_AUTHOR", array());
                }
                echo "\" title=\"";
                echo $this->getAttribute($context["contact"], "NAME", array());
                echo "\"";
                if ($this->getAttribute((isset($context["definition"]) ? $context["definition"] : null), "S_LAST_CELL", array())) {
                    echo " class=\"last-cell\"";
                }
                if (($this->getAttribute($context["contact"], "ID", array()) == "jabber")) {
                    echo " onclick=\"popup(this.href, 750, 320); return false;\"";
                }
                echo ">
\t\t\t\t\t\t\t\t\t\t<span class=\"contact-icon ";
                // line 61
                echo $this->getAttribute($context["contact"], "ID", array());
                echo "-icon\">";
                echo $this->getAttribute($context["contact"], "NAME", array());
                echo "</span>
\t\t\t\t\t\t\t\t\t</a>
\t\t\t\t\t\t\t\t";
                // line 63
                if ((((isset($context["REMAINDER"]) ? $context["REMAINDER"] : null) == 3) || $this->getAttribute($context["contact"], "S_LAST_ROW", array()))) {
                    // line 64
                    echo "\t\t\t\t\t\t\t\t\t</div>
\t\t\t\t\t\t\t\t";
                }
                // line 66
                echo "\t\t\t\t\t\t\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['contact'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 67
            echo "\t\t\t\t\t\t</div>
\t\t\t\t\t</div>
\t\t\t\t</div>
\t\t\t</dd>
\t\t";
        }
        // line 72
        echo "\t\t";
        // line 73
        echo "\t</dl>

\t<div class=\"postbody\">
\t\t<h3 class=\"first\">";
        // line 76
        echo (isset($context["SUBJECT"]) ? $context["SUBJECT"] : null);
        echo "</h3>

\t\t";
        // line 78
        $value = ((((isset($context["U_EDIT"]) ? $context["U_EDIT"] : null) || (isset($context["U_DELETE"]) ? $context["U_DELETE"] : null)) || (isset($context["U_REPORT"]) ? $context["U_REPORT"] : null)) || (isset($context["U_QUOTE"]) ? $context["U_QUOTE"] : null));
        $context['definition']->set('SHOW_PM_POST_BUTTONS', $value);
        // line 79
        echo "\t\t";
        // line 80
        echo "\t\t";
        if ($this->getAttribute((isset($context["definition"]) ? $context["definition"] : null), "SHOW_PM_POST_BUTTONS", array())) {
            // line 81
            echo "\t\t<ul class=\"post-buttons\">
\t\t\t";
            // line 82
            // line 83
            echo "\t\t\t";
            if ((isset($context["U_EDIT"]) ? $context["U_EDIT"] : null)) {
                // line 84
                echo "\t\t\t\t<li>
\t\t\t\t\t<a href=\"";
                // line 85
                echo (isset($context["U_EDIT"]) ? $context["U_EDIT"] : null);
                echo "\" title=\"";
                echo $this->env->getExtension('phpbb')->lang("POST_EDIT_PM");
                echo "\" class=\"button icon-button edit-icon\"><span>";
                echo $this->env->getExtension('phpbb')->lang("POST_EDIT_PM");
                echo "</span></a>
\t\t\t\t</li>
\t\t\t";
            }
            // line 88
            echo "\t\t\t";
            if ((isset($context["U_DELETE"]) ? $context["U_DELETE"] : null)) {
                // line 89
                echo "\t\t\t\t<li>
\t\t\t\t\t<a href=\"";
                // line 90
                echo (isset($context["U_DELETE"]) ? $context["U_DELETE"] : null);
                echo "\" title=\"";
                echo $this->env->getExtension('phpbb')->lang("DELETE_MESSAGE");
                echo "\" class=\"button icon-button delete-icon\"><span>";
                echo $this->env->getExtension('phpbb')->lang("DELETE_MESSAGE");
                echo "</span></a>
\t\t\t\t</li>
\t\t\t";
            }
            // line 93
            echo "\t\t\t";
            if ((isset($context["U_REPORT"]) ? $context["U_REPORT"] : null)) {
                // line 94
                echo "\t\t\t\t<li>
\t\t\t\t\t<a href=\"";
                // line 95
                echo (isset($context["U_REPORT"]) ? $context["U_REPORT"] : null);
                echo "\" title=\"";
                echo $this->env->getExtension('phpbb')->lang("REPORT_PM");
                echo "\" class=\"button icon-button report-icon\"><span>";
                echo $this->env->getExtension('phpbb')->lang("REPORT_PM");
                echo "</span></a>
\t\t\t\t</li>
\t\t\t";
            }
            // line 98
            echo "\t\t\t";
            if ((isset($context["U_QUOTE"]) ? $context["U_QUOTE"] : null)) {
                // line 99
                echo "\t\t\t\t<li>
\t\t\t\t\t<a href=\"";
                // line 100
                echo (isset($context["U_QUOTE"]) ? $context["U_QUOTE"] : null);
                echo "\" title=\"";
                echo $this->env->getExtension('phpbb')->lang("POST_QUOTE_PM");
                echo "\" class=\"button icon-button quote-icon\"><span>";
                echo $this->env->getExtension('phpbb')->lang("POST_QUOTE_PM");
                echo "</span></a>
\t\t\t\t</li>
\t\t\t";
            }
            // line 103
            echo "\t\t\t";
            // line 104
            echo "\t\t</ul>
\t\t";
        }
        // line 106
        echo "\t\t";
        // line 107
        echo "
\t\t<p class=\"author\">
\t\t\t<strong>";
        // line 109
        echo $this->env->getExtension('phpbb')->lang("SENT_AT");
        echo $this->env->getExtension('phpbb')->lang("COLON");
        echo "</strong> ";
        echo (isset($context["SENT_DATE"]) ? $context["SENT_DATE"] : null);
        echo "
\t\t\t<br /><strong>";
        // line 110
        echo $this->env->getExtension('phpbb')->lang("PM_FROM");
        echo $this->env->getExtension('phpbb')->lang("COLON");
        echo "</strong> ";
        echo (isset($context["MESSAGE_AUTHOR_FULL"]) ? $context["MESSAGE_AUTHOR_FULL"] : null);
        echo "
\t\t\t";
        // line 111
        if ((isset($context["S_TO_RECIPIENT"]) ? $context["S_TO_RECIPIENT"] : null)) {
            echo "<br /><strong>";
            echo $this->env->getExtension('phpbb')->lang("TO");
            echo $this->env->getExtension('phpbb')->lang("COLON");
            echo "</strong> ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "to_recipient", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["to_recipient"]) {
                if ($this->getAttribute($context["to_recipient"], "NAME_FULL", array())) {
                    echo $this->getAttribute($context["to_recipient"], "NAME_FULL", array());
                } else {
                    echo "<a href=\"";
                    echo $this->getAttribute($context["to_recipient"], "U_VIEW", array());
                    echo "\" style=\"color:";
                    if ($this->getAttribute($context["to_recipient"], "COLOUR", array())) {
                        echo $this->getAttribute($context["to_recipient"], "COLOUR", array());
                    } elseif ($this->getAttribute($context["to_recipient"], "IS_GROUP", array())) {
                        echo "#0000FF";
                    }
                    echo ";\">";
                    echo $this->getAttribute($context["to_recipient"], "NAME", array());
                    echo "</a>";
                }
                echo "&nbsp;";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['to_recipient'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
        }
        // line 112
        echo "\t\t\t";
        if ((isset($context["S_BCC_RECIPIENT"]) ? $context["S_BCC_RECIPIENT"] : null)) {
            echo "<br /><strong>";
            echo $this->env->getExtension('phpbb')->lang("BCC");
            echo $this->env->getExtension('phpbb')->lang("COLON");
            echo "</strong> ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "bcc_recipient", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["bcc_recipient"]) {
                if ($this->getAttribute($context["bcc_recipient"], "NAME_FULL", array())) {
                    echo $this->getAttribute($context["bcc_recipient"], "NAME_FULL", array());
                } else {
                    echo "<a href=\"";
                    echo $this->getAttribute($context["bcc_recipient"], "U_VIEW", array());
                    echo "\" style=\"color:";
                    if ($this->getAttribute($context["bcc_recipient"], "COLOUR", array())) {
                        echo $this->getAttribute($context["bcc_recipient"], "COLOUR", array());
                    } elseif ($this->getAttribute($context["bcc_recipient"], "IS_GROUP", array())) {
                        echo "#0000FF";
                    }
                    echo ";\">";
                    echo $this->getAttribute($context["bcc_recipient"], "NAME", array());
                    echo "</a>";
                }
                echo "&nbsp;";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['bcc_recipient'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
        }
        // line 113
        echo "\t\t</p>


\t\t<div class=\"content\">";
        // line 116
        echo (isset($context["MESSAGE"]) ? $context["MESSAGE"] : null);
        echo "</div>

\t\t";
        // line 118
        if ((isset($context["S_HAS_ATTACHMENTS"]) ? $context["S_HAS_ATTACHMENTS"] : null)) {
            // line 119
            echo "\t\t\t<dl class=\"attachbox\">
\t\t\t\t<dt>
\t\t\t\t\t";
            // line 121
            echo $this->env->getExtension('phpbb')->lang("ATTACHMENTS");
            echo "
\t\t\t\t</dt>
\t\t\t\t";
            // line 123
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["loops"]) ? $context["loops"] : null), "attachment", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["attachment"]) {
                // line 124
                echo "\t\t\t\t\t<dd>";
                echo $this->getAttribute($context["attachment"], "DISPLAY_ATTACHMENT", array());
                echo "</dd>
\t\t\t\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['attachment'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 126
            echo "\t\t\t</dl>
\t\t";
        }
        // line 128
        echo "
\t\t";
        // line 129
        if ((isset($context["S_DISPLAY_NOTICE"]) ? $context["S_DISPLAY_NOTICE"] : null)) {
            // line 130
            echo "\t\t\t<div class=\"post-notice error\">";
            echo $this->env->getExtension('phpbb')->lang("DOWNLOAD_NOTICE");
            echo "</div>
\t\t";
        }
        // line 132
        echo "
\t\t";
        // line 133
        if (((isset($context["EDITED_MESSAGE"]) ? $context["EDITED_MESSAGE"] : null) || (isset($context["EDIT_REASON"]) ? $context["EDIT_REASON"] : null))) {
            // line 134
            echo "\t\t<div class=\"notice\">";
            echo (isset($context["EDITED_MESSAGE"]) ? $context["EDITED_MESSAGE"] : null);
            echo "
\t\t\t";
            // line 135
            if ((isset($context["EDIT_REASON"]) ? $context["EDIT_REASON"] : null)) {
                echo "<br /><strong>";
                echo $this->env->getExtension('phpbb')->lang("REASON");
                echo $this->env->getExtension('phpbb')->lang("COLON");
                echo "</strong> <em>";
                echo (isset($context["EDIT_REASON"]) ? $context["EDIT_REASON"] : null);
                echo "</em>";
            }
            // line 136
            echo "\t\t</div>
\t\t";
        }
        // line 138
        echo "
\t\t";
        // line 139
        if ((isset($context["SIGNATURE"]) ? $context["SIGNATURE"] : null)) {
            // line 140
            echo "\t\t\t<div id=\"sig";
            echo (isset($context["MESSAGE_ID"]) ? $context["MESSAGE_ID"] : null);
            echo "\" class=\"signature\">";
            echo (isset($context["SIGNATURE"]) ? $context["SIGNATURE"] : null);
            echo "</div>
\t\t";
        }
        // line 142
        echo "\t</div>

\t<div class=\"back2top\"><a href=\"#top\" class=\"top\" title=\"";
        // line 144
        echo $this->env->getExtension('phpbb')->lang("BACK_TO_TOP");
        echo "\">";
        echo $this->env->getExtension('phpbb')->lang("BACK_TO_TOP");
        echo "</a></div>

\t</div>
</div>

";
        // line 149
        if ((isset($context["S_VIEW_MESSAGE"]) ? $context["S_VIEW_MESSAGE"] : null)) {
            // line 150
            echo "<fieldset class=\"display-options\">
\t";
            // line 151
            if ((isset($context["U_PREVIOUS_PM"]) ? $context["U_PREVIOUS_PM"] : null)) {
                echo "<a href=\"";
                echo (isset($context["U_PREVIOUS_PM"]) ? $context["U_PREVIOUS_PM"] : null);
                echo "\" class=\"left-box arrow-";
                echo (isset($context["S_CONTENT_FLOW_BEGIN"]) ? $context["S_CONTENT_FLOW_BEGIN"] : null);
                echo "\">";
                echo $this->env->getExtension('phpbb')->lang("VIEW_PREVIOUS_PM");
                echo "</a>";
            }
            // line 152
            echo "\t";
            if ((isset($context["U_NEXT_PM"]) ? $context["U_NEXT_PM"] : null)) {
                echo "<a href=\"";
                echo (isset($context["U_NEXT_PM"]) ? $context["U_NEXT_PM"] : null);
                echo "\" class=\"right-box arrow-";
                echo (isset($context["S_CONTENT_FLOW_END"]) ? $context["S_CONTENT_FLOW_END"] : null);
                echo "\">";
                echo $this->env->getExtension('phpbb')->lang("VIEW_NEXT_PM");
                echo "</a>";
            }
            // line 153
            echo "
\t";
            // line 154
            if ((isset($context["S_MARK_OPTIONS"]) ? $context["S_MARK_OPTIONS"] : null)) {
                echo "<label for=\"mark_option\"><select name=\"mark_option\" id=\"mark_option\">";
                echo (isset($context["S_MARK_OPTIONS"]) ? $context["S_MARK_OPTIONS"] : null);
                echo "</select></label>&nbsp;<input class=\"button2\" type=\"submit\" name=\"submit_mark\" value=\"";
                echo $this->env->getExtension('phpbb')->lang("GO");
                echo "\" />";
            }
            // line 155
            echo "\t";
            if (( !(isset($context["S_UNREAD"]) ? $context["S_UNREAD"] : null) &&  !(isset($context["S_SPECIAL_FOLDER"]) ? $context["S_SPECIAL_FOLDER"] : null))) {
                echo "<label for=\"dest_folder\">";
                if ((isset($context["S_VIEW_MESSAGE"]) ? $context["S_VIEW_MESSAGE"] : null)) {
                    echo $this->env->getExtension('phpbb')->lang("MOVE_TO_FOLDER");
                    echo $this->env->getExtension('phpbb')->lang("COLON");
                    echo " ";
                } else {
                    echo $this->env->getExtension('phpbb')->lang("MOVE_MARKED_TO_FOLDER");
                }
                echo " <select name=\"dest_folder\" id=\"dest_folder\">";
                echo (isset($context["S_TO_FOLDER_OPTIONS"]) ? $context["S_TO_FOLDER_OPTIONS"] : null);
                echo "</select></label> <input class=\"button2\" type=\"submit\" name=\"move_pm\" value=\"";
                echo $this->env->getExtension('phpbb')->lang("GO");
                echo "\" />";
            }
            // line 156
            echo "\t<input type=\"hidden\" name=\"marked_msg_id[]\" value=\"";
            echo (isset($context["MSG_ID"]) ? $context["MSG_ID"] : null);
            echo "\" />
\t<input type=\"hidden\" name=\"cur_folder_id\" value=\"";
            // line 157
            echo (isset($context["CUR_FOLDER_ID"]) ? $context["CUR_FOLDER_ID"] : null);
            echo "\" />
\t<input type=\"hidden\" name=\"p\" value=\"";
            // line 158
            echo (isset($context["MSG_ID"]) ? $context["MSG_ID"] : null);
            echo "\" />
</fieldset>
";
        }
        // line 161
        echo "
";
        // line 162
        $location = "ucp_pm_message_footer.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->loadTemplate("ucp_pm_message_footer.html", "ucp_pm_viewmessage.html", 162)->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
        // line 163
        echo "
";
        // line 164
        if ((isset($context["S_DISPLAY_HISTORY"]) ? $context["S_DISPLAY_HISTORY"] : null)) {
            $location = "ucp_pm_history.html";
            $namespace = false;
            if (strpos($location, '@') === 0) {
                $namespace = substr($location, 1, strpos($location, '/') - 1);
                $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
                $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
            }
            $this->loadTemplate("ucp_pm_history.html", "ucp_pm_viewmessage.html", 164)->display($context);
            if ($namespace) {
                $this->env->setNamespaceLookUpOrder($previous_look_up_order);
            }
        }
        // line 165
        echo "
";
        // line 166
        $location = "ucp_footer.html";
        $namespace = false;
        if (strpos($location, '@') === 0) {
            $namespace = substr($location, 1, strpos($location, '/') - 1);
            $previous_look_up_order = $this->env->getNamespaceLookUpOrder();
            $this->env->setNamespaceLookUpOrder(array($namespace, '__main__'));
        }
        $this->loadTemplate("ucp_footer.html", "ucp_pm_viewmessage.html", 166)->display($context);
        if ($namespace) {
            $this->env->setNamespaceLookUpOrder($previous_look_up_order);
        }
    }

    public function getTemplateName()
    {
        return "ucp_pm_viewmessage.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  677 => 166,  674 => 165,  660 => 164,  657 => 163,  645 => 162,  642 => 161,  636 => 158,  632 => 157,  627 => 156,  610 => 155,  602 => 154,  599 => 153,  588 => 152,  578 => 151,  575 => 150,  573 => 149,  563 => 144,  559 => 142,  551 => 140,  549 => 139,  546 => 138,  542 => 136,  533 => 135,  528 => 134,  526 => 133,  523 => 132,  517 => 130,  515 => 129,  512 => 128,  508 => 126,  499 => 124,  495 => 123,  490 => 121,  486 => 119,  484 => 118,  479 => 116,  474 => 113,  443 => 112,  413 => 111,  406 => 110,  399 => 109,  395 => 107,  393 => 106,  389 => 104,  387 => 103,  377 => 100,  374 => 99,  371 => 98,  361 => 95,  358 => 94,  355 => 93,  345 => 90,  342 => 89,  339 => 88,  329 => 85,  326 => 84,  323 => 83,  322 => 82,  319 => 81,  316 => 80,  314 => 79,  311 => 78,  306 => 76,  301 => 73,  299 => 72,  292 => 67,  286 => 66,  282 => 64,  280 => 63,  273 => 61,  255 => 60,  251 => 58,  248 => 57,  244 => 56,  241 => 55,  237 => 54,  228 => 50,  222 => 48,  219 => 47,  216 => 46,  215 => 45,  212 => 44,  210 => 43,  204 => 42,  193 => 40,  190 => 39,  185 => 38,  184 => 37,  181 => 36,  172 => 35,  157 => 34,  154 => 33,  152 => 32,  141 => 31,  140 => 30,  134 => 27,  131 => 26,  129 => 25,  120 => 24,  119 => 23,  104 => 21,  100 => 20,  87 => 17,  83 => 15,  79 => 13,  68 => 12,  58 => 11,  55 => 10,  53 => 9,  46 => 4,  34 => 3,  31 => 2,  19 => 1,);
    }
}
/* <!-- INCLUDE ucp_header.html -->*/
/* */
/* <!-- INCLUDE ucp_pm_message_header.html -->*/
/* */
/* 	</div>*/
/* </div>*/
/* */
/* */
/* <!-- IF S_DISPLAY_HISTORY and (U_VIEW_PREVIOUS_HISTORY or U_VIEW_NEXT_HISTORY) -->*/
/* 	<fieldset class="display-options clearfix">*/
/* 		<!-- IF U_VIEW_PREVIOUS_HISTORY --><a href="{U_VIEW_PREVIOUS_HISTORY}" class="left-box arrow-{S_CONTENT_FLOW_BEGIN}">{L_VIEW_PREVIOUS_HISTORY}</a><!-- ENDIF -->*/
/* 		<!-- IF U_VIEW_NEXT_HISTORY --><a href="{U_VIEW_NEXT_HISTORY}" class="right-box arrow-{S_CONTENT_FLOW_END}">{L_VIEW_NEXT_HISTORY}</a><!-- ENDIF -->*/
/* 	</fieldset>*/
/* <!-- ENDIF -->*/
/* */
/* */
/* <div id="post-{MESSAGE_ID}" class="post pm has-profile<!-- IF S_POST_UNAPPROVED or S_POST_REPORTED --> reported<!-- ENDIF --><!-- IF S_ONLINE --> online<!-- ENDIF -->">*/
/* <div class="inner">*/
/* */
/* 	<dl class="postprofile" id="profile{MESSAGE_ID}">*/
/* 		<dt class="<!-- IF RANK_TITLE or RANK_IMG -->has-profile-rank<!-- ELSE -->no-profile-rank<!-- ENDIF --> <!-- IF AUTHOR_AVATAR -->has-avatar<!-- ELSE -->no-avatar<!-- ENDIF -->">*/
/* 			<div class="avatar-container">*/
/* 				<!-- EVENT ucp_pm_viewmessage_avatar_before -->*/
/* 				<!-- IF AUTHOR_AVATAR --><a href="{U_MESSAGE_AUTHOR}" class="avatar">{AUTHOR_AVATAR}</a><!-- ENDIF -->*/
/* 				<!-- EVENT ucp_pm_viewmessage_avatar_after -->*/
/* 			</div>*/
/* 			{MESSAGE_AUTHOR_FULL}*/
/* 		</dt>*/
/* */
/* 		<!-- EVENT ucp_pm_viewmessage_rank_before -->*/
/* 		<!-- IF RANK_TITLE or RANK_IMG --><dd class="profile-rank">{RANK_TITLE}<!-- IF RANK_TITLE and RANK_IMG --><br /><!-- ENDIF -->{RANK_IMG}</dd><!-- ENDIF -->*/
/* 		<!-- EVENT ucp_pm_viewmessage_rank_after -->*/
/* */
/* 		<dd class="profile-posts"><strong>{L_POSTS}{L_COLON}</strong> <!-- IF U_AUTHOR_POSTS != '' --><a href="{U_AUTHOR_POSTS}">{AUTHOR_POSTS}</a><!-- ELSE -->{AUTHOR_POSTS}<!-- ENDIF --></dd>*/
/* 		<!-- IF AUTHOR_JOINED --><dd class="profile-joined"><strong>{L_JOINED}{L_COLON}</strong> {AUTHOR_JOINED}</dd><!-- ENDIF -->*/
/* */
/* 		<!-- EVENT ucp_pm_viewmessage_custom_fields_before -->*/
/* 		<!-- BEGIN custom_fields -->*/
/* 			<!-- IF not custom_fields.S_PROFILE_CONTACT -->*/
/* 				<dd class="profile-custom-field profile-{custom_fields.PROFILE_FIELD_IDENT}"><strong>{custom_fields.PROFILE_FIELD_NAME}{L_COLON}</strong> {custom_fields.PROFILE_FIELD_VALUE}</dd>*/
/* 			<!-- ENDIF -->*/
/* 		<!-- END custom_fields -->*/
/* 		<!-- EVENT ucp_pm_viewmessage_custom_fields_after -->*/
/* */
/* 		<!-- EVENT ucp_pm_viewmessage_contact_fields_before -->*/
/* 		<!-- IF .contact -->*/
/* 			<dd class="profile-contact">*/
/* 				<strong>{L_CONTACT}{L_COLON}</strong>*/
/* 				<div class="dropdown-container dropdown-left">*/
/* 					<a href="#" class="dropdown-trigger"><span class="imageset icon_contact" title="{CONTACT_USER}">{CONTACT_USER}</span></a>*/
/* 					<div class="dropdown hidden">*/
/* 						<div class="pointer"><div class="pointer-inner"></div></div>*/
/* 						<div class="dropdown-contents contact-icons">*/
/* 							<!-- BEGIN contact -->*/
/* 								{% set REMAINDER = contact.S_ROW_COUNT % 4 %}*/
/* 								<!-- DEFINE $S_LAST_CELL = ((REMAINDER eq 3) or (contact.S_LAST_ROW and contact.S_NUM_ROWS < 4)) -->*/
/* 								<!-- IF REMAINDER eq 0 -->*/
/* 									<div>*/
/* 								<!-- ENDIF -->*/
/* 									<a href="<!-- IF contact.U_CONTACT -->{contact.U_CONTACT}<!-- ELSE -->{contact.U_PROFILE_AUTHOR}<!-- ENDIF -->" title="{contact.NAME}"<!-- IF $S_LAST_CELL --> class="last-cell"<!-- ENDIF --><!-- IF contact.ID eq 'jabber' --> onclick="popup(this.href, 750, 320); return false;"<!-- ENDIF -->>*/
/* 										<span class="contact-icon {contact.ID}-icon">{contact.NAME}</span>*/
/* 									</a>*/
/* 								<!-- IF REMAINDER eq 3 or contact.S_LAST_ROW -->*/
/* 									</div>*/
/* 								<!-- ENDIF -->*/
/* 							<!-- END contact -->*/
/* 						</div>*/
/* 					</div>*/
/* 				</div>*/
/* 			</dd>*/
/* 		<!-- ENDIF -->*/
/* 		<!-- EVENT ucp_pm_viewmessage_contact_fields_after -->*/
/* 	</dl>*/
/* */
/* 	<div class="postbody">*/
/* 		<h3 class="first">{SUBJECT}</h3>*/
/* */
/* 		<!-- DEFINE $SHOW_PM_POST_BUTTONS = (U_EDIT or U_DELETE or U_REPORT or U_QUOTE) -->*/
/* 		<!-- EVENT ucp_pm_viewmessage_post_buttons_list_before -->*/
/* 		<!-- IF $SHOW_PM_POST_BUTTONS -->*/
/* 		<ul class="post-buttons">*/
/* 			<!-- EVENT ucp_pm_viewmessage_post_buttons_before -->*/
/* 			<!-- IF U_EDIT -->*/
/* 				<li>*/
/* 					<a href="{U_EDIT}" title="{L_POST_EDIT_PM}" class="button icon-button edit-icon"><span>{L_POST_EDIT_PM}</span></a>*/
/* 				</li>*/
/* 			<!-- ENDIF -->*/
/* 			<!-- IF U_DELETE -->*/
/* 				<li>*/
/* 					<a href="{U_DELETE}" title="{L_DELETE_MESSAGE}" class="button icon-button delete-icon"><span>{L_DELETE_MESSAGE}</span></a>*/
/* 				</li>*/
/* 			<!-- ENDIF -->*/
/* 			<!-- IF U_REPORT -->*/
/* 				<li>*/
/* 					<a href="{U_REPORT}" title="{L_REPORT_PM}" class="button icon-button report-icon"><span>{L_REPORT_PM}</span></a>*/
/* 				</li>*/
/* 			<!-- ENDIF -->*/
/* 			<!-- IF U_QUOTE -->*/
/* 				<li>*/
/* 					<a href="{U_QUOTE}" title="{L_POST_QUOTE_PM}" class="button icon-button quote-icon"><span>{L_POST_QUOTE_PM}</span></a>*/
/* 				</li>*/
/* 			<!-- ENDIF -->*/
/* 			<!-- EVENT ucp_pm_viewmessage_post_buttons_after -->*/
/* 		</ul>*/
/* 		<!-- ENDIF -->*/
/* 		<!-- EVENT ucp_pm_viewmessage_post_buttons_list_after -->*/
/* */
/* 		<p class="author">*/
/* 			<strong>{L_SENT_AT}{L_COLON}</strong> {SENT_DATE}*/
/* 			<br /><strong>{L_PM_FROM}{L_COLON}</strong> {MESSAGE_AUTHOR_FULL}*/
/* 			<!-- IF S_TO_RECIPIENT --><br /><strong>{L_TO}{L_COLON}</strong> <!-- BEGIN to_recipient --><!-- IF to_recipient.NAME_FULL -->{to_recipient.NAME_FULL}<!-- ELSE --><a href="{to_recipient.U_VIEW}" style="color:<!-- IF to_recipient.COLOUR -->{to_recipient.COLOUR}<!-- ELSEIF to_recipient.IS_GROUP -->#0000FF<!-- ENDIF -->;">{to_recipient.NAME}</a><!-- ENDIF -->&nbsp;<!-- END to_recipient --><!-- ENDIF -->*/
/* 			<!-- IF S_BCC_RECIPIENT --><br /><strong>{L_BCC}{L_COLON}</strong> <!-- BEGIN bcc_recipient --><!-- IF bcc_recipient.NAME_FULL -->{bcc_recipient.NAME_FULL}<!-- ELSE --><a href="{bcc_recipient.U_VIEW}" style="color:<!-- IF bcc_recipient.COLOUR -->{bcc_recipient.COLOUR}<!-- ELSEIF bcc_recipient.IS_GROUP -->#0000FF<!-- ENDIF -->;">{bcc_recipient.NAME}</a><!-- ENDIF -->&nbsp;<!-- END bcc_recipient --><!-- ENDIF -->*/
/* 		</p>*/
/* */
/* */
/* 		<div class="content">{MESSAGE}</div>*/
/* */
/* 		<!-- IF S_HAS_ATTACHMENTS -->*/
/* 			<dl class="attachbox">*/
/* 				<dt>*/
/* 					{L_ATTACHMENTS}*/
/* 				</dt>*/
/* 				<!-- BEGIN attachment -->*/
/* 					<dd>{attachment.DISPLAY_ATTACHMENT}</dd>*/
/* 				<!-- END attachment -->*/
/* 			</dl>*/
/* 		<!-- ENDIF -->*/
/* */
/* 		<!-- IF S_DISPLAY_NOTICE -->*/
/* 			<div class="post-notice error">{L_DOWNLOAD_NOTICE}</div>*/
/* 		<!-- ENDIF -->*/
/* */
/* 		<!-- IF EDITED_MESSAGE or EDIT_REASON -->*/
/* 		<div class="notice">{EDITED_MESSAGE}*/
/* 			<!-- IF EDIT_REASON --><br /><strong>{L_REASON}{L_COLON}</strong> <em>{EDIT_REASON}</em><!-- ENDIF -->*/
/* 		</div>*/
/* 		<!-- ENDIF -->*/
/* */
/* 		<!-- IF SIGNATURE -->*/
/* 			<div id="sig{MESSAGE_ID}" class="signature">{SIGNATURE}</div>*/
/* 		<!-- ENDIF -->*/
/* 	</div>*/
/* */
/* 	<div class="back2top"><a href="#top" class="top" title="{L_BACK_TO_TOP}">{L_BACK_TO_TOP}</a></div>*/
/* */
/* 	</div>*/
/* </div>*/
/* */
/* <!-- IF S_VIEW_MESSAGE -->*/
/* <fieldset class="display-options">*/
/* 	<!-- IF U_PREVIOUS_PM --><a href="{U_PREVIOUS_PM}" class="left-box arrow-{S_CONTENT_FLOW_BEGIN}">{L_VIEW_PREVIOUS_PM}</a><!-- ENDIF -->*/
/* 	<!-- IF U_NEXT_PM --><a href="{U_NEXT_PM}" class="right-box arrow-{S_CONTENT_FLOW_END}">{L_VIEW_NEXT_PM}</a><!-- ENDIF -->*/
/* */
/* 	<!-- IF S_MARK_OPTIONS --><label for="mark_option"><select name="mark_option" id="mark_option">{S_MARK_OPTIONS}</select></label>&nbsp;<input class="button2" type="submit" name="submit_mark" value="{L_GO}" /><!-- ENDIF -->*/
/* 	<!-- IF not S_UNREAD and not S_SPECIAL_FOLDER --><label for="dest_folder"><!-- IF S_VIEW_MESSAGE -->{L_MOVE_TO_FOLDER}{L_COLON} <!-- ELSE -->{L_MOVE_MARKED_TO_FOLDER}<!-- ENDIF --> <select name="dest_folder" id="dest_folder">{S_TO_FOLDER_OPTIONS}</select></label> <input class="button2" type="submit" name="move_pm" value="{L_GO}" /><!-- ENDIF -->*/
/* 	<input type="hidden" name="marked_msg_id[]" value="{MSG_ID}" />*/
/* 	<input type="hidden" name="cur_folder_id" value="{CUR_FOLDER_ID}" />*/
/* 	<input type="hidden" name="p" value="{MSG_ID}" />*/
/* </fieldset>*/
/* <!-- ENDIF -->*/
/* */
/* <!-- INCLUDE ucp_pm_message_footer.html -->*/
/* */
/* <!-- IF S_DISPLAY_HISTORY --><!-- INCLUDE ucp_pm_history.html --><!-- ENDIF -->*/
/* */
/* <!-- INCLUDE ucp_footer.html -->*/
/* */
